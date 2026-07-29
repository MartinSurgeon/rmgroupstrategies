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
    <link rel="icon" type="image/jpeg" href="<?php echo BASE_URL; ?>/assets/images/icon.jpeg">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/images/icon.jpeg">

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

<body class="font-inter bg-brand-black text-brand-white">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>

        <!-- ═══════════════════════════════════════════════════════
             SECTION 1: HERO
             ═══════════════════════════════════════════════════════ -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden py-16">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/hero-bg.png');"></div>
            <!-- Dark Overlay -->
            <div class="absolute inset-0 hero-overlay"></div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center max-w-4xl mx-auto px-4 sm:px-6">
                <!-- Company Name -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight sm:tracking-[0.12em] uppercase mb-6 animate-fade-in-up animate-delay-100 break-words drop-shadow-lg">
                    <span class="gradient-text-gold"><?php echo SITE_NAME_SHORT; ?></span> <span class="text-brand-gold font-bold">LLC</span>
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
                <a href="#about" class="scroll-indicator flex flex-col items-center gap-2 text-white/30 hover:text-brand-gold transition-colors duration-300" aria-label="Scroll to content">
                    <span class="text-[10px] uppercase tracking-[0.2em]">Explore</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                    </svg>
                </a>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 2: COMPANY OVERVIEW
             ═══════════════════════════════════════════════════════ -->
        <section id="about" class="bg-brand-dark-gray py-20 lg:py-28 relative">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Who We Are</h2>
                        <div class="gold-divider-lg"></div>
                    </div>

                    <p class="text-center text-white/75 text-base sm:text-lg leading-relaxed max-w-3xl mx-auto mb-14">
                        <?php echo SITE_NAME; ?> is a Nevada-based parent holding and management group delivering high-performance solutions in government contracting, transportation, fleet services, construction, equipment rental, procurement, and strategic consulting nationwide.
                    </p>

                    <!-- Differentiators Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                        <!-- Differentiator 1 -->
                        <div class="glass-card stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2 tracking-wide">Diversified Portfolio</h3>
                            <p class="text-xs text-white/60 leading-relaxed">Multi-industry expertise across government, commercial, and private sectors.</p>
                        </div>

                        <!-- Differentiator 2 -->
                        <div class="glass-card stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2 tracking-wide">Nevada-Based</h3>
                            <p class="text-xs text-white/60 leading-relaxed">Headquartered in Las Vegas with operations throughout the United States.</p>
                        </div>

                        <!-- Differentiator 3 -->
                        <div class="glass-card stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2 tracking-wide">Government Ready</h3>
                            <p class="text-xs text-white/60 leading-relaxed">Registered, certified, and equipped for federal, state, and local contracts.</p>
                        </div>

                        <!-- Differentiator 4 -->
                        <div class="glass-card stagger-child text-center p-6 rounded-xl">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2 tracking-wide">Strategic Partnerships</h3>
                            <p class="text-xs text-white/60 leading-relaxed">Collaborative approach with prime contractors, vendors, and subcontractors.</p>
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
        <section id="services" class="bg-brand-black py-20 lg:py-28 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-14">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Our Core Services</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-white/60 text-sm sm:text-base max-w-2xl mx-auto"><?php echo SITE_TAGLINE_SUPPORT; ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">

                    <!-- Service 1: Government Contracting -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Government Contracting</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Federal, state, and local contracting capabilities with full procurement readiness.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 2: Transportation & Logistics -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Transportation &amp; Logistics</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Freight transportation, delivery services, and government logistics contracts.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-transport.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 3: Fleet Management -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l5.1 5.1m-4.24 4.24l4.24-4.24m-4.24 4.24L8.66 18.5a2.5 2.5 0 01-3.54 0l-.7-.7a2.5 2.5 0 010-3.54l4.24-4.24"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Fleet Management</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Vehicle acquisition, fleet leasing, maintenance coordination, and government fleet support.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-fleet.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 4: Construction & Remodeling -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Construction &amp; Remodeling</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Residential renovations, commercial improvements, property rehabilitation, and general contracting.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-remodeling.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 5: Equipment Rental -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Equipment Rental</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Construction equipment, tools and machinery, jobsite equipment, and contractor supplies.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 6: Procurement Solutions -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Procurement Solutions</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Procurement assistance, vendor management, and supply chain coordination.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="gold-link inline-flex items-center gap-1.5 text-xs font-semibold">
                            Explore Capabilities
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- Service 7: Business Consulting -->
                    <div class="glass-card stagger-child rounded-xl border-t-2 border-t-brand-gold p-7 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mb-5 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2.5 tracking-wide">Business Consulting</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-6">Strategic business development, management consulting, and growth advisory services.</p>
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
             SECTION 4: AFFILIATED OPERATING DIVISIONS
             ═══════════════════════════════════════════════════════ -->
        <section id="companies" class="bg-brand-dark-gray py-20 lg:py-28 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-14">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Our Operating Divisions</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-white/60 text-sm sm:text-base max-w-2xl mx-auto">Operating through RM Nevada Series LLC, our specialized divisions deliver focused operational excellence across key industries.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-on-scroll">

                    <!-- RM Fleet -->
                    <div class="glass-card stagger-child rounded-xl p-6 text-center flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25m0 0V4.5m0 0H8.25"/></svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2">RM Fleet</h3>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">Fleet leasing, vehicle acquisition, and comprehensive fleet support.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-fleet.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                            Division Overview
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- RM Remodeling -->
                    <div class="glass-card stagger-child rounded-xl p-6 text-center flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2">RM Remodeling</h3>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">Residential and commercial construction, renovations, and property upgrades.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-remodeling.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                            Division Overview
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- RM Tools & Equipment -->
                    <div class="glass-card stagger-child rounded-xl p-6 text-center flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1a2.5 2.5 0 010-3.54l.7-.7a2.5 2.5 0 013.54 0l.7.7a2.5 2.5 0 010 3.54l-5.1 5.1m0 0l5.1 5.1"/></svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2">RM Tools &amp; Equipment</h3>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">Equipment leasing, tools, jobsite machinery, and contractor rentals.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-tools-equipment.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                            Division Overview
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                    <!-- RM Transport -->
                    <div class="glass-card stagger-child rounded-xl p-6 text-center flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 border border-brand-gold/30 flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            </div>
                            <h3 class="text-white font-bold text-base mb-2">RM Transport</h3>
                            <p class="text-white/50 text-xs leading-relaxed mb-6">Freight transport, logistics support, and government delivery contracts.</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/rm-transport.php" class="gold-link text-xs font-semibold inline-flex items-center justify-center gap-1">
                            Division Overview
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>

                </div>
            </div>
        </section>


        <!-- ═══════════════════════════════════════════════════════
             SECTION 5: GOVERNMENT CONTRACTING READINESS
             ═══════════════════════════════════════════════════════ -->
        <section id="gov-ready" class="bg-brand-black py-20 lg:py-28 relative">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="animate-on-scroll">
                    <div class="section-heading mb-12">
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Government Contracting Readiness</h2>
                        <div class="gold-divider-lg"></div>
                        <p class="text-white/60 text-sm sm:text-base max-w-2xl mx-auto">Registered, certified, and positioned for federal, state, and local procurement opportunities.</p>
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
        <section id="cta" class="bg-brand-dark-gray py-20 lg:py-28 relative overflow-hidden">
            <!-- Subtle gold accent line at top -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-gold/40 to-transparent"></div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-wide">
                    Ready to Build a Strategic Partnership?
                </h2>
                <p class="text-white/70 text-base sm:text-lg leading-relaxed mb-10 max-w-2xl mx-auto">
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

