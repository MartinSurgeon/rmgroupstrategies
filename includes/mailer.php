<?php
/**
 * RM Group Strategies LLC — Enterprise Mailer Engine
 * 
 * Provides authenticated SMTP delivery with automatic graceful fallback
 * to native PHP mail() with RFC-compliant headers, MIME multipart support,
 * delivery tracking, and database logging.
 */

class SimpleSMTPMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;

    public function __construct($host = 'mail.privateemail.com', $port = 465, $username = '', $password = '', $encryption = 'ssl') {
        $this->host = $host;
        $this->port = (int)$port;
        $this->username = trim($username);
        $this->password = trim($password);
        if ($this->port === 465) {
            $this->encryption = 'ssl';
        } elseif ($this->port === 587) {
            $this->encryption = 'tls';
        } else {
            $this->encryption = strtolower($encryption);
        }
    }

    public function send($to, $subject, $body, $fromEmail, $fromName = '', $replyTo = '', $htmlBody = '') {
        // CRLF Injection Mitigation
        $to        = str_replace(["\r", "\n"], '', trim($to));
        $subject   = str_replace(["\r", "\n"], '', trim($subject));
        $fromEmail = str_replace(["\r", "\n"], '', trim($fromEmail));
        $fromName  = str_replace(["\r", "\n"], '', trim($fromName));
        $replyTo   = str_replace(["\r", "\n"], '', trim($replyTo));

        $timeout = 5;
        $remote = ($this->encryption === 'ssl') ? 'ssl://' . $this->host : $this->host;
        $socket = @fsockopen($remote, $this->port, $errno, $errstr, $timeout);

        if (!$socket) {
            return [false, "Connection error: $errstr ($errno) to $remote:$this->port"];
        }

        stream_set_timeout($socket, $timeout);

        $getResponse = function() use ($socket) {
            $response = '';
            while ($str = fgets($socket, 515)) {
                $response .= $str;
                if (substr($str, 3, 1) === ' ') break;
            }
            return $response;
        };

        $sendCommand = function($command) use ($socket, $getResponse) {
            fputs($socket, $command . "\r\n");
            return $getResponse();
        };

        $res = $getResponse();
        if (substr($res, 0, 3) !== '220') {
            @fclose($socket);
            return [false, "Server not ready: " . trim($res)];
        }

        $clientHost = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : gethostname();
        $res = $sendCommand('EHLO ' . $clientHost);

        if ($this->encryption === 'tls') {
            $res = $sendCommand('STARTTLS');
            if (substr($res, 0, 3) !== '220') {
                @fclose($socket);
                return [false, "STARTTLS failed: " . trim($res)];
            }
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
            $res = $sendCommand('EHLO ' . $clientHost);
        }

        if (!empty($this->username) && !empty($this->password)) {
            $res = $sendCommand('AUTH LOGIN');
            if (substr($res, 0, 3) !== '334') {
                @fclose($socket);
                return [false, "AUTH LOGIN failed: " . trim($res)];
            }
            $res = $sendCommand(base64_encode($this->username));
            if (substr($res, 0, 3) !== '334') {
                @fclose($socket);
                return [false, "Username rejected: " . trim($res)];
            }
            $res = $sendCommand(base64_encode($this->password));
            if (substr($res, 0, 3) !== '235') {
                @fclose($socket);
                return [false, "Password rejected: " . trim($res)];
            }
        }

        $fromAddress = !empty($this->username) ? $this->username : $fromEmail;
        $res = $sendCommand("MAIL FROM: <$fromAddress>");
        if (substr($res, 0, 3) !== '250') {
            @fclose($socket);
            return [false, "MAIL FROM rejected: " . trim($res)];
        }

        $res = $sendCommand("RCPT TO: <$to>");
        if (substr($res, 0, 3) !== '250') {
            @fclose($socket);
            return [false, "RCPT TO rejected for <$to>: " . trim($res)];
        }

        $res = $sendCommand("DATA");
        if (substr($res, 0, 3) !== '354') {
            @fclose($socket);
            return [false, "DATA command rejected: " . trim($res)];
        }

        $hostDomain = parse_url(defined('SITE_URL') ? SITE_URL : 'https://rmgroupstrategies.com', PHP_URL_HOST) ?: 'rmgroupstrategies.com';
        $messageId = '<' . bin2hex(random_bytes(16)) . '.' . time() . '@' . $hostDomain . '>';

        $headers = [];
        $headers[] = "From: " . ($fromName ? "=?UTF-8?B?" . base64_encode($fromName) . "?= <$fromAddress>" : $fromAddress);
        if (!empty($replyTo)) {
            $headers[] = "Reply-To: <$replyTo>";
        }
        $headers[] = "To: <$to>";
        $headers[] = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers[] = "Date: " . date('r');
        $headers[] = "Message-ID: " . $messageId;
        $headers[] = "X-Mailer: RMGroupStrategies-Mailer/2.0";
        $headers[] = "MIME-Version: 1.0";

        if (!empty($htmlBody)) {
            $boundary = "----=_Part_" . bin2hex(random_bytes(12));
            $headers[] = "Content-Type: multipart/alternative; boundary=\"$boundary\"";
            
            $content = "--" . $boundary . "\r\n";
            $content .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $content .= $body . "\r\n\r\n";
            $content .= "--" . $boundary . "\r\n";
            $content .= "Content-Type: text/html; charset=UTF-8\r\n";
            $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $content .= $htmlBody . "\r\n\r\n";
            $content .= "--" . $boundary . "--";
        } else {
            $headers[] = "Content-Type: text/plain; charset=UTF-8";
            $headers[] = "Content-Transfer-Encoding: 8bit";
            $content = $body;
        }

        // Standard dot-stuffing for SMTP DATA
        $lines = explode("\n", str_replace("\r\n", "\n", $content));
        $stuffedContent = '';
        foreach ($lines as $line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
            $stuffedContent .= $line . "\r\n";
        }

        $fullPayload = implode("\r\n", $headers) . "\r\n\r\n" . $stuffedContent . "\r\n.";
        $res = $sendCommand($fullPayload);
        if (substr($res, 0, 3) !== '250') {
            @fclose($socket);
            return [false, "Message rejected during DATA transmission: " . trim($res)];
        }

        $sendCommand("QUIT");
        @fclose($socket);
        return [true, "Message sent successfully via SMTP"];
    }
}

/**
 * Generate a branded, responsive HTML email shell
 */
function build_branded_email_html($headline, $badge, $content_html, $action_url = '', $action_text = '') {
    $site_name = defined('SITE_NAME') ? SITE_NAME : 'RM Group Strategies LLC';
    $site_url  = defined('SITE_URL') ? SITE_URL : 'https://rmgroupstrategies.com';
    $phone     = defined('SITE_PHONE_DISPLAY') ? SITE_PHONE_DISPLAY : '(702) 504-8128';
    $email     = defined('SITE_EMAIL') ? SITE_EMAIL : 'info@rmgroupstrategies.com';
    $year      = date('Y');

    $button_html = '';
    if (!empty($action_url) && !empty($action_text)) {
        $button_html = '
        <div style="text-align: center; margin: 32px 0 24px 0;">
            <a href="' . htmlspecialchars($action_url) . '" target="_blank" style="background-color: #D4AF37; color: #0b0f19; padding: 14px 32px; font-size: 15px; font-weight: bold; text-decoration: none; border-radius: 6px; display: inline-block; box-shadow: 0 4px 12px rgba(212,175,55,0.35); text-transform: uppercase; letter-spacing: 0.5px;">' . htmlspecialchars($action_text) . '</a>
        </div>
        <p style="font-size: 12px; color: #888888; text-align: center; margin-top: 8px;">
            Or copy and paste this link into your browser:<br>
            <a href="' . htmlspecialchars($action_url) . '" style="color: #D4AF37; word-break: break-all;">' . htmlspecialchars($action_url) . '</a>
        </p>';
    }

    return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($headline) . '</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f5f7; padding: 24px 12px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0b0f19 0%, #172136 100%); padding: 32px 30px; text-align: center; border-bottom: 3px solid #D4AF37;">
                            <div style="display: inline-block; padding: 4px 14px; background-color: rgba(212,175,55,0.15); border: 1px solid #D4AF37; border-radius: 20px; font-size: 11px; font-weight: 700; color: #D4AF37; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 12px;">
                                ' . htmlspecialchars($badge) . '
                            </div>
                            <h1 style="color: #ffffff; font-size: 24px; margin: 0 0 6px 0; font-weight: 700; letter-spacing: -0.5px;">
                                ' . htmlspecialchars($headline) . '
                            </h1>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                                ' . htmlspecialchars($site_name) . '
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 30px; color: #334155; font-size: 15px; line-height: 1.6;">
                            ' . $content_html . '
                            ' . $button_html . '
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 30px; border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 12px; line-height: 1.6;">
                            <p style="margin: 0 0 6px 0; font-weight: 600; color: #334155;">' . htmlspecialchars($site_name) . '</p>
                            <p style="margin: 0 0 6px 0;">Las Vegas, Nevada • Dispatch Hotline: <a href="tel:+17025048128" style="color: #D4AF37; text-decoration: none; font-weight: 600;">' . htmlspecialchars($phone) . '</a> • <a href="mailto:' . htmlspecialchars($email) . '" style="color: #D4AF37; text-decoration: none;">' . htmlspecialchars($email) . '</a></p>
                            <p style="margin: 0; color: #94a3b8; font-size: 11px;">© ' . $year . ' ' . htmlspecialchars($site_name) . '. All rights reserved. This electronic communication contains confidential information.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
}

/**
 * Universal Multi-Tier System Email Sender
 * 
 * Automatically selects the best delivery mechanism:
 * 1. Authenticated SMTP (PrivateEmail) if configured.
 * 2. Automatic fallback to PHP mail() with envelope sender if SMTP is missing or fails.
 * 3. Graceful simulation on localhost if no SMTP credentials are set.
 * 4. Logs delivery results to MySQL `email_logs` table and debug log.
 *
 * @param string $to Recipient email
 * @param string $subject Email subject line
 * @param string $plain_body Plain text representation
 * @param string $html_body Optional HTML representation
 * @param string $reply_to Optional reply-to email address
 * @param string|null $from_email Optional custom sender email
 * @param string|null $from_name Optional custom sender name
 * @param int|null $lead_id Optional ID for `lead_submissions` linking
 * @param PDO|null $db Optional PDO instance for logging
 * @return array [bool $success, string $method, string $message]
 */
function send_system_email(
    $to,
    $subject,
    $plain_body,
    $html_body = '',
    $reply_to = '',
    $from_email = null,
    $from_name = null,
    $lead_id = null,
    $db = null
) {
    // CRLF Injection Mitigation
    $to         = str_replace(["\r", "\n"], '', trim($to));
    $subject    = str_replace(["\r", "\n"], '', trim($subject));
    $from_email = str_replace(["\r", "\n"], '', trim($from_email ?: (defined('SITE_EMAIL') ? SITE_EMAIL : 'info@rmgroupstrategies.com')));
    $from_name  = str_replace(["\r", "\n"], '', trim($from_name  ?: (defined('SITE_NAME') ? SITE_NAME : 'RM Group Strategies LLC')));
    $reply_to   = str_replace(["\r", "\n"], '', trim($reply_to   ?: $from_email));

    $http_host = $_SERVER['HTTP_HOST'] ?? '';
    $host_only = strtolower(explode(':', $http_host)[0]);
    $is_local_env = (
        defined('IS_LOCAL_ENV') ? IS_LOCAL_ENV : (
            empty($host_only) ||
            in_array($host_only, ['localhost', '127.0.0.1', '::1']) ||
            strpos($host_only, '192.168.') === 0 ||
            strpos($host_only, '10.') === 0 ||
            strpos($host_only, '172.16.') === 0 ||
            strpos($host_only, '.local') !== false ||
            strpos($host_only, '.test') !== false ||
            php_sapi_name() === 'cli'
        )
    );

    $log_dir = dirname(__DIR__) . '/scratch';
    if (!file_exists($log_dir)) {
        @mkdir($log_dir, 0777, true);
    }

    $delivery_status = 'failed';
    $delivery_method = 'none';
    $error_details   = '';

    // ── Local Simulation (Only when local and no SMTP password is provided) ──
    if ($is_local_env && (!defined('SMTP_PASS') || empty(SMTP_PASS)) && !defined('FORCE_SEND_MAIL')) {
        $delivery_status = 'sent';
        $delivery_method = 'simulated';
        $error_details   = 'Simulated locally (saved to scratch)';

        $preview_file_base = $log_dir . '/preview_' . date('Ymd_His') . '_' . substr(md5($to . $subject . microtime(true)), 0, 8);
        @file_put_contents($preview_file_base . '.txt', "TO: $to\nFROM: $from_name <$from_email>\nREPLY-TO: $reply_to\nSUBJECT: $subject\nDATE: " . date('r') . "\n\n" . $plain_body);
        if (!empty($html_body)) {
            @file_put_contents($preview_file_base . '.html', $html_body);
        }
    } else {
        // ── TIER 1: Authenticated SMTP ──
        $smtp_attempted = false;
        if (defined('SMTP_PASS') && !empty(SMTP_PASS)) {
            $smtp_attempted = true;
            $smtp_host = defined('SMTP_HOST') ? SMTP_HOST : 'mail.privateemail.com';
            $smtp_port = defined('SMTP_PORT') ? SMTP_PORT : 465;
            $smtp_user = defined('SMTP_USER') ? SMTP_USER : $from_email;
            $smtp_enc  = defined('SMTP_ENC')  ? SMTP_ENC  : 'ssl';

            $mailer = new SimpleSMTPMailer($smtp_host, $smtp_port, $smtp_user, SMTP_PASS, $smtp_enc);
            list($smtp_ok, $smtp_msg) = $mailer->send($to, $subject, $plain_body, $from_email, $from_name, $reply_to, $html_body);

            if ($smtp_ok) {
                $delivery_status = 'sent';
                $delivery_method = 'authenticated_smtp';
                $error_details   = 'Delivered via SMTP (' . $smtp_host . ')';
            } else {
                $error_details = 'SMTP failed: ' . $smtp_msg;
            }
        }

        // ── TIER 2: Native PHP mail() with envelope sender fallback ──
        if ($delivery_status !== 'sent') {
            $hostDomain = parse_url(defined('SITE_URL') ? SITE_URL : 'https://rmgroupstrategies.com', PHP_URL_HOST) ?: 'rmgroupstrategies.com';
            $messageId  = '<' . bin2hex(random_bytes(16)) . '.' . time() . '@' . $hostDomain . '>';

            $headers = [];
            $headers[] = "From: " . ($from_name ? "=?UTF-8?B?" . base64_encode($from_name) . "?= <$from_email>" : $from_email);
            if (!empty($reply_to)) {
                $headers[] = "Reply-To: <$reply_to>";
            }
            $headers[] = "Date: " . date('r');
            $headers[] = "Message-ID: " . $messageId;
            $headers[] = "X-Mailer: PHP/" . phpversion();
            $headers[] = "MIME-Version: 1.0";

            if (!empty($html_body)) {
                $boundary = "----=_Part_" . bin2hex(random_bytes(12));
                $headers[] = "Content-Type: multipart/alternative; boundary=\"$boundary\"";

                $content = "--" . $boundary . "\r\n";
                $content .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
                $content .= $plain_body . "\r\n\r\n";
                $content .= "--" . $boundary . "\r\n";
                $content .= "Content-Type: text/html; charset=UTF-8\r\n";
                $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
                $content .= $html_body . "\r\n\r\n";
                $content .= "--" . $boundary . "--";
            } else {
                $headers[] = "Content-Type: text/plain; charset=UTF-8";
                $headers[] = "Content-Transfer-Encoding: 8bit";
                $content = $plain_body;
            }

            $additional_params = "-f " . $from_email;
            $header_str = implode("\r\n", $headers);
            $mail_ok = @mail($to, $subject, $content, $header_str, $additional_params);

            if ($mail_ok) {
                $delivery_status = 'sent';
                $delivery_method = $smtp_attempted ? 'php_mail_fallback' : 'php_mail';
                $error_details   = $smtp_attempted 
                    ? ($error_details . ' -> Fallback PHP mail() succeeded') 
                    : 'Delivered via native PHP mail()';
            } else {
                $delivery_method = $smtp_attempted ? 'smtp_and_php_mail_failed' : 'php_mail_failed';
                $error_details   = $smtp_attempted 
                    ? ($error_details . ' -> Fallback PHP mail() also failed') 
                    : 'PHP mail() rejected by server MTA';
            }
        }
    }

    // ── File Diagnostic Logging ──
    $log_file = $log_dir . '/submission_debug.log';
    $log_line = "[" . date('Y-m-d H:i:s') . "] [" . strtoupper($delivery_status) . "] [EMAIL] To: " . $to . " | Subject: " . $subject . " | Method: " . $delivery_method . " | Details: " . $error_details . "\n";
    @file_put_contents($log_file, $log_line, FILE_APPEND);

    // ── Database Logging (email_logs table) ──
    $db_conn = $db ?: ($GLOBALS['db'] ?? null);
    if ($db_conn instanceof PDO) {
        try {
            $stmt = $db_conn->prepare("INSERT INTO email_logs (lead_submission_id, recipient_email, subject, status, error_message) VALUES (:lead_id, :recipient, :subject, :status, :error)");
            $stmt->execute([
                ':lead_id'   => $lead_id,
                ':recipient' => $to,
                ':subject'   => $subject,
                ':status'    => $delivery_status . ':' . $delivery_method,
                ':error'     => $error_details
            ]);
        } catch (Exception $e) {
            @file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] [WARNING] email_logs DB insert failed: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }

    return [$delivery_status === 'sent', $delivery_method, $error_details];
}
