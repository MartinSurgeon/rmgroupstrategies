<?php
/**
 * Standalone SMTP Diagnostic Script with Config Path Debugging
 */
header('Content-Type: text/plain; charset=utf-8');

echo "=======================================================\n";
echo "       SMTP LIVE PASSWORD & PATH DIAGNOSTIC             \n";
echo "=======================================================\n\n";

$includes_path = __DIR__ . '/../includes/config.local.php';
$root_path     = __DIR__ . '/../config.local.php';

echo "1. FILE PATH CHECK:\n";
echo "   - includes/config.local.php: " . (file_exists($includes_path) ? "EXISTS" : "NOT FOUND") . "\n";
echo "   - root/config.local.php:     " . (file_exists($root_path) ? "EXISTS" : "NOT FOUND") . "\n\n";

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

echo "2. LOADED CONSTANTS:\n";
echo "   - SMTP Host: " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT DEFINED') . "\n";
echo "   - SMTP Port: " . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT DEFINED') . "\n";
echo "   - SMTP User: " . (defined('SMTP_USER') ? SMTP_USER : 'NOT DEFINED') . "\n";
echo "   - SMTP Pass: " . (defined('SMTP_PASS') && !empty(SMTP_PASS) ? "DEFINED (" . strlen(SMTP_PASS) . " chars)" : "EMPTY / NOT DEFINED") . "\n\n";

if (!defined('SMTP_PASS') || empty(SMTP_PASS)) {
    echo "ERROR: Password is empty in the loaded config file.\n";
    exit;
}

echo "3. AUTHENTICATION TEST WITH MAIL SERVER...\n";
$mailer = new SimpleSMTPMailer(SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, 'ssl');
list($sent, $msg) = $mailer->send('erm@rmgroupstrategies.com', 'Test Auth', 'Test body', 'erm@rmgroupstrategies.com', SITE_NAME);

echo "   - Connection Status: " . ($sent ? "SUCCESS [250 OK]" : "FAILED") . "\n";
echo "   - Response Message:  " . trim($msg) . "\n\n";

echo "=======================================================\n";
