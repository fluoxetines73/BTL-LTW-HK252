document.addEventListener('DOMContentLoaded', function () {
    // ========================================================================
    // ADMIN SIDEBAR TOGGLE FUNCTIONALITY
    // ========================================================================

    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('sidebar-toggle');

    if (!sidebar || !toggleBtn) {
        console.warn('Admin Sidebar: Required elements not found');
        return;
    }

    var STORAGE_KEY = 'sidebar-collapsed';

    // ========================================================================
    // 1. RESTORE SAVED STATE ON PAGE LOAD
    // ========================================================================
    function initializeSidebarState() {
        var savedState = localStorage.getItem(STORAGE_KEY);
        var isMobile = window.innerWidth < 768;

        if (isMobile) {
            // Mobile: sidebar hidden by default, no collapsed class
            sidebar.classList.remove('collapsed');
            sidebar.classList.remove('open');
        } else {
            // Desktop: restore saved collapsed state
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }
    }

    // Initialize sidebar state on load
    initializeSidebarState();

    // ========================================================================
    // 2. TOGGLE BUTTON CLICK HANDLER
    // ========================================================================
    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var isMobile = window.innerWidth < 768;

        if (isMobile) {
            // Mobile: toggle 'open' class for slide-in sidebar
            sidebar.classList.toggle('open');
        } else {
            // Desktop: toggle 'collapsed' class for narrow sidebar
            sidebar.classList.toggle('collapsed');

            // Save state to localStorage
            var isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem(STORAGE_KEY, isCollapsed ? 'true' : 'false');
        }
    });

    // ========================================================================
    // 3. MOBILE: CLOSE SIDEBAR ON CLICK OUTSIDE
    // ========================================================================
    document.addEventListener('click', function (e) {
        var isMobile = window.innerWidth < 768;

        if (!isMobile) return;
        if (!sidebar.classList.contains('open')) return;

        // Check if click was outside sidebar and toggle button
        var clickedOutside = !sidebar.contains(e.target) && !toggleBtn.contains(e.target);

        if (clickedOutside) {
            sidebar.classList.remove('open');
        }
    });

    // ========================================================================
    // 4. OPTIONAL: ACTIVE LINK DETECTION FALLBACK
    // ========================================================================
    function initializeActiveLinks() {
        var sidebarLinks = document.querySelectorAll('.sidebar-menu a');

        if (!sidebarLinks || sidebarLinks.length === 0) {
            return;
        }

        var currentPath = window.location.pathname.replace(/\/$/, '') || '/';

        sidebarLinks.forEach(function (link) {
            if (link.getAttribute('href') === '#' || !link.getAttribute('href')) {
                return;
            }

            var href = link.getAttribute('href');
            var normalizedHref = href.replace(window.location.origin, '').replace(/\/$/, '') || '/';

            var isMatch = false;
            if (normalizedHref === '/' && currentPath === '/') {
                isMatch = true;
            } else if (normalizedHref !== '/' && currentPath.endsWith(normalizedHref)) {
                isMatch = true;
            }

            if (isMatch) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    initializeActiveLinks();

    // ========================================================================
    // 5. HANDLE WINDOW RESIZE (MOBILE/DESKTOP TRANSITION)
    // ========================================================================
    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            initializeSidebarState();
        }, 250);
    });
});