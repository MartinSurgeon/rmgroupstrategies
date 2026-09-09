<?php
/**
 * RM Group Strategies LLC — RM Tools & Equipment Division Page
 * Features live rental catalog, actual equipment inventory photos, contractor rental terms, rate calculator & reservation request.
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'companies';

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

// Handle Rental Form Submission
$errors = [];
$success_msg = "";

$rental_tool = $_GET['tool'] ?? '';
$client_name = '';
$client_email = '';
$client_phone = '';
$client_company = '';
$delivery_city = '';
$delivery_address = '';
$start_date = '';
$end_date = '';
$rental_notes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rental_reservation') {
    // Rate limit check: max 5 requests per 10 minutes
    $rate_check = check_submission_rate_limit('equipment_reservation', 5, 600);
    if (!$rate_check['allowed']) {
        $errors[] = $rate_check['message'];
    }

    // 1. CSRF Verification
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Security check failed. Please refresh the page and try again.";
    }

    // 2. Honeypot check
    if (!empty($_POST['website'])) {
        $errors[] = "Spam detected. Submission rejected.";
    }

    // 3. Captcha check
    $captcha_answer = isset($_POST['captcha_answer']) ? intval($_POST['captcha_answer']) : 0;
    $expected_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
    if ($captcha_answer !== $expected_answer) {
        $errors[] = "Math verification is incorrect. Please try again.";
    }

    // Reset captcha
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
    $captcha_question = "What is " . $_SESSION['captcha_num1'] . " + " . $_SESSION['captcha_num2'] . "?";

    // 4. Sanitize inputs
    $client_name      = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $client_email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $client_phone     = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $client_company   = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
    $rental_tool      = filter_input(INPUT_POST, 'equipment_item', FILTER_SANITIZE_SPECIAL_CHARS);
    $delivery_city    = filter_input(INPUT_POST, 'delivery_city', FILTER_SANITIZE_SPECIAL_CHARS);
    $delivery_address = filter_input(INPUT_POST, 'delivery_address', FILTER_SANITIZE_SPECIAL_CHARS);
    $start_date       = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $end_date         = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $rental_notes     = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($client_name)) $errors[] = "Your name is required.";
    if (!$client_email) $errors[] = "A valid email address is required.";
    if (empty($client_phone)) $errors[] = "Phone number is required for delivery coordination.";
    if (empty($rental_tool)) $errors[] = "Please select an equipment item.";
    if (empty($delivery_city)) $errors[] = "Please select a delivery city.";
    if (empty($start_date) || empty($end_date)) {
        $errors[] = "Please provide both rental start and end dates.";
    } else {
        $d1 = strtotime($start_date);
        $d2 = strtotime($end_date);
        if ($d2 < $d1) {
            $errors[] = "End date cannot be before start date.";
        } else {
            $days = max(1, round(($d2 - $d1) / (60 * 60 * 24)) + 1);
            if ($days < 2) {
                $errors[] = "Minimum rental duration is 2 days as per rental terms.";
            }
        }
    }

    if (empty($errors)) {
        // Calculate estimated cost
        $days = max(2, round((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24)) + 1);
        $weeks = floor($days / 7);
        $rem_days = $days % 7;
        
        // Pricing calculation: $100/day or $500/week ($50 deposit)
        $items_count = ($rental_tool === 'both_package') ? 2 : 1;
        $tool_rental_cost = ($weeks * 500 + $rem_days * 100) * $items_count;
        $deposit_cost = 50 * $items_count;
        $total_est = $tool_rental_cost + $deposit_cost;

        $tool_labels = [
            'jackhammer'   => 'Bauer Demolition Jackhammer ($100/day, $500/wk)',
            'blower'       => 'RedMax EBZ8560 Commercial Backpack Blower ($100/day, $500/wk)',
            'both_package' => 'Tool Package (Bauer Jackhammer + RedMax EBZ8560 Blower)'
        ];
        $tool_display = $tool_labels[$rental_tool] ?? $rental_tool;

        $project_desc = "EQUIPMENT RESERVATION REQUEST\n";
        $project_desc .= "Selected Tool: " . $tool_display . "\n";
        $project_desc .= "Rental Duration: " . $days . " days (" . $start_date . " to " . $end_date . ")\n";
        $project_desc .= "Estimated Rental Fee: $" . number_format($tool_rental_cost, 2) . " ($" . number_format($deposit_cost, 2) . " Refundable Deposit)\n";
        $project_desc .= "Estimated Total: $" . number_format($total_est, 2) . "\n";
        $project_desc .= "Delivery Zone: " . $delivery_city . "\n";
        $project_desc .= "Delivery Address: " . $delivery_address . "\n";
        if (!empty($rental_notes)) {
            $project_desc .= "Client Notes: " . $rental_notes . "\n";
        }

        // Save into database if available
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $db = new PDO($dsn, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("INSERT INTO lead_submissions 
                (inquiry_type, name, company, email, phone, service_interest, project_description, source_page, ip_address, user_agent)
                VALUES (:inquiry_type, :name, :company, :email, :phone, :service_interest, :project_description, :source_page, :ip_address, :user_agent)");
            
            $stmt->execute([
                ':inquiry_type' => 'equipment_rental',
                ':name' => $client_name,
                ':company' => $client_company,
                ':email' => $client_email,
                ':phone' => $client_phone,
                ':service_interest' => 'RM Tools & Equipment Rental',
                ':project_description' => $project_desc,
                ':source_page' => 'rm-tools-equipment.php',
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            $lead_id = $db->lastInsertId();
        } catch (Exception $e) {
            $lead_id = null;
        }

        // ── Email Generation & Multi-Tier Dispatch ─────────────────────────────
        require_once __DIR__ . '/includes/mailer.php';

        $base_domain = defined('SITE_URL') ? rtrim(SITE_URL, '/') : 'https://rmgroupstrategies.com';
        $agreement_page_url = $base_domain . (defined('BASE_URL') ? BASE_URL : '') . '/rental-agreement.php?tool=' . urlencode($rental_tool);

        $to = defined('EMAIL_EQUIPMENT') ? EMAIL_EQUIPMENT : (defined('SITE_EMAIL') ? SITE_EMAIL : 'info@rmgroupstrategies.com');
        $subject = "New Equipment Rental Request: " . $tool_display;

        // Staff Plain-Text Notification
        $mail_body = "A new equipment rental reservation request has been submitted on RM Group Strategies:\n\n";
        $mail_body .= "Client Name: " . $client_name . "\n";
        if (!empty($client_company)) $mail_body .= "Company: " . $client_company . "\n";
        $mail_body .= "Email: " . $client_email . "\n";
        $mail_body .= "Phone: " . $client_phone . "\n";
        $mail_body .= "\n----------------------------------------\n";
        $mail_body .= $project_desc;
        $mail_body .= "----------------------------------------\n\n";
        $mail_body .= "Terms Reminder:\n";
        $mail_body .= "- Minimum 2-day rental period.\n";
        $mail_body .= "- $100/day | $500/week per tool.\n";
        $mail_body .= "- $50 security deposit per tool.\n";
        $mail_body .= "- Delivery areas: Las Vegas, North Las Vegas, and Henderson.\n\n";
        $mail_body .= "Please contact the client promptly to confirm equipment availability, delivery window, and payment details.";

        // Staff HTML Notification
        $staff_html_content = '
        <div style="background-color: #f1f5f9; border-left: 4px solid #D4AF37; padding: 16px 20px; border-radius: 4px; margin-bottom: 24px;">
            <p style="margin: 0 0 6px 0; font-size: 16px; font-weight: bold; color: #0f172a;">New Equipment Reservation Request</p>
            <p style="margin: 0; font-size: 14px; color: #475569;">Equipment: <strong>' . htmlspecialchars($tool_display) . '</strong></p>
        </div>

        <h3 style="font-size: 15px; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin: 20px 0 12px 0;">Client Information</h3>
        <table width="100%" cellpadding="6" cellspacing="0" style="font-size: 14px; margin-bottom: 20px;">
            <tr><td width="35%" style="color: #64748b;">Client Name:</td><td><strong>' . htmlspecialchars($client_name) . '</strong></td></tr>
            ' . (!empty($client_company) ? '<tr><td style="color: #64748b;">Company:</td><td>' . htmlspecialchars($client_company) . '</td></tr>' : '') . '
            <tr><td style="color: #64748b;">Email:</td><td><a href="mailto:' . htmlspecialchars($client_email) . '" style="color: #D4AF37;">' . htmlspecialchars($client_email) . '</a></td></tr>
            <tr><td style="color: #64748b;">Phone:</td><td><a href="tel:' . htmlspecialchars($client_phone) . '" style="color: #0f172a; text-decoration: none; font-weight: 600;">' . htmlspecialchars($client_phone) . '</a></td></tr>
            <tr><td style="color: #64748b;">Delivery City:</td><td>' . htmlspecialchars($delivery_city) . '</td></tr>
            ' . (!empty($delivery_address) ? '<tr><td style="color: #64748b;">Delivery Address:</td><td>' . htmlspecialchars($delivery_address) . '</td></tr>' : '') . '
        </table>

        <h3 style="font-size: 15px; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin: 20px 0 12px 0;">Reservation Schedule &amp; Estimates</h3>
        <table width="100%" cellpadding="6" cellspacing="0" style="font-size: 14px; margin-bottom: 20px;">
            <tr><td width="35%" style="color: #64748b;">Duration:</td><td>' . $days . ' Days (' . htmlspecialchars($start_date) . ' to ' . htmlspecialchars($end_date) . ')</td></tr>
            <tr><td style="color: #64748b;">Estimated Rental:</td><td>$' . number_format($tool_rental_cost, 2) . '</td></tr>
            <tr><td style="color: #64748b;">Security Deposit:</td><td>$' . number_format($deposit_cost, 2) . '</td></tr>
            <tr><td style="color: #64748b;">Estimated Total:</td><td><strong style="color: #0f172a; font-size: 16px;">$' . number_format($total_est, 2) . '</strong></td></tr>
        </table>
        ' . (!empty($rental_notes) ? '<p style="font-size: 13px; color: #475569; background: #f8fafc; padding: 12px; border-radius: 4px;"><strong>Client Notes:</strong> ' . nl2br(htmlspecialchars($rental_notes)) . '</p>' : '');

        $staff_html = build_branded_email_html(
            "Equipment Reservation Request",
            "Dispatch Lead",
            $staff_html_content,
            $agreement_page_url,
            "View Full Rental Agreement Form"
        );

        // ── 1. Dispatch Operations / Staff Notification ──
        send_system_email(
            $to,
            $subject,
            $mail_body,
            $staff_html,
            $client_email, // Reply-To client
            SITE_EMAIL,
            SITE_NAME,
            $lead_id,
            isset($db) ? $db : null
        );

        // Small buffer to avoid mail server rate limit / connection bursts
        usleep(300000); // 0.3s

        // ── 2. Dispatch Customer / Client Confirmation Copy ──
        $cust_subject = "Equipment Rental Request Received — RM Tools & Equipment";

        $cust_plain = "Dear " . $client_name . ",\n\n";
        $cust_plain .= "Thank you for requesting an equipment rental reservation with RM Tools & Equipment (RM Group Strategies LLC).\n\n";
        $cust_plain .= "RESERVATION DETAILS:\n";
        $cust_plain .= "- Equipment: " . $tool_display . "\n";
        $cust_plain .= "- Duration: " . $days . " days (" . $start_date . " to " . $end_date . ")\n";
        $cust_plain .= "- Delivery Zone: " . $delivery_city . "\n";
        if (!empty($delivery_address)) $cust_plain .= "- Delivery Address: " . $delivery_address . "\n";
        $cust_plain .= "- Estimated Rental Fee: $" . number_format($tool_rental_cost, 2) . "\n";
        $cust_plain .= "- Refundable Deposit: $" . number_format($deposit_cost, 2) . "\n";
        $cust_plain .= "- Estimated Total: $" . number_format($total_est, 2) . "\n\n";
        $cust_plain .= "NEXT STEPS:\n";
        $cust_plain .= "Our dispatch coordinator will contact you at " . $client_phone . " shortly to confirm availability and schedule your delivery window.\n\n";
        $cust_plain .= "You can also pre-complete the formal online rental application and electronic signature agreement here:\n";
        $cust_plain .= $agreement_page_url . "\n\n";
        $cust_plain .= "QUESTIONS OR IMMEDIATE DISPATCH:\n";
        $cust_plain .= "Phone: " . (defined('SITE_PHONE_DISPLAY') ? SITE_PHONE_DISPLAY : '(702) 504-8128') . "\n";
        $cust_plain .= "Email: " . (defined('SITE_EMAIL') ? SITE_EMAIL : 'info@rmgroupstrategies.com') . "\n";
        $cust_plain .= "RM Group Strategies LLC — Las Vegas, NV\n";

        $cust_html_content = '
        <p style="margin: 0 0 16px 0; font-size: 16px;">Dear <strong>' . htmlspecialchars($client_name) . '</strong>,</p>
        <p style="margin: 0 0 20px 0; line-height: 1.6;">
            Thank you for contacting <strong>RM Tools &amp; Equipment</strong>. We have received your reservation request for the <strong>' . htmlspecialchars($tool_display) . '</strong>.
        </p>

        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
            <h3 style="margin: 0 0 12px 0; font-size: 14px; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                Reservation Summary
            </h3>
            <table width="100%" cellpadding="6" cellspacing="0" style="font-size: 14px;">
                <tr><td width="38%" style="color: #64748b;">Equipment:</td><td><strong>' . htmlspecialchars($tool_display) . '</strong></td></tr>
                <tr><td style="color: #64748b;">Rental Period:</td><td>' . $days . ' Days (' . htmlspecialchars($start_date) . ' to ' . htmlspecialchars($end_date) . ')</td></tr>
                <tr><td style="color: #64748b;">Delivery Area:</td><td>' . htmlspecialchars($delivery_city) . '</td></tr>
                <tr><td style="color: #64748b;">Estimated Rental:</td><td>$' . number_format($tool_rental_cost, 2) . '</td></tr>
                <tr><td style="color: #64748b;">Refundable Deposit:</td><td>$' . number_format($deposit_cost, 2) . '</td></tr>
                <tr><td style="color: #64748b;">Estimated Total:</td><td><strong style="color: #0f172a; font-size: 16px;">$' . number_format($total_est, 2) . '</strong></td></tr>
            </table>
        </div>

        <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 14px 18px; border-radius: 4px; margin-bottom: 24px;">
            <p style="margin: 0 0 4px 0; font-weight: 600; color: #1e40af; font-size: 14px;">What Happens Next?</p>
            <p style="margin: 0; font-size: 13px; color: #1e3a8a; line-height: 1.5;">
                Our equipment dispatch coordinator will review unit availability and contact you at <strong>' . htmlspecialchars($client_phone) . '</strong> to confirm your delivery window and delivery address access.
            </p>
        </div>

        <p style="font-size: 14px; color: #475569; line-height: 1.6;">
            To expedite your rental checkout and schedule immediate delivery, you can complete the online rental application &amp; electronic signature agreement here:
        </p>';

        $cust_html = build_branded_email_html(
            "Reservation Request Received",
            "RM Tools & Equipment",
            $cust_html_content,
            $agreement_page_url,
            "Complete E-Sign Rental Agreement"
        );

        send_system_email(
            $client_email,
            $cust_subject,
            $cust_plain,
            $cust_html,
            SITE_EMAIL, // Reply-To company
            SITE_EMAIL,
            SITE_NAME,
            $lead_id,
            isset($db) ? $db : null
        );

        $success_msg = "Thank you, " . htmlspecialchars($client_name) . "! Your rental reservation request for the " . htmlspecialchars($tool_display) . " has been received. Our equipment dispatch team will contact you at " . htmlspecialchars($client_phone) . " shortly to confirm delivery to " . htmlspecialchars($delivery_city) . " and finalize your reservation.";
        
        // Clear fields on success
        $client_name = $client_email = $client_phone = $client_company = $delivery_city = $delivery_address = $start_date = $end_date = $rental_notes = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/ico.png">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/images/ico.png">

    <!-- SEO -->
    <title>RM Tools &amp; Equipment | Tool &amp; Equipment Rentals Las Vegas | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Commercial tool and equipment rentals in Las Vegas, North Las Vegas, and Henderson. Rent Bauer demolition jackhammers and RedMax EBZ8560 commercial backpack blowers at $100/day or $500/week with jobsite delivery.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/rm-tools-equipment.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="RM Tools &amp; Equipment | Rental Rates &amp; Catalog | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Rent professional Bauer jackhammers and RedMax EBZ8560 commercial blowers with direct jobsite delivery in Las Vegas, North Las Vegas, and Henderson. $100/day, $500/week.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/rm-tools-equipment.php">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-black':      '#000000',
                        'brand-navy':       '#0B1727',
                        'brand-gold':       '#C5A059',
                        'brand-gold-accent':'#D4AF37',
                        'brand-white':      '#FFFFFF',
                        'brand-dark-gray':  '#0F172A',
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
</head>
<body class="bg-slate-50 font-inter text-slate-800 antialiased selection:bg-brand-gold selection:text-white">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Division Hero Banner -->
    <section class="relative bg-brand-navy py-20 lg:py-28 overflow-hidden text-white border-b border-brand-gold/30">
        <div class="absolute inset-0 z-0 opacity-25">
            <img src="<?php echo BASE_URL; ?>/assets/images/rm-tools-equipment.png" alt="RM Tools Banner" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0B1727] via-[#0B1727]/95 to-[#0B1727]/75 z-1"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-gold/15 border border-brand-gold/40 text-brand-gold text-xs font-semibold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-gold animate-pulse"></span>
                    <span>Operating Division of RM Group Strategies</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    RM Tools &amp; Equipment
                </h1>
                <p class="text-lg text-slate-200 leading-relaxed mb-8">
                    Commercial-grade construction tool rentals, industrial equipment leasing, and fast jobsite delivery across <strong class="text-brand-gold font-semibold">Las Vegas, North Las Vegas, and Henderson</strong>.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="#rental-inventory" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-7 shadow-lg shadow-brand-gold/20">
                        View Available Tools &amp; Rates
                    </a>
                    <a href="<?php echo BASE_URL; ?>/rental-agreement.php" class="btn-outline-white text-xs uppercase tracking-widest font-semibold py-3.5 px-7">
                        Complete E-Sign Agreement
                    </a>
                    <a href="#reservation-form" class="text-slate-300 hover:text-brand-gold text-xs uppercase tracking-widest font-semibold py-2 px-3 underline">
                        Quick Booking Form
                    </a>
                </div>

                <!-- Quick Highlights Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-12 pt-8 border-t border-white/10 text-xs">
                    <div>
                        <span class="text-brand-gold font-bold block text-sm sm:text-base">$100 / Day</span>
                        <span class="text-slate-300">Daily Contractor Rate</span>
                    </div>
                    <div>
                        <span class="text-brand-gold font-bold block text-sm sm:text-base">$500 / Week</span>
                        <span class="text-slate-300">Discounted Weekly Rate</span>
                    </div>
                    <div>
                        <span class="text-brand-gold font-bold block text-sm sm:text-base">2-Day Min</span>
                        <span class="text-slate-300">Flexible Rental Period</span>
                    </div>
                    <div>
                        <span class="text-brand-gold font-bold block text-sm sm:text-base">LV Metro</span>
                        <span class="text-slate-300">Jobsite Delivery Available</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rental Terms & Policies Overview Banner -->
    <section class="bg-[#0f1d32] border-b border-brand-gold/20 py-10 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Contractor Terms &amp; Conditions</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white">Transparent, Straightforward Rental Terms</h2>
                <div class="gold-divider-sm mx-auto mt-3"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Policy 1 -->
                <div class="bg-brand-navy/80 border border-brand-gold/30 rounded-xl p-6 relative overflow-hidden group hover:border-brand-gold transition-all duration-300">
                    <div class="w-10 h-10 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-base mb-1">$100 / Day • $500 / Wk</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Flat contractor daily rate or cost-saving weekly package (save $200 per week).</p>
                </div>

                <!-- Policy 2 -->
                <div class="bg-brand-navy/80 border border-brand-gold/30 rounded-xl p-6 relative overflow-hidden group hover:border-brand-gold transition-all duration-300">
                    <div class="w-10 h-10 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-base mb-1">Minimum 2-Day Rental</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">All tool rentals require a 2-day minimum booking to support scheduling and logistics.</p>
                </div>

                <!-- Policy 3 -->
                <div class="bg-brand-navy/80 border border-brand-gold/30 rounded-xl p-6 relative overflow-hidden group hover:border-brand-gold transition-all duration-300">
                    <div class="w-10 h-10 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-base mb-1">$50 Security Deposit</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Low flat deposit per tool, fully reconciled and refunded upon return inspection.</p>
                </div>

                <!-- Policy 4 -->
                <div class="bg-brand-navy/80 border border-brand-gold/30 rounded-xl p-6 relative overflow-hidden group hover:border-brand-gold transition-all duration-300">
                    <div class="w-10 h-10 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-white font-bold text-base mb-1">Local Jobsite Delivery</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Direct delivery service across <strong>Las Vegas</strong>, <strong>North Las Vegas</strong>, and <strong>Henderson</strong>.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Success / Error Notifications -->
    <?php if (!empty($success_msg)): ?>
    <section class="max-w-5xl mx-auto px-4 pt-10">
        <div class="p-6 rounded-2xl bg-emerald-950/90 border-2 border-emerald-500 text-emerald-100 shadow-2xl flex items-start gap-4 animate-fade-in-up">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 border border-emerald-400 flex-shrink-0 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-white mb-1">Reservation Request Received!</h3>
                <p class="text-sm text-emerald-200 leading-relaxed"><?php echo $success_msg; ?></p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <section class="max-w-5xl mx-auto px-4 pt-10">
        <div class="p-6 rounded-2xl bg-rose-950/90 border-2 border-rose-500 text-rose-100 shadow-2xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-500/20 border border-rose-400 flex-shrink-0 flex items-center justify-center text-rose-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-white mb-1">Please correct the following:</h3>
                <ul class="list-disc list-inside text-sm text-rose-200 space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════
         FEATURED AVAILABLE EQUIPMENT (LIVE INVENTORY WITH ACTUAL PHOTOS)
         ═══════════════════════════════════════════════════════ -->
    <section id="rental-inventory" class="py-20 lg:py-24 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Available for Immediate Rental</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Featured In-Stock Equipment &amp; Rates</h2>
                <div class="gold-divider-lg mx-auto mb-4"></div>
                <p class="text-slate-600 text-sm sm:text-base">Actual equipment ready for immediate delivery to your jobsite in Las Vegas, North Las Vegas, and Henderson.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

                <!-- ITEM 1: BAUER HEAVY-DUTY DEMOLITION JACKHAMMER -->
                <div class="bg-white border-2 border-brand-gold/40 rounded-2xl shadow-xl overflow-hidden flex flex-col justify-between hover:shadow-2xl hover:border-brand-gold transition-all duration-300 transform hover:-translate-y-1">
                    <div>
                        <!-- Card Header & Badge -->
                        <div class="bg-[#0B1727] text-white p-6 border-b border-brand-gold/30">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    In Stock &amp; Ready
                                </span>
                                <span class="text-brand-gold font-bold text-xs uppercase tracking-wider">Demolition Hammer</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white tracking-tight">Bauer Heavy-Duty Demolition Jackhammer</h3>
                            <p class="text-slate-300 text-xs mt-1">Concrete, Asphalt &amp; Masonry Demolition Breaker</p>
                        </div>

                        <!-- Real Equipment Photo Showcase -->
                        <div class="relative h-64 bg-slate-900 overflow-hidden border-b border-slate-200 group">
                            <img src="<?php echo BASE_URL; ?>/assets/images/equipment/bauer-demolition-jackhammer.jpg" alt="Bauer Heavy-Duty Demolition Jackhammer" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur-sm text-white text-[11px] px-3 py-1 rounded-md border border-white/20 font-medium">
                                Actual Tool Photo
                            </div>
                        </div>

                        <!-- Card Body & Pricing Block -->
                        <div class="p-6 sm:p-8">
                            <!-- Price Highlight Box -->
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-6">
                                <div class="grid grid-cols-2 gap-4 divide-x divide-slate-200">
                                    <div class="text-center pr-2">
                                        <span class="text-slate-500 text-xs font-medium uppercase tracking-wider block">Daily Rate</span>
                                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">$100 <span class="text-xs font-normal text-slate-500">/day</span></div>
                                        <span class="text-[11px] text-amber-700 font-semibold mt-1 inline-block">Min. 2 Days</span>
                                    </div>
                                    <div class="text-center pl-2">
                                        <span class="text-brand-gold text-xs font-bold uppercase tracking-wider block">Weekly Special</span>
                                        <div class="text-2xl sm:text-3xl font-extrabold text-[#0B1727] mt-1">$500 <span class="text-xs font-normal text-slate-500">/wk</span></div>
                                        <span class="text-[11px] text-emerald-700 font-bold mt-1 inline-block">Save $200 / week!</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                                    <span><strong>Deposit:</strong> $50 refundable</span>
                                    <span><strong>Delivery:</strong> LV, North LV &amp; Henderson</span>
                                </div>
                            </div>

                            <!-- Features / Specs List -->
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tool Specifications &amp; Inclusions:</h4>
                            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 mb-6">
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>High-Impact Breaker Power:</strong> Professional Bauer demolition motor designed to break concrete slabs, foundations, footings, brick, and asphalt.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Anti-Vibration Dual Grip:</strong> Ergonomic shock-damping side handles allow maximum downward pressure with minimized user fatigue.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Heavy-Duty Chisel Included:</strong> Supplied with durable steel chisel bit and long commercial-grade power cord.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Tested &amp; Jobsite-Ready:</strong> Thoroughly inspected, lubricated, and electrically tested before delivery.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200">
                        <a href="#reservation-form" onclick="selectEquipment('jackhammer')" class="btn-gold w-full text-center text-xs uppercase tracking-widest font-bold py-3.5 block shadow-md">
                            Reserve Bauer Jackhammer ($100/day)
                        </a>
                    </div>
                </div>

                <!-- ITEM 2: REDMAX EBZ8560 COMMERCIAL BACKPACK BLOWER -->
                <div class="bg-white border-2 border-brand-gold/40 rounded-2xl shadow-xl overflow-hidden flex flex-col justify-between hover:shadow-2xl hover:border-brand-gold transition-all duration-300 transform hover:-translate-y-1">
                    <div>
                        <!-- Card Header & Badge -->
                        <div class="bg-[#0B1727] text-white p-6 border-b border-brand-gold/30">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    In Stock &amp; Ready
                                </span>
                                <span class="text-brand-gold font-bold text-xs uppercase tracking-wider">Commercial Backpack Blower</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white tracking-tight">RedMax EBZ8560 Commercial Blower</h3>
                            <p class="text-slate-300 text-xs mt-1">High-CFM Industrial Jobsite Backpack Blower</p>
                        </div>

                        <!-- Real Equipment Photo Showcase -->
                        <div class="relative h-64 bg-slate-900 overflow-hidden border-b border-slate-200 group">
                            <img src="<?php echo BASE_URL; ?>/assets/images/equipment/redmax-ebz8560-blower.jpg" alt="RedMax EBZ8560 Commercial Backpack Blower" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute bottom-3 right-3 bg-black/75 backdrop-blur-sm text-white text-[11px] px-3 py-1 rounded-md border border-white/20 font-medium">
                                Actual Tool Photo (Model EBZ8560)
                            </div>
                        </div>

                        <!-- Card Body & Pricing Block -->
                        <div class="p-6 sm:p-8">
                            <!-- Price Highlight Box -->
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-6">
                                <div class="grid grid-cols-2 gap-4 divide-x divide-slate-200">
                                    <div class="text-center pr-2">
                                        <span class="text-slate-500 text-xs font-medium uppercase tracking-wider block">Daily Rate</span>
                                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">$100 <span class="text-xs font-normal text-slate-500">/day</span></div>
                                        <span class="text-[11px] text-amber-700 font-semibold mt-1 inline-block">Min. 2 Days</span>
                                    </div>
                                    <div class="text-center pl-2">
                                        <span class="text-brand-gold text-xs font-bold uppercase tracking-wider block">Weekly Special</span>
                                        <div class="text-2xl sm:text-3xl font-extrabold text-[#0B1727] mt-1">$500 <span class="text-xs font-normal text-slate-500">/wk</span></div>
                                        <span class="text-[11px] text-emerald-700 font-bold mt-1 inline-block">Save $200 / week!</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                                    <span><strong>Deposit:</strong> $50 refundable</span>
                                    <span><strong>Delivery:</strong> LV, North LV &amp; Henderson</span>
                                </div>
                            </div>

                            <!-- Features / Specs List -->
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tool Specifications &amp; Inclusions:</h4>
                            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 mb-6">
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Industry-Leading RedMax EBZ8560 Power:</strong> 75.6cc Strato-Charged commercial 2-stroke engine with extreme clearing velocity.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Jobsite Debris &amp; Dust Clearance:</strong> Rapidly clears concrete dust, construction debris, asphalt clippings, turf, and large sites.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Heavy-Duty Backpack Comfort Harness:</strong> Ergonomic contoured back pad and wide shoulder straps designed for long contractor shifts.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span><strong>Complete Tube Assembly:</strong> Full length blower pipe, flexible elbow, and tube-mounted throttle lever with cruise control.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200">
                        <a href="#reservation-form" onclick="selectEquipment('blower')" class="btn-gold w-full text-center text-xs uppercase tracking-widest font-bold py-3.5 block shadow-md">
                            Reserve RedMax Blower ($100/day)
                        </a>
                    </div>
                </div>

            </div>

            <!-- Two Tool Combo Bundle Note -->
            <div class="max-w-5xl mx-auto mt-8 bg-brand-navy border border-brand-gold/40 rounded-xl p-6 text-white flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-brand-gold/20 border border-brand-gold/40 flex items-center justify-center text-brand-gold flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-white">Need Both the Bauer Jackhammer &amp; RedMax Blower?</h4>
                        <p class="text-slate-300 text-xs">Bundle both tools into a single scheduled delivery. Select "Equipment Package (Both Tools)" in the reservation form below.</p>
                    </div>
                </div>
                <a href="#reservation-form" onclick="selectEquipment('both_package')" class="btn-gold text-xs uppercase tracking-widest font-bold py-3 px-6 whitespace-nowrap">
                    Reserve Both Tools
                </a>
            </div>

            <!-- E-Sign Agreement Banner -->
            <div class="max-w-5xl mx-auto mt-6 bg-gradient-to-r from-[#0B1727] via-[#10233d] to-[#0B1727] border-2 border-brand-gold/50 rounded-2xl p-6 sm:p-8 text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold/20 border border-brand-gold text-brand-gold text-[11px] font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Official Nevada E-Sign Application
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-white">Need to Complete Your Rental Paperwork?</h3>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                        Complete and electronically sign the official <strong>Online Rental Application + E-Sign Agreement</strong> in advance to ensure rapid jobsite delivery upon booking verification.
                    </p>
                </div>
                <a href="<?php echo BASE_URL; ?>/rental-agreement.php" class="btn-gold text-xs uppercase tracking-widest font-extrabold py-3.5 px-7 whitespace-nowrap shadow-lg shadow-brand-gold/25 flex items-center gap-2">
                    <span>Fill E-Sign Agreement</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         HOW IT WORKS: 3-STEP RESERVATION WORKFLOW
         ═══════════════════════════════════════════════════════ -->
    <section class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Hassle-Free Process</span>
                <h2 class="text-3xl font-extrabold text-slate-900">How to Rent Equipment in 3 Easy Steps</h2>
                <div class="gold-divider-sm mx-auto mt-3"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center p-6 rounded-xl bg-slate-50 border border-slate-200 relative">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#0B1727] text-brand-gold font-extrabold text-lg flex items-center justify-center mb-4 border-2 border-brand-gold">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Submit Online Request</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Choose your tool, dates (minimum 2 days), and jobsite location in Las Vegas, North Las Vegas, or Henderson.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center p-6 rounded-xl bg-slate-50 border border-slate-200 relative">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#0B1727] text-brand-gold font-extrabold text-lg flex items-center justify-center mb-4 border-2 border-brand-gold">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Dispatch Confirmation</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Our equipment team verifies availability, confirms your delivery time window, and finalizes reservation details.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center p-6 rounded-xl bg-slate-50 border border-slate-200 relative">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#0B1727] text-brand-gold font-extrabold text-lg flex items-center justify-center mb-4 border-2 border-brand-gold">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Jobsite Delivery</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">We deliver inspected, ready-to-work equipment directly to your site. When completed, we schedule prompt pickup.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         ONLINE RESERVATION & COST ESTIMATOR FORM
         ═══════════════════════════════════════════════════════ -->
    <section id="reservation-form" class="py-20 lg:py-24 bg-brand-navy text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(197,160,89,0.5) 35px, rgba(197,160,89,0.5) 36px);"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Book Your Equipment</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Equipment Rental Reservation Request</h2>
                <div class="gold-divider-lg mx-auto my-4"></div>
                <p class="text-slate-300 text-sm max-w-xl mx-auto">Submit your reservation details below. No upfront online charge is required today — our team will confirm your dates and delivery schedule directly.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-[#0f1e33] border border-brand-gold/40 rounded-2xl p-6 sm:p-10 shadow-2xl">
                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>#reservation-form" method="POST" class="space-y-6" id="equipmentRentalForm">
                    <input type="hidden" name="action" value="rental_reservation">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <!-- Honeypot Anti-Spam -->
                    <div style="display:none;">
                        <label for="website_hp">Leave empty</label>
                        <input type="text" name="website" id="website_hp" autocomplete="off">
                    </div>

                    <!-- Equipment Selection -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-gold mb-2">
                            1. Select Equipment to Rent <span class="text-rose-400">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-center p-3.5 rounded-xl border border-white/15 bg-white/5 hover:border-brand-gold cursor-pointer transition-all duration-200 has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/15">
                                <input type="radio" name="equipment_item" value="jackhammer" id="radio-jackhammer" class="text-brand-gold focus:ring-brand-gold" <?php echo ($rental_tool === 'jackhammer' || empty($rental_tool)) ? 'checked' : ''; ?> onchange="calculateRentalTotal()">
                                <span class="ml-2.5 text-xs sm:text-sm font-semibold text-white">Bauer Jackhammer <br><span class="text-xs text-brand-gold font-normal">$100/day • $500/wk</span></span>
                            </label>

                            <label class="flex items-center p-3.5 rounded-xl border border-white/15 bg-white/5 hover:border-brand-gold cursor-pointer transition-all duration-200 has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/15">
                                <input type="radio" name="equipment_item" value="blower" id="radio-blower" class="text-brand-gold focus:ring-brand-gold" <?php echo ($rental_tool === 'blower') ? 'checked' : ''; ?> onchange="calculateRentalTotal()">
                                <span class="ml-2.5 text-xs sm:text-sm font-semibold text-white">RedMax EBZ8560 Blower <br><span class="text-xs text-brand-gold font-normal">$100/day • $500/wk</span></span>
                            </label>

                            <label class="flex items-center p-3.5 rounded-xl border border-white/15 bg-white/5 hover:border-brand-gold cursor-pointer transition-all duration-200 has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/15">
                                <input type="radio" name="equipment_item" value="both_package" id="radio-both" class="text-brand-gold focus:ring-brand-gold" <?php echo ($rental_tool === 'both_package') ? 'checked' : ''; ?> onchange="calculateRentalTotal()">
                                <span class="ml-2.5 text-xs sm:text-sm font-semibold text-white">Both Tools Package <br><span class="text-xs text-brand-gold font-normal">Jackhammer + Blower</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Rental Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Start Date <span class="text-rose-400">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($start_date); ?>" min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold" onchange="calculateRentalTotal()">
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                End Date <span class="text-rose-400">*</span> <span class="text-[11px] text-brand-gold font-normal">(Min. 2 Days)</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" required value="<?php echo htmlspecialchars($end_date); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold" onchange="calculateRentalTotal()">
                        </div>
                    </div>

                    <!-- Dynamic Cost Estimator Box -->
                    <div id="cost-estimator-box" class="bg-brand-navy/90 border border-brand-gold/50 rounded-xl p-4 sm:p-5 text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <div class="text-brand-gold font-bold uppercase tracking-wider text-xs mb-1">Estimated Rental Summary</div>
                            <div id="calc-duration-text" class="text-slate-300 text-xs">Select your rental dates to compute estimate (2-day minimum).</div>
                        </div>
                        <div class="text-right sm:border-l sm:border-white/10 sm:pl-6">
                            <span class="text-slate-400 text-xs block">Estimated Rate Total</span>
                            <div id="calc-total-display" class="text-xl sm:text-2xl font-extrabold text-white">$250.00</div>
                            <span id="calc-deposit-note" class="text-[10px] text-brand-gold block">Includes $50 refundable deposit</span>
                        </div>
                    </div>

                    <!-- Delivery City & Location -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label for="delivery_city" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Delivery City <span class="text-rose-400">*</span>
                            </label>
                            <select id="delivery_city" name="delivery_city" required class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold">
                                <option value="">— Select Service Area —</option>
                                <option value="Las Vegas" <?php echo ($delivery_city === 'Las Vegas') ? 'selected' : ''; ?>>Las Vegas, NV</option>
                                <option value="North Las Vegas" <?php echo ($delivery_city === 'North Las Vegas') ? 'selected' : ''; ?>>North Las Vegas, NV</option>
                                <option value="Henderson" <?php echo ($delivery_city === 'Henderson') ? 'selected' : ''; ?>>Henderson, NV</option>
                                <option value="Other Area (Call to Confirm)" <?php echo ($delivery_city === 'Other Area (Call to Confirm)') ? 'selected' : ''; ?>>Other Metro Area (Call to Confirm)</option>
                            </select>
                        </div>

                        <div>
                            <label for="delivery_address" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Jobsite / Delivery Address <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="delivery_address" name="delivery_address" required value="<?php echo htmlspecialchars($delivery_address); ?>" placeholder="Street Address, Suite or Unit #" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500">
                        </div>
                    </div>

                    <!-- Customer Contact Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Contact Name <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($client_name); ?>" placeholder="Your Full Name" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500">
                        </div>

                        <div>
                            <label for="company" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Company / Contractor Name <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($client_company); ?>" placeholder="Business or Contractor Name" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Phone Number <span class="text-rose-400">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required value="<?php echo htmlspecialchars($client_phone); ?>" placeholder="(702) 555-0199" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                                Email Address <span class="text-rose-400">*</span>
                            </label>
                            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($client_email); ?>" placeholder="name@company.com" class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500">
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-200 mb-1.5">
                            Job Details / Special Instructions <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Provide any gate codes, site access notes, or project requirements..." class="w-full px-4 py-3 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold placeholder:text-slate-500"><?php echo htmlspecialchars($rental_notes); ?></textarea>
                    </div>

                    <!-- Math Verification Captcha -->
                    <div class="bg-black/30 p-4 rounded-xl border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <label for="captcha_answer" class="text-xs font-medium text-slate-300">
                            Security Verification: <strong class="text-brand-gold"><?php echo htmlspecialchars($captcha_question, ENT_QUOTES, 'UTF-8'); ?></strong>
                        </label>
                        <input type="number" id="captcha_answer" name="captcha_answer" required placeholder="Answer" class="w-28 px-3 py-2 rounded-lg bg-[#0B1727] border border-white/20 text-white text-sm focus:outline-none focus:border-brand-gold text-center">
                    </div>

                    <!-- Terms Acknowledgment -->
                    <div class="text-xs text-slate-300 flex items-start gap-2 pt-2">
                        <input type="checkbox" id="agree_terms" required checked class="mt-1 text-brand-gold focus:ring-brand-gold rounded">
                        <label for="agree_terms">
                            I understand that equipment rentals require a 2-day minimum rental, a $50 refundable deposit per tool, and delivery is available within Las Vegas, North Las Vegas, and Henderson.
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-gold w-full text-center text-xs uppercase tracking-widest font-extrabold py-4 px-8 shadow-xl shadow-brand-gold/25 block">
                        Submit Equipment Reservation Request
                    </button>
                    <p class="text-center text-[11px] text-slate-400">
                        Need immediate assistance? Call our Equipment Dispatch at <a href="<?php echo SITE_PHONE_LINK; ?>" class="text-brand-gold underline font-semibold"><?php echo SITE_PHONE_DISPLAY; ?></a>.
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Additional Categories Overview (Future Expansion) -->
    <section class="py-20 lg:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Fleet Scope</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight mb-4">Additional Equipment Capabilities</h2>
                <div class="gold-divider-lg mx-auto mb-4"></div>
                <p class="text-slate-600 text-sm sm:text-base">In addition to specialty contractor tools, RM Tools &amp; Equipment provides comprehensive equipment support for commercial and government contractors.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Heavy Machinery &amp; Earthmoving</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Compact excavators, skid steers, backhoes, trenchers, and material handling units for civil, commercial, and municipal sites.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Jobsite Power &amp; Lighting</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Towable diesel generators, temporary power distribution boxes, LED mobile light towers, and industrial air compressors.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l.7.7a2.5 2.5 0 010 3.54l-5.1 5.1m0 0l5.1 5.1"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Commercial Contractor Tools</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Demolition jackhammers, high-CFM industrial blowers, core drills, compaction plates, scaffolding, and submersibles.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Image Section -->
    <section class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Fleet Reliability</span>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Inspected &amp; Jobsite-Ready Equipment</h2>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        RM Tools &amp; Equipment maintains rigorous safety and performance standards. Every tool and machine is tested, serviced, and cleaned prior to delivery to ensure maximum uptime on your jobsite.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            $100 Daily &amp; $500 Weekly Rate Plans
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Direct Delivery to Las Vegas, North Las Vegas &amp; Henderson
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Responsive Contractor Support &amp; Fast Dispatch
                        </li>
                    </ul>
                    <a href="#reservation-form" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-8">
                        Request Tool Reservation
                    </a>
                </div>
                <div class="rounded-2xl overflow-hidden border border-brand-gold/30 shadow-2xl">
                    <img src="<?php echo BASE_URL; ?>/assets/images/rm-tools-equipment.png" alt="RM Tools Showcase" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
    // Quick tool select helper from cards
    function selectEquipment(itemVal) {
        const radio = document.querySelector('input[name="equipment_item"][value="' + itemVal + '"]');
        if (radio) {
            radio.checked = true;
            calculateRentalTotal();
        }
    }

    // Interactive Rental Cost Estimator
    function calculateRentalTotal() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const durationText = document.getElementById('calc-duration-text');
        const totalDisplay = document.getElementById('calc-total-display');
        const depositNote = document.getElementById('calc-deposit-note');

        const selectedToolRadio = document.querySelector('input[name="equipment_item"]:checked');
        const selectedTool = selectedToolRadio ? selectedToolRadio.value : 'jackhammer';

        const multiplier = (selectedTool === 'both_package') ? 2 : 1;
        const depositPerItem = 50;
        const totalDeposit = depositPerItem * multiplier;

        if (!startInput.value || !endInput.value) {
            const toolName = (selectedTool === 'both_package') ? 'Both Tools (Bauer Jackhammer + RedMax Blower)' : (selectedTool === 'blower' ? 'RedMax EBZ8560 Blower' : 'Bauer Heavy-Duty Jackhammer');
            durationText.innerHTML = 'Selected: <strong class="text-white">' + toolName + '</strong>. Pick start and end dates (min. 2 days) to calculate total.';
            totalDisplay.innerText = '$' + (200 * multiplier + totalDeposit).toFixed(2);
            depositNote.innerText = 'Includes $' + totalDeposit + ' refundable security deposit';
            return;
        }

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (end < start) {
            durationText.innerHTML = '<span class="text-rose-400 font-semibold">End date must be after start date.</span>';
            totalDisplay.innerText = '--';
            return;
        }

        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // inclusive days

        if (diffDays < 2) {
            durationText.innerHTML = '<span class="text-amber-400 font-semibold">Note: Minimum rental duration is 2 days ($100/day per tool).</span>';
        }

        const effectiveDays = Math.max(2, diffDays);
        const weeks = Math.floor(effectiveDays / 7);
        const remDays = effectiveDays % 7;

        const rentalFee = (weeks * 500 + remDays * 100) * multiplier;
        const totalCost = rentalFee + totalDeposit;

        let breakdownText = effectiveDays + ' day rental period';
        if (weeks > 0) {
            breakdownText += ' (' + weeks + ' wk' + (weeks > 1 ? 's' : '') + (remDays > 0 ? ' + ' + remDays + ' day' + (remDays > 1 ? 's' : '') : '') + ')';
        }
        if (selectedTool === 'both_package') {
            breakdownText += ' for 2 tools (Bauer Jackhammer & RedMax Blower)';
        }

        durationText.innerHTML = '<strong class="text-white">' + breakdownText + '</strong>: $' + rentalFee.toFixed(2) + ' rental fee + $' + totalDeposit + ' deposit';
        totalDisplay.innerText = '$' + totalCost.toFixed(2);
        depositNote.innerText = 'Includes $' + totalDeposit + ' refundable security deposit';
    }

    // Set default dates if empty
    window.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        if (startInput && !startInput.value) {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const dayAfter = new Date(today);
            dayAfter.setDate(dayAfter.getDate() + 3);

            startInput.value = tomorrow.toISOString().split('T')[0];
            endInput.value = dayAfter.toISOString().split('T')[0];
        }
        calculateRentalTotal();
    });
    </script>
</body>
</html>
