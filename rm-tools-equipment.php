<?php
/**
 * RM Group Strategies LLC — RM Tools & Equipment Division Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'companies';
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
    <title>RM Tools & Equipment | Rental & Leasing | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="RM Tools & Equipment offers industrial equipment rentals, construction tool leasing, heavy machinery, and jobsite support in Nevada and nationwide.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/rm-tools-equipment.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="RM Tools & Equipment | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Industrial equipment leasing and construction tool rental solutions by RM Tools & Equipment, an operating division of RM Group Strategies LLC.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/rm-tools-equipment.php">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        <div class="absolute inset-0 bg-gradient-to-r from-[#0B1727] via-[#0B1727]/90 to-transparent z-1"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-gold/10 border border-brand-gold/30 text-brand-gold text-xs font-semibold uppercase tracking-wider mb-6">
                    <span>Operating Division of RM Group Strategies</span>
                </div>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    RM Tools &amp; Equipment
                </h1>
                <p class="text-lg text-slate-300 leading-relaxed mb-8">
                    Providing commercial-grade construction equipment rentals, specialized tool leasing, jobsite power solutions, and equipment fleet support for contractors and government projects.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo BASE_URL; ?>/contact.php?type=consultation&sector=tools_equipment" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-7">
                        Request Equipment Reservation
                    </a>
                    <a href="#inventory" class="btn-outline-white text-xs uppercase tracking-widest font-semibold py-3.5 px-7">
                        View Equipment Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Division Capabilities Grid -->
    <section id="inventory" class="py-20 lg:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight mb-4">Equipment &amp; Tool Rental Categories</h2>
                <div class="gold-divider-lg mx-auto mb-4"></div>
                <p class="text-slate-600 text-sm sm:text-base">Reliable, fully-maintained machinery and industrial tools ready for immediate jobsite deployment.</p>
            </div>

            <!-- Dark Tone Cards with HCI Contrast -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Heavy Machinery &amp; Excavation</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Compact excavators, skid steers, backhoes, trenchers, and earthmoving machinery equipped for commercial and municipal projects.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Jobsite Power &amp; Lighting</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Industrial diesel generators, temporary power distribution, mobile light towers, and high-output air compressors.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l.7.7a2.5 2.5 0 010 3.54l-5.1 5.1m0 0l5.1 5.1"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Professional Contractor Tools</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Concrete breakers, saws, scaffolding, compaction equipment, commercial pumps, and specialty trade tools.</p>
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
                        RM Tools &amp; Equipment maintains rigorous safety and performance inspection standards. Every tool and machine is tested prior to delivery to ensure maximum uptime on your jobsite.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Daily, Weekly, &amp; Monthly Flexible Lease Terms
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Direct On-Site Jobsite Delivery &amp; Pickup
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            24/7 Equipment Field Maintenance Support
                        </li>
                    </ul>
                    <a href="<?php echo BASE_URL; ?>/contact.php?type=consultation" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-8">
                        Contact Equipment Department
                    </a>
                </div>
                <div class="rounded-2xl overflow-hidden border border-brand-gold/30 shadow-2xl">
                    <img src="<?php echo BASE_URL; ?>/assets/images/rm-tools-equipment.png" alt="RM Tools Showcase" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
