<?php
/**
 * RM Group Strategies LLC — RM Nevada Series LLC Overview Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'companies'; // Matches companies category in nav dropdowns
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title>RM Nevada Series LLC | Our Divisions | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Explore the corporate structure, asset protection advantages, and operational divisions of RM Nevada Series LLC under <?php echo SITE_NAME; ?>.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/rm-nevada-series.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="RM Nevada Series LLC | Our Divisions | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Overview of our specialized Nevada Series LLC divisions in fleet management, construction remodeling, tool leasing, and transport logistics.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/rm-nevada-series.php">
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
    <link rel="stylesheet" href="/rmgroupstrategies/assets/css/custom.css">
</head>

<body class="font-inter bg-brand-black text-brand-white">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: BREADCRUMB / HEADER BANNER
             ═══════════════════════════════════════════════════════ -->
        <section class="bg-brand-dark-gray border-b border-white/5 py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="shield shield-sm">
                        <div class="shield-border"></div>
                        <div class="shield-bg"></div>
                        <span class="shield-text shield-shimmer">RM</span>
                    </div>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold tracking-wide text-white mb-3">
                    RM Nevada Series LLC
                </h1>
                <nav class="flex justify-center text-xs tracking-wider uppercase text-white/40" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li>
                            <a href="/rmgroupstrategies/" class="hover:text-brand-gold transition-colors duration-300">Home</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>/</span>
                            <span class="text-white/40">Our Companies</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>/</span>
                            <span class="text-brand-gold">RM Nevada Series LLC</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: HOLDING OVERVIEW & ADVANTAGE
             ═══════════════════════════════════════════════════════ -->
        <section id="structure-overview" class="bg-brand-black py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                    
                    <!-- Left Column: Narrative -->
                    <div class="lg:col-span-7 space-y-6 animate-fade-in-up animate-delay-100">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-wide">
                            Corporate Holding &amp; Protection
                        </h2>
                        <div class="h-0.5 w-16 bg-brand-gold"></div>
                        <p class="text-white/70 text-sm leading-relaxed">
                            <strong>RM Nevada Series LLC</strong> is structured under Nevada's Series LLC laws as a specialized holding entity designed to support corporate asset segregation and risk management strategies.
                        </p>
                        <p class="text-white/60 text-xs leading-relaxed">
                            This corporate structure enables us to house our transport logistics, general contracting, tool leasing, and commercial fleet management operations in separate, independent series divisions. Each series functions with its own segregated assets and liabilities, providing a resilient operational foundation that shields the parent group and guarantees business continuity for our contract partners.
                        </p>
                    </div>

                    <!-- Right Column: Advantage Info Card -->
                    <div class="lg:col-span-5 bg-brand-dark-gray/40 border border-brand-gold/20 rounded-xl p-6 sm:p-8 animate-fade-in-up animate-delay-200">
                        <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296"/></svg>
                            The Series LLC Advantage
                        </h3>
                        <ul class="space-y-4 text-xs text-white/70">
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-gold mt-1.5 flex-shrink-0"></span>
                                <span><strong>Liability Segregation:</strong> Liability claims against one division cannot impact the assets of other divisions.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-gold mt-1.5 flex-shrink-0"></span>
                                <span><strong>Asset Segregation:</strong> Equipment, tools, and vehicle holdings are isolated within their target divisions.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-gold mt-1.5 flex-shrink-0"></span>
                                <span><strong>Unified Compliance:</strong> All divisions benefit from centralized legal oversight andFAR regulatory management.</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3: CORPORATE DIVISIONS GRID (2x2)
             ═══════════════════════════════════════════════════════ -->
        <section id="divisions" class="bg-brand-dark-gray py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2>Our Operational Divisions</h2>
                        <div class="gold-divider-lg"></div>
                        <p>Explore the specialized services and contract capabilities housed within our independent corporate divisions.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-on-scroll">
                    
                    <!-- Division 1: RM Fleet -->
                    <div class="stagger-child bg-brand-black border border-white/5 rounded-xl p-8 flex flex-col justify-between hover:border-brand-gold/30 transition-all duration-300">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-white font-bold text-lg tracking-wide">RM Fleet</h3>
                                <div class="w-10 h-10 rounded bg-brand-gold/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09.542.56.94 1.11.94h1.093c.55 0 1.02-.398 1.11-.94l.149-.894c.07-.424.384-.774.8-.915l.747-.25a1.155 1.155 0 011.243.298l.647.647c.376.376.883.527 1.39.403l.797-.193a1.155 1.155 0 011.243.297l.648.648c.376.376.527.884.403 1.39l-.193.797c-.124.507.027 1.014.403 1.39l.647.647c.394.394.512.98.298 1.243l-.25.747a1.155 1.155 0 01-.915.8l-.894.149a1.11 1.11 0 00-.94 1.11v1.093c0 .55.398 1.02.94 1.11l.894.149c.424.07.774.384.915.8l.25.747a1.155 1.155 0 01-.298 1.243l-.647.647c-.376.376-.884.527-1.39.403l-.797-.193a1.155 1.155 0 01-1.243.297l-.648.648a1.155 1.155 0 01-1.243.297l-.797-.193a1.11 1.11 0 00-1.11.94l-.149.894c-.07.424-.384.774-.8.915l-.747.25a1.155 1.155 0 01-1.243-.298l-.647-.647a1.11 1.11 0 00-1.39-.403l-.797.193a1.155 1.155 0 01-1.243-.297l-.648-.648a1.155 1.155 0 01-.403-1.39l.193-.797c.124-.507-.027-1.014-.403-1.39l-.647-.647a1.155 1.155 0 01-.298-1.243l.25-.747c.14-.416.49-.73.915-.8l.894-.149a1.11 1.11 0 00.94-1.11v-1.093c0-.55-.398-1.02-.94-1.11l-.894-.149a1.155 1.155 0 01-.915-.8l-.25-.747a1.155 1.155 0 01.298-1.243l.647-.647c.376-.376.884-.527 1.39-.403l.797.193a1.155 1.155 0 011.243-.297l.648-.648c.376-.376.403-1.39l-.193-.797a1.155 1.155 0 01-.403-1.39l.647-.647z"/></svg>
                                </div>
                            </div>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">
                                Commercial vehicle leasing, fleet sourcing, preventative maintenance coordination, and government vehicle pool management.
                            </p>
                            <ul class="space-y-2 text-xs text-white/70 mb-8">
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Commercial &amp; Government Fleet Leasing</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Specialized Vehicle Sourcing &amp; Acquisition</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Preventative Fleet Maintenance Coordination</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Commercial Vehicle Management Program</span>
                                </li>
                            </ul>
                        </div>
                        <a href="/rmgroupstrategies/rm-fleet.php" class="btn-outline-gold w-full text-center text-xs py-3">
                            View Fleet Division
                        </a>
                    </div>

                    <!-- Division 2: RM Remodeling -->
                    <div class="stagger-child bg-brand-black border border-white/5 rounded-xl p-8 flex flex-col justify-between hover:border-brand-gold/30 transition-all duration-300">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-white font-bold text-lg tracking-wide">RM Remodeling</h3>
                                <div class="w-10 h-10 rounded bg-brand-gold/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                </div>
                            </div>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">
                                Commercial tenant improvements, residential interior/exterior renovations, general contracting, and property rehabilitation.
                            </p>
                            <ul class="space-y-2 text-xs text-white/70 mb-8">
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Commercial Tenant Interior Improvements</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Residential Renovations &amp; Additions</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>General Contracting Project Oversight</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Property Rehabilitation &amp; Structural Finish</span>
                                </li>
                            </ul>
                        </div>
                        <a href="/rmgroupstrategies/rm-remodeling.php" class="btn-outline-gold w-full text-center text-xs py-3">
                            View Remodeling Division
                        </a>
                    </div>

                    <!-- Division 3: RM Tools & Equipment -->
                    <div class="stagger-child bg-brand-black border border-white/5 rounded-xl p-8 flex flex-col justify-between hover:border-brand-gold/30 transition-all duration-300">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-white font-bold text-lg tracking-wide">RM Tools &amp; Equipment</h3>
                                <div class="w-10 h-10 rounded bg-brand-gold/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l.7.7a2.5 2.5 0 010 3.54l-5.1 5.1m0 0l5.1 5.1"/></svg>
                                </div>
                            </div>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">
                                Construction machinery lease programs, specialized tool rentals, jobsite equipment logistics, and vendor supply chain procurement.
                            </p>
                            <ul class="space-y-2 text-xs text-white/70 mb-8">
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Heavy Construction Equipment Rentals</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Specialized Commercial Machinery Sourcing</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Contractor Jobsite Tool Sourcing &amp; Supplies</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Procurement Solutions &amp; Program Sourcing</span>
                                </li>
                            </ul>
                        </div>
                        <a href="/rmgroupstrategies/rm-tools-equipment.php" class="btn-outline-gold w-full text-center text-xs py-3">
                            View Equipment Division
                        </a>
                    </div>

                    <!-- Division 4: RM Transport -->
                    <div class="stagger-child bg-brand-black border border-white/5 rounded-xl p-8 flex flex-col justify-between hover:border-brand-gold/30 transition-all duration-300">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-white font-bold text-lg tracking-wide">RM Transport</h3>
                                <div class="w-10 h-10 rounded bg-brand-gold/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25"/></svg>
                                </div>
                            </div>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">
                                Local and long-distance freight hauling, supply chain logistics routing, delivery support, and government transportation contracts.
                            </p>
                            <ul class="space-y-2 text-xs text-white/70 mb-8">
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Regional Freight Hauling &amp; Transit</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>LTL / FTL Shipping Coordination</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Supply Chain &amp; Route Logistics Sourcing</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1 h-1 rounded-full bg-brand-gold"></span>
                                    <span>Government Transport Support Contracts</span>
                                </li>
                            </ul>
                        </div>
                        <a href="/rmgroupstrategies/rm-transport.php" class="btn-outline-gold w-full text-center text-xs py-3">
                            View Transport Division
                        </a>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 4: LEAD CONSULTATION CTA
             ═══════════════════════════════════════════════════════ -->
        <section id="cta" class="bg-brand-black py-20 relative overflow-hidden">
            <!-- Top divider line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
                <h2 class="text-2xl font-bold text-white mb-3 tracking-wide">
                    Looking for Specialized Operational Support?
                </h2>
                <p class="text-white/60 text-sm leading-relaxed mb-8 max-w-lg mx-auto">
                    Our divisions coordinate under RM Group Strategies LLC to deliver turnkey logistics, construction, equipment, and fleet solutions.
                </p>
                <a href="/rmgroupstrategies/contact.php?type=consultation" class="btn-gold text-sm px-8 py-3.5">
                    Request Division Consultation
                </a>
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
