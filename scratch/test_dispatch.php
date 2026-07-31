<?php
/**
 * Standalone SMTP Diagnostic Script with Multi-Port Testing
 */
header('Content-Type: text/plain; charset=utf-8');

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

$to = isset($_GET['to']) ? trim($_GET['to']) : 'info@rmgroupstrategies.com';

echo "=======================================================\n";
echo "       SMTP MULTI-PORT DIAGNOSTIC TESTER               \n";
echo "=======================================================\n\n";

$pass = defined('SMTP_PASS') ? SMTP_PASS : '';

echo "1. CONFIGURATION:\n";
echo "   - SMTP Host: " . SMTP_HOST . "\n";
echo "   - SMTP User: " . SMTP_USER . "\n";
echo "   - Password Length: " . strlen($pass) . " chars\n";
echo "   - Target Recipient: " . $to . "\n\n";

$tests = [
    ['host' => 'mail.privateemail.com', 'port' => 587, 'enc' => 'tls'],
    ['host' => 'mail.privateemail.com', 'port' => 465, 'enc' => 'ssl'],
    ['host' => 'localhost',            'port' => 25,  'enc' => 'none'],
];

foreach ($tests as $t) {
    echo "-------------------------------------------------------\n";
    echo "TESTING: " . $t['host'] . ":" . $t['port'] . " (" . strtoupper($t['enc']) . ")...\n";
    
    $start = microtime(true);
    $mailer = new SimpleSMTPMailer($t['host'], $t['port'], SMTP_USER, $pass, $t['enc']);
    list($sent, $msg) = $mailer->send($to, "Test " . $t['port'], "Test body", SMTP_USER, SITE_NAME);
    $duration = round(microtime(true) - $start, 2);
    
    echo "   - Duration: {$duration}s\n";
    echo "   - Status: " . ($sent ? "SUCCESS [250 OK]" : "FAILED") . "\n";
    echo "   - Response: " . $msg . "\n";
    if ($sent) {
        echo ">>> SUCCESSFUL CONNECTION METHOD: " . $t['host'] . ":" . $t['port'] . " <<<\n";
        break;
    }
}

echo "=======================================================\n";
