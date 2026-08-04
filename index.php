<?php
/**
 * RM Group Strategies LLC — Home Page
 */
require_once __DIR__ . '/includes/config.php';
$current_page = 'home';
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
    <title><?php echo SITE_NAME; ?> | Government Contracting, Transportation, Construction, Fleet Solutions</title>
    <meta name="description" content="<?php echo SITE_NAME; ?> is a Nevada-based company specializing in government contracting, transportation, fleet services, construction, remodeling, logistics, equipment rental, procurement solutions, and business consulting in Las Vegas, Nevada.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo SITE_NAME; ?> | <?php echo SITE_TAGLINE; ?>">
    <meta property="og:description" content="Nevada-based government contractor and diversified business group. Transportation, fleet services, construction, remodeling, logistics, equipment rental, procurement solutions, and business consulting.">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/">
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
                        'brand-navy':       '#0B1727',
                        'brand-gold':       '#C5A059',
                        'brand-gold-accent':'#D4AF37',
                        'brand-white':      '#FFFFFF',
                        'brand-dark-gray':  '#1E293B',
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
             SECTION 1: HERO
             ═══════════════════════════════════════════════════════ -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden py-16">
            <!-- Hero Background Slideshow (crossfade) -->
            <div id="heroBg" class="absolute inset-0 overflow-hidden">
                <div class="hero-bg-slide active"   style="background-image: url('<?php echo BASE_URL; ?>/assets/images/hero-bg.png');"></div>
                <div class="hero-bg-slide"           style="background-image: url('<?php echo BASE_URL; ?>/assets/images/hero-bg1.png');"></div>
                <div class="hero-bg-slide"           style="background-image: url('<?php echo BASE_URL; ?>/assets/images/hero-bg2.png');"></div>
            </div>
            <!-- Dark Overlay -->
            <div class="absolute inset-0 hero-overlay" style="z-index:1;"></div>

            <!-- Hero Content -->
            <div class="relative z-20 text-center max-w-4xl mx-auto px-4 sm:px-6">
                <!-- Company Name -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight sm:tracking-[0.12em] uppercase mb-6 animate-fade-in-up animate-delay-100 break-words drop-shadow-lg">
                    <span class="text-white"><?php echo SITE_NAME_SHORT; ?></span> <span class="text-brand-gold font-bold">LLC</span>
                </h1>

                <!-- Professional Dynamic Service Rotator -->
                <div class="my-4 max-w-xl mx-auto px-4 animate-fade-in-up animate-delay-300">
                    <!-- Rotator Line -->
                    <div class="h-8 sm:h-10 flex items-center justify-center overflow-hidden relative">
                        <div id="service-rotator" class="text-sm sm:text-base md:text-lg font-bold tracking-[0.14em] text-brand-gold uppercase text-center transition-all duration-500 transform translate-y-0 opacity-100 drop-shadow-md" aria-live="polite">
                            Government Contracting
                        </div>
                    </div>
                </div>

                <!-- Gold Divider -->
                <div class="flex justify-center mb-6 animate-fade-in-up animate-delay-300">
                    <div class="gold-divider"></div>
                </div>

                <!-- Tagline -->
                <p class="text-xs sm:text-lg md:text-xl text-white/90 mb-8 sm:mb-10 animate-fade-in-up animate-delay-400 leading-normal sm:leading-relaxed max-w-xs sm:max-w-2xl mx-auto px-2 font-normal">
                    Building Strategic Partnerships,<br class="block sm:hidden" /> Delivering Reliable Solutions.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 animate-fade-in-up animate-delay-500 w-full mx-auto px-4">
                    <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-gold w-64 sm:w-auto text-center justify-center rounded-lg shadow-lg">
                        Request Consultation
                    </a>
                    <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="btn-outline-gold w-64 sm:w-auto text-center justify-center rounded-lg">
                        Government Contracting
                    </a>
                    <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-outline-white w-64 sm:w-auto text-center justify-center rounded-lg">
                        Contact Us
                    </a>
                </div>


            </div>

            <!-- Scroll Down Indicator -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 animate-fade-in-up animate-delay-700 hidden sm:block">
                <a href="#about" class="scroll-indicator flex flex-col items-center gap-2 text-white/40 hover:text-brand-gold transition-colors duration-300" aria-label="Scroll to content">
                    <span class="text-[10px] uppercase tracking-[0.2em]">Explore</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Hero Background Crossfade Script -->
        <script>
        (function () {
            const slides  = document.querySelectorAll('#heroBg .hero-bg-slide');
            let   current = 0;
            if (slides.length < 2) return;
            setInterval(function () {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 6000);
        })();
        </script>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: COMPANY OVERVIEW
             ═══════════════════════════════════════════════════════ -->
        <section id="about" class="bg-white py-20 lg:py-28 relative border-b border-slate-200/80">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">Who We Are</h2>
                        <div class="gold-divider-lg"></div>
                    </div>

                    <p class="text-center text-slate-600 text-base sm:text-lg leading-relaxed max-w-3xl mx-auto mb-14">
                        <?php echo SITE_NAME; ?> is a Nevada-based parent holding and management group delivering high-performance solutions in government contracting, transportation, fleet services, construction, equipment rental, procurement, and strategic consulting nationwide.
                    </p>

                    <!-- Differentiators Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                        <!-- Differentiator 1 -->
                        <div class="card-executive stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2 tracking-wide">Diversified Portfolio</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Multi-industry expertise across government, commercial, and private sectors.</p>
                        </div>

                        <!-- Differentiator 2 -->
                        <div class="card-executive stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2 tracking-wide">Nevada-Based</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Headquartered in Las Vegas with operations throughout the United States.</p>
                        </div>

                        <!-- Differentiator 3 -->
                        <div class="card-executive stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2 tracking-wide">Government Ready</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Registered, certified, and equipped for federal, state, and local contracts.</p>
                        </div>

                        <!-- Differentiator 4 -->
                        <div class="card-executive stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-2 tracking-wide">Strategic Partnerships</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Collaborative approach with prime contractors, vendors, and subcontractors.</p>
                        </div>
                    </div>

                    <!-- Learn More Link -->
                    <div class="text-center">
                        <a href="<?php echo BASE_URL; ?>/about.php" class="gold-link inline-flex items-center gap-2 text-sm font-semibold">
                            Learn More About Our Operations
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3: SERVICES GRID
             ═══════════════════════════════════════════════════════ -->
        <section id="services" class="bg-slate-50 py-20 lg:py-28 relative border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-14">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">Our Core Services</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto"><?php echo SITE_TAGLINE_SUPPORT; ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">

                    <!-- Service 1: Government Contracting -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Government Contracting</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Federal, state, and local contracting capabilities with full procurement readiness.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 2: Transportation & Logistics -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Transportation &amp; Logistics</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Freight transportation, delivery services, and government logistics contracts.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-transport.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 3: Fleet Management -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l5.1 5.1m-4.24 4.24l4.24-4.24m-4.24 4.24L8.66 18.5a2.5 2.5 0 01-3.54 0l-.7-.7a2.5 2.5 0 010-3.54l4.24-4.24"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Fleet Management</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Vehicle acquisition, fleet leasing, maintenance coordination, and government fleet support.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-fleet.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 4: Construction & Remodeling -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Construction &amp; Remodeling</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Residential renovations, commercial improvements, property rehabilitation, and general contracting.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-remodeling.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 5: Equipment Rental -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Equipment Rental</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Construction equipment, tools and machinery, jobsite equipment, and contractor supplies.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 6: Procurement Solutions -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Procurement Solutions</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Procurement assistance, vendor management, and supply chain coordination.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 7: Business Consulting -->
                    <div class="card-executive stagger-child rounded-xl border-t-4 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <h3 class="text-slate-900 font-bold text-base mb-2.5 tracking-wide">Business Consulting</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-6">Strategic business development, management consulting, and growth advisory services.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 3.5: DIVISION SHOWCASE CAROUSEL
             ═══════════════════════════════════════════════════════ -->
        <section id="division-carousel" class="bg-brand-navy py-20 lg:py-24 relative overflow-hidden border-y border-brand-gold/25">
            <!-- Subtle background grid texture -->
            <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(197,160,89,0.5) 39px, rgba(197,160,89,0.5) 40px), repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(197,160,89,0.5) 39px, rgba(197,160,89,0.5) 40px);"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Heading -->
                <div class="text-center mb-12 animate-on-scroll">
                    <span class="text-brand-gold text-xs uppercase tracking-[0.25em] font-bold block mb-3">Our Operating Divisions</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">Built for Government &amp; Commercial Excellence</h2>
                    <div class="gold-divider-lg mx-auto mb-4"></div>
                    <p class="text-slate-400 text-sm max-w-2xl mx-auto">Specialized divisions delivering focused operational performance across construction, fleet, equipment, and logistics.</p>
                </div>

                <!-- Carousel Wrapper -->
                <div class="relative" id="divCarousel" aria-label="Division image carousel" role="region">

                    <!-- Slides -->
                    <div class="carousel-track overflow-hidden rounded-2xl border border-brand-gold/30 shadow-2xl" style="height: 480px;">
                        <div class="carousel-slides flex transition-transform duration-700 ease-in-out h-full" id="carouselSlides">

                            <!-- Slide 1: RM Remodeling -->
                            <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-remodeling.png" alt="RM Remodeling — Construction & Renovation" class="w-full h-full object-cover" loading="eager">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12">
                                    <span class="inline-block bg-emerald-800/90 text-emerald-300 border border-emerald-500/40 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3">Active Division</span>
                                    <h3 class="text-2xl lg:text-3xl font-extrabold text-white mb-2">RM Remodeling</h3>
                                    <p class="text-slate-300 text-sm mb-5 max-w-xl">Premier commercial tenant improvements, residential renovations, and government facility upgrades delivered on time and within budget.</p>
                                    <a href="<?php echo BASE_URL; ?>/rm-remodeling.php" class="btn-gold text-xs uppercase tracking-widest font-bold py-2.5 px-6 inline-flex items-center gap-2">
                                        View Capabilities
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Slide 2: RM Tools & Equipment -->
                            <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-tools-equipment.png" alt="RM Tools & Equipment — Heavy Machinery Rental" class="w-full h-full object-cover" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12">
                                    <span class="inline-block bg-emerald-800/90 text-emerald-300 border border-emerald-500/40 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3">Active Division</span>
                                    <h3 class="text-2xl lg:text-3xl font-extrabold text-white mb-2">RM Tools &amp; Equipment</h3>
                                    <p class="text-slate-300 text-sm mb-5 max-w-xl">Heavy machinery, construction equipment rental, industrial tools, and jobsite power solutions for contractors and government projects.</p>
                                    <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="btn-gold text-xs uppercase tracking-widest font-bold py-2.5 px-6 inline-flex items-center gap-2">
                                        View Capabilities
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Slide 3: RM Fleet -->
                            <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-fleet.png" alt="RM Fleet — Commercial Fleet Management" class="w-full h-full object-cover" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12">
                                    <span class="inline-block bg-slate-800/90 text-brand-gold border border-brand-gold/40 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3">Coming Soon</span>
                                    <h3 class="text-2xl lg:text-3xl font-extrabold text-white mb-2">RM Fleet</h3>
                                    <p class="text-slate-300 text-sm mb-5 max-w-xl">Commercial fleet leasing, vehicle acquisition, maintenance management, and government fleet support services across Nevada.</p>
                                    <a href="<?php echo BASE_URL; ?>/rm-fleet.php" class="btn-gold text-xs uppercase tracking-widest font-bold py-2.5 px-6 inline-flex items-center gap-2">
                                        Learn More
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Slide 4: RM Transport -->
                            <div class="carousel-slide flex-shrink-0 w-full h-full relative">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-transport.png" alt="RM Transport — Logistics & Freight" class="w-full h-full object-cover" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12">
                                    <span class="inline-block bg-slate-800/90 text-brand-gold border border-brand-gold/40 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3">Coming Soon</span>
                                    <h3 class="text-2xl lg:text-3xl font-extrabold text-white mb-2">RM Transport</h3>
                                    <p class="text-slate-300 text-sm mb-5 max-w-xl">Freight logistics, cargo transport routing, and government delivery contracts with reliable on-time performance nationwide.</p>
                                    <a href="<?php echo BASE_URL; ?>/rm-transport.php" class="btn-gold text-xs uppercase tracking-widest font-bold py-2.5 px-6 inline-flex items-center gap-2">
                                        Learn More
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                        </div><!-- /.carousel-slides -->
                    </div><!-- /.carousel-track -->

                    <!-- Prev / Next Arrows -->
                    <button id="carouselPrev" class="absolute top-1/2 -translate-y-1/2 left-3 lg:-left-5 z-20 w-11 h-11 rounded-full bg-brand-navy/90 border border-brand-gold/40 text-brand-gold flex items-center justify-center hover:bg-brand-gold hover:text-brand-navy transition-all duration-200 shadow-lg" aria-label="Previous slide">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button id="carouselNext" class="absolute top-1/2 -translate-y-1/2 right-3 lg:-right-5 z-20 w-11 h-11 rounded-full bg-brand-navy/90 border border-brand-gold/40 text-brand-gold flex items-center justify-center hover:bg-brand-gold hover:text-brand-navy transition-all duration-200 shadow-lg" aria-label="Next slide">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <!-- Dot Indicators -->
                    <div class="flex items-center justify-center gap-2.5 mt-7" role="tablist" aria-label="Carousel slides">
                        <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-brand-gold transition-all duration-300" data-slide="0" role="tab" aria-selected="true" aria-label="Slide 1: RM Remodeling"></button>
                        <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/25 transition-all duration-300 hover:bg-brand-gold/60" data-slide="1" role="tab" aria-selected="false" aria-label="Slide 2: RM Tools & Equipment"></button>
                        <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/25 transition-all duration-300 hover:bg-brand-gold/60" data-slide="2" role="tab" aria-selected="false" aria-label="Slide 3: RM Fleet"></button>
                        <button class="carousel-dot w-2.5 h-2.5 rounded-full bg-white/25 transition-all duration-300 hover:bg-brand-gold/60" data-slide="3" role="tab" aria-selected="false" aria-label="Slide 4: RM Transport"></button>
                    </div>

                </div><!-- /#divCarousel -->
            </div>
        </section>

        <!-- Carousel Script -->
        <script>
        (function() {
            const slidesEl   = document.getElementById('carouselSlides');
            const dots       = document.querySelectorAll('.carousel-dot');
            const prevBtn    = document.getElementById('carouselPrev');
            const nextBtn    = document.getElementById('carouselNext');
            const total      = 4;
            let   current    = 0;
            let   timer      = null;
            const INTERVAL   = 5000;

            function goTo(idx) {
                current = (idx + total) % total;
                slidesEl.style.transform = `translateX(-${current * 100}%)`;
                dots.forEach((d, i) => {
                    d.classList.toggle('bg-brand-gold', i === current);
                    d.classList.toggle('w-6', i === current);          // active dot wider
                    d.classList.toggle('bg-white/25', i !== current);
                    d.classList.toggle('w-2.5', i !== current);
                    d.setAttribute('aria-selected', i === current ? 'true' : 'false');
                });
            }

            function startAuto() {
                timer = setInterval(() => goTo(current + 1), INTERVAL);
            }
            function stopAuto() {
                clearInterval(timer);
            }

            prevBtn.addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
            nextBtn.addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });
            dots.forEach(d => d.addEventListener('click', () => { stopAuto(); goTo(+d.dataset.slide); startAuto(); }));

            // Touch / swipe support
            let touchStartX = 0;
            slidesEl.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
            slidesEl.addEventListener('touchend',   e => {
                const diff = touchStartX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) { stopAuto(); goTo(current + (diff > 0 ? 1 : -1)); startAuto(); }
            });

            // Pause on hover
            const track = document.querySelector('.carousel-track');
            track.addEventListener('mouseenter', stopAuto);
            track.addEventListener('mouseleave', startAuto);

            // Init
            goTo(0);
            startAuto();
        })();
        </script>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 4: AFFILIATED OPERATING DIVISIONS
             ═══════════════════════════════════════════════════════ -->
        <section id="companies" class="bg-white py-20 lg:py-28 relative border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-14">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">Our Operating Divisions</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">Our specialized divisions deliver focused operational excellence across key industries nationwide.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">

                    <!-- RM Fleet -->
                    <div class="card-executive stagger-child rounded-xl overflow-hidden text-center flex flex-col justify-between group">
                        <div>
                            <div class="h-36 w-full overflow-hidden relative border-b border-brand-gold/30">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-fleet.png" alt="RM Fleet" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                                <span class="absolute top-2 right-2 bg-slate-900/90 text-brand-gold border border-brand-gold/40 text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-full">Coming Soon</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-white font-bold text-base mb-2">RM Fleet</h3>
                                <p class="text-slate-300 text-xs leading-relaxed mb-4">Fleet leasing, vehicle acquisition, and comprehensive fleet support.</p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="<?php echo BASE_URL; ?>/rm-fleet.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                                Division Overview
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- RM Remodeling (Active Page) -->
                    <div class="card-executive stagger-child rounded-xl overflow-hidden text-center flex flex-col justify-between group">
                        <div>
                            <div class="h-36 w-full overflow-hidden relative border-b border-brand-gold/30">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-remodeling.png" alt="RM Remodeling" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                                <span class="absolute top-2 right-2 bg-emerald-900/90 text-emerald-300 border border-emerald-500/40 text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-full">Active Division</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-white font-bold text-base mb-2">RM Remodeling</h3>
                                <p class="text-slate-300 text-xs leading-relaxed mb-4">Residential and commercial construction, renovations, and property upgrades.</p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="<?php echo BASE_URL; ?>/rm-remodeling.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                                View Capabilities
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- RM Tools & Equipment (Active Page) -->
                    <div class="card-executive stagger-child rounded-xl overflow-hidden text-center flex flex-col justify-between group">
                        <div>
                            <div class="h-36 w-full overflow-hidden relative border-b border-brand-gold/30">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-tools-equipment.png" alt="RM Tools & Equipment" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                                <span class="absolute top-2 right-2 bg-emerald-900/90 text-emerald-300 border border-emerald-500/40 text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-full">Active Division</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-white font-bold text-base mb-2">RM Tools &amp; Equipment</h3>
                                <p class="text-slate-300 text-xs leading-relaxed mb-4">Equipment leasing, tools, jobsite machinery, and contractor rentals.</p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                                View Capabilities
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- RM Transport -->
                    <div class="card-executive stagger-child rounded-xl overflow-hidden text-center flex flex-col justify-between group">
                        <div>
                            <div class="h-36 w-full overflow-hidden relative border-b border-brand-gold/30">
                                <img src="<?php echo BASE_URL; ?>/assets/images/rm-transport.png" alt="RM Transport" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                                <span class="absolute top-2 right-2 bg-slate-900/90 text-brand-gold border border-brand-gold/40 text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-full">Coming Soon</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-white font-bold text-base mb-2">RM Transport</h3>
                                <p class="text-slate-300 text-xs leading-relaxed mb-4">Freight transport, logistics support, and government delivery contracts.</p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="<?php echo BASE_URL; ?>/rm-transport.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                                Division Overview
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 5: GOVERNMENT CONTRACTING READINESS
             ═══════════════════════════════════════════════════════ -->
        <section id="gov-ready" class="bg-slate-50 py-20 lg:py-28 relative border-b border-slate-200/80">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-12">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">Government Contracting Readiness</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">Registered, certified, and positioned for federal, state, and local procurement opportunities.</p>
                    </div>
                </div>

                <div class="animate-on-scroll">
                    <div class="flex flex-wrap justify-center gap-3.5 mb-10">
                        <div class="stagger-child badge-pill">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>UEI Registered</span>
                        </div>
                        <div class="stagger-child badge-pill">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>SAM.gov Active</span>
                        </div>
                        <div class="stagger-child badge-pill">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>Federal Contract Ready</span>
                        </div>
                        <div class="stagger-child badge-pill">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>Small Business Opportunities</span>
                        </div>
                        <div class="stagger-child badge-pill">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>Subcontracting Partnerships</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center">
                        <a href="<?php echo BASE_URL; ?>/capability-statement.php" class="btn-gold text-xs py-3 px-6 rounded-lg inline-flex items-center gap-2 shadow-lg">
                            <span>View Capability Statement</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 6: LEAD CTA
             ═══════════════════════════════════════════════════════ -->
        <section id="cta" class="bg-slate-900 text-white py-20 lg:py-28 relative overflow-hidden">
            <!-- Subtle gold accent line at top -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/40 to-transparent"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-wide">
                    Ready to Build a Strategic Partnership?
                </h2>
                <p class="text-slate-300 text-base sm:text-lg leading-relaxed mb-10 max-w-2xl mx-auto">
                    Connect with our executive management team to discuss government contracting, commercial projects, fleet solutions, or vendor opportunities.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-gold text-sm px-8 py-3.5 rounded-lg shadow-xl w-64 sm:w-auto text-center justify-center">
                        Request Consultation
                    </a>
                    <a href="<?php echo SITE_PHONE_LINK; ?>" class="btn-outline-gold text-sm px-8 py-3.5 rounded-lg w-64 sm:w-auto text-center justify-center">
                        Call <?php echo SITE_PHONE_DISPLAY; ?>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- ═══════════════════════════════════════════════════════
         Scroll Animation Observer
         ═══════════════════════════════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll Animation Observer
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

            // Dynamic Service Text Rotator
            (function() {
                const services = [
                    "Government Contracting",
                    "Business Development",
                    "Transportation & Logistics",
                    "Construction & Remodeling",
                    "Fleet Solutions"
                ];
                let currentIndex = 0;
                const rotatorEl = document.getElementById('service-rotator');
                
                if (rotatorEl) {
                    setInterval(function() {
                        rotatorEl.style.opacity = '0';
                        rotatorEl.style.transform = 'translateY(-8px)';
                        
                        setTimeout(function() {
                            currentIndex = (currentIndex + 1) % services.length;
                            rotatorEl.textContent = services[currentIndex];
                            rotatorEl.style.transform = 'translateY(8px)';
                            
                            requestAnimationFrame(function() {
                                rotatorEl.style.opacity = '1';
                                rotatorEl.style.transform = 'translateY(0)';
                            });
                        }, 400);
                    }, 3000);
                }
            })();
        });
    </script>

</body>
</html>

