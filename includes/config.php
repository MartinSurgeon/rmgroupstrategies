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
define('SITE_EMAIL',          'support@rmgroupstrategies.com');
define('EMAIL_ERM',           'erm@rmgroupstrategies.com');
define('EMAIL_ARM',           'arm@rmgroupstrategies.com');
define('EMAIL_CONTRACTS',     'contracts@rmgroupstrategies.com');
define('EMAIL_EQUIPMENT',     'equipment@rmgroupstrategies.com');
define('EMAIL_FLEET',         'fleet@rmgroupstrategies.com');
define('EMAIL_REMODELING',    'remodeling@rmgroupstrategies.com');
define('EMAIL_SUPPORT',       'support@rmgroupstrategies.com');
define('EMAIL_TRANSPORT',     'transports@rmgroupstrategies.com');
define('SITE_LOCATION',       'Las Vegas, Nevada');
define('SITE_URL',            'https://rmgroupstrategies.com');
define('SITE_YEAR',           date('Y'));

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
        'url'   => '/rmgroupstrategies/',
        'slug'  => 'home',
    ],
    [
        'title' => 'About',
        'url'   => '/rmgroupstrategies/about.php',
        'slug'  => 'about',
    ],
    [
        'title'    => 'Government Contracting',
        'url'      => '/rmgroupstrategies/government-contracting.php',
        'slug'     => 'government-contracting',
        'children' => [
            [
                'title' => 'Capability Statement',
                'url'   => '/rmgroupstrategies/capability-statement.php',
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
                'url'   => '/rmgroupstrategies/rm-nevada-series.php',
                'slug'  => 'rm-nevada-series',
            ],
            [
                'title' => 'RM Fleet',
                'url'   => '/rmgroupstrategies/rm-fleet.php',
                'slug'  => 'rm-fleet',
            ],
            [
                'title' => 'RM Remodeling',
                'url'   => '/rmgroupstrategies/rm-remodeling.php',
                'slug'  => 'rm-remodeling',
            ],
            [
                'title' => 'RM Tools & Equipment',
                'url'   => '/rmgroupstrategies/rm-tools-equipment.php',
                'slug'  => 'rm-tools-equipment',
            ],
            [
                'title' => 'RM Transport',
                'url'   => '/rmgroupstrategies/rm-transport.php',
                'slug'  => 'rm-transport',
            ],
        ],
    ],
    [
        'title' => 'Contact',
        'url'   => '/rmgroupstrategies/contact.php',
        'slug'  => 'contact',
    ],
];
