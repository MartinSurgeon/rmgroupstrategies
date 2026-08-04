<?php
/**
 * RM Group Strategies LLC — RM Transport Division Page (Coming Soon)
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
    <title>RM Transport | Logistics & Transportation | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="RM Transport - Freight logistics, regional transportation, and government delivery services. Division portal coming soon.">
    <meta name="robots" content="index, follow">

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

    <!-- Coming Soon Hero Section -->
    <section class="min-h-[70vh] flex items-center justify-center bg-brand-navy py-20 px-4 text-white relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="<?php echo BASE_URL; ?>/assets/images/rm-transport.png" alt="RM Transport Preview" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/90 to-[#0B1727]/70 z-1"></div>

        <div class="max-w-2xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-gold/10 border border-brand-gold/30 text-brand-gold text-xs font-semibold uppercase tracking-wider mb-6">
                <span>Operating Division</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">
                RM Transport
            </h1>
            <div class="gold-divider-lg mx-auto mb-6"></div>
            <p class="text-lg text-slate-300 leading-relaxed mb-8">
                Freight logistics, cargo transportation routing, and government transport dispatch portal is under active development. Full division launch coming soon.
            </p>
            <div class="bg-[#0F172A] border border-brand-gold/30 rounded-2xl p-8 shadow-2xl mb-8">
                <h2 class="text-xl font-bold text-white mb-2">Transport Dispatch &amp; Logistics</h2>
                <p class="text-slate-400 text-xs leading-relaxed mb-6">For urgent transport scheduling, freight quotes, or government logistics inquiries, contact our transport team directly.</p>
                <a href="<?php echo BASE_URL; ?>/contact.php?type=consultation&sector=transport_logistics" class="btn-gold text-xs uppercase tracking-widest font-bold py-3.5 px-8 inline-flex items-center gap-2">
                    Contact Transport Team
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <a href="<?php echo BASE_URL; ?>/" class="text-xs text-brand-gold hover:underline tracking-wide font-medium">
                &larr; Return to RM Group Strategies Homepage
            </a>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
