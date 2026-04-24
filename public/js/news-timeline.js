(function () {
    const revealItems = document.querySelectorAll('.reveal-on-scroll');
    if (!('IntersectionObserver' in window) || revealItems.length === 0) {
        revealItems.forEach((el) => el.classList.add('visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    revealItems.forEach((item) => observer.observe(item));
})();
