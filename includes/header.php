<?php
/**
 * RM Group Strategies LLC — Header Partial
 * 
 * Sticky navigation bar with dropdown menus and mobile hamburger.
 * Requires: $current_page (string slug), $navigation (array from config.php)
 */
global $navigation, $current_page;
if (!isset($navigation)) {
    require_once __DIR__ . '/config.php';
}
if (!isset($current_page)) {
    $current_page = 'home';
}
?>


<header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 shadow-md" style="background: rgba(11,23,39,0.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08);">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <div class="flex items-center justify-between h-16 lg:h-20">

            <!-- Logo / Brand -->
            <a href="<?php echo BASE_URL; ?>/" class="flex items-center gap-3 group" aria-label="RM Group Strategies Home">
                <img src="<?php echo BASE_URL; ?>/assets/images/ico.png" alt="RM Group Strategies Logo" class="h-10 sm:h-12 w-auto object-contain rounded-md transition-transform duration-300 group-hover:scale-105" />
                <span class="hidden sm:block text-white text-sm font-semibold tracking-[0.15em] uppercase group-hover:text-brand-gold transition-colors duration-300">
                    RM Group Strategies
                </span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-1">
                <?php foreach ($navigation as $item): ?>
                    <?php 
                        $is_active = ($current_page === $item['slug']);
                        if (isset($item['children'])) {
                            foreach ($item['children'] as $child) {
                                if (isset($child['slug']) && $child['slug'] === $current_page) {
                                    $is_active = true;
                                    break;
                                }
                            }
                        }
                    ?>
                    <?php if (isset($item['children'])): ?>
                        <!-- Dropdown nav item -->
                        <div class="nav-item relative">
                            <button class="nav-link flex items-center gap-1.5 px-3.5 py-2 text-sm text-white/90 hover:text-brand-gold tracking-wide font-medium <?php echo $is_active ? 'active text-brand-gold' : ''; ?>"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                <?php echo htmlspecialchars($item['title']); ?>
                                <svg class="w-3.5 h-3.5 opacity-60 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div class="nav-dropdown absolute top-full left-0 mt-1 w-64 bg-[#0B1727]/98 backdrop-blur-xl border border-brand-gold/35 rounded-xl shadow-2xl py-2 z-50 overflow-hidden" role="menu">
                                <!-- Parent link in dropdown if valid URL -->
                                <?php if (isset($item['url']) && $item['url'] !== '#'): ?>
                                <a href="<?php echo $item['url']; ?>" class="dropdown-item dropdown-item-overview block px-4 py-2.5 text-sm text-slate-100 font-medium transition-all duration-200 <?php echo ($current_page === $item['slug']) ? 'active' : ''; ?>" role="menuitem">
                                    <span class="flex items-center justify-between">
                                        <span><?php echo htmlspecialchars($item['title']); ?> Overview</span>
                                        <svg class="w-4 h-4 text-brand-gold/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </a>
                                <?php endif; ?>
                                <?php foreach ($item['children'] as $child): ?>
                                    <a href="<?php echo $child['url']; ?>" class="dropdown-item block px-4 py-2.5 text-sm text-slate-100 font-medium transition-all duration-200 <?php echo ($current_page === ($child['slug'] ?? '')) ? 'active' : ''; ?>" role="menuitem">
                                        <?php echo htmlspecialchars($child['title']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Simple nav link -->
                        <a href="<?php echo $item['url']; ?>"
                           class="nav-link px-3.5 py-2 text-sm text-white/90 hover:text-brand-gold tracking-wide font-medium <?php echo $is_active ? 'active text-brand-gold' : ''; ?>">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Contact CTA -->
            <a href="<?php echo BASE_URL; ?>/contact.php" class="hidden lg:inline-flex btn-gold text-xs py-2.5 px-5">
                Contact Us
            </a>

            <!-- Mobile Hamburger -->
            <button id="mobile-menu-btn" class="lg:hidden flex flex-col items-center justify-center w-10 h-10 gap-1.5" aria-label="Toggle menu" aria-expanded="false">
                <span class="hamburger-line w-6 h-0.5 bg-white rounded transition-all duration-300"></span>
                <span class="hamburger-line w-6 h-0.5 bg-white rounded transition-all duration-300"></span>
                <span class="hamburger-line w-6 h-0.5 bg-white rounded transition-all duration-300"></span>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-nav" class="mobile-nav lg:hidden">
            <div class="pb-4 pt-2 border-t border-white/10 space-y-1">
                <?php foreach ($navigation as $item): ?>
                    <a href="<?php echo ($item['url'] !== '#') ? $item['url'] : 'javascript:void(0)'; ?>"
                       class="block px-3 py-3 text-sm text-white/80 hover:text-brand-gold tracking-wide <?php echo ($current_page === $item['slug']) ? 'text-brand-gold' : ''; ?>">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </a>
                    <?php if (isset($item['children'])): ?>
                        <?php foreach ($item['children'] as $child): ?>
                            <a href="<?php echo $child['url']; ?>"
                               class="block pl-8 pr-3 py-2 text-sm text-white/50 hover:text-brand-gold tracking-wide">
                                <?php echo htmlspecialchars($child['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="pt-3 px-3">
                    <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-gold w-full text-center text-xs py-2.5">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Header spacer to prevent content from hiding behind fixed nav -->
<div class="h-16 lg:h-20"></div>

<script>
// Mobile menu toggle
document.getElementById('mobile-menu-btn').addEventListener('click', function() {
    const nav = document.getElementById('mobile-nav');
    const lines = this.querySelectorAll('.hamburger-line');
    const isOpen = nav.classList.toggle('is-open');
    this.setAttribute('aria-expanded', isOpen);
    
    if (isOpen) {
        lines[0].style.transform = 'rotate(45deg) translate(4px, 4px)';
        lines[1].style.opacity = '0';
        lines[2].style.transform = 'rotate(-45deg) translate(4px, -4px)';
    } else {
        lines[0].style.transform = '';
        lines[1].style.opacity = '1';
        lines[2].style.transform = '';
    }
});
</script>

