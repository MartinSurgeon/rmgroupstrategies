<?php
/**
 * RM Group Strategies LLC — Government Contracting Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'government-contracting';
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
    <title>Government Contracting Capabilities | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Explore the government contracting capabilities, active SAM registration, UEI status, NAICS codes, and contracting vehicles for <?php echo SITE_NAME; ?> in Las Vegas, Nevada.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/government-contracting.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Government Contracting Capabilities | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="SAM.gov registered government contractor specializing in transportation, fleet, construction, and administrative support. Review our NAICS codes and capabilities.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/government-contracting.php">
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
</head>

<body class="font-inter bg-slate-50 text-slate-800">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: BREADCRUMB / HEADER BANNER
             ═══════════════════════════════════════════════════════ -->
        <section class="bg-slate-900 border-b border-slate-800 py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-wide text-white mb-3">
                    Government Contracting
                </h1>
                <nav class="flex justify-center text-xs tracking-wider uppercase text-white/50" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li>
                            <a href="<?php echo BASE_URL; ?>/" class="hover:text-brand-gold transition-colors duration-300">Home</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>/</span>
                            <span class="text-brand-gold">Government Contracting</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: IDENTIFIERS & NAICS DASHBOARD
             ═══════════════════════════════════════════════════════ -->
        <section id="identifiers" class="bg-white py-20 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Identifiers &amp; Classifications</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">Core company registration codes and classifications for government procurement officers.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-on-scroll mt-8">
                    
                    <!-- Card 1: Registration Status -->
                    <div class="stagger-child card-executive rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-brand-gold"></div>
                        <h3 class="text-slate-900 font-bold text-sm tracking-wider uppercase mb-5 pb-2 border-b border-slate-100">
                            Registration Status
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start justify-between text-xs">
                                <span class="text-slate-500">SAM.gov Status</span>
                                <span class="font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Active Registration</span>
                            </li>
                            <li class="flex items-start justify-between text-xs">
                                <span class="text-slate-500">UEI Number</span>
                                <span class="font-semibold text-brand-gold">Available Upon Request</span>
                            </li>
                            <li class="flex items-start justify-between text-xs">
                                <span class="text-slate-500">CAGE Code</span>
                                <span class="font-semibold text-brand-gold">Available Upon Request</span>
                            </li>
                            <li class="flex items-start justify-between text-xs">
                                <span class="text-slate-500">Federal Contract Ready</span>
                                <span class="font-semibold text-slate-900">Yes</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card 2: Core NAICS Codes -->
                    <div class="stagger-child card-executive rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-brand-gold"></div>
                        <h3 class="text-slate-900 font-bold text-sm tracking-wider uppercase mb-5 pb-2 border-b border-slate-100">
                            Primary NAICS Directory
                        </h3>
                        <ul class="space-y-3 text-xs">
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">541611</span>
                                <span class="text-slate-700">Administrative Management and General Management Consulting Services (Primary)</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">484110</span>
                                <span class="text-slate-700">General Freight Trucking, Local</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">484121</span>
                                <span class="text-slate-700">General Freight Trucking, Long-Distance</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">488490</span>
                                <span class="text-slate-700">Other Support Activities for Road Transportation</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">236118</span>
                                <span class="text-slate-700">Residential Remodelers</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">236220</span>
                                <span class="text-slate-700">Commercial and Institutional Building Construction</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-brand-gold">532412</span>
                                <span class="text-slate-700">Construction, Mining, and Forestry Machinery and Equipment Rental and Leasing</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card 3: Business Classifications -->
                    <div class="stagger-child card-executive rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-brand-gold"></div>
                        <h3 class="text-slate-900 font-bold text-sm tracking-wider uppercase mb-5 pb-2 border-b border-slate-100">
                            Business Classifications
                        </h3>
                        <ul class="space-y-4 text-xs">
                            <li class="flex items-start justify-between">
                                <span class="text-slate-500">Business Structure</span>
                                <span class="font-semibold text-slate-900">Limited Liability Company (LLC)</span>
                            </li>
                            <li class="flex items-start justify-between">
                                <span class="text-slate-500">Socio-Economic Category</span>
                                <span class="font-semibold text-slate-900">Self-Certified Small Business</span>
                            </li>
                            <li class="flex items-start justify-between">
                                <span class="text-slate-500">State of Incorporation</span>
                                <span class="font-semibold text-slate-900">Nevada</span>
                            </li>
                            <li class="flex items-start justify-between">
                                <span class="text-slate-500">Service Category</span>
                                <span class="font-semibold text-slate-900">Diversified Services Group</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3: CORE CAPABILITIES GRID
             ═══════════════════════════════════════════════════════ -->
        <section id="capabilities" class="bg-slate-50 py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Core Capabilities</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">We deliver specialized execution and project oversight capabilities across our primary operational divisions.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">
                    
                    <!-- Capability 1 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l.7.7a2.5 2.5 0 010 3.54l-5.1 5.1m0 0l5.1 5.1"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">General Contracting</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Comprehensive oversight, subcontractor scheduling, and project management for commercial renovations and infrastructure maintenance.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 2 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Facility Maintenance</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Reliable interior, exterior, electrical, and structural upkeep for state, municipal, and educational facilities.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 3 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09.542.56.94 1.11.94h1.093c.55 0 1.02-.398 1.11-.94l.149-.894c.07-.424.384-.774.8-.915l.747-.25a1.155 1.155 0 011.243.298l.647.647c.376.376.883.527 1.39.403l.797-.193a1.155 1.155 0 011.243.297l.648.648c.376.376.527.884.403 1.39l-.193.797c-.124.507.027 1.014.403 1.39l.647.647c.394.394.512.98.298 1.243l-.25.747a1.155 1.155 0 01-.915.8l-.894.149a1.11 1.11 0 00-.94 1.11v1.093c0 .55.398 1.02.94 1.11l.894.149c.424.07.774.384.915.8l.25.747a1.155 1.155 0 01-.298 1.243l-.647.647c-.376.376-.884.527-1.39.403l-.797-.193a1.155 1.155 0 01-1.243.297l-.648.648a1.155 1.155 0 01-1.243.297l-.797-.193a1.11 1.11 0 00-1.11.94l-.149.894c-.07.424-.384.774-.8.915l-.747.25a1.155 1.155 0 01-1.243-.298l-.647-.647a1.11 1.11 0 00-1.39-.403l-.797.193a1.155 1.155 0 01-1.243-.297l-.648-.648a1.155 1.155 0 01-.403-1.39l.193-.797c.124-.507-.027-1.014-.403-1.39l-.647-.647a1.155 1.155 0 01-.298-1.243l.25-.747c.14-.416.49-.73.915-.8l.894-.149a1.11 1.11 0 00.94-1.11v-1.093c0-.55-.398-1.02-.94-1.11l-.894-.149a1.155 1.155 0 01-.915-.8l-.25-.747a1.155 1.155 0 01.298-1.243l.647-.647c.376-.376.884-.527 1.39-.403l.797.193a1.155 1.155 0 011.243-.297l.648-.648c.376-.376.403-1.39l-.193-.797a1.155 1.155 0 01-.403-1.39l.647-.647z"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Fleet Support Services</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Logistics, leasing, parts sourcing, and routine preventative maintenance coordination for government and support fleets.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 4 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Transportation Logistics</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Freight routing, hot-shot delivery, LTL/FTL logistics, and support activities for regional transportation networks.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 5 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Equipment Rental</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Short and long-term rental solutions for commercial construction machinery, specialized tools, and jobsite supplies.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 6 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Construction Services</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Interior build-outs, tenant improvements, framing, drywall, remodeling, and structural upgrades for public buildings.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 7 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Administrative Support</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Project management assistance, operational consulting, documentation coordination, and strategic business advisory.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 8 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-4 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-2 tracking-wide">Procurement Assistance</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                End-to-end supply chain logistics, equipment acquisition, vendor sourcing, and purchasing program assistance.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 4: WHY WORK WITH RM GROUP STRATEGIES
             ═══════════════════════════════════════════════════════ -->
        <section id="why-work-with-us" class="bg-white py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    
                    <!-- Left Column -->
                    <div class="space-y-6 animate-on-scroll">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-wide">
                            Why Work With RM Group Strategies?
                        </h2>
                        <div class="h-0.5 w-16 bg-brand-gold"></div>
                        <p class="text-slate-700 text-sm leading-relaxed">
                            Government agencies and prime contractors require vendors who possess more than just basic capability—they demand regulatory alignment, execution safety, and administrative transparency.
                        </p>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Our corporate holding model ensures that each of our specialized operational divisions benefits from centralized legal, compliance, and financial management. This structural setup provides the stability of a large organization combined with the focus of a specialized contractor.
                        </p>
                    </div>

                    <!-- Right Column (Checklist) -->
                    <div class="space-y-4 animate-on-scroll">
                        <!-- Bullet 1 -->
                        <div class="stagger-child flex gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200/80 shadow-sm">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296"/></svg>
                            <div>
                                <h4 class="text-slate-900 font-bold text-xs uppercase tracking-wider mb-1">FAR Regulatory Alignment</h4>
                                <p class="text-slate-600 text-[11px] leading-relaxed">We strictly adhere to Federal Acquisition Regulations (FAR) and project parameters.</p>
                            </div>
                        </div>

                        <!-- Bullet 2 -->
                        <div class="stagger-child flex gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200/80 shadow-sm">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296"/></svg>
                            <div>
                                <h4 class="text-slate-900 font-bold text-xs uppercase tracking-wider mb-1">Schedule &amp; Quality Control</h4>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Unified project oversight structures ensure field safety, quality controls, and clean timelines.</p>
                            </div>
                        </div>

                        <!-- Bullet 3 -->
                        <div class="stagger-child flex gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200/80 shadow-sm">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296"/></svg>
                            <div>
                                <h4 class="text-slate-900 font-bold text-xs uppercase tracking-wider mb-1">Detailed Operational Reporting</h4>
                                <p class="text-slate-600 text-[11px] leading-relaxed">Centralized accounting and reporting systems supply clean audits and project compliance documentation.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 5: CONTRACT VEHICLES
             ═══════════════════════════════════════════════════════ -->
        <section id="vehicles" class="bg-slate-50 py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Contract Vehicles</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">We are equipped to execute contracts and coordinate partnerships across multiple organizational tiers.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 animate-on-scroll mt-8">
                    
                    <!-- Vehicle 1: Federal Agencies -->
                    <div class="stagger-child card-executive rounded-xl border-t-4 border-t-brand-gold p-6 text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M3 21h18M12 6.75h.008v.008H12V6.75z"/>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-sm mb-2">Federal Agencies</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Defense and civilian federal procurement and service support contracts.
                        </p>
                    </div>

                    <!-- Vehicle 2: State Agencies -->
                    <div class="stagger-child card-executive rounded-xl border-t-4 border-t-brand-gold p-6 text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a.75.75 0 00.584-.73V4.26a.75.75 0 00-.916-.736l-2.884.679a9 9 0 01-6.086-.71l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"/>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-sm mb-2">State Agencies</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            State infrastructure, fleet leasing, and regional logistics agreements.
                        </p>
                    </div>

                    <!-- Vehicle 3: Municipalities -->
                    <div class="stagger-child card-executive rounded-xl border-t-4 border-t-brand-gold p-6 text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-sm mb-2">Municipalities</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Local public works, vehicle pools, and municipal facility support.
                        </p>
                    </div>

                    <!-- Vehicle 4: Education -->
                    <div class="stagger-child card-executive rounded-xl border-t-4 border-t-brand-gold p-6 text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 14.634l7.74-4.487L12 5.658 4.26 10.147zm0 0v4.487l7.74 4.487v-4.487L4.26 10.147z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 12v4.5M12 19.121l7.74-4.487"/>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-sm mb-2">Education</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            School district logistics, facility remodeling, and equipment leasing.
                        </p>
                    </div>

                    <!-- Vehicle 5: Prime Partners -->
                    <div class="stagger-child card-executive rounded-xl border-t-4 border-t-brand-gold p-6 text-center col-span-1 md:col-span-3 lg:col-span-1">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-lg bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a5.97 5.97 0 00-.942 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold text-sm mb-2">Prime Partners</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Subcontracting partner helping prime contractors meet small business goals.
                        </p>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 6: OUTLINED CONTRACTING CTA
             ═══════════════════════════════════════════════════════ -->
        <section id="cta" class="bg-slate-900 py-20 lg:py-28 relative overflow-hidden text-white">
            <!-- Top divider accent line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent"></div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="border border-brand-gold/30 bg-slate-800/80 rounded-2xl p-8 sm:p-12 text-center animate-on-scroll shadow-xl">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 tracking-wide">
                        Inquire About Subcontracting &amp; Contract Opportunities
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed mb-8 max-w-xl mx-auto">
                        Our business development team responds to contracting officers, procurement agencies, and prime partners within 24 business hours.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-gold w-full sm:w-auto">
                            Submit Opportunity Inquiry
                        </a>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-outline-gold w-full sm:w-auto">
                            Request Capability Statement
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Scroll Animation Observer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -40px 0px'
            });

            document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
                observer.observe(el);
            });
        });
    </script>

</body>
</html>

