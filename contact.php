<?php
/**
 * RM Group Strategies LLC — Contact Page & Lead Capture System
 */
session_start();
require_once __DIR__ . '/includes/config.php';

// Version verification endpoint for deployment tracking
if (isset($_GET['version_check'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'version' => '2.0.0-SMTP-DIRECT',
        'target_recipient' => defined('EMAIL_ERM') ? EMAIL_ERM : 'erm@rmgroupstrategies.com',
        'smtp_pass_configured' => (defined('SMTP_PASS') && !empty(SMTP_PASS))
    ], JSON_PRETTY_PRINT);
    exit;
}

$current_page = 'contact';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Generate simple math question if not exists
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}

$captcha_question = "What is " . $_SESSION['captcha_num1'] . " + " . $_SESSION['captcha_num2'] . "?";

// Database Connection (MySQL)
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create lead_submissions table
    $db->exec("CREATE TABLE IF NOT EXISTS lead_submissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        inquiry_type VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        company VARCHAR(255),
        agency VARCHAR(255),
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50),
        service_interest VARCHAR(255),
        project_description TEXT,
        trades_licenses VARCHAR(255),
        status VARCHAR(50) DEFAULT 'new',
        source_page VARCHAR(255),
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Create email_logs table
    $db->exec("CREATE TABLE IF NOT EXISTS email_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lead_submission_id INT,
        recipient_email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        status VARCHAR(50) NOT NULL,
        error_message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    $db_error = "Database setup failed: " . $e->getMessage();
}

// Handle Form Submission POST
$errors = [];
$success_msg = "";

// Initialize variables for form fields to preserve input on error
$inquiry_type = '';
$name = '';
$email = '';
$phone = '';
$company = '';
$agency = '';
$service_interest = '';
$project_description = '';
$trades_licenses = '';

// Diagnostic Logger Helper Function
function log_submission_event($level, $message, array $context = []) {
    $log_dir = __DIR__ . '/scratch';
    if (!file_exists($log_dir)) {
        @mkdir($log_dir, 0777, true);
    }
    $log_file = $log_dir . '/submission_debug.log';
    $entry = "[" . date('Y-m-d H:i:s') . "] [" . strtoupper($level) . "] " . $message;
    if (!empty($context)) {
        $entry .= " | Context: " . json_encode($context, JSON_UNESCAPED_SLASHES);
    }
    $entry .= "\n";
    @file_put_contents($log_file, $entry, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    log_submission_event('INFO', 'Contact form submission POST received', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'email' => $_POST['email'] ?? '',
        'inquiry_type' => $_POST['inquiry_type'] ?? ''
    ]);

    // 1. CSRF Verification
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Security check failed. Please refresh the page and try again.";
        log_submission_event('WARNING', 'CSRF verification failed');
    }
    
    // 2. Honeypot check
    if (!empty($_POST['website'])) {
        $errors[] = "Spam block triggered. Attempt rejected.";
        log_submission_event('WARNING', 'Honeypot triggered', ['website_val' => $_POST['website']]);
    }
    
    // 3. Captcha check
    $captcha_answer = isset($_POST['captcha_answer']) ? intval($_POST['captcha_answer']) : 0;
    $expected_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
    if ($captcha_answer !== $expected_answer) {
        $errors[] = "Incorrect math verification answer. Please try again.";
        log_submission_event('WARNING', 'Math captcha incorrect', [
            'provided' => $captcha_answer,
            'expected' => $expected_answer
        ]);
    }
    
    // Reset captcha for next attempt
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
    $captcha_question = "What is " . $_SESSION['captcha_num1'] . " + " . $_SESSION['captcha_num2'] . "?";
    
    // 4. Sanitize and Validate Inputs
    $inquiry_type = filter_input(INPUT_POST, 'inquiry_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
    $agency = filter_input(INPUT_POST, 'agency', FILTER_SANITIZE_SPECIAL_CHARS);
    $service_interest = filter_input(INPUT_POST, 'service_interest', FILTER_SANITIZE_SPECIAL_CHARS);
    $project_description = filter_input(INPUT_POST, 'project_description', FILTER_SANITIZE_SPECIAL_CHARS);
    $trades_licenses = filter_input(INPUT_POST, 'trades_licenses', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (empty($inquiry_type)) $errors[] = "Inquiry type is required.";
    if (empty($name)) $errors[] = "Name is required.";
    if (!$email) $errors[] = "A valid email address is required.";
    
    if (!empty($errors)) {
        log_submission_event('WARNING', 'Validation errors blocked submission', ['errors' => $errors]);
    }

    // 5. Database Save & Email Simulation
    if (empty($errors)) {
        $lead_id = null;
        
        // Attempt database insert if DB is available
        if (isset($db)) {
            try {
                $stmt = $db->prepare("INSERT INTO lead_submissions 
                    (inquiry_type, name, company, agency, email, phone, service_interest, project_description, trades_licenses, source_page, ip_address, user_agent)
                    VALUES (:inquiry_type, :name, :company, :agency, :email, :phone, :service_interest, :project_description, :trades_licenses, :source_page, :ip_address, :user_agent)");
                
                $stmt->execute([
                    ':inquiry_type' => $inquiry_type,
                    ':name' => $name,
                    ':company' => $company,
                    ':agency' => $agency,
                    ':email' => $_POST['email'],
                    ':phone' => $phone,
                    ':service_interest' => $service_interest,
                    ':project_description' => $project_description,
                    ':trades_licenses' => $trades_licenses,
                    ':source_page' => $_SERVER['HTTP_REFERER'] ?? 'direct',
                    ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                    ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
                ]);
                
                $lead_id = $db->lastInsertId();
                log_submission_event('INFO', 'Successfully inserted lead into MySQL DB', ['lead_id' => $lead_id]);
            } catch (PDOException $e) {
                log_submission_event('WARNING', 'Database insert failed (email will still send)', ['error' => $e->getMessage()]);
            }
        } else {
            log_submission_event('WARNING', 'Database object $db is not initialized (email will still send)');
        }

        // Send to the real Private Email mailbox (info@) — erm@ is a catch-all alias with no direct SMTP delivery
        $to = 'info@rmgroupstrategies.com';
        $subject = "New Website Lead: " . ucwords(str_replace('_', ' ', $inquiry_type));
        
        // Format email body
        $mail_body = "New inquiry submitted via RM Group Strategies Website:\n\n";
        $mail_body .= "Inquiry Type: " . ucwords(str_replace('_', ' ', $inquiry_type)) . "\n";
        $mail_body .= "Name: $name\n";
        $mail_body .= "Email: " . $_POST['email'] . "\n";
        if (!empty($phone)) $mail_body .= "Phone: $phone\n";
        if (!empty($company)) $mail_body .= "Company: $company\n";
        if (!empty($agency)) $mail_body .= "Agency: $agency\n";
        if (!empty($service_interest)) $mail_body .= "Service Interest: $service_interest\n";
        if (!empty($trades_licenses)) $mail_body .= "Trades/Licenses: $trades_licenses\n";
        if (!empty($project_description)) $mail_body .= "Description:\n$project_description\n";
        
        // Write backup preview log
        $log_dir = __DIR__ . '/scratch';
        if (!file_exists($log_dir)) {
            @mkdir($log_dir, 0777, true);
        }
        $mail_preview_file = $log_dir . '/email_preview_' . ($lead_id ?? time()) . '.txt';
        @file_put_contents($mail_preview_file, "TO: $to\nSUBJECT: $subject\n\n$mail_body");
        
        // Build SMTP-compliant headers
        $headers = "From: " . SITE_NAME . " <" . SITE_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . $_POST['email'] . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Send actual email
        $mail_sent = false;
        $delivery_method = 'mail';

        if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
            $status = 'simulated';
            $mail_sent = true;
            $delivery_method = 'simulated';
        } elseif (defined('SMTP_PASS') && !empty(SMTP_PASS)) {
            $delivery_method = 'authenticated_smtp';
            require_once __DIR__ . '/includes/mailer.php';
            $mailer = new SimpleSMTPMailer(SMTP_HOST, defined('SMTP_PORT') ? SMTP_PORT : 465, SMTP_USER, SMTP_PASS, defined('SMTP_ENC') ? SMTP_ENC : 'ssl');
            list($mail_sent, $smtp_msg) = $mailer->send($to, $subject, $mail_body, SITE_EMAIL, SITE_NAME, $_POST['email']);
            $status = $mail_sent ? 'sent' : 'failed: ' . substr($smtp_msg, 0, 100);
        } else {
            $delivery_method = 'php_mail_envelope';
            $additional_params = "-f " . SITE_EMAIL;
            $mail_sent = @mail($to, $subject, $mail_body, $headers, $additional_params);
            $status = $mail_sent ? 'sent' : 'failed';
        }
        
        // Log delivery attempt in database if DB is available
        if (isset($db) && $lead_id) {
            try {
                $log_stmt = $db->prepare("INSERT INTO email_logs (lead_submission_id, recipient_email, subject, status) VALUES (:lead_id, :recipient, :subject, :status)");
                $log_stmt->execute([
                    ':lead_id' => $lead_id,
                    ':recipient' => $to,
                    ':subject' => $subject,
                    ':status' => $status
                ]);
            } catch (PDOException $ex) {
                log_submission_event('WARNING', 'Failed to insert email_log record', ['error' => $ex->getMessage()]);
            }
        }

        log_submission_event($mail_sent ? 'INFO' : 'ERROR', 'Email dispatch completed', [
            'recipient' => $to,
            'method' => $delivery_method,
            'status' => $status
        ]);
        
        if ($mail_sent) {
            $_SESSION['flash_success'] = "Thank you! Your submission has been received. Our business development team will contact you shortly.";
            // Clear draft inputs on success
            unset($_SESSION['draft_inputs']);
        } else {
            $_SESSION['flash_errors'] = ["Email delivery encountered a temporary server error. Please email us directly at info@rmgroupstrategies.com."];
        }
    } else {
        $_SESSION['flash_errors'] = $errors;
        // Maintain inputs on error
        $_SESSION['draft_inputs'] = [
            'inquiry_type' => $inquiry_type,
            'name' => $name,
            'email' => $_POST['email'] ?? '',
            'phone' => $phone,
            'company' => $company,
            'agency' => $agency,
            'service_interest' => $service_interest,
            'project_description' => $project_description,
            'trades_licenses' => $trades_licenses
        ];
    }

    if (!empty($warnings)) {
        $_SESSION['flash_warnings'] = $warnings;
    }

    // Clean HTTP 303 Redirect back to GET request (Eliminates ERR_CACHE_MISS & Confirm Form Resubmission dialogs!)
    header("Location: " . BASE_URL . "/contact.php?status=submitted#contact-portal-form");
    exit;
}

// GET Request: Retrieve and clear session flash messages
$errors = $_SESSION['flash_errors'] ?? [];
$warnings = $_SESSION['flash_warnings'] ?? [];
$success_msg = $_SESSION['flash_success'] ?? "";

// Restore draft inputs on error if available
if (!empty($_SESSION['draft_inputs'])) {
    $inquiry_type        = $_SESSION['draft_inputs']['inquiry_type'] ?? '';
    $name                = $_SESSION['draft_inputs']['name'] ?? '';
    $email               = $_SESSION['draft_inputs']['email'] ?? '';
    $phone               = $_SESSION['draft_inputs']['phone'] ?? '';
    $company             = $_SESSION['draft_inputs']['company'] ?? '';
    $agency              = $_SESSION['draft_inputs']['agency'] ?? '';
    $service_interest    = $_SESSION['draft_inputs']['service_interest'] ?? '';
    $project_description = $_SESSION['draft_inputs']['project_description'] ?? '';
    $trades_licenses     = $_SESSION['draft_inputs']['trades_licenses'] ?? '';
}

// Clear flash session data so it doesn't persist on fresh page reloads
unset($_SESSION['flash_errors'], $_SESSION['flash_success'], $_SESSION['draft_inputs']);

// Pre-select mode from URL type parameter
$url_type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
$selected_type = 'general';
if (in_array($url_type, ['consultation', 'contract', 'vendor', 'subcontractor', 'capability'])) {
    $selected_type = $url_type;
}
if (!empty($inquiry_type)) {
    $selected_type = $inquiry_type; // Maintain selection on error
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/ico.png">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/images/ico.png">

    <!-- SEO -->
    <title>Contact Our Team | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Get in touch with <?php echo SITE_NAME; ?>. Submit consultation requests, subcontracting opportunities, capability statement requests, or vendor registrations.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/contact.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Contact Our Team | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Dynamic inquiry portal for RM Group Strategies LLC. Support, consulting, contracting, and vendor channels.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/contact.php">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-black':      '#000000',
                        'brand-gold':       '#D4AF37',
                        'brand-gold-accent':'#FFD700',
                        'brand-white':      '#FFFFFF',
                        'brand-dark-gray':  '#1E1E1E',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom.css">
    
    <style>
        .form-input {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-input:focus {
            border-color: #C5A059;
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.15);
            outline: none;
        }

        .form-label {
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
            display: block;
        }
    </style>
</head>

<body class="font-inter bg-slate-50 text-slate-800">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: BREADCRUMB / HEADER BANNER
             ═══════════════════════════════════════════════════════ -->
        <section class="bg-slate-900 border-b border-slate-800 py-12 sm:py-16 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-wide text-white mb-3">
                    Contact Our Team
                </h1>
                <nav class="flex justify-center text-xs tracking-wider uppercase text-white/50" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li>
                            <a href="<?php echo BASE_URL; ?>/" class="hover:text-brand-gold transition-colors duration-300">Home</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>/</span>
                            <span class="text-brand-gold">Contact</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: DUAL CONTACT CHANNELS (INFO / FORM)
             ═══════════════════════════════════════════════════════ -->
        <section id="contact-channels" class="bg-slate-50 py-16 sm:py-20 lg:py-24 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

                    <!-- Left: Contact Information Panel (col-span-4) -->
                    <div class="lg:col-span-4 space-y-10 animate-fade-in-up animate-delay-100">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-wide mb-4">
                                Connect With Us
                            </h2>
                            <div class="h-0.5 w-12 bg-brand-gold mb-6"></div>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Our business development and compliance departments are organized to handle government contracting, subcontracting partnerships, commercial operations, and procurement. Reach out directly or select your query type in the portal.
                            </p>
                        </div>

                        <!-- Direct Contacts -->
                        <div class="space-y-6">
                            <!-- Support -->
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-slate-400 block uppercase tracking-wider text-[9px] mb-0.5">General &amp; Admin Support</span>
                                    <a href="mailto:<?php echo EMAIL_SUPPORT; ?>" class="text-slate-900 hover:text-brand-gold font-semibold transition-colors duration-300"><?php echo EMAIL_SUPPORT; ?></a>
                                </div>
                            </div>

                            <!-- Contracts -->
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296"/></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-slate-400 block uppercase tracking-wider text-[9px] mb-0.5">Government Contracting</span>
                                    <a href="mailto:<?php echo EMAIL_CONTRACTS; ?>" class="text-slate-900 hover:text-brand-gold font-semibold transition-colors duration-300"><?php echo EMAIL_CONTRACTS; ?></a>
                                </div>
                            </div>

                            <!-- Corporate Office -->
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-slate-400 block uppercase tracking-wider text-[9px] mb-0.5">Nevada HQ Location</span>
                                    <span class="text-slate-900 font-semibold"><?php echo SITE_LOCATION; ?></span>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-slate-400 block uppercase tracking-wider text-[9px] mb-0.5">Call Business Development</span>
                                    <a href="<?php echo SITE_PHONE_LINK; ?>" class="text-slate-900 hover:text-brand-gold font-semibold transition-colors duration-300"><?php echo SITE_PHONE_DISPLAY; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Dynamic Lead Form (col-span-8) -->
                    <div class="lg:col-span-8 card-executive rounded-2xl p-6 sm:p-10 shadow-xl animate-fade-in-up animate-delay-200">
                        
                        <!-- Header Message / Feedback Banners -->
                        <?php if (!empty($errors)): ?>
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-xs space-y-1.5">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                    <span class="font-bold uppercase tracking-wide text-[10px] text-red-600">Please resolve the following:</span>
                                </div>
                                <?php foreach ($errors as $error): ?>
                                    <p class="pl-6">• <?php echo $error; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success_msg)): ?>
                            <!-- ══════════════════════════════════════════
                                 PREMIUM SUCCESS STATE — Replaces form
                                 ══════════════════════════════════════════ -->
                            <style>
                                @keyframes success-pop {
                                    0%   { opacity: 0; transform: scale(0.92) translateY(16px); }
                                    60%  { transform: scale(1.02) translateY(-2px); }
                                    100% { opacity: 1; transform: scale(1) translateY(0); }
                                }
                                @keyframes check-draw {
                                    0%   { stroke-dashoffset: 60; }
                                    100% { stroke-dashoffset: 0; }
                                }
                                @keyframes ring-pulse {
                                    0%, 100% { opacity: 0.15; transform: scale(1); }
                                    50%       { opacity: 0.35; transform: scale(1.08); }
                                }
                                .success-panel {
                                    animation: success-pop 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                                }
                                .check-svg path {
                                    stroke-dasharray: 60;
                                    stroke-dashoffset: 60;
                                    animation: check-draw 0.5s 0.3s ease forwards;
                                }
                                .ring-pulse {
                                    animation: ring-pulse 2.5s ease-in-out infinite;
                                }
                            </style>

                            <div class="success-panel py-6">
                                <!-- Animated Check Icon -->
                                <div class="relative flex justify-center mb-8">
                                    <!-- Outer pulsing ring -->
                                    <div class="ring-pulse absolute w-28 h-28 rounded-full bg-amber-400/20 border border-amber-400/30"></div>
                                    <!-- Icon circle -->
                                    <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-400/30 flex items-center justify-center">
                                        <svg class="check-svg w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Heading -->
                                <div class="text-center mb-8">
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-brand-gold font-semibold mb-2">Message Received</p>
                                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mb-3">You're on Our Radar</h2>
                                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                                        <?php echo htmlspecialchars($success_msg); ?>
                                    </p>
                                </div>

                                <!-- Divider -->
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="flex-1 h-px bg-slate-200"></div>
                                    <span class="text-slate-400 text-[10px] uppercase tracking-widest font-semibold">What Happens Next</span>
                                    <div class="flex-1 h-px bg-slate-200"></div>
                                </div>

                                <!-- Next Steps -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-center">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                        </div>
                                        <p class="text-slate-900 font-semibold text-xs mb-1">Review</p>
                                        <p class="text-slate-500 text-[10px] leading-relaxed">Your inquiry is routed to our business development team for review.</p>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-center">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-slate-900 font-semibold text-xs mb-1">1–2 Business Days</p>
                                        <p class="text-slate-500 text-[10px] leading-relaxed">A representative will follow up within 1–2 business days.</p>
                                    </div>
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-center">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                        </div>
                                        <p class="text-slate-900 font-semibold text-xs mb-1">Direct Contact</p>
                                        <p class="text-slate-500 text-[10px] leading-relaxed">Need faster support? Call or email us directly any time.</p>
                                    </div>
                                </div>

                                <!-- Urgent Contact Bar -->
                                <div class="bg-amber-50/60 border border-amber-200/60 rounded-xl px-5 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
                                    <div>
                                        <p class="text-slate-700 font-semibold text-xs mb-0.5">Need an urgent response?</p>
                                        <p class="text-slate-500 text-[10px]">Reach our team directly and reference your inquiry.</p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-3 text-xs font-semibold">
                                        <a href="mailto:<?php echo EMAIL_SUPPORT; ?>" class="inline-flex items-center gap-1.5 text-slate-800 hover:text-brand-gold transition-colors duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                            <?php echo EMAIL_SUPPORT; ?>
                                        </a>
                                        <a href="<?php echo SITE_PHONE_LINK; ?>" class="inline-flex items-center gap-1.5 text-slate-800 hover:text-brand-gold transition-colors duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                            <?php echo SITE_PHONE_DISPLAY; ?>
                                        </a>
                                    </div>
                                </div>

                                <!-- CTA Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="<?php echo BASE_URL; ?>/" class="flex-1 btn-gold text-center text-xs py-3">
                                        Return to Homepage
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/contact.php" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-3 px-6 rounded-lg transition-colors duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        Submit Another Inquiry
                                    </a>
                                </div>
                            </div>

                        <?php else: ?>
                        <!-- Form Start (only shown when NO success state) -->
                        <form action="<?php echo BASE_URL; ?>/contact.php" method="POST" id="contact-portal-form" class="space-y-6">
                            
                            <!-- Hidden honeypot field for spam prevention -->
                            <div class="hidden" aria-hidden="true">
                                <label for="website">Leave this field blank:</label>
                                <input type="text" name="website" id="website" tabindex="-1" value="">
                            </div>

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <!-- Select Inquiry Type -->
                            <div>
                                <label for="inquiry_type" class="form-label">Select Inquiry Type</label>
                                <select name="inquiry_type" id="inquiry_type" class="form-input w-full rounded px-4 py-3 text-xs font-semibold" required>
                                    <option value="general" <?php echo ($selected_type === 'general') ? 'selected' : ''; ?>>General Contact</option>
                                    <option value="consultation" <?php echo ($selected_type === 'consultation') ? 'selected' : ''; ?>>Request Consultation</option>
                                    <option value="contract" <?php echo ($selected_type === 'contract') ? 'selected' : ''; ?>>Contract / Subcontracting Opportunity</option>
                                    <option value="vendor" <?php echo ($selected_type === 'vendor') ? 'selected' : ''; ?>>Vendor Registration</option>
                                    <option value="subcontractor" <?php echo ($selected_type === 'subcontractor') ? 'selected' : ''; ?>>Subcontractor Registration</option>
                                    <option value="capability" <?php echo ($selected_type === 'capability') ? 'selected' : ''; ?>>Request Capability Statement</option>
                                </select>
                            </div>

                            <!-- Form Fields Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                
                                <!-- Full Name (All) -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" name="name" id="name" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. John Doe" value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>

                                <!-- Email Address (All) -->
                                <div>
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" name="email" id="email" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. john@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>

                                <!-- Phone Number (All) -->
                                <div>
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. (702) 504-8128" value="<?php echo htmlspecialchars($phone); ?>">
                                </div>

                                <!-- Company (Consultation, Contract, Vendor, Subcontractor, Capability) -->
                                <div class="field-group" id="group-company">
                                    <label for="company" class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="company" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. Acme Corp" value="<?php echo htmlspecialchars($company); ?>">
                                </div>

                                <!-- Agency (Contract, Capability) -->
                                <div class="field-group" id="group-agency">
                                    <label for="agency" class="form-label">Procurement Agency</label>
                                    <input type="text" name="agency" id="agency" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. Department of Transportation" value="<?php echo htmlspecialchars($agency); ?>">
                                </div>

                                <!-- Service Interest dropdown (Consultation, Vendor) -->
                                <div class="sm:col-span-2 field-group" id="group-service">
                                    <label for="service_interest" class="form-label">Operational Sector Interest</label>
                                    <select name="service_interest" id="service_interest" class="form-input w-full rounded px-4 py-3 text-xs">
                                        <option value="">-- Select Sector --</option>
                                        <option value="general_consulting" <?php echo ($service_interest === 'general_consulting') ? 'selected' : ''; ?>>Business Development &amp; Consulting</option>
                                        <option value="government_contracting" <?php echo ($service_interest === 'government_contracting') ? 'selected' : ''; ?>>Government Contracting Opportunities</option>
                                        <option value="fleet_management" <?php echo ($service_interest === 'fleet_management') ? 'selected' : ''; ?>>RM Fleet Leasing &amp; Maintenance</option>
                                        <option value="construction_remodeling" <?php echo ($service_interest === 'construction_remodeling') ? 'selected' : ''; ?>>RM Remodeling (Construction)</option>
                                        <option value="tools_equipment" <?php echo ($service_interest === 'tools_equipment') ? 'selected' : ''; ?>>RM Tools &amp; Equipment Rental</option>
                                        <option value="transport_logistics" <?php echo ($service_interest === 'transport_logistics') ? 'selected' : ''; ?>>RM Transport (Logistics Routing)</option>
                                    </select>
                                </div>

                                <!-- Trades & Licenses (Subcontractor Only) -->
                                <div class="sm:col-span-2 field-group" id="group-trades">
                                    <label for="trades_licenses" class="form-label">Specialized Trades &amp; License Numbers</label>
                                    <input type="text" name="trades_licenses" id="trades_licenses" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="e.g. C-2 Electrical License #12345, Carpentry, Painting" value="<?php echo htmlspecialchars($trades_licenses); ?>">
                                </div>

                                <!-- Project / Inquiry Description (All) -->
                                <div class="sm:col-span-2">
                                    <label for="project_description" class="form-label" id="label-description">Inquiry Details *</label>
                                    <textarea name="project_description" id="project_description" rows="5" class="form-input w-full rounded px-4 py-3 text-xs" placeholder="Provide details here..." required><?php echo htmlspecialchars($project_description); ?></textarea>
                                </div>

                                <!-- Spam Capcha math verification (All) -->
                                <div class="sm:col-span-2 border-t border-white/5 pt-5">
                                    <label for="captcha_answer" class="form-label">Spam Prevention Verification *</label>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-brand-dark-gray border border-white/10 px-4 py-2.5 rounded font-bold font-mono text-xs text-brand-gold select-none">
                                            <?php echo $captcha_question; ?>
                                        </span>
                                        <input type="number" name="captcha_answer" id="captcha_answer" class="form-input w-28 rounded px-4 py-2.5 text-xs text-center" placeholder="Answer" required>
                                    </div>
                                </div>

                            </div>

                            <!-- Submit Button -->
                            <div class="border-t border-white/5 pt-6">
                                <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-widest font-bold">
                                    Submit Inquiry
                                </button>
                            </div>

                        </form>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </section>

    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Client-side Dynamic Fields Visibility Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('inquiry_type');
            
            const groupCompany = document.getElementById('group-company');
            const groupAgency = document.getElementById('group-agency');
            const groupService = document.getElementById('group-service');
            const groupTrades = document.getElementById('group-trades');
            
            const labelDesc = document.getElementById('label-description');
            const inputDesc = document.getElementById('project_description');
            
            function updateFormLayout() {
                const val = dropdown.value;
                
                // Hide all dynamic elements by default
                groupCompany.classList.add('hidden');
                groupAgency.classList.add('hidden');
                groupService.classList.add('hidden');
                groupTrades.classList.add('hidden');
                
                // Reset labels and placeholders
                labelDesc.textContent = "Inquiry Details *";
                inputDesc.placeholder = "Provide details here...";
                
                switch(val) {
                    case 'general':
                        // General contact needs minimal fields
                        break;
                    case 'consultation':
                        groupCompany.classList.remove('hidden');
                        groupService.classList.remove('hidden');
                        labelDesc.textContent = "Consultation Scope Details *";
                        inputDesc.placeholder = "Describe your consulting requirements or business goals...";
                        break;
                    case 'contract':
                        groupCompany.classList.remove('hidden');
                        groupAgency.classList.remove('hidden');
                        labelDesc.textContent = "Opportunity Specifications *";
                        inputDesc.placeholder = "Provide UEI/SAM constraints, solicitation numbers, scope details, and deadlines...";
                        break;
                    case 'vendor':
                        groupCompany.classList.remove('hidden');
                        groupService.classList.remove('hidden');
                        labelDesc.textContent = "Supplied Products & Logistics Details *";
                        inputDesc.placeholder = "Detail what supplies, equipment, or transport services you offer...";
                        break;
                    case 'subcontractor':
                        groupCompany.classList.remove('hidden');
                        groupTrades.classList.remove('hidden');
                        labelDesc.textContent = "Core Trades Experience & Team Size *";
                        inputDesc.placeholder = "List past public/commercial contracts, references, bonding capacities, and crew sizing...";
                        break;
                    case 'capability':
                        groupCompany.classList.remove('hidden');
                        groupAgency.classList.remove('hidden');
                        labelDesc.textContent = "Intended Contract Sector Scope *";
                        inputDesc.placeholder = "Briefly describe your agency requirements or the prime opportunity you are bidding...";
                        break;
                }
            }
            
            dropdown.addEventListener('change', updateFormLayout);
            updateFormLayout(); // Run immediately on load to account for pre-selection
        });
    </script>

</body>
</html>

