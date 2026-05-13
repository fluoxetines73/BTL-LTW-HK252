document.addEventListener('DOMContentLoaded', function () {
    function initializeActiveLink() {
        var currentPath = window.location.pathname.replace(/\/$/, '') || '/';
        var navLinks = document.querySelectorAll('header .navbar-nav .nav-link');

        if (navLinks.length === 0) {
            return;
        }

        navLinks.forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href || href === '#') {
                return;
            }

            var normalizedHref = href.replace(window.location.origin, '').replace(/\/$/, '') || '/';
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

    initializeActiveLink();

    function initializeAccordion() {
        var dropdownToggles = document.querySelectorAll('header .navbar-nav .dropdown-toggle');

        if (dropdownToggles.length === 0) {
            return;
        }

        dropdownToggles.forEach(function (toggle) {
            var clone = toggle.cloneNode(true);
            toggle.parentNode.replaceChild(clone, toggle);
        });

        dropdownToggles = document.querySelectorAll('header .navbar-nav .dropdown-toggle');

        dropdownToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                if (window.innerWidth > 768) {
                    return;
                }

                e.preventDefault();

                var navItem = toggle.closest('.nav-item');
                if (!navItem) return;

                var dropdownMenu = navItem.querySelector('.dropdown-menu');
                if (!dropdownMenu) return;

                var isOpen = navItem.classList.contains('show');

                var allNavItems = document.querySelectorAll('header .navbar-nav .nav-item.show');
                allNavItems.forEach(function (item) {
                    if (item !== navItem) {
                        item.classList.remove('show');
                    }
                });

                if (isOpen) {
                    navItem.classList.remove('show');
                } else {
                    navItem.classList.add('show');
                }
            });
        });
    }

    initializeAccordion();

    var resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            initializeAccordion();
        }, 250);
    });
});