<?php
/**
 * RM Group Strategies LLC — Capability Statement Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'government-contracting'; // Belongs to Government Contracting category in nav
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="<?php echo BASE_URL; ?>/assets/images/icon.jpeg">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/images/icon.jpeg">

    <!-- SEO -->
    <title>Capability Statement | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Review the official capability statement for <?php echo SITE_NAME; ?>. Core competencies, company data, NAICS codes, past performance, and differentiators.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/capability-statement.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Capability Statement | <?php echo SITE_NAME; ?>">
    <meta property="og:description" content="Download or review the capability statement of RM Group Strategies LLC. UEI/CAGE registered small business in Nevada.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/capability-statement.php">
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
        /* Printable layout visual improvements */
        .capability-sheet {
            background-color: #0d0d0d;
            background-image: radial-gradient(circle at 100% 0%, rgba(212, 175, 55, 0.03) 0%, transparent 40%);
        }

        .section-accent-header {
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .past-perf-card {
            border-left: 2px solid rgba(212, 175, 55, 0.4);
            padding-left: 1rem;
            transition: border-color 0.3s ease;
        }

        .past-perf-card:hover {
            border-color: #D4AF37;
        }
    </style>
</head>

<body class="font-inter bg-brand-black text-brand-white">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: BREADCRUMB / HEADER BANNER
             ═══════════════════════════════════════════════════════ -->
        <section class="bg-brand-dark-gray border-b border-white/5 py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    
                    <!-- Left: Titles -->
                    <div class="text-center md:text-left">
                        <div class="flex justify-center md:justify-start mb-3">
                            <img src="<?php echo BASE_URL; ?>/assets/images/icon.jpeg" alt="<?php echo SITE_NAME; ?> Logo" class="h-10 w-auto object-contain rounded-md" />
                        </div>
                        <h1 class="text-3xl font-bold tracking-wide text-white mb-2">
                            Capability Statement
                        </h1>
                        <nav class="flex justify-center md:justify-start text-xs tracking-wider uppercase text-white/40" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-2">
                                <li>
                                    <a href="<?php echo BASE_URL; ?>/" class="hover:text-brand-gold transition-colors duration-300">Home</a>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span>/</span>
                                    <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="hover:text-brand-gold transition-colors duration-300">Government Contracting</a>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span>/</span>
                                    <span class="text-brand-gold">Capability Statement</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Right: Quick CTA Button -->
                    <div class="w-full md:w-auto">
                        <a href="<?php echo BASE_URL; ?>/contact.php?type=capability" class="btn-gold w-full md:w-auto text-xs py-3 px-6">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Request PDF Version
                        </a>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: CAPABILITY SHEET CARD CONTAINER
             ═══════════════════════════════════════════════════════ -->
        <section id="capability-sheet" class="bg-brand-black py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Inner Sheet Container (resembling a printed page) -->
                <div class="capability-sheet border border-white/5 rounded-2xl p-8 sm:p-12 shadow-2xl animate-on-scroll">
                    
                    <!-- Sheet Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 pb-8 border-b border-white/5 mb-10">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <img src="<?php echo BASE_URL; ?>/assets/images/icon.jpeg" alt="<?php echo SITE_NAME; ?> Logo" class="h-12 w-auto object-contain rounded-md" />
                                <h2 class="text-xl sm:text-2xl font-bold uppercase tracking-wider text-white">
                                    <?php echo SITE_NAME_SHORT; ?> <span class="font-normal text-brand-gold">LLC</span>
                                </h2>
                            </div>
                            <p class="text-xs text-white/50 italic tracking-wide">
                                <?php echo SITE_TAGLINE; ?>
                            </p>
                        </div>
                        <div class="text-left sm:text-right text-xs space-y-1">
                            <p class="text-white/40 uppercase tracking-widest text-[9px] font-semibold">Corporate Identification</p>
                            <p class="text-white/80"><span class="text-white/40">SAM Status:</span> <span class="text-green-400 font-semibold">Active</span></p>
                            <p class="text-white/80"><span class="text-white/40">UEI:</span> <span class="text-brand-gold font-semibold">Available Upon Request</span></p>
                            <p class="text-white/80"><span class="text-white/40">CAGE:</span> <span class="text-brand-gold font-semibold">Available Upon Request</span></p>
                        </div>
                    </div>

                    <!-- Two Column Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        <!-- LEFT COLUMN: Competencies & Past Performance (col-span-8) -->
                        <div class="lg:col-span-8 space-y-10">
                            
                            <!-- Core Competencies -->
                            <div class="stagger-child">
                                <div class="section-accent-header">
                                    <h3 class="text-sm uppercase tracking-widest text-brand-gold font-bold">
                                        Core Competencies
                                    </h3>
                                </div>
                                <p class="text-white/70 text-xs leading-relaxed mb-6">
                                    RM Group Strategies LLC is a diversified government contracting and commercial services group. We provide direct procurement, logistics oversight, remodeling, and fleet support through our specialized operations:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <ul class="space-y-2.5 text-xs text-white/60">
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Government Contracting Execution</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>General Contracting &amp; Facilities</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Transportation Routing &amp; Logistics</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Fleet Leasing &amp; Coordination</span>
                                        </li>
                                    </ul>
                                    <ul class="space-y-2.5 text-xs text-white/60">
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Commercial Renovations &amp; Improvements</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Equipment Rental Solutions</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Administrative &amp; Management Consulting</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                                            <span>Procurement Program Support</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Past Performance -->
                            <div class="stagger-child">
                                <div class="section-accent-header">
                                    <h3 class="text-sm uppercase tracking-widest text-brand-gold font-bold">
                                        Representative Past Performance
                                    </h3>
                                </div>
                                <p class="text-white/70 text-xs leading-relaxed mb-6">
                                    Our operational divisions successfully deliver support contracts and project execution across key focus areas:
                                </p>
                                <div class="space-y-6">
                                    <!-- Project 1 -->
                                    <div class="past-perf-card">
                                        <h4 class="text-xs uppercase tracking-wider text-white font-bold mb-1">
                                            Freight &amp; Logistics Routing Support
                                        </h4>
                                        <p class="text-white/40 text-[10px] mb-2">Division: RM Transport | Commercial Scale</p>
                                        <p class="text-white/60 text-xs leading-relaxed">
                                            Coordinated long-haul freight shipping, scheduling, and local routing logistics. Consistently maintained on-schedule performance targets across multi-state shipping corridors.
                                        </p>
                                    </div>

                                    <!-- Project 2 -->
                                    <div class="past-perf-card">
                                        <h4 class="text-xs uppercase tracking-wider text-white font-bold mb-1">
                                            Commercial Remodeling &amp; Tenant Improvements
                                        </h4>
                                        <p class="text-white/40 text-[10px] mb-2">Division: RM Remodeling | Commercial &amp; Private Scale</p>
                                        <p class="text-white/60 text-xs leading-relaxed">
                                            Executed interior remodeling, layout reconfiguration, dry-wall, framing, and finish improvements for commercial office suites, completing projects within target budget guidelines.
                                        </p>
                                    </div>

                                    <!-- Project 3 -->
                                    <div class="past-perf-card">
                                        <h4 class="text-xs uppercase tracking-wider text-white font-bold mb-1">
                                            Municipal Fleet Leasing Coordination
                                        </h4>
                                        <p class="text-white/40 text-[10px] mb-2">Division: RM Fleet | Municipal Scale Support</p>
                                        <p class="text-white/60 text-xs leading-relaxed">
                                            Supported local agency fleet coordination, organizing lease timelines, vehicle replacement parts acquisitions, and routine preventative service records.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT COLUMN: Codes & Differentiators (col-span-4) -->
                        <div class="lg:col-span-4 space-y-10 lg:border-l lg:border-white/5 lg:pl-8">
                            
                            <!-- Company Data -->
                            <div class="stagger-child">
                                <div class="section-accent-header">
                                    <h3 class="text-sm uppercase tracking-widest text-brand-gold font-bold">
                                        Company Data
                                    </h3>
                                </div>
                                <ul class="space-y-4 text-xs">
                                    <li>
                                        <span class="text-white/40 block uppercase tracking-wider text-[9px] mb-1">Incorporation State</span>
                                        <span class="text-white font-semibold">Nevada</span>
                                    </li>
                                    <li>
                                        <span class="text-white/40 block uppercase tracking-wider text-[9px] mb-1">SAM.gov Registration</span>
                                        <span class="text-white font-semibold">Active Status</span>
                                    </li>
                                    <li>
                                        <span class="text-white/40 block uppercase tracking-wider text-[9px] mb-1">Business Category</span>
                                        <span class="text-white font-semibold">Self-Certified Small Business</span>
                                    </li>
                                    <li>
                                        <span class="text-white/40 block uppercase tracking-wider text-[9px] mb-1">Target Codes (NAICS)</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">541611</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">484110</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">484121</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">488490</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">236118</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">236220</span>
                                            <span class="bg-brand-dark-gray border border-white/5 text-brand-gold px-2 py-0.5 rounded font-mono text-[10px] font-bold">532412</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- Differentiators -->
                            <div class="stagger-child">
                                <div class="section-accent-header">
                                    <h3 class="text-sm uppercase tracking-widest text-brand-gold font-bold">
                                        Differentiators
                                    </h3>
                                </div>
                                <ul class="space-y-3.5 text-xs text-white/70">
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span><strong>Diversified Entity:</strong> Consolidates construction, fleet, logistics, and consulting under one legal parent.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span><strong>Nevada Base:</strong> Strategically headquartered in Las Vegas with local, regional, and national logistics capacity.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-brand-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span><strong>Centralized Legal &amp; Compliance:</strong> Assures that all contract administration FAR rules and bookkeeping are audited.</span>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <!-- Print Notice / Note -->
                    <div class="border-t border-white/5 mt-10 pt-6 text-center text-[10px] text-white/30">
                        RM Group Strategies LLC | Las Vegas, NV | contracts@rmgroupstrategies.com | (702) 504-8128
                    </div>

                </div>

            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3: REQUEST CTA
             ═══════════════════════════════════════════════════════ -->
        <section id="cta" class="bg-brand-dark-gray py-20 relative overflow-hidden">
            <!-- Top border divider -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
                <h2 class="text-2xl font-bold text-white mb-3 tracking-wide">
                    Need a Physical Capability Statement?
                </h2>
                <p class="text-white/60 text-sm leading-relaxed mb-8 max-w-lg mx-auto">
                    Submit a formal request to our business development group. A PDF copy of our capability statement will be emailed to your inbox within 24 hours.
                </p>
                <a href="<?php echo BASE_URL; ?>/contact.php?type=capability" class="btn-gold text-sm px-8 py-3.5">
                    Request PDF Capability Statement
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

