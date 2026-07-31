<?php
/**
 * RM Group Strategies LLC — Footer Partial
 * 
 * Multi-column footer with brand, quick links, and contact info.
 * Requires: config.php constants (SITE_NAME, SITE_PHONE, etc.)
 */
?>

<!-- Footer -->
<footer class="bg-slate-900 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16">

            <!-- Column 1: Brand -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="<?php echo BASE_URL; ?>/assets/images/ico.png" alt="<?php echo SITE_NAME_SHORT; ?> Logo" class="h-10 w-auto object-contain rounded-md" />
                    <span class="text-white text-sm font-semibold tracking-[0.15em] uppercase">
                        <?php echo SITE_NAME_SHORT; ?>
                    </span>
                </div>
                <p class="text-white/40 text-sm leading-relaxed mb-4">
                    <?php echo SITE_TAGLINE; ?>
                </p>
                <p class="text-white/30 text-xs leading-relaxed">
                    <?php echo SITE_TAGLINE_SUPPORT; ?>
                </p>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="text-xs uppercase tracking-[0.2em] text-brand-gold/70 font-medium mb-6">
                    Quick Links
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="<?php echo BASE_URL; ?>/" class="text-sm text-white/60 hover:text-brand-gold transition-colors duration-300">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/about.php" class="text-sm text-white/60 hover:text-brand-gold transition-colors duration-300">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/government-contracting.php" class="text-sm text-white/60 hover:text-brand-gold transition-colors duration-300">
                            Government Contracting
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/capability-statement.php" class="text-sm text-white/60 hover:text-brand-gold transition-colors duration-300">
                            Capability Statement
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="text-sm text-white/60 hover:text-brand-gold transition-colors duration-300">
                            Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Contact Info -->
            <div>
                <h3 class="text-xs uppercase tracking-[0.2em] text-brand-gold/70 font-medium mb-6">
                    Contact Us
                </h3>
                <ul class="space-y-4">
                    <!-- Phone -->
                    <li>
                        <a href="<?php echo SITE_PHONE_LINK; ?>" class="contact-link flex items-center gap-3 text-sm text-white/60 hover:text-brand-gold-accent">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            <span><?php echo SITE_PHONE_DISPLAY; ?></span>
                        </a>
                    </li>
                    <!-- Email -->
                    <li>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="contact-link flex items-center gap-3 text-sm text-white/60 hover:text-brand-gold-accent">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            <span><?php echo SITE_EMAIL; ?></span>
                        </a>
                    </li>
                    <!-- Location -->
                    <li>
                        <span class="flex items-center gap-3 text-sm text-white/60">
                            <svg class="w-4 h-4 text-brand-gold flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <span><?php echo SITE_LOCATION; ?></span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-white/25 tracking-wide">
                &copy; <?php echo SITE_YEAR; ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </p>
            <a href="<?php echo BASE_URL; ?>/privacy-policy.php" class="text-xs text-white/25 hover:text-brand-gold transition-colors duration-300 tracking-wide">
                Privacy Policy
            </a>
        </div>
    </div>
</footer>

