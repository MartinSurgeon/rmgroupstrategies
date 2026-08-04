<?php
/**
 * RM Group Strategies LLC — RM Remodeling Division Page
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
    <title>RM Remodeling | Construction & Renovation | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="RM Remodeling provides premier residential and commercial construction, remodeling, tenant improvements, and property renovation services in Nevada and nationwide.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/rm-remodeling.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="RM Remodeling | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Premier construction, renovations, and remodeling solutions by RM Remodeling, an operating division of RM Group Strategies LLC.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/rm-remodeling.php">

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
            <img src="<?php echo BASE_URL; ?>/assets/images/rm-remodeling.png" alt="RM Remodeling Banner" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0B1727] via-[#0B1727]/90 to-transparent z-1"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-gold/10 border border-brand-gold/30 text-brand-gold text-xs font-semibold uppercase tracking-wider mb-6">
                    <span>Operating Division of RM Group Strategies</span>
                </div>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    RM Remodeling
                </h1>
                <p class="text-lg text-slate-300 leading-relaxed mb-8">
                    Delivering premier commercial interior improvements, residential renovations, property upgrades, and general contracting solutions with uncompromised quality and reliability.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo BASE_URL; ?>/contact.php?type=consultation&sector=construction_remodeling" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-7">
                        Request Remodeling Quote
                    </a>
                    <a href="#capabilities" class="btn-outline-white text-xs uppercase tracking-widest font-semibold py-3.5 px-7">
                        View Capabilities
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Division Capabilities Grid -->
    <section id="capabilities" class="py-20 lg:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight mb-4">Remodeling &amp; Construction Services</h2>
                <div class="gold-divider-lg mx-auto mb-4"></div>
                <p class="text-slate-600 text-sm sm:text-base">Comprehensive contractor capabilities designed for commercial facility managers, government property upgrades, and residential estate developments.</p>
            </div>

            <!-- Dark Tone Cards with HCI Contrast -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Commercial Tenant Improvements</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Full-scale commercial office build-outs, retail space refurbishments, executive suite upgrades, and ADA compliance retrofits.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Residential Renovations</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Complete home transformations, kitchen &amp; bath remodels, room additions, structural upgrades, and exterior facade enhancements.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#0B1727] border border-brand-gold/30 rounded-xl p-8 text-white shadow-xl hover:border-brand-gold transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-brand-gold/15 border border-brand-gold/40 flex items-center justify-center text-brand-gold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Government Facility Maintenance</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Public sector building upgrades, preventive facility repairs, municipal office remodeling, and government contracting compliance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Image Section -->
    <section class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-brand-gold text-xs font-bold uppercase tracking-widest block mb-2">Craftsmanship &amp; Quality</span>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Precision Remodeling Built for Impact</h2>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        RM Remodeling combines experienced project managers, skilled licensed tradesmen, and top-grade materials to deliver projects on schedule and within budget.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Licensed, Insured, &amp; Safety Compliant
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dedicated Project Supervision &amp; Daily Reporting
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Transparent Estimates &amp; Budget Management
                        </li>
                    </ul>
                    <a href="<?php echo BASE_URL; ?>/contact.php?type=consultation" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-8">
                        Contact RM Remodeling Team
                    </a>
                </div>
                <div class="rounded-2xl overflow-hidden border border-brand-gold/30 shadow-2xl">
                    <img src="<?php echo BASE_URL; ?>/assets/images/rm-remodeling.png" alt="RM Remodeling Showcase" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
