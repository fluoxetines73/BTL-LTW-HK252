(function () {
    var links = document.querySelectorAll('.nav-links a');
    var currentPath = window.location.pathname.replace(/\/$/, '');

    links.forEach(function (link) {
        var href = link.getAttribute('href');
        if (!href) return;

        var normalized = href.replace(window.location.origin, '').replace(/\/$/, '');
        if (normalized !== '' && currentPath.endsWith(normalized)) {
            link.classList.add('active');
        }
    });
})();

(function () {
    var revealItems = document.querySelectorAll('.timeline-reveal');
    if (!revealItems.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
        return;
    }

    var observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.18,
        rootMargin: '0px 0px -30px 0px'
    });

    revealItems.forEach(function (item) {
        observer.observe(item);
    });
})();
