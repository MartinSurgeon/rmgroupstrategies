<?php
/**
 * Test & Verify Email Delivery and Fallback Engine
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/mailer.php';

echo "=======================================================\n";
echo "    TESTING RM GROUP STRATEGIES EMAIL ENGINE           \n";
echo "=======================================================\n\n";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "1. DATABASE CONNECTION: OK\n";
} catch (Exception $e) {
    echo "1. DATABASE CONNECTION: FAILED (" . $e->getMessage() . ")\n";
    $db = null;
}

echo "\n2. CONFIGURATION STATUS:\n";
echo "   - Host Environment: " . (defined('IS_LOCAL_ENV') && IS_LOCAL_ENV ? "LOCAL" : "LIVE") . "\n";
echo "   - SMTP Host: " . (defined('SMTP_HOST') ? SMTP_HOST : "not set") . "\n";
echo "   - SMTP User: " . (defined('SMTP_USER') ? SMTP_USER : "not set") . "\n";
echo "   - SMTP Pass Configured: " . (defined('SMTP_PASS') && !empty(SMTP_PASS) ? "YES" : "NO (Will trigger fallback on live)") . "\n";

echo "\n3. TESTING SIMULATED CLIENT & STAFF DISPATCH:\n";
$test_agreement_id = 'RM-AGR-2026-TEST' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
$test_client_email = 'test.client@example.com';
$test_staff_email  = defined('SITE_EMAIL') ? SITE_EMAIL : 'info@rmgroupstrategies.com';

$test_url = SITE_URL . '/rental-agreement.php?view=' . $test_agreement_id;

$client_plain = "Dear Test Client,\n\nYour agreement " . $test_agreement_id . " has been recorded.\nView online: " . $test_url . "\n";
$client_html  = build_branded_email_html(
    "Rental Agreement Confirmation",
    "RM Tools & Equipment",
    "<p>Dear <strong>Test Client</strong>,</p><p>Your rental agreement <strong>" . $test_agreement_id . "</strong> has been submitted.</p>",
    $test_url,
    "View / Print Signed Agreement"
);

list($ok_staff, $method_staff, $msg_staff) = send_system_email(
    $test_staff_email,
    "SIGNED RENTAL AGREEMENT [" . $test_agreement_id . "] — Test Client",
    "Staff plain text for " . $test_agreement_id,
    "<p>Staff HTML for " . $test_agreement_id . "</p>",
    $test_client_email,
    SITE_EMAIL,
    SITE_NAME,
    null,
    $db
);
echo "   - Staff Notification Result: " . ($ok_staff ? "SUCCESS" : "FAILED") . " | Method: " . $method_staff . " | Details: " . $msg_staff . "\n";

list($ok_client, $method_client, $msg_client) = send_system_email(
    $test_client_email,
    "Your Signed Rental Agreement Copy — RM Tools & Equipment [" . $test_agreement_id . "]",
    $client_plain,
    $client_html,
    SITE_EMAIL,
    SITE_EMAIL,
    SITE_NAME,
    null,
    $db
);
echo "   - Client Confirmation Result: " . ($ok_client ? "SUCCESS" : "FAILED") . " | Method: " . $method_client . " | Details: " . $msg_client . "\n";

echo "\n4. VERIFYING DATABASE EMAIL_LOGS TABLE:\n";
if ($db) {
    $stmt = $db->query("SELECT id, recipient_email, subject, status, error_message, created_at FROM email_logs ORDER BY id DESC LIMIT 4");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($logs as $log) {
        echo "   [ID " . $log['id'] . "] " . $log['created_at'] . " | " . $log['recipient_email'] . " | Status: " . $log['status'] . " | Subj: " . substr($log['subject'], 0, 45) . "...\n";
    }
}

echo "\n5. VERIFYING GENERATED PREVIEW FILES IN SCRATCH:\n";
$scratch_files = glob(__DIR__ . '/preview_*.html');
if (!empty($scratch_files)) {
    echo "   - Found " . count($scratch_files) . " preview HTML files in scratch/.\n";
    echo "   - Latest file: " . basename(end($scratch_files)) . "\n";
} else {
    echo "   - No preview HTML files found in scratch/.\n";
}

echo "\n=======================================================\n";
echo "                 TEST COMPLETE                         \n";
echo "=======================================================\n";
