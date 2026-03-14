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
