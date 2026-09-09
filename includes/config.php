<?php
/**
 * RM Group Strategies LLC — Site Configuration
 * 
 * Central configuration file used by all pages.
 * Include this file at the top of every page.
 */

// ─── Company Information ───────────────────────────────────────────
define('SITE_NAME',           'RM Group Strategies LLC');
define('SITE_NAME_SHORT',     'RM Group Strategies');
define('SITE_TAGLINE',        'Building Strategic Partnerships, Delivering Reliable Solutions.');
define('SITE_TAGLINE_SUPPORT','Strategic Solutions. Reliable Performance. Government & Commercial Excellence.');
define('SITE_PHONE',          '702-504-8128');
define('SITE_PHONE_DISPLAY',  '(702) 504-8128');
define('SITE_PHONE_LINK',     'tel:+17025048128');
define('SITE_EMAIL',          'info@rmgroupstrategies.com');
define('EMAIL_ERM',           'info@rmgroupstrategies.com');
define('EMAIL_ARM',           'info@rmgroupstrategies.com');
define('EMAIL_CONTRACTS',     'info@rmgroupstrategies.com');
define('EMAIL_EQUIPMENT',     'info@rmgroupstrategies.com');
define('EMAIL_FLEET',         'info@rmgroupstrategies.com');
define('EMAIL_REMODELING',    'info@rmgroupstrategies.com');
define('EMAIL_SUPPORT',       'info@rmgroupstrategies.com');
define('EMAIL_TRANSPORT',     'info@rmgroupstrategies.com');

// Load secure local environment secrets (checks both includes/ and root folder)
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
} elseif (file_exists(dirname(__DIR__) . '/config.local.php')) {
    require_once dirname(__DIR__) . '/config.local.php';
}

if (!defined('SMTP_HOST')) define('SMTP_HOST', 'mail.privateemail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 465);
if (!defined('SMTP_ENC'))  define('SMTP_ENC',  'ssl');
if (!defined('SMTP_USER')) define('SMTP_USER', 'info@rmgroupstrategies.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', '');
define('SITE_LOCATION',       'Las Vegas, Nevada');
define('SITE_URL',            'https://rmgroupstrategies.com');
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$host_only = strtolower(explode(':', $http_host)[0]);

$is_local_env = (
    empty($host_only) ||
    $host_only === 'localhost' ||
    $host_only === '127.0.0.1' ||
    $host_only === '::1' ||
    strpos($host_only, '192.168.') === 0 ||
    strpos($host_only, '10.') === 0 ||
    strpos($host_only, '172.16.') === 0 ||
    strpos($host_only, '.local') !== false ||
    strpos($host_only, '.test') !== false ||
    php_sapi_name() === 'cli'
);

define('IS_LOCAL_ENV',        $is_local_env);
define('BASE_URL',            $is_local_env ? '/rmgroupstrategies' : '');
define('SITE_YEAR',           date('Y'));

// ─── Environment Error Handling ─────────────────────────────────────
if (!$is_local_env) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// ─── Secure Session Initialization ──────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => !$is_local_env,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// ─── Cryptographic Application Secret ───────────────────────────────
if (!defined('APP_SECRET')) {
    define('APP_SECRET', getenv('APP_SECRET') ?: 'RM_GS_SEC_7f8a9e2b4c6d1f0e3a5b7c9d1e2f4a6b');
}

// ─── Database Configuration ─────────────────────────────────────────
if ($is_local_env) {
    if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
    if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
    if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'rmgroupstrategies');
} else {
    if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'rmgrbkkc_rm');
    if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: 'Monday20$2026');
    if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'rmgrbkkc_rmgroupstrategies');
}

// ─── Rate Limiter Helper ────────────────────────────────────────────
/**
 * Check submission rate limit per IP / Session to prevent abuse
 *
 * @param string $action Action key identifier
 * @param int $max_attempts Maximum allowed attempts within window
 * @param int $decay_seconds Window length in seconds
 * @return array ['allowed' => bool, 'message' => string]
 */
function check_submission_rate_limit($action, $max_attempts = 5, $decay_seconds = 600) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['rate_limits'])) {
        $_SESSION['rate_limits'] = [];
    }
    $now = time();
    if (!isset($_SESSION['rate_limits'][$action]) || !is_array($_SESSION['rate_limits'][$action])) {
        $_SESSION['rate_limits'][$action] = [];
    }
    // Discard attempts older than decay window
    $_SESSION['rate_limits'][$action] = array_values(array_filter(
        $_SESSION['rate_limits'][$action],
        function($timestamp) use ($now, $decay_seconds) {
            return ($now - $timestamp) < $decay_seconds;
        }
    ));
    if (count($_SESSION['rate_limits'][$action]) >= $max_attempts) {
        return [
            'allowed' => false,
            'message' => 'Too many submission attempts. Please wait a few minutes before trying again.'
        ];
    }
    $_SESSION['rate_limits'][$action][] = $now;
    return [
        'allowed' => true,
        'message' => ''
    ];
}

// ─── Brand Colors (for reference in PHP-generated content) ─────────
define('COLOR_BLACK',      '#000000');
define('COLOR_GOLD',       '#D4AF37');
define('COLOR_GOLD_ACCENT','#FFD700');
define('COLOR_WHITE',      '#FFFFFF');
define('COLOR_DARK_GRAY',  '#1E1E1E');

// ─── Navigation ────────────────────────────────────────────────────
$navigation = [
    [
        'title' => 'Home',
        'url'   => BASE_URL . '/',
        'slug'  => 'home',
    ],
    [
        'title' => 'About',
        'url'   => BASE_URL . '/about',
        'slug'  => 'about',
    ],
    [
        'title'    => 'Government Contracting',
        'url'      => BASE_URL . '/government-contracting',
        'slug'     => 'government-contracting',
        'children' => [
            [
                'title' => 'Capability Statement',
                'url'   => BASE_URL . '/capability-statement',
                'slug'  => 'capability-statement',
            ],
        ],
    ],
    [
        'title'    => 'Our Companies',
        'url'      => '#',
        'slug'     => 'companies',
        'children' => [
            [
                'title' => 'RM Fleet',
                'url'   => BASE_URL . '/rm-fleet',
                'slug'  => 'rm-fleet',
            ],
            [
                'title' => 'RM Remodeling',
                'url'   => BASE_URL . '/rm-remodeling',
                'slug'  => 'rm-remodeling',
            ],
            [
                'title' => 'RM Tools & Equipment',
                'url'   => BASE_URL . '/rm-tools-equipment',
                'slug'  => 'rm-tools-equipment',
            ],
            [
                'title' => 'RM Transport',
                'url'   => BASE_URL . '/rm-transport',
                'slug'  => 'rm-transport',
            ],
        ],
    ],
    [
        'title' => 'Contact',
        'url'   => BASE_URL . '/contact',
        'slug'  => 'contact',
    ],
];

