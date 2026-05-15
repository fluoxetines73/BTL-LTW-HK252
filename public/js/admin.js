document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('sidebar-toggle');
    var mainContent = document.querySelector('.main-content');
    var container = document.querySelector('.admin-container');
    var closeBtn = document.getElementById('sidebar-close-btn');

    if (!sidebar || !toggleBtn) {
        console.warn('Admin Sidebar: Required elements not found');
        return;
    }

    var STORAGE_KEY = 'sidebar-collapsed';

    // Create overlay element for mobile
    var overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.id = 'sidebar-overlay';
    document.body.appendChild(overlay);

    function openSidebar() {
        sidebar.classList.add('open');
        if (window.innerWidth < 768) {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    function initializeSidebarState() {
        var savedState = localStorage.getItem(STORAGE_KEY);
        var isMobile = window.innerWidth < 768;

        if (isMobile) {
            sidebar.classList.remove('collapsed');
            closeSidebar();
        } else {
            closeSidebar();
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                if (container) container.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                if (container) container.classList.remove('sidebar-collapsed');
            }
        }
    }

    initializeSidebarState();

    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var isMobile = window.innerWidth < 768;

        if (isMobile) {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        } else {
            sidebar.classList.toggle('collapsed');
            var isCollapsed = sidebar.classList.contains('collapsed');
            if (container) {
                if (isCollapsed) {
                    container.classList.add('sidebar-collapsed');
                } else {
                    container.classList.remove('sidebar-collapsed');
                }
            }
            localStorage.setItem(STORAGE_KEY, isCollapsed ? 'true' : 'false');
        }
    });

    // Close button inside sidebar
    if (closeBtn) {
        closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        });
    }

    // Click outside to close (including overlay)
    document.addEventListener('click', function (e) {
        var isMobile = window.innerWidth < 768;

        if (!isMobile) return;
        if (!sidebar.classList.contains('open')) return;

        var clickedOutside = !sidebar.contains(e.target) && !toggleBtn.contains(e.target);

        if (clickedOutside) {
            closeSidebar();
        }
    });

    function normalizePath(path) {
        if (!path) return '/';
        var clean = path.split('?')[0].split('#')[0].replace(/\/$/, '') || '/';

        // Treat `/foo/index` as `/foo` for active menu matching.
        clean = clean.replace(/\/index$/i, '') || '/';
        return clean;
    }

    function initializeActiveLinks() {
        var sidebarLinks = document.querySelectorAll('.sidebar-menu a');

        if (sidebarLinks.length === 0) {
            return;
        }

        var currentPath = normalizePath(window.location.pathname);

        sidebarLinks.forEach(function (link) {
            if (link.getAttribute('href') === '#' || !link.getAttribute('href')) {
                return;
            }

            var href = link.getAttribute('href');
            var url;

            try {
                url = new URL(href, window.location.origin);
            } catch (err) {
                return;
            }

            var normalizedHref = normalizePath(url.pathname);

            var isMatch = false;
            if (normalizedHref === '/' && currentPath === '/') {
                isMatch = true;
            } else if (normalizedHref !== '/' && (currentPath === normalizedHref || currentPath.startsWith(normalizedHref + '/'))) {
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

    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            initializeSidebarState();
        }, 250);
    });
});