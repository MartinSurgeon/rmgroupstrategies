<?php
/**
 * RM Group Strategies LLC — RM Tools & Equipment Division
 * Online Rental Application + E-Sign Agreement
 * 
 * Formal Nevada Rental Agreement & Electronic Signature capture
 * Entity: RM Nevada Series LLC - Tools & Equipment
 */
session_start();
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

// Ensure rental_agreements table exists
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS rental_agreements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        agreement_id VARCHAR(64) UNIQUE NOT NULL,
        rental_type VARCHAR(50) NOT NULL DEFAULT 'individual',
        legal_name VARCHAR(255) NOT NULL,
        business_name VARCHAR(255) DEFAULT NULL,
        billing_address TEXT NOT NULL,
        city_state_zip VARCHAR(255) NOT NULL,
        mobile_phone VARCHAR(50) NOT NULL,
        alternate_phone VARCHAR(50) DEFAULT NULL,
        email VARCHAR(255) NOT NULL,
        driver_license_no VARCHAR(100) NOT NULL,
        driver_license_state VARCHAR(50) NOT NULL,
        driver_license_exp VARCHAR(50) NOT NULL,
        date_of_birth VARCHAR(50) DEFAULT NULL,
        authorized_rep VARCHAR(255) DEFAULT NULL,
        emergency_contact_name VARCHAR(255) DEFAULT NULL,
        emergency_contact_phone VARCHAR(50) DEFAULT NULL,
        fulfillment_type VARCHAR(50) NOT NULL DEFAULT 'delivery',
        jobsite_address TEXT NOT NULL,
        jobsite_city_state_zip VARCHAR(255) NOT NULL,
        project_type TEXT DEFAULT NULL,
        authorized_operators TEXT DEFAULT NULL,
        equipment_schedule LONGTEXT NOT NULL,
        rental_start_date DATE NOT NULL,
        rental_start_time VARCHAR(20) DEFAULT NULL,
        rental_return_date DATE NOT NULL,
        rental_return_time VARCHAR(20) DEFAULT NULL,
        rental_duration_days INT NOT NULL DEFAULT 2,
        estimated_rental_charge DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        security_deposit DECIMAL(10,2) NOT NULL DEFAULT 50.00,
        delivery_pickup_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        damage_waiver_status VARCHAR(20) DEFAULT 'declined',
        damage_waiver_fee DECIMAL(10,2) DEFAULT 0.00,
        taxes_amount DECIMAL(10,2) DEFAULT 0.00,
        estimated_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        payment_method VARCHAR(50) DEFAULT 'credit_card',
        cardholder_name VARCHAR(255) DEFAULT NULL,
        card_last_four VARCHAR(4) DEFAULT NULL,
        card_exp VARCHAR(10) DEFAULT NULL,
        insurance_carrier VARCHAR(255) DEFAULT NULL,
        insurance_policy_no VARCHAR(100) DEFAULT NULL,
        insurance_exp VARCHAR(50) DEFAULT NULL,
        coi_status VARCHAR(50) DEFAULT 'not_required',
        acknowledgments_accepted TINYINT(1) DEFAULT 1,
        terms_accepted TINYINT(1) DEFAULT 1,
        signature_type VARCHAR(50) DEFAULT 'drawn',
        signature_data LONGTEXT DEFAULT NULL,
        signer_printed_name VARCHAR(255) NOT NULL,
        signer_ip VARCHAR(45) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (Exception $e) {
    $log_dir = __DIR__ . '/scratch';
    if (!file_exists($log_dir)) @mkdir($log_dir, 0777, true);
    @file_put_contents($log_dir . '/submission_debug.log', "[" . date('Y-m-d H:i:s') . "] [DB INIT ERROR] " . $e->getMessage() . "\n", FILE_APPEND);
}

// Form state variables
$errors = [];
$completed_agreement = null;

// Handle direct view of an existing signed agreement (e.g. ?view=RM-AGR-2026-XXXXX)
if (isset($_GET['view']) && !empty($_GET['view']) && isset($db)) {
    try {
        $view_stmt = $db->prepare("SELECT * FROM rental_agreements WHERE agreement_id = :aid LIMIT 1");
        $view_stmt->execute([':aid' => trim($_GET['view'])]);
        $row = $view_stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $completed_agreement = [
                'agreement_id'           => $row['agreement_id'],
                'legal_name'             => $row['legal_name'],
                'business_name'          => $row['business_name'],
                'billing_address'        => $row['billing_address'],
                'city_state_zip'         => $row['city_state_zip'],
                'mobile_phone'           => $row['mobile_phone'],
                'email'                  => $row['email'],
                'driver_license_no'      => $row['driver_license_no'],
                'driver_license_state'   => $row['driver_license_state'],
                'driver_license_exp'     => $row['driver_license_exp'],
                'fulfillment_type'       => $row['fulfillment_type'],
                'jobsite_address'        => $row['jobsite_address'],
                'jobsite_city_state_zip' => $row['jobsite_city_state_zip'],
                'project_type'           => $row['project_type'],
                'equipment_list'         => json_decode($row['equipment_schedule'], true) ?: [],
                'start_date'             => $row['rental_start_date'],
                'start_time'             => $row['rental_start_time'] ?: '08:00 AM',
                'return_date'            => $row['rental_return_date'],
                'return_time'            => $row['rental_return_time'] ?: '05:00 PM',
                'days'                   => $row['rental_duration_days'],
                'total_rental_charge'    => $row['estimated_rental_charge'],
                'total_security_deposit' => $row['security_deposit'],
                'estimated_total'        => $row['estimated_total'],
                'payment_method'         => $row['payment_method'],
                'card_last_four'         => $row['card_last_four'],
                'signature_type'         => $row['signature_type'],
                'signature_data'         => $row['signature_data'],
                'signer_printed_name'    => $row['signer_printed_name'],
                'signer_ip'              => $row['signer_ip'],
                'signed_timestamp'       => $row['created_at']
            ];
        }
    } catch (Exception $e) {
        // Query error logged
    }
}

// Initial pre-fill query parameters if any
$param_tool = $_GET['tool'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_esign_agreement') {
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

    // Section A: Customer Info
    $rental_type            = filter_input(INPUT_POST, 'rental_type', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'individual';
    $legal_name             = filter_input(INPUT_POST, 'legal_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $business_name          = filter_input(INPUT_POST, 'business_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $billing_address        = filter_input(INPUT_POST, 'billing_address', FILTER_SANITIZE_SPECIAL_CHARS);
    $city_state_zip         = filter_input(INPUT_POST, 'city_state_zip', FILTER_SANITIZE_SPECIAL_CHARS);
    $mobile_phone           = filter_input(INPUT_POST, 'mobile_phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $alternate_phone        = filter_input(INPUT_POST, 'alternate_phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $email                  = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $driver_license_no      = filter_input(INPUT_POST, 'driver_license_no', FILTER_SANITIZE_SPECIAL_CHARS);
    $driver_license_state   = filter_input(INPUT_POST, 'driver_license_state', FILTER_SANITIZE_SPECIAL_CHARS);
    $driver_license_exp     = filter_input(INPUT_POST, 'driver_license_exp', FILTER_SANITIZE_SPECIAL_CHARS);
    $date_of_birth          = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_SPECIAL_CHARS);
    $authorized_rep         = filter_input(INPUT_POST, 'authorized_rep', FILTER_SANITIZE_SPECIAL_CHARS);
    $emergency_contact_name = filter_input(INPUT_POST, 'emergency_contact_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $emergency_contact_phone= filter_input(INPUT_POST, 'emergency_contact_phone', FILTER_SANITIZE_SPECIAL_CHARS);

    // Section B: Location / Jobsite
    $fulfillment_type       = filter_input(INPUT_POST, 'fulfillment_type', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'delivery';
    $jobsite_address        = filter_input(INPUT_POST, 'jobsite_address', FILTER_SANITIZE_SPECIAL_CHARS);
    $jobsite_city_state_zip = filter_input(INPUT_POST, 'jobsite_city_state_zip', FILTER_SANITIZE_SPECIAL_CHARS);
    $project_type           = filter_input(INPUT_POST, 'project_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $authorized_operators   = filter_input(INPUT_POST, 'authorized_operators', FILTER_SANITIZE_SPECIAL_CHARS);

    // Section C & D: Equipment & Rates
    $tools_selected         = $_POST['tools'] ?? [];
    $start_date             = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $start_time             = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_SPECIAL_CHARS) ?: '08:00 AM';
    $return_date            = filter_input(INPUT_POST, 'return_date', FILTER_SANITIZE_SPECIAL_CHARS);
    $return_time            = filter_input(INPUT_POST, 'return_time', FILTER_SANITIZE_SPECIAL_CHARS) ?: '05:00 PM';
    $damage_waiver_status   = filter_input(INPUT_POST, 'damage_waiver', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'declined';

    // Section E: Payment & Insurance
    $payment_method         = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'credit_card';
    $cardholder_name        = filter_input(INPUT_POST, 'cardholder_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $card_last_four         = filter_input(INPUT_POST, 'card_last_four', FILTER_SANITIZE_SPECIAL_CHARS);
    $card_exp               = filter_input(INPUT_POST, 'card_exp', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurance_carrier      = filter_input(INPUT_POST, 'insurance_carrier', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurance_policy_no    = filter_input(INPUT_POST, 'insurance_policy_no', FILTER_SANITIZE_SPECIAL_CHARS);
    $insurance_exp          = filter_input(INPUT_POST, 'insurance_exp', FILTER_SANITIZE_SPECIAL_CHARS);
    $coi_status             = filter_input(INPUT_POST, 'coi_status', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'not_required';

    // Section G & I: Acknowledgments & Signature
    $ack_1                  = isset($_POST['ack_1']);
    $ack_2                  = isset($_POST['ack_2']);
    $ack_3                  = isset($_POST['ack_3']);
    $ack_4                  = isset($_POST['ack_4']);
    $ack_5                  = isset($_POST['ack_5']);
    $ack_6                  = isset($_POST['ack_6']);
    $ack_7                  = isset($_POST['ack_7']);
    $e_consent_agreed       = isset($_POST['e_consent_agreed']);

    $signature_type         = filter_input(INPUT_POST, 'signature_type', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'drawn';
    $signature_data         = $_POST['signature_data'] ?? '';
    $signer_printed_name    = filter_input(INPUT_POST, 'signer_printed_name', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validations
    if (empty($legal_name)) $errors[] = "Legal customer/signer name is required.";
    if (empty($billing_address) || empty($city_state_zip)) $errors[] = "Billing address and City/State/ZIP are required.";
    if (empty($mobile_phone)) $errors[] = "Mobile phone number is required.";
    if (!$email) $errors[] = "A valid email address is required for sending the signed agreement copy.";
    if (empty($driver_license_no) || empty($driver_license_state)) $errors[] = "Driver's license / Government ID number and state are required.";
    if (empty($jobsite_address)) $errors[] = "Jobsite / Delivery address is required.";
    
    if (empty($start_date) || empty($return_date)) {
        $errors[] = "Rental start and return dates are required.";
    } else {
        $d1 = strtotime($start_date);
        $d2 = strtotime($return_date);
        if ($d2 < $d1) {
            $errors[] = "Return date cannot be before start date.";
        } else {
            $days = max(1, round(($d2 - $d1) / (60 * 60 * 24)) + 1);
            if ($days < 2) {
                $errors[] = "Minimum rental period is 2 days.";
            }
        }
    }

    if (empty($tools_selected) || !is_array($tools_selected)) {
        $errors[] = "Please select at least one tool / equipment item to rent.";
    }

    if (!$ack_1 || !$ack_2 || !$ack_3 || !$ack_4 || !$ack_5 || !$ack_6 || !$ack_7) {
        $errors[] = "All 7 customer acknowledgment checkboxes in Section G must be accepted to proceed.";
    }

    if (!$e_consent_agreed) {
        $errors[] = "You must select the Electronic Consent & Terms acceptance box in Section I.";
    }

    if (empty($signer_printed_name)) {
        $errors[] = "Signer printed legal name is required in Section I.";
    }

    if (empty($signature_data)) {
        $errors[] = "An electronic signature is required. Please draw your signature or type your name.";
    }

    if (empty($errors)) {
        // Calculate Schedule and Rates
        $days = max(2, round((strtotime($return_date) - strtotime($start_date)) / (60 * 60 * 24)) + 1);
        $weeks = floor($days / 7);
        $rem_days = $days % 7;

        $equipment_list = [];
        $total_rental_charge = 0;
        $total_security_deposit = 0;

        foreach ($tools_selected as $tool_key) {
            if ($tool_key === 'jackhammer') {
                $tool_cost = ($weeks * 500) + ($rem_days * 100);
                $total_rental_charge += $tool_cost;
                $total_security_deposit += 50.00;
                $equipment_list[] = [
                    'qty' => 1,
                    'tool' => 'Bauer Heavy-Duty Demolition Jackhammer',
                    'serial' => 'BAU-DH15-NV',
                    'daily_rate' => 100.00,
                    'weekly_rate' => 500.00,
                    'rate_type' => ($weeks > 0 && $rem_days == 0) ? 'Weekly' : 'Daily/Weekly',
                    'item_charge' => $tool_cost,
                    'replacement_value' => 850.00
                ];
            } elseif ($tool_key === 'blower') {
                $tool_cost = ($weeks * 500) + ($rem_days * 100);
                $total_rental_charge += $tool_cost;
                $total_security_deposit += 50.00;
                $equipment_list[] = [
                    'qty' => 1,
                    'tool' => 'RedMax EBZ8560 Commercial Backpack Blower',
                    'serial' => 'RMX-EBZ8560-NV',
                    'daily_rate' => 100.00,
                    'weekly_rate' => 500.00,
                    'rate_type' => ($weeks > 0 && $rem_days == 0) ? 'Weekly' : 'Daily/Weekly',
                    'item_charge' => $tool_cost,
                    'replacement_value' => 750.00
                ];
            }
        }

        $damage_waiver_fee = ($damage_waiver_status === 'accepted') ? (15.00 * count($equipment_list) * $days) : 0.00;
        $delivery_pickup_fee = 0.00; // Local delivery included or invoiced
        $taxes_amount = 0.00;
        $estimated_total = $total_rental_charge + $total_security_deposit + $damage_waiver_fee + $delivery_pickup_fee + $taxes_amount;

        // Generate Agreement ID & Audit Data
        $agreement_id = 'RM-AGR-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $signer_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $signed_timestamp = date('Y-m-d H:i:s');

        // Save to Database
        try {
            $stmt = $db->prepare("INSERT INTO rental_agreements (
                agreement_id, rental_type, legal_name, business_name, billing_address, city_state_zip,
                mobile_phone, alternate_phone, email, driver_license_no, driver_license_state, driver_license_exp,
                date_of_birth, authorized_rep, emergency_contact_name, emergency_contact_phone,
                fulfillment_type, jobsite_address, jobsite_city_state_zip, project_type, authorized_operators,
                equipment_schedule, rental_start_date, rental_start_time, rental_return_date, rental_return_time,
                rental_duration_days, estimated_rental_charge, security_deposit, delivery_pickup_fee,
                damage_waiver_status, damage_waiver_fee, taxes_amount, estimated_total,
                payment_method, cardholder_name, card_last_four, card_exp,
                insurance_carrier, insurance_policy_no, insurance_exp, coi_status,
                acknowledgments_accepted, terms_accepted, signature_type, signature_data,
                signer_printed_name, signer_ip, status
            ) VALUES (
                :agreement_id, :rental_type, :legal_name, :business_name, :billing_address, :city_state_zip,
                :mobile_phone, :alternate_phone, :email, :driver_license_no, :driver_license_state, :driver_license_exp,
                :date_of_birth, :authorized_rep, :emergency_contact_name, :emergency_contact_phone,
                :fulfillment_type, :jobsite_address, :jobsite_city_state_zip, :project_type, :authorized_operators,
                :equipment_schedule, :rental_start_date, :rental_start_time, :rental_return_date, :rental_return_time,
                :rental_duration_days, :estimated_rental_charge, :security_deposit, :delivery_pickup_fee,
                :damage_waiver_status, :damage_waiver_fee, :taxes_amount, :estimated_total,
                :payment_method, :cardholder_name, :card_last_four, :card_exp,
                :insurance_carrier, :insurance_policy_no, :insurance_exp, :coi_status,
                1, 1, :signature_type, :signature_data,
                :signer_printed_name, :signer_ip, 'pending'
            )");

            $stmt->execute([
                ':agreement_id'             => $agreement_id,
                ':rental_type'               => $rental_type,
                ':legal_name'                => $legal_name,
                ':business_name'             => $business_name,
                ':billing_address'           => $billing_address,
                ':city_state_zip'            => $city_state_zip,
                ':mobile_phone'              => $mobile_phone,
                ':alternate_phone'           => $alternate_phone,
                ':email'                     => $email,
                ':driver_license_no'         => $driver_license_no,
                ':driver_license_state'      => $driver_license_state,
                ':driver_license_exp'        => $driver_license_exp,
                ':date_of_birth'             => $date_of_birth,
                ':authorized_rep'            => $authorized_rep,
                ':emergency_contact_name'    => $emergency_contact_name,
                ':emergency_contact_phone'   => $emergency_contact_phone,
                ':fulfillment_type'          => $fulfillment_type,
                ':jobsite_address'           => $jobsite_address,
                ':jobsite_city_state_zip'    => $jobsite_city_state_zip,
                ':project_type'              => $project_type,
                ':authorized_operators'      => $authorized_operators,
                ':equipment_schedule'        => json_encode($equipment_list),
                ':rental_start_date'         => $start_date,
                ':rental_start_time'         => $start_time,
                ':rental_return_date'        => $return_date,
                ':rental_return_time'        => $return_time,
                ':rental_duration_days'      => $days,
                ':estimated_rental_charge'   => $total_rental_charge,
                ':security_deposit'          => $total_security_deposit,
                ':delivery_pickup_fee'       => $delivery_pickup_fee,
                ':damage_waiver_status'      => $damage_waiver_status,
                ':damage_waiver_fee'         => $damage_waiver_fee,
                ':taxes_amount'              => $taxes_amount,
                ':estimated_total'           => $estimated_total,
                ':payment_method'            => $payment_method,
                ':cardholder_name'           => $cardholder_name,
                ':card_last_four'            => $card_last_four,
                ':card_exp'                  => $card_exp,
                ':insurance_carrier'         => $insurance_carrier,
                ':insurance_policy_no'       => $insurance_policy_no,
                ':insurance_exp'             => $insurance_exp,
                ':coi_status'                => $coi_status,
                ':signature_type'            => $signature_type,
                ':signature_data'            => $signature_data,
                ':signer_printed_name'       => $signer_printed_name,
                ':signer_ip'                 => $signer_ip
            ]);
        } catch (Exception $e) {
            $log_dir = __DIR__ . '/scratch';
            if (!file_exists($log_dir)) @mkdir($log_dir, 0777, true);
            @file_put_contents($log_dir . '/submission_debug.log', "[" . date('Y-m-d H:i:s') . "] [DB INSERT ERROR] " . $e->getMessage() . "\n", FILE_APPEND);
        }

        // Email Dispatch
        $to = defined('EMAIL_EQUIPMENT') ? EMAIL_EQUIPMENT : 'info@rmgroupstrategies.com';
        $subject = "SIGNED RENTAL AGREEMENT [" . $agreement_id . "] — " . $legal_name;

        $mail_body = "RM TOOLS & EQUIPMENT — SIGNED ONLINE RENTAL APPLICATION + E-SIGN AGREEMENT\n";
        $mail_body .= "Agreement ID: " . $agreement_id . "\n";
        $mail_body .= "Timestamp: " . $signed_timestamp . " UTC\n";
        $mail_body .= "Signer IP: " . $signer_ip . "\n\n";
        $mail_body .= "========================================================\n";
        $mail_body .= "SECTION A: CUSTOMER / BUSINESS APPLICATION\n";
        $mail_body .= "Rental Type: " . ucfirst($rental_type) . "\n";
        $mail_body .= "Legal Name: " . $legal_name . "\n";
        if (!empty($business_name)) $mail_body .= "Business Name: " . $business_name . "\n";
        $mail_body .= "Billing Address: " . $billing_address . ", " . $city_state_zip . "\n";
        $mail_body .= "Phone: " . $mobile_phone . " | Email: " . $email . "\n";
        $mail_body .= "Driver License: " . $driver_license_no . " (" . $driver_license_state . ") Exp: " . $driver_license_exp . "\n";
        if (!empty($emergency_contact_name)) $mail_body .= "Emergency Contact: " . $emergency_contact_name . " (" . $emergency_contact_phone . ")\n\n";
        $mail_body .= "SECTION B: LOCATION & JOBSITE\n";
        $mail_body .= "Fulfillment: " . ucfirst($fulfillment_type) . "\n";
        $mail_body .= "Jobsite Address: " . $jobsite_address . ", " . $jobsite_city_state_zip . "\n";
        if (!empty($project_type)) $mail_body .= "Project Type: " . $project_type . "\n\n";
        $mail_body .= "SECTION C & D: EQUIPMENT SCHEDULE & CHARGES\n";
        $mail_body .= "Rental Period: " . $days . " days (" . $start_date . " " . $start_time . " to " . $return_date . " " . $return_time . ")\n";
        foreach ($equipment_list as $eq) {
            $mail_body .= "- " . $eq['qty'] . "x " . $eq['tool'] . " | Rate: $" . number_format($eq['item_charge'], 2) . "\n";
        }
        $mail_body .= "Estimated Rental Fee: $" . number_format($total_rental_charge, 2) . "\n";
        $mail_body .= "Security Deposit: $" . number_format($total_security_deposit, 2) . " (Refundable)\n";
        $mail_body .= "Estimated Total: $" . number_format($estimated_total, 2) . "\n\n";
        $mail_body .= "SECTION E: PAYMENT & INSURANCE\n";
        $mail_body .= "Payment Method: " . ucwords(str_replace('_', ' ', $payment_method)) . "\n";
        if (!empty($card_last_four)) $mail_body .= "Card on File (Last 4): **** " . $card_last_four . " (Exp: " . $card_exp . ")\n\n";
        $mail_body .= "SECTION I: E-SIGNATURE & LEGAL CONSENT\n";
        $mail_body .= "Signer Printed Name: " . $signer_printed_name . "\n";
        $mail_body .= "Signature Type: " . ucfirst($signature_type) . "\n";
        $mail_body .= "Electronic Consent: Agreed & Legally Bound under Nevada UCC Article 2A.\n";

        // Dispatch email
        $mail_sent_staff = false;
        $mail_sent_client = false;

        if (defined('SMTP_PASS') && !empty(SMTP_PASS)) {
            require_once __DIR__ . '/includes/mailer.php';
            $mailer = new SimpleSMTPMailer(SMTP_HOST, defined('SMTP_PORT') ? SMTP_PORT : 465, SMTP_USER, SMTP_PASS, defined('SMTP_ENC') ? SMTP_ENC : 'ssl');
            list($mail_sent_staff, $msg1) = $mailer->send($to, $subject, $mail_body, SITE_EMAIL, SITE_NAME, $email);
            // Also send copy directly to client
            list($mail_sent_client, $msg2) = $mailer->send($email, "Your Signed Rental Agreement — RM Tools & Equipment [" . $agreement_id . "]", $mail_body, SITE_EMAIL, SITE_NAME);
            
            $log_dir = __DIR__ . '/scratch';
            if (!file_exists($log_dir)) @mkdir($log_dir, 0777, true);
            @file_put_contents($log_dir . '/submission_debug.log', "[" . date('Y-m-d H:i:s') . "] [EMAIL SMTP] Staff sent: " . ($mail_sent_staff ? 'YES' : 'NO') . ", Client sent: " . ($mail_sent_client ? 'YES' : 'NO') . "\n", FILE_APPEND);
        } else {
            $log_dir = __DIR__ . '/scratch';
            if (!file_exists($log_dir)) @mkdir($log_dir, 0777, true);
            @file_put_contents($log_dir . '/signed_agreement_' . $agreement_id . '.txt', "TO: $to\nCLIENT COPY TO: $email\nSUBJECT: $subject\n\n$mail_body");
            @file_put_contents($log_dir . '/submission_debug.log', "[" . date('Y-m-d H:i:s') . "] [EMAIL SIMULATED] Saved to scratch/signed_agreement_" . $agreement_id . ".txt\n", FILE_APPEND);
        }

        $completed_agreement = [
            'agreement_id'           => $agreement_id,
            'legal_name'             => $legal_name,
            'business_name'          => $business_name,
            'billing_address'        => $billing_address,
            'city_state_zip'         => $city_state_zip,
            'mobile_phone'           => $mobile_phone,
            'email'                  => $email,
            'driver_license_no'      => $driver_license_no,
            'driver_license_state'   => $driver_license_state,
            'driver_license_exp'     => $driver_license_exp,
            'fulfillment_type'       => $fulfillment_type,
            'jobsite_address'        => $jobsite_address,
            'jobsite_city_state_zip' => $jobsite_city_state_zip,
            'project_type'           => $project_type,
            'equipment_list'         => $equipment_list,
            'start_date'             => $start_date,
            'start_time'             => $start_time,
            'return_date'            => $return_date,
            'return_time'            => $return_time,
            'days'                   => $days,
            'total_rental_charge'    => $total_rental_charge,
            'total_security_deposit' => $total_security_deposit,
            'estimated_total'        => $estimated_total,
            'payment_method'         => $payment_method,
            'card_last_four'         => $card_last_four,
            'signature_type'         => $signature_type,
            'signature_data'         => $signature_data,
            'signer_printed_name'    => $signer_printed_name,
            'signer_ip'              => $signer_ip,
            'signed_timestamp'       => $signed_timestamp
        ];
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

    <!-- SEO -->
    <title>Online Rental Application &amp; E-Sign Agreement | RM Tools &amp; Equipment | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Online tool and equipment rental application and electronic signature agreement for RM Nevada Series LLC - Tools & Equipment in Las Vegas, Nevada.">
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                        'signature': ['Dancing Script', 'cursive'],
                    },
                },
            },
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom.css">

    <style>
        @media print {
            header, footer, nav, #site-header, .no-print, [class*="h-16"], [class*="h-20"] {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: 1px solid #C5A059 !important;
                border-radius: 0 !important;
                page-break-inside: auto !important;
            }
            @page {
                size: auto;
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-slate-900 font-inter text-slate-800 antialiased selection:bg-brand-gold selection:text-white min-h-screen flex flex-col">

    <div class="no-print">
        <?php include __DIR__ . '/includes/header.php'; ?>
    </div>

    <main class="flex-grow py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <?php if ($completed_agreement): ?>
            <!-- ═══════════════════════════════════════════════════════
                 SIGNED CONFIRMATION / OFFICIAL RECEIPT VIEW
                 ═══════════════════════════════════════════════════════ -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-brand-gold/50 print-container animate-fade-in-up">
                <!-- Receipt Top Status -->
                <div class="bg-[#0B1727] text-white p-6 sm:p-8 border-b-4 border-brand-gold">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Electronically Signed &amp; Recorded</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Rental Agreement &amp; Application Record</h1>
                            <p class="text-xs text-brand-gold mt-1 font-semibold">RM Nevada Series LLC - Tools &amp; Equipment</p>
                        </div>
                        <div class="text-left sm:text-right no-print">
                            <span class="text-xs text-slate-400 block">Agreement ID</span>
                            <span class="text-base sm:text-lg font-mono font-bold text-white"><?php echo $completed_agreement['agreement_id']; ?></span>
                            <button type="button" onclick="triggerPrintAgreement()" class="mt-3 btn-gold text-xs uppercase tracking-widest font-bold py-2.5 px-5 flex items-center gap-2 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Print / Save PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Printable Document Body -->
                <div class="p-6 sm:p-10 space-y-8 text-xs sm:text-sm text-slate-800">
                    
                    <!-- Audit Summary Strip -->
                    <div class="bg-slate-100 p-4 rounded-xl border border-slate-300 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div><strong>Agreement ID:</strong> <span class="font-mono"><?php echo $completed_agreement['agreement_id']; ?></span></div>
                        <div><strong>Signed Date/Time:</strong> <?php echo $completed_agreement['signed_timestamp']; ?> UTC</div>
                        <div><strong>Signer Audit IP:</strong> <?php echo $completed_agreement['signer_ip']; ?></div>
                    </div>

                    <!-- Customer & Jobsite Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-200 pb-6">
                        <div>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-brand-navy border-b border-slate-300 pb-1.5 mb-2">Customer / Lessee</h3>
                            <p class="font-bold text-slate-900 text-sm"><?php echo htmlspecialchars($completed_agreement['legal_name']); ?></p>
                            <?php if (!empty($completed_agreement['business_name'])): ?>
                                <p class="text-slate-600"><strong>Company:</strong> <?php echo htmlspecialchars($completed_agreement['business_name']); ?></p>
                            <?php endif; ?>
                            <p class="text-slate-600"><strong>Billing:</strong> <?php echo htmlspecialchars($completed_agreement['billing_address'] . ', ' . $completed_agreement['city_state_zip']); ?></p>
                            <p class="text-slate-600"><strong>Phone:</strong> <?php echo htmlspecialchars($completed_agreement['mobile_phone']); ?></p>
                            <p class="text-slate-600"><strong>Email:</strong> <?php echo htmlspecialchars($completed_agreement['email']); ?></p>
                            <p class="text-slate-600"><strong>Driver's License:</strong> <?php echo htmlspecialchars($completed_agreement['driver_license_no'] . ' (' . $completed_agreement['driver_license_state'] . ') Exp: ' . $completed_agreement['driver_license_exp']); ?></p>
                        </div>

                        <div>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-brand-navy border-b border-slate-300 pb-1.5 mb-2">Rental Jobsite &amp; Logistics</h3>
                            <p class="text-slate-600"><strong>Fulfillment:</strong> <?php echo ucfirst($completed_agreement['fulfillment_type']); ?></p>
                            <p class="text-slate-600"><strong>Jobsite Address:</strong> <?php echo htmlspecialchars($completed_agreement['jobsite_address'] . ', ' . $completed_agreement['jobsite_city_state_zip']); ?></p>
                            <p class="text-slate-600"><strong>Start Date:</strong> <?php echo $completed_agreement['start_date'] . ' @ ' . $completed_agreement['start_time']; ?></p>
                            <p class="text-slate-600"><strong>Return Date:</strong> <?php echo $completed_agreement['return_date'] . ' @ ' . $completed_agreement['return_time']; ?></p>
                            <p class="text-slate-600"><strong>Duration:</strong> <?php echo $completed_agreement['days']; ?> Days (Min. 2 Days)</p>
                        </div>
                    </div>

                    <!-- Equipment Schedule Table -->
                    <div>
                        <h3 class="font-bold text-xs uppercase tracking-wider text-brand-navy border-b border-slate-300 pb-1.5 mb-3">Equipment Schedule &amp; Rates</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border border-slate-200">
                                <thead class="bg-[#0B1727] text-white">
                                    <tr>
                                        <th class="p-2.5">Qty</th>
                                        <th class="p-2.5">Tool / Equipment Description</th>
                                        <th class="p-2.5">Asset / Serial #</th>
                                        <th class="p-2.5 text-right">Daily Rate</th>
                                        <th class="p-2.5 text-right">Weekly Rate</th>
                                        <th class="p-2.5 text-right">Charge</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php foreach ($completed_agreement['equipment_list'] as $eq): ?>
                                    <tr>
                                        <td class="p-2.5 font-bold"><?php echo htmlspecialchars($eq['qty'] ?? '1'); ?></td>
                                        <td class="p-2.5 font-semibold text-slate-900"><?php echo htmlspecialchars($eq['tool'] ?? 'Equipment Item'); ?></td>
                                        <td class="p-2.5 font-mono text-slate-600"><?php echo htmlspecialchars($eq['serial'] ?? 'BAU-DH15-NV'); ?></td>
                                        <td class="p-2.5 text-right">$<?php echo number_format($eq['daily_rate'] ?? 100.00, 2); ?></td>
                                        <td class="p-2.5 text-right">$<?php echo number_format($eq['weekly_rate'] ?? 500.00, 2); ?></td>
                                        <td class="p-2.5 text-right font-bold text-slate-900">$<?php echo number_format($eq['item_charge'] ?? $eq['charge'] ?? 100.00, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-slate-50 font-bold border-t-2 border-slate-300">
                                    <tr>
                                        <td colspan="5" class="p-2.5 text-right">Estimated Rental Charge:</td>
                                        <td class="p-2.5 text-right">$<?php echo number_format($completed_agreement['total_rental_charge'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="p-2.5 text-right">Security Deposit (Refundable Hold):</td>
                                        <td class="p-2.5 text-right">$<?php echo number_format($completed_agreement['total_security_deposit'], 2); ?></td>
                                    </tr>
                                    <tr class="text-sm bg-brand-navy/10 text-[#0B1727]">
                                        <td colspan="5" class="p-3 text-right font-extrabold">ESTIMATED TOTAL:</td>
                                        <td class="p-3 text-right font-extrabold text-brand-navy">$<?php echo number_format($completed_agreement['estimated_total'], 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- E-Signature Block -->
                    <div class="border-t-2 border-slate-300 pt-6">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-brand-navy mb-4">Section I: Electronic Consent &amp; Signature Record</h3>
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-300 grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                            <div>
                                <span class="text-xs text-slate-500 block mb-1">Lessee Electronic Signature:</span>
                                <?php if ($completed_agreement['signature_type'] === 'drawn' && strpos($completed_agreement['signature_data'], 'data:image') === 0): ?>
                                    <img src="<?php echo $completed_agreement['signature_data']; ?>" alt="Lessee Signature" class="h-16 max-w-xs border-b-2 border-slate-400">
                                <?php else: ?>
                                    <div class="font-signature text-3xl text-brand-navy border-b-2 border-slate-400 py-1">
                                        <?php echo htmlspecialchars($completed_agreement['signature_data']); ?>
                                    </div>
                                <?php endif; ?>
                                <span class="text-xs text-slate-700 font-bold mt-2 block">Printed Name: <?php echo htmlspecialchars($completed_agreement['signer_printed_name']); ?></span>
                            </div>

                            <div class="text-xs text-slate-600 space-y-1 sm:border-l sm:border-slate-300 sm:pl-6">
                                <p><strong>Electronic Consent:</strong> Confirmed &amp; Accepted</p>
                                <p><strong>Governing Law:</strong> Nevada UCC Article 2A</p>
                                <p><strong>Timestamp:</strong> <?php echo $completed_agreement['signed_timestamp']; ?> UTC</p>
                                <p><strong>Audit IP:</strong> <?php echo $completed_agreement['signer_ip']; ?></p>
                                <p class="text-emerald-700 font-semibold mt-2">✓ Verified Electronic Transaction</p>
                            </div>
                        </div>
                    </div>

                    <div class="no-print pt-6 flex flex-wrap gap-4 justify-between items-center border-t border-slate-200">
                        <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="text-xs font-bold text-slate-600 hover:text-brand-gold flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Tools &amp; Equipment
                        </a>
                        <button type="button" onclick="triggerPrintAgreement()" class="btn-gold text-xs uppercase tracking-widest font-bold py-3 px-6 shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print / Save Agreement Copy
                        </button>
                    </div>

                </div>
            </div>

            <?php else: ?>

            <!-- ═══════════════════════════════════════════════════════
                 ONLINE RENTAL APPLICATION & E-SIGN FORM
                 ═══════════════════════════════════════════════════════ -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-brand-gold/40 animate-fade-in-up">
                
                <!-- Document Header -->
                <div class="bg-[#0B1727] text-white p-6 sm:p-10 border-b-4 border-brand-gold relative">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold/15 border border-brand-gold/40 text-brand-gold text-xs font-bold uppercase tracking-wider mb-3">
                                Official Nevada Rental Agreement
                            </span>
                            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                                RM TOOLS &amp; EQUIPMENT
                            </h1>
                            <div class="text-base sm:text-xl text-brand-gold font-bold tracking-wide mt-1">
                                Online Rental Application + E-Sign Agreement
                            </div>
                            <p class="text-xs text-slate-300 mt-2 font-medium">
                                RM Nevada Series LLC - Tools &amp; Equipment &bull; Use: Customer application, rental checkout, electronic consent, and signature record for Nevada tool/equipment rentals.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                <div class="m-6 p-5 rounded-xl bg-rose-50 border-2 border-rose-400 text-rose-800 text-xs sm:text-sm">
                    <div class="flex items-center gap-2 font-bold mb-2 text-rose-900">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Please complete required agreement fields:
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/rental-agreement.php" method="POST" id="eSignAgreementForm" class="p-6 sm:p-10 space-y-10">
                    <input type="hidden" name="action" value="submit_esign_agreement">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <!-- Honeypot -->
                    <div style="display:none;">
                        <input type="text" name="website" autocomplete="off">
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION A: CUSTOMER / BUSINESS APPLICATION
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">A. CUSTOMER / BUSINESS APPLICATION</h2>
                            <span class="text-[11px] text-brand-gold">Lessee Profile</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Rental Type -->
                            <div class="sm:col-span-2 flex flex-wrap gap-6 items-center bg-slate-50 p-3.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <span class="text-slate-700">Rental Type: <span class="text-rose-500">*</span></span>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="rental_type" value="individual" checked class="text-brand-gold focus:ring-brand-gold">
                                    <span>Individual / Consumer</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="rental_type" value="commercial" class="text-brand-gold focus:ring-brand-gold">
                                    <span>Business / Commercial</span>
                                </label>
                            </div>

                            <!-- Legal Name -->
                            <div>
                                <label for="legal_name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Legal Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="legal_name" name="legal_name" required placeholder="Full Legal Name" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none" oninput="syncPrintedName(this.value)">
                            </div>

                            <!-- Business Name -->
                            <div>
                                <label for="business_name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Business Name (if any)
                                </label>
                                <input type="text" id="business_name" name="business_name" placeholder="Company or DBA" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- Billing Address -->
                            <div>
                                <label for="billing_address" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Billing Address <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="billing_address" name="billing_address" required placeholder="Street Address" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- City / State / ZIP -->
                            <div>
                                <label for="city_state_zip" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    City / State / ZIP <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="city_state_zip" name="city_state_zip" required placeholder="Las Vegas, NV 89101" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- Mobile Phone -->
                            <div>
                                <label for="mobile_phone" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Mobile Phone <span class="text-rose-500">*</span>
                                </label>
                                <input type="tel" id="mobile_phone" name="mobile_phone" required placeholder="(702) 555-0199" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- Alternate Phone -->
                            <div>
                                <label for="alternate_phone" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Alternate Phone
                                </label>
                                <input type="tel" id="alternate_phone" name="alternate_phone" placeholder="Optional" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Email Address (for signed copy delivery) <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required placeholder="lessee@company.com" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm focus:border-brand-gold focus:ring-1 focus:ring-brand-gold outline-none">
                            </div>

                            <!-- Driver License No / State / Exp -->
                            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div>
                                    <label for="driver_license_no" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Driver License / ID No. <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="driver_license_no" name="driver_license_no" required placeholder="ID Number" class="w-full px-3 py-2 rounded border border-slate-300 text-xs sm:text-sm">
                                </div>
                                <div>
                                    <label for="driver_license_state" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        State <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="driver_license_state" name="driver_license_state" required placeholder="NV" maxlength="2" class="w-full px-3 py-2 rounded border border-slate-300 text-xs sm:text-sm uppercase">
                                </div>
                                <div>
                                    <label for="driver_license_exp" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Expiration Date <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="driver_license_exp" name="driver_license_exp" required placeholder="MM/YY or MM/DD/YYYY" class="w-full px-3 py-2 rounded border border-slate-300 text-xs sm:text-sm">
                                </div>
                            </div>

                            <!-- Date of Birth & Authorized Rep -->
                            <div>
                                <label for="date_of_birth" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Date of Birth (if required for verification)
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <div>
                                <label for="authorized_rep" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Authorized Business Representative
                                </label>
                                <input type="text" id="authorized_rep" name="authorized_rep" placeholder="If renting on behalf of company" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- Emergency Contact -->
                            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="emergency_contact_name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Emergency Contact Name
                                    </label>
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" placeholder="Full Name" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                                </div>
                                <div>
                                    <label for="emergency_contact_phone" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Emergency Contact Phone
                                    </label>
                                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" placeholder="Phone Number" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION B: RENTAL LOCATION / JOBSITE
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">B. RENTAL LOCATION / JOBSITE</h2>
                            <span class="text-[11px] text-brand-gold">Deployment Site</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Pickup / Delivery -->
                            <div class="sm:col-span-2 flex flex-wrap gap-6 items-center bg-slate-50 p-3.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                <span class="text-slate-700">Fulfillment Method: <span class="text-rose-500">*</span></span>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="fulfillment_type" value="delivery" checked class="text-brand-gold focus:ring-brand-gold">
                                    <span>Delivery Requested (Las Vegas / North Las Vegas / Henderson)</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="fulfillment_type" value="pickup" class="text-brand-gold focus:ring-brand-gold">
                                    <span>Customer Pickup</span>
                                </label>
                            </div>

                            <!-- Jobsite Address -->
                            <div>
                                <label for="jobsite_address" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Jobsite / Use Address <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="jobsite_address" name="jobsite_address" required placeholder="Street address where equipment will be operated" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- City / State / ZIP -->
                            <div>
                                <label for="jobsite_city_state_zip" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Jobsite City / State / ZIP <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="jobsite_city_state_zip" name="jobsite_city_state_zip" required placeholder="Las Vegas, NV 89101" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- Type of Project -->
                            <div>
                                <label for="project_type" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Type of Project / Intended Use
                                </label>
                                <input type="text" id="project_type" name="project_type" placeholder="e.g. Concrete removal, tenant remodel, drying" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- Authorized Operators -->
                            <div>
                                <label for="authorized_operators" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Authorized Operators
                                </label>
                                <input type="text" id="authorized_operators" name="authorized_operators" placeholder="Names of trained personnel operating tool" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION C: EQUIPMENT AND RATE SELECTION
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">C. EQUIPMENT AND RATE SELECTION</h2>
                            <span class="text-[11px] text-brand-gold">Tool Schedule</span>
                        </div>

                        <div class="overflow-x-auto border border-slate-300 rounded-xl">
                            <table class="w-full text-left text-xs sm:text-sm">
                                <thead class="bg-slate-100 text-slate-800 uppercase text-[11px] font-bold border-b border-slate-300">
                                    <tr>
                                        <th class="p-3 text-center">Select</th>
                                        <th class="p-3">Tool / Equipment</th>
                                        <th class="p-3">Asset / Serial #</th>
                                        <th class="p-3 text-right">Daily Rate</th>
                                        <th class="p-3 text-right">Weekly Rate</th>
                                        <th class="p-3 text-right">Replacement Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <!-- Tool 1 -->
                                    <tr class="hover:bg-amber-50/50 transition-colors">
                                        <td class="p-3 text-center">
                                            <input type="checkbox" name="tools[]" value="jackhammer" id="chk_jackhammer" checked class="tool-checkbox text-brand-gold focus:ring-brand-gold h-4 w-4 rounded" onchange="calculateFormCharges()">
                                        </td>
                                        <td class="p-3">
                                            <label for="chk_jackhammer" class="font-bold text-slate-900 cursor-pointer">
                                                Bauer Heavy-Duty Demolition Jackhammer
                                            </label>
                                            <span class="block text-[11px] text-slate-500">Concrete &amp; masonry demolition breaker</span>
                                        </td>
                                        <td class="p-3 font-mono text-xs text-slate-600">BAU-DH15-NV</td>
                                        <td class="p-3 text-right font-semibold text-slate-900">$100.00</td>
                                        <td class="p-3 text-right font-semibold text-emerald-700">$500.00</td>
                                        <td class="p-3 text-right text-slate-600">$850.00</td>
                                    </tr>

                                    <!-- Tool 2 -->
                                    <tr class="hover:bg-amber-50/50 transition-colors">
                                        <td class="p-3 text-center">
                                            <input type="checkbox" name="tools[]" value="blower" id="chk_blower" class="tool-checkbox text-brand-gold focus:ring-brand-gold h-4 w-4 rounded" onchange="calculateFormCharges()">
                                        </td>
                                        <td class="p-3">
                                            <label for="chk_blower" class="font-bold text-slate-900 cursor-pointer">
                                                RedMax EBZ8560 Commercial Backpack Blower
                                            </label>
                                            <span class="block text-[11px] text-slate-500">75.6cc High-CFM commercial air mover &amp; ventilator</span>
                                        </td>
                                        <td class="p-3 font-mono text-xs text-slate-600">RMX-EBZ8560-NV</td>
                                        <td class="p-3 text-right font-semibold text-slate-900">$100.00</td>
                                        <td class="p-3 text-right font-semibold text-emerald-700">$500.00</td>
                                        <td class="p-3 text-right text-slate-600">$750.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[11px] text-slate-500 italic">
                            * Rate definitions: Daily ($100) and weekly ($500) rates apply per selected tool. The selected rate controls unless the parties electronically approve an extension or rate change. Minimum 2-day rental required.
                        </p>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION D: RENTAL PERIOD AND ESTIMATED CHARGES
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">D. RENTAL PERIOD AND ESTIMATED CHARGES</h2>
                            <span class="text-[11px] text-brand-gold">Billing &amp; Calculations</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Rental Start -->
                            <div>
                                <label for="start_date" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Rental Start Date <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm" onchange="calculateFormCharges()">
                            </div>
                            <div>
                                <label for="start_time" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Start Time
                                </label>
                                <input type="time" id="start_time" name="start_time" value="08:00" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- Scheduled Return -->
                            <div>
                                <label for="return_date" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Scheduled Return Date <span class="text-rose-500">*</span> <span class="text-[11px] text-brand-gold font-normal">(Min. 2 Days)</span>
                                </label>
                                <input type="date" id="return_date" name="return_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm" onchange="calculateFormCharges()">
                            </div>
                            <div>
                                <label for="return_time" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Return Time
                                </label>
                                <input type="time" id="return_time" name="return_time" value="17:00" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>
                        </div>

                        <!-- Live Cost Breakdown Summary -->
                        <div class="bg-slate-50 border border-slate-300 rounded-xl p-5 text-xs sm:text-sm space-y-3">
                            <div class="flex justify-between items-center py-1 border-b border-slate-200">
                                <span class="text-slate-600">Rental Duration:</span>
                                <span id="summary-duration" class="font-bold text-slate-900">2 Days</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200">
                                <span class="text-slate-600">Estimated Rental Charge ($100/day or $500/wk):</span>
                                <span id="summary-rental-charge" class="font-bold text-slate-900">$200.00</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200">
                                <span class="text-slate-600">Security Deposit / Hold ($50 refundable per tool):</span>
                                <span id="summary-deposit" class="font-bold text-slate-900">$50.00</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200">
                                <span class="text-slate-600">Delivery / Pickup (Las Vegas / North LV / Henderson):</span>
                                <span class="font-semibold text-emerald-700">Included on scheduled delivery</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200">
                                <span class="text-slate-600">Damage Waiver (Optional Protection):</span>
                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="damage_waiver" value="declined" checked class="text-brand-gold focus:ring-brand-gold" onchange="calculateFormCharges()">
                                        <span>Declined</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="damage_waiver" value="accepted" class="text-brand-gold focus:ring-brand-gold" onchange="calculateFormCharges()">
                                        <span>Accepted (+$15/day)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-2 text-base font-extrabold text-[#0B1727]">
                                <span>ESTIMATED TOTAL:</span>
                                <span id="summary-total" class="text-xl text-brand-gold font-extrabold">$250.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION E: IDENTITY, PAYMENT AND OPTIONAL INSURANCE
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">E. IDENTITY, PAYMENT AND OPTIONAL INSURANCE</h2>
                            <span class="text-[11px] text-brand-gold">Payment Verification</span>
                        </div>

                        <!-- Security Callout -->
                        <div class="p-4 rounded-lg bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <strong>Payment security notice:</strong> Do not place a full payment-card number or security code in this agreement. Use the company's secure payment processor for card credentials and any separate authorization.
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Payment Method <span class="text-rose-500">*</span>
                                </label>
                                <select name="payment_method" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                                    <option value="credit_card">Credit / Debit Card</option>
                                    <option value="ach">ACH Transfer</option>
                                    <option value="invoice">Invoice (Approved Business Account)</option>
                                    <option value="payment_on_delivery">Payment on Delivery</option>
                                </select>
                            </div>

                            <!-- Cardholder Name -->
                            <div>
                                <label for="cardholder_name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Cardholder / Account Name
                                </label>
                                <input type="text" id="cardholder_name" name="cardholder_name" placeholder="Name on Card" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <!-- Last 4 Digits & Exp -->
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="card_last_four" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Last 4 Digits
                                    </label>
                                    <input type="text" id="card_last_four" name="card_last_four" maxlength="4" placeholder="4321" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm text-center font-mono">
                                </div>
                                <div>
                                    <label for="card_exp" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Exp.
                                    </label>
                                    <input type="text" id="card_exp" name="card_exp" placeholder="MM/YY" maxlength="5" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm text-center font-mono">
                                </div>
                            </div>

                            <!-- Optional Insurance Details -->
                            <div>
                                <label for="insurance_carrier" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Insurance Carrier (if required)
                                </label>
                                <input type="text" id="insurance_carrier" name="insurance_carrier" placeholder="Carrier Name" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <div>
                                <label for="insurance_policy_no" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Policy No.
                                </label>
                                <input type="text" id="insurance_policy_no" name="insurance_policy_no" placeholder="Policy #" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                    Certificate of Insurance (COI)
                                </label>
                                <div class="flex gap-4 pt-2 text-xs">
                                    <label class="inline-flex items-center gap-1.5">
                                        <input type="radio" name="coi_status" value="not_required" checked class="text-brand-gold focus:ring-brand-gold">
                                        <span>Not Required</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5">
                                        <input type="radio" name="coi_status" value="attached" class="text-brand-gold focus:ring-brand-gold">
                                        <span>Provided to Dispatch</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION G: CUSTOMER ACKNOWLEDGMENTS
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-4">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">G. CUSTOMER ACKNOWLEDGMENTS</h2>
                            <span class="text-[11px] text-brand-gold">Required Consents</span>
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-300 space-y-3.5 text-xs text-slate-800">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_1" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I confirm the equipment and rental rates shown above were presented to me before I signed.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_2" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I had an opportunity to inspect the equipment and will report an apparent defect or unsafe condition before use.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_3" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I will use the equipment only for its intended lawful purpose and follow manufacturer instructions, warnings, required PPE, training, permits, and site rules.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_4" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I will not subrent, sell, pledge, loan, materially alter, defeat safety devices, or permit unauthorized use of the equipment.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_5" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I will stop use and promptly notify RM Tools &amp; Equipment if equipment becomes unsafe, damaged, lost, or stolen.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_6" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I consent to receive this agreement, disclosures, receipts, notices, and a copy of the signed record electronically at the email provided above. I may request a paper copy.</span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="ack_7" required checked class="mt-0.5 text-brand-gold focus:ring-brand-gold h-4 w-4 rounded flex-shrink-0">
                                <span>I understand that electronic acceptance and signature are intended to have the same effect as a handwritten signature.</span>
                            </label>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION H: RENTAL TERMS & CONDITIONS
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-4">
                        <div class="bg-[#0B1727] text-white px-4 py-2.5 rounded-lg flex items-center justify-between">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider">H. RENTAL TERMS (NEVADA LAW)</h2>
                            <span class="text-[11px] text-brand-gold">12 Clauses</span>
                        </div>

                        <div class="h-60 overflow-y-auto bg-slate-50 p-5 rounded-xl border border-slate-300 text-xs text-slate-700 space-y-3 leading-relaxed">
                            <p><strong>1. Rental and ownership.</strong> RM Nevada Series LLC - Tools &amp; Equipment (Lessor) rents the listed movable tools/equipment to the customer (Lessee) for the stated term. Ownership remains with Lessor.</p>
                            <p><strong>2. Rates and extensions.</strong> Hourly, daily, or weekly rates apply as selected above. Lessee must obtain approval for an extension. Additional rental time may be charged at the disclosed selected rate or a replacement rate expressly approved by the parties, subject to applicable law.</p>
                            <p><strong>3. Care and safeguarding.</strong> From pickup/delivery until accepted return, Lessee must exercise reasonable care and safeguard the equipment. Ordinary wear from proper use is excepted.</p>
                            <p><strong>4. Loss, theft, and damage.</strong> To the extent permitted by Nevada law, Lessee is responsible for loss, theft, misuse, abuse, unauthorized modification, and damage beyond ordinary wear while the equipment is in Lessee's possession or control. Repair/replacement charges must be based on actual or commercially reasonable costs attributable to the loss or damage.</p>
                            <p><strong>5. Return.</strong> Equipment must be returned to the agreed location by the scheduled date/time, reasonably clean and in substantially the same condition as received, ordinary wear excepted.</p>
                            <p><strong>6. Payment.</strong> Lessee agrees to pay disclosed rental charges, applicable taxes, approved delivery/pickup charges, and lawful amounts due for loss or damage. A security deposit or payment hold is not a final charge unless amounts become due under this Agreement.</p>
                            <p><strong>7. Safety and qualified operation.</strong> Only competent, legally qualified, and authorized persons may operate the equipment. Lessee is responsible for required permits, certifications, PPE, and jobsite authorization.</p>
                            <p><strong>8. Warranties.</strong> Any warranty disclaimer or limitation applies only to the extent permitted by Nevada law and must be interpreted consistently with Nevada UCC Article 2A. Nothing in this Agreement waives a right or remedy that cannot lawfully be waived.</p>
                            <p><strong>9. Indemnity.</strong> To the extent permitted by law, Lessee will indemnify Lessor against third-party claims caused by Lessee's negligent, reckless, unlawful, or unauthorized possession, transportation, or use. This does not require Lessee to indemnify Lessor for liability that cannot lawfully be shifted.</p>
                            <p><strong>10. Default and recovery.</strong> Material nonpayment, prohibited use, material misrepresentation, unauthorized transfer, or failure to return as agreed may constitute default. Lessor may exercise lawful remedies, including termination and recovery of equipment through lawful means.</p>
                            <p><strong>11. Nevada law.</strong> Nevada law governs. Any forum or venue term applies only to the extent enforceable, including limitations applicable to consumer leases.</p>
                            <p><strong>12. Entire agreement.</strong> This agreement, equipment/rate schedule, checkout/return records, electronically accepted addenda, and disclosed damage-waiver terms (if any) constitute the rental agreement. Changes require written or electronic agreement by authorized parties.</p>
                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                         SECTION I: ELECTRONIC CONSENT AND E-SIGNATURE
                         ══════════════════════════════════════════════════ -->
                    <div class="space-y-5 border-t-2 border-brand-gold pt-6">
                        <div class="bg-[#0B1727] text-white p-4 rounded-lg">
                            <h2 class="font-bold text-xs sm:text-sm uppercase tracking-wider mb-2">I. ELECTRONIC CONSENT AND E-SIGNATURE</h2>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                By selecting the acceptance box and applying an electronic signature, the signer confirms that: (1) the signer has reviewed the completed rental application, equipment schedule, selected daily/weekly rate, charges, and terms; (2) the signer intends to sign and be legally bound; (3) the signer consents to transact electronically; and (4) the signer can access and retain an electronic copy of this record.
                            </p>
                        </div>

                        <!-- Agreement Checkbox -->
                        <div class="bg-amber-50 p-4 rounded-xl border-2 border-brand-gold">
                            <label class="flex items-start gap-3 cursor-pointer text-xs sm:text-sm font-bold text-slate-900">
                                <input type="checkbox" name="e_consent_agreed" required class="mt-0.5 text-brand-gold focus:ring-brand-gold h-5 w-5 rounded flex-shrink-0">
                                <span>I AGREE TO THE RENTAL TERMS AND ELECTRONIC TRANSACTION (Nevada UCC Article 2A)</span>
                            </label>
                        </div>

                        <!-- Signature Mode Selector -->
                        <div class="space-y-4 bg-slate-50 p-6 rounded-xl border border-slate-300">
                            <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-200 pb-3">
                                <span class="font-bold text-xs uppercase tracking-wider text-slate-900">Lessee Electronic Signature Pad</span>
                                <div class="flex items-center gap-3 text-xs">
                                    <button type="button" id="btn-mode-draw" onclick="switchSignatureMode('drawn')" class="px-3 py-1.5 rounded-lg bg-[#0B1727] text-white font-semibold">
                                        Draw Signature
                                    </button>
                                    <button type="button" id="btn-mode-type" onclick="switchSignatureMode('typed')" class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-semibold hover:bg-slate-300">
                                        Type Name to Sign
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="signature_type" id="signature_type" value="drawn">
                            <input type="hidden" name="signature_data" id="signature_data" value="">

                            <!-- DRAW CANVAS MODE -->
                            <div id="container-draw-signature" class="space-y-2">
                                <div class="border-2 border-dashed border-slate-400 rounded-xl bg-white relative overflow-hidden h-44 cursor-crosshair">
                                    <canvas id="signatureCanvas" class="w-full h-full touch-none"></canvas>
                                    <div class="absolute bottom-2 left-4 text-[11px] text-slate-400 pointer-events-none">
                                        Sign inside the box using mouse or finger
                                    </div>
                                    <button type="button" onclick="clearSignatureCanvas()" class="absolute top-2 right-2 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded border border-slate-300 font-medium">
                                        Clear
                                    </button>
                                </div>
                            </div>

                            <!-- TYPE SIGNATURE MODE -->
                            <div id="container-type-signature" class="space-y-2 hidden">
                                <label for="typed_signature_input" class="block text-xs font-bold text-slate-700">
                                    Type your legal name to generate electronic signature:
                                </label>
                                <input type="text" id="typed_signature_input" placeholder="Type full name" class="w-full px-4 py-3 rounded-lg border border-slate-300 text-sm focus:border-brand-gold outline-none" oninput="updateTypedPreview(this.value)">
                                <div class="p-4 bg-white border border-slate-300 rounded-xl text-center">
                                    <span class="text-[11px] text-slate-400 block mb-1">Generated Signature Preview:</span>
                                    <div id="typed-signature-preview" class="font-signature text-3xl sm:text-4xl text-brand-navy">
                                        Your Signature
                                    </div>
                                </div>
                            </div>

                            <!-- Printed Name Sync -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-200">
                                <div>
                                    <label for="signer_printed_name" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                                        Signer Printed Legal Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="signer_printed_name" name="signer_printed_name" required placeholder="Full Legal Name" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold">
                                </div>
                                <div class="text-xs text-slate-500 flex flex-col justify-end">
                                    <span><strong>Signing Date:</strong> <?php echo date('F j, Y'); ?></span>
                                    <span><strong>Audit IP Address:</strong> <?php echo $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'; ?> (Logged)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Math Captcha -->
                        <div class="bg-slate-100 p-4 rounded-xl border border-slate-300 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <label for="captcha_answer" class="text-xs font-medium text-slate-700">
                                Security Verification: <strong class="text-brand-navy"><?php echo $captcha_question; ?></strong>
                            </label>
                            <input type="number" id="captcha_answer" name="captcha_answer" required placeholder="Answer" class="w-28 px-3 py-2 rounded-lg bg-white border border-slate-300 text-xs sm:text-sm text-center">
                        </div>

                        <!-- Final Submit Button -->
                        <button type="submit" class="btn-gold w-full text-center text-xs sm:text-sm uppercase tracking-widest font-extrabold py-4 px-8 shadow-xl shadow-brand-gold/30 block">
                            Accept Terms &amp; Submit Electronic Agreement
                        </button>
                    </div>

                </form>
            </div>
            <?php endif; ?>

        </div>
    </main>

    <div class="no-print">
        <?php include __DIR__ . '/includes/footer.php'; ?>
    </div>

    <!-- Interactive Logic & Signature Canvas -->
    <script>
    function syncPrintedName(val) {
        const printed = document.getElementById('signer_printed_name');
        if (printed && (!printed.value || printed.dataset.manual !== 'true')) {
            printed.value = val;
        }
    }

    // Rate Calculator
    function calculateFormCharges() {
        const startInput = document.getElementById('start_date');
        const returnInput = document.getElementById('return_date');
        const durationDisplay = document.getElementById('summary-duration');
        const rentalChargeDisplay = document.getElementById('summary-rental-charge');
        const depositDisplay = document.getElementById('summary-deposit');
        const totalDisplay = document.getElementById('summary-total');
        const damageWaiverChecked = document.querySelector('input[name="damage_waiver"]:checked');

        const checkboxes = document.querySelectorAll('.tool-checkbox:checked');
        const toolCount = checkboxes.length;

        if (toolCount === 0) {
            if (rentalChargeDisplay) rentalChargeDisplay.innerText = '$0.00';
            if (depositDisplay) depositDisplay.innerText = '$0.00';
            if (totalDisplay) totalDisplay.innerText = '$0.00';
            return;
        }

        let days = 2;
        if (startInput && returnInput && startInput.value && returnInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(returnInput.value);
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                days = Math.max(2, Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1);
            }
        }

        const weeks = Math.floor(days / 7);
        const remDays = days % 7;
        const rentalPerTool = (weeks * 500) + (remDays * 100);
        const totalRentalCharge = rentalPerTool * toolCount;
        const totalDeposit = 50.00 * toolCount;

        const isWaiver = (damageWaiverChecked && damageWaiverChecked.value === 'accepted');
        const waiverFee = isWaiver ? (15.00 * toolCount * days) : 0.00;
        const grandTotal = totalRentalCharge + totalDeposit + waiverFee;

        if (durationDisplay) {
            durationDisplay.innerText = days + ' Days' + (weeks > 0 ? ' (' + weeks + ' wk' + (weeks > 1 ? 's' : '') + (remDays > 0 ? ' + ' + remDays + ' days' : '') + ')' : '');
        }
        if (rentalChargeDisplay) rentalChargeDisplay.innerText = '$' + totalRentalCharge.toFixed(2);
        if (depositDisplay) depositDisplay.innerText = '$' + totalDeposit.toFixed(2);
        if (totalDisplay) totalDisplay.innerText = '$' + grandTotal.toFixed(2);
    }

    // Signature Mode Switcher
    function switchSignatureMode(mode) {
        const typeInput = document.getElementById('signature_type');
        const btnDraw = document.getElementById('btn-mode-draw');
        const btnType = document.getElementById('btn-mode-type');
        const containerDraw = document.getElementById('container-draw-signature');
        const containerType = document.getElementById('container-type-signature');

        typeInput.value = mode;
        if (mode === 'drawn') {
            btnDraw.className = 'px-3 py-1.5 rounded-lg bg-[#0B1727] text-white font-semibold';
            btnType.className = 'px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-semibold hover:bg-slate-300';
            containerDraw.classList.remove('hidden');
            containerType.classList.add('hidden');
        } else {
            btnType.className = 'px-3 py-1.5 rounded-lg bg-[#0B1727] text-white font-semibold';
            btnDraw.className = 'px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-semibold hover:bg-slate-300';
            containerType.classList.remove('hidden');
            containerDraw.classList.add('hidden');
            updateTypedPreview(document.getElementById('typed_signature_input').value);
        }
    }

    function updateTypedPreview(val) {
        const preview = document.getElementById('typed-signature-preview');
        const dataInput = document.getElementById('signature_data');
        const printed = document.getElementById('signer_printed_name');

        const text = val.trim() || 'Your Signature';
        preview.innerText = text;
        if (document.getElementById('signature_type').value === 'typed') {
            dataInput.value = text;
            if (printed && (!printed.value || printed.dataset.manual !== 'true')) {
                printed.value = text;
            }
        }
    }

    // HTML5 Canvas Drawing
    let canvas, ctx, isDrawing = false;

    function initSignatureCanvas() {
        canvas = document.getElementById('signatureCanvas');
        if (!canvas) return;

        // Resize properly
        canvas.width = canvas.parentElement.clientWidth;
        canvas.height = canvas.parentElement.clientHeight;

        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#0B1727';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            if (e.touches && e.touches.length > 0) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top
                };
            }
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }

        function startDraw(e) {
            isDrawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        function stopDraw() {
            if (isDrawing) {
                isDrawing = false;
                const dataInput = document.getElementById('signature_data');
                if (dataInput && document.getElementById('signature_type').value === 'drawn') {
                    dataInput.value = canvas.toDataURL('image/png');
                }
            }
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDraw);

        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        window.addEventListener('touchend', stopDraw);
    }

    function clearSignatureCanvas() {
        if (!canvas || !ctx) return;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signature_data').value = '';
    }

    // Print & PDF Export Trigger
    function triggerPrintAgreement() {
        window.focus();
        setTimeout(function() {
            window.print();
        }, 150);
    }

    // Form Submission Interceptor
    document.addEventListener('DOMContentLoaded', () => {
        initSignatureCanvas();

        // Default Dates Setup
        const startInput = document.getElementById('start_date');
        const returnInput = document.getElementById('return_date');
        if (startInput && !startInput.value) {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const dayAfter = new Date(today);
            dayAfter.setDate(dayAfter.getDate() + 3);

            startInput.value = tomorrow.toISOString().split('T')[0];
            returnInput.value = dayAfter.toISOString().split('T')[0];
        }

        calculateFormCharges();

        const form = document.getElementById('eSignAgreementForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                const sigType = document.getElementById('signature_type').value;
                const sigData = document.getElementById('signature_data');
                if (sigType === 'drawn' && (!sigData.value || sigData.value.length < 50)) {
                    if (canvas) {
                        sigData.value = canvas.toDataURL('image/png');
                    }
                } else if (sigType === 'typed') {
                    const typedVal = document.getElementById('typed_signature_input').value.trim();
                    if (typedVal) {
                        sigData.value = typedVal;
                    }
                }

                if (!sigData.value) {
                    alert('Please apply an electronic signature before submitting.');
                    e.preventDefault();
                }
            });
        }
    });

    window.addEventListener('resize', () => {
        if (canvas && ctx && document.getElementById('signature_type').value === 'drawn') {
            const currentData = document.getElementById('signature_data').value;
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
            ctx.strokeStyle = '#0B1727';
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            if (currentData) {
                const img = new Image();
                img.onload = () => ctx.drawImage(img, 0, 0);
                img.src = currentData;
            }
        }
    });
    </script>
</body>
</html>
