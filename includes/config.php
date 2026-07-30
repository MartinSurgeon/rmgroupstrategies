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
define('EMAIL_ERM',           'erm@rmgroupstrategies.com');
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
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'info@rmgroupstrategies.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', '');
define('SITE_LOCATION',       'Las Vegas, Nevada');
define('SITE_URL',            'https://rmgroupstrategies.com');
define('BASE_URL',            (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']) ? '/rmgroupstrategies' : ''));
define('SITE_YEAR',           date('Y'));

if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'rmgroupstrategies');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'rmgrbkkc_rm');
    define('DB_PASS', 'Monday20$2026');
    define('DB_NAME', 'rmgrbkkc_rmgroupstrategies');
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
        'url'   => BASE_URL . '/about.php',
        'slug'  => 'about',
    ],
    [
        'title'    => 'Government Contracting',
        'url'      => BASE_URL . '/government-contracting.php',
        'slug'     => 'government-contracting',
        'children' => [
            [
                'title' => 'Capability Statement',
                'url'   => BASE_URL . '/capability-statement.php',
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
                'title' => 'RM Nevada Series LLC',
                'url'   => BASE_URL . '/rm-nevada-series.php',
                'slug'  => 'rm-nevada-series',
            ],
            [
                'title' => 'RM Fleet',
                'url'   => BASE_URL . '/rm-fleet.php',
                'slug'  => 'rm-fleet',
            ],
            [
                'title' => 'RM Remodeling',
                'url'   => BASE_URL . '/rm-remodeling.php',
                'slug'  => 'rm-remodeling',
            ],
            [
                'title' => 'RM Tools & Equipment',
                'url'   => BASE_URL . '/rm-tools-equipment.php',
                'slug'  => 'rm-tools-equipment',
            ],
            [
                'title' => 'RM Transport',
                'url'   => BASE_URL . '/rm-transport.php',
                'slug'  => 'rm-transport',
            ],
        ],
    ],
    [
        'title' => 'Contact',
        'url'   => BASE_URL . '/contact.php',
        'slug'  => 'contact',
    ],
];
