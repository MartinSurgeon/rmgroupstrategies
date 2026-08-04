<?php
/**
 * RM Group Strategies LLC — About Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'about';
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
    <title>About Our Company | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Learn about <?php echo SITE_NAME; ?>, a Nevada-based parent company and diversified business holding group specializing in government contracting, transportation, fleet management, and construction.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/about.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="About Our Company | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Diversified business holding and management group headquarted in Las Vegas, Nevada. Specialized divisions in transportation, fleet, remodeling, and tools.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/about.php">
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
    
    <style>
        /* Specific CSS flowchart connector lines */
        .connector-line-v {
            width: 2px;
            height: 32px;
            background: linear-gradient(180deg, #D4AF37, rgba(212, 175, 55, 0.2));
            margin: 0 auto;
        }

        .connector-line-h {
            height: 2px;
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.2), #D4AF37 50%, rgba(212, 175, 55, 0.2));
        }

        .flow-node {
            transition: all 0.3s ease;
        }

        .flow-node:hover {
            border-color: #D4AF37;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="font-inter bg-slate-50 text-slate-800">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: BREADCRUMB / HEADER BANNER
             ═══════════════════════════════════════════════════════ -->
        <section class="relative bg-slate-900 border-b border-slate-800 py-16 sm:py-24 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/abouthero.jpg');"></div>
            <!-- Dark Overlay for Readability -->
            <div class="absolute inset-0 bg-slate-900/80 bg-gradient-to-b from-slate-900/60 to-slate-900/95"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-in-up">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
                    About Our Company
                </h1>
                <nav class="flex justify-center text-xs sm:text-sm tracking-wider uppercase text-white/60 font-semibold" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2.5">
                        <li>
                            <a href="<?php echo BASE_URL; ?>/" class="hover:text-brand-gold transition-colors duration-300">Home</a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-white/30">/</span>
                            <span class="text-brand-gold">About</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: WHO WE ARE (POSITIONING & MISSION)
             ═══════════════════════════════════════════════════════ -->
        <section id="positioning" class="bg-white py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    
                    <!-- Left: Premium Text Block -->
                    <div class="space-y-8 animate-fade-in-up animate-delay-100">
                        <div>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                                A Diversified Business Holding &amp; Management Group
                            </h2>
                            <div class="h-1.5 w-24 bg-brand-gold rounded-full"></div>
                        </div>
                        
                        <div class="space-y-6 text-slate-600 leading-relaxed text-base sm:text-lg">
                            <p>
                                <strong class="text-slate-900 font-bold">RM Group Strategies LLC</strong> is a diversified business holding and management company specializing in government contracting, transportation services, fleet management, construction and remodeling, equipment rental, business consulting, and procurement solutions.
                            </p>
                            <p>
                                Operating from our headquarters in Las Vegas, Nevada, we structure and coordinate high-performing division lines designed to deliver dependable solutions for commercial entities, government procurement agencies, and private-sector clients across the United States.
                            </p>
                        </div>

                        <div class="pt-2">
                            <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="btn-gold px-8 py-3.5 text-sm inline-flex items-center gap-2">
                                Review Core Capabilities
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Interactive / Executive Visual Component -->
                    <div class="relative animate-fade-in-up animate-delay-200 lg:pl-8">
                        <!-- Decorative background element -->
                        <div class="absolute inset-0 bg-slate-100 rounded-3xl transform translate-x-4 translate-y-4 border border-slate-200"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-slate-900 rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 flex flex-col justify-between overflow-hidden">
                            <!-- Subtle Gold gradient glow in the corner -->
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-brand-gold/10 blur-3xl rounded-full pointer-events-none"></div>

                            <!-- Logo Header -->
                            <div class="relative z-10 flex items-center justify-between mb-12 border-b border-white/10 pb-8">
                                <img src="<?php echo BASE_URL; ?>/assets/images/ico.png" alt="<?php echo SITE_NAME; ?> Crest Logo" class="h-20 w-auto object-contain drop-shadow-lg" />
                                <span class="px-3 py-1.5 rounded-lg bg-amber-400/10 border border-amber-400/20 text-brand-gold text-[10px] font-bold uppercase tracking-widest hidden sm:inline-block">
                                    HQ: Las Vegas, NV
                                </span>
                            </div>

                            <!-- Mission Statement -->
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-8 h-8 rounded bg-brand-gold/20 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                    </div>
                                    <h3 class="text-white font-bold tracking-widest uppercase text-xs">Our Mission</h3>
                                </div>
                                <blockquote class="text-slate-300/90 italic leading-relaxed sm:text-lg">
                                    "To build strategic, long-term partnerships by delivering reliable, high-performance operational solutions across our diversified division lines, ensuring excellence in every contract and commercial project."
                                </blockquote>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3: CORE DIFFERENTIATORS
             ═══════════════════════════════════════════════════════ -->
        <section id="differentiators" class="bg-slate-50 py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Our Core Differentiators</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">Why government agencies and commercial prime contractors choose to partner with RM Group Strategies.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">

                    <!-- Diff 1 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-3 tracking-wide">Diversified Service Portfolio</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Our multi-division model offers a unified channel for transportation logistics, commercial remodeling, fleet operations, and equipment rental. This reduces vendor management friction for our strategic clients.
                            </p>
                        </div>
                    </div>

                    <!-- Diff 2 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.868 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.651A1.125 1.125 0 003 6.658v11.182c0 .426.24.817.622 1.006l4.875 2.437c.316.158.69.158 1.006 0l5.006-2.503z"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-3 tracking-wide">Nevada-Based Operations</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Headquartered in Las Vegas, Nevada, we leverage our strategic geographic location to manage national fleet routing, supply chains, and procurement solutions with regional and federal agility.
                            </p>
                        </div>
                    </div>

                    <!-- Diff 3 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-3 tracking-wide">Government Contract Ready</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                We are SAM.gov registered, hold an active UEI number, and are structurally aligned for small business opportunities, joint ventures, and prime contractor compliance frameworks.
                            </p>
                        </div>
                    </div>

                    <!-- Diff 4 -->
                    <div class="stagger-child card-executive rounded-xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 mb-5 rounded bg-amber-50 border border-amber-200/60 flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-semibold text-sm mb-3 tracking-wide">Strategic Alliances</h3>
                            <p class="text-slate-600 text-xs leading-relaxed">
                                Our business development group manages close communication channels with trusted vendors, subcontractors, and prime contractors to guarantee execution capacity and speed.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 4: CORPORATE STRUCTURE
             ═══════════════════════════════════════════════════════ -->
        <section id="structure" class="bg-white py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Our Corporate Structure</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">We operate under a structured corporate hierarchy built for compliance, financial protection, and operational focus.</p>
                    </div>
                </div>

                <!-- CSS Flowchart Diagram -->
                <div class="max-w-3xl mx-auto animate-on-scroll mt-12 px-4 py-8 bg-slate-50 border border-slate-200/80 rounded-xl shadow-sm">
                    <div class="flex flex-col items-center">
                        
                        <!-- Parent Node -->
                        <div class="flow-node bg-white border border-brand-gold/40 rounded-lg px-6 py-4 text-center max-w-sm w-full shadow-sm">
                            <span class="text-xs uppercase tracking-widest text-brand-gold font-semibold mb-1 block">Parent Corporation</span>
                            <h3 class="text-slate-900 font-bold text-base">RM Group Strategies LLC</h3>
                            <p class="text-[10px] text-slate-500 mt-1">Strategic Oversight &amp; Compliance Management</p>
                        </div>

                        <!-- Connector -->
                        <div class="connector-line-v"></div>

                        <!-- Horizontal Divider line for grid nodes -->
                        <div class="w-full grid grid-cols-4 px-[12.5%]">
                            <div class="connector-line-h col-span-3"></div>
                            <div class="connector-line-h"></div>
                        </div>

                        <!-- Vertical Connectors into Grid Nodes -->
                        <div class="w-full grid grid-cols-4 text-center">
                            <div><div class="connector-line-v h-6"></div></div>
                            <div><div class="connector-line-v h-6"></div></div>
                            <div><div class="connector-line-v h-6"></div></div>
                            <div><div class="connector-line-v h-6"></div></div>
                        </div>

                        <!-- Division Nodes Grid -->
                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Div 1 -->
                            <div class="flow-node bg-white border border-slate-200 rounded-lg p-4 text-center shadow-sm">
                                <span class="text-[10px] uppercase tracking-widest text-brand-gold font-semibold block mb-1">Division</span>
                                <h4 class="text-slate-900 font-bold text-sm">RM Fleet</h4>
                                <p class="text-[9px] text-slate-500 mt-1">Fleet Leasing &amp; Maintenance</p>
                            </div>
                            <!-- Div 2 -->
                            <div class="flow-node bg-white border border-slate-200 rounded-lg p-4 text-center shadow-sm">
                                <span class="text-[10px] uppercase tracking-widest text-brand-gold font-semibold block mb-1">Division</span>
                                <h4 class="text-slate-900 font-bold text-sm">RM Remodeling</h4>
                                <p class="text-[9px] text-slate-500 mt-1">Construction &amp; Renovations</p>
                            </div>
                            <!-- Div 3 -->
                            <div class="flow-node bg-white border border-slate-200 rounded-lg p-4 text-center shadow-sm">
                                <span class="text-[10px] uppercase tracking-widest text-brand-gold font-semibold block mb-1">Division</span>
                                <h4 class="text-slate-900 font-bold text-sm">RM Tools &amp; Equipment</h4>
                                <p class="text-[9px] text-slate-500 mt-1">Equipment Rental &amp; Procurement</p>
                            </div>
                            <!-- Div 4 -->
                            <div class="flow-node bg-white border border-slate-200 rounded-lg p-4 text-center shadow-sm">
                                <span class="text-[10px] uppercase tracking-widest text-brand-gold font-semibold block mb-1">Division</span>
                                <h4 class="text-slate-900 font-bold text-sm">RM Transport</h4>
                                <p class="text-[9px] text-slate-500 mt-1">Freight &amp; Logistics Routing</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 5: MANAGEMENT PHILOSOPHY
             ═══════════════════════════════════════════════════════ -->
        <section id="philosophy" class="bg-slate-50 py-20 lg:py-28 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-slate-900 font-bold text-3xl">Management Philosophy</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600">Our operational framework focuses on three pillars to ensure successful contract execution.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-on-scroll">

                    <!-- Pillar 1: Compliance -->
                    <div class="stagger-child card-executive p-6 rounded-xl border-l-4 border-l-brand-gold">
                        <h3 class="text-slate-900 font-bold text-base mb-3 tracking-wide">1. Rigorous Compliance</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Federal acquisition guidelines (FAR), SAM registration parameters, and small-business subcontracting limits demand absolute regulatory precision. We maintain compliance protocols across all active divisions.
                        </p>
                    </div>

                    <!-- Pillar 2: Execution -->
                    <div class="stagger-child card-executive p-6 rounded-xl border-l-4 border-l-brand-gold">
                        <h3 class="text-slate-900 font-bold text-base mb-3 tracking-wide">2. Quality Execution</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            Whether managing freight transportation timelines or executing tenant improvements under RM Remodeling, our division heads prioritize field safety, clean timelines, and budget targets.
                        </p>
                    </div>

                    <!-- Pillar 3: Alliance -->
                    <div class="stagger-child card-executive p-6 rounded-xl border-l-4 border-l-brand-gold">
                        <h3 class="text-slate-900 font-bold text-base mb-3 tracking-wide">3. Collaborative Alliance</h3>
                        <p class="text-slate-600 text-xs leading-relaxed">
                            No project succeeds in isolation. We treat subcontractor networks, suppliers, and procurement officials as strategic partners, sharing alignment goals to deliver top-tier contract performance.
                        </p>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 6: CALL TO ACTION
             ═══════════════════════════════════════════════════════ -->
        <section id="cta" class="bg-slate-900 py-20 lg:py-28 relative overflow-hidden text-white">
            <!-- Top border divider -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 tracking-wide">
                    Let’s Build a Strategic Partnership
                </h2>
                <p class="text-slate-300 text-base leading-relaxed mb-10 max-w-xl mx-auto">
                    Speak with our business development team today about government contracting, prime partnership, or commercial opportunities.
                </p>
                <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-gold text-base px-8 py-4">
                    Contact Our Team
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

