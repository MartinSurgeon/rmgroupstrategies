<?php
/**
 * Simple Lightweight SMTP Mailer for Namecheap Private Email
 */

class SimpleSMTPMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;

    public function __construct($host = 'mail.privateemail.com', $port = 587, $username = '', $password = '', $encryption = 'tls') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = strtolower($encryption);
    }

    public function send($to, $subject, $body, $fromEmail, $fromName = '', $replyTo = '') {
        $timeout = 15;
        $remote = ($this->encryption === 'ssl') ? 'ssl://' . $this->host : $this->host;
        $socket = @fsockopen($remote, $this->port, $errno, $errstr, $timeout);

        if (!$socket) {
            return [false, "Connection error: $errstr ($errno)"];
        }

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
            fclose($socket);
            return [false, "Server not ready: $res"];
        }

        $res = $sendCommand('EHLO ' . gethostname());
        if ($this->encryption === 'tls') {
            $res = $sendCommand('STARTTLS');
            if (substr($res, 0, 3) !== '220') {
                fclose($socket);
                return [false, "STARTTLS failed: $res"];
            }
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
            $res = $sendCommand('EHLO ' . gethostname());
        }

        if (!empty($this->username) && !empty($this->password)) {
            $res = $sendCommand('AUTH LOGIN');
            if (substr($res, 0, 3) !== '334') {
                fclose($socket);
                return [false, "AUTH LOGIN failed: $res"];
            }
            $res = $sendCommand(base64_encode($this->username));
            if (substr($res, 0, 3) !== '334') {
                fclose($socket);
                return [false, "Username rejected: $res"];
            }
            $res = $sendCommand(base64_encode($this->password));
            if (substr($res, 0, 3) !== '235') {
                fclose($socket);
                return [false, "Password rejected: $res"];
            }
        }

        $fromAddress = !empty($this->username) ? $this->username : $fromEmail;
        $res = $sendCommand("MAIL FROM: <$fromAddress>");
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return [false, "MAIL FROM rejected: $res"];
        }

        $res = $sendCommand("RCPT TO: <$to>");
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return [false, "RCPT TO rejected: $res"];
        }

        $res = $sendCommand("DATA");
        if (substr($res, 0, 3) !== '354') {
            fclose($socket);
            return [false, "DATA command rejected: $res"];
        }

        $headers = "From: " . ($fromName ? "=?UTF-8?B?" . base64_encode($fromName) . "?= <$fromAddress>" : $fromAddress) . "\r\n";
        if (!empty($replyTo)) {
            $headers .= "Reply-To: <$replyTo>\r\n";
        }
        $headers .= "To: <$to>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "Date: " . date('r') . "\r\n";

        $data = $headers . "\r\n" . $body . "\r\n.";
        $res = $sendCommand($data);
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return [false, "Message rejected: $res"];
        }

        $sendCommand("QUIT");
        fclose($socket);
        return [true, "Message sent successfully"];
    }
}
