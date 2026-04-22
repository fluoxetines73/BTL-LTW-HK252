document.addEventListener('DOMContentLoaded', function () {
    // ========================================================================
    // WAVE 4 TASK 7: Active Link Detection & Accordion Toggle
    // ========================================================================

    // ========================================================================
    // 1. ACTIVE LINK DETECTION
    // ========================================================================
    function initializeActiveLink() {
        // Get current page path (normalized - no trailing slash, no origin)
        var currentPath = window.location.pathname.replace(/\/$/, '') || '/';

        // Get all nav links in header
        var navLinks = document.querySelectorAll('header .navbar-nav .nav-link');
        
        if (!navLinks || navLinks.length === 0) {
            console.warn('CGV App: No nav links found in header');
            return;
        }

        navLinks.forEach(function (link) {
            // Skip dropdown toggles without real hrefs
            if (link.getAttribute('href') === '#' || !link.getAttribute('href')) {
                return;
            }

            var href = link.getAttribute('href');
            if (!href) return;

            // Normalize href: remove origin, remove trailing slash
            var normalizedHref = href.replace(window.location.origin, '').replace(/\/$/, '') || '/';

            // Handle home page special case: only match exact "/" path
            var isHome = normalizedHref === '/';
            var isCurrentHome = currentPath === '/';
            
            var isMatch = false;
            if (isHome && isCurrentHome) {
                isMatch = true;
            } else if (!isHome && currentPath.endsWith(normalizedHref)) {
                isMatch = true;
            }

            if (isMatch) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // Initialize active link on page load
    initializeActiveLink();

    // ========================================================================
    // 2. ACCORDION TOGGLE FOR MOBILE
    // ========================================================================
    function initializeAccordion() {
        var isMobile = window.innerWidth <= 768;

        // Get all dropdown toggles in header
        var dropdownToggles = document.querySelectorAll('header .navbar-nav .dropdown-toggle');
        
        if (!dropdownToggles || dropdownToggles.length === 0) {
            return;
        }

        dropdownToggles.forEach(function (toggle) {
            // Remove any existing listeners by cloning and replacing
            var clone = toggle.cloneNode(true);
            toggle.parentNode.replaceChild(clone, toggle);
        });

        // Re-query after cloning
        dropdownToggles = document.querySelectorAll('header .navbar-nav .dropdown-toggle');

        dropdownToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                // Only handle clicks on mobile
                if (window.innerWidth > 768) {
                    return;
                }

                e.preventDefault();

                // Get the parent nav-item
                var navItem = toggle.closest('.nav-item');
                if (!navItem) return;

                // Get the dropdown menu for this toggle
                var dropdownMenu = navItem.querySelector('.dropdown-menu');
                if (!dropdownMenu) return;

                // Check if this menu is already open (has .show class)
                var isOpen = navItem.classList.contains('show');

                // Close all other open dropdowns
                var allNavItems = document.querySelectorAll('header .navbar-nav .nav-item.show');
                allNavItems.forEach(function (item) {
                    if (item !== navItem) {
                        item.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                if (isOpen) {
                    navItem.classList.remove('show');
                } else {
                    navItem.classList.add('show');
                }
            });
        });
    }

    // Initialize accordion on page load
    initializeAccordion();

    // Re-initialize accordion on window resize (to handle mobile/desktop switch)
    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            initializeAccordion();
        }, 250);
    });
});
