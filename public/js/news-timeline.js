(function () {
    const searchForm = document.querySelector('.news-search-form');
    if (searchForm) {
        const sectionInput = searchForm.querySelector('#news-search-section');
        const chipButtons = searchForm.querySelectorAll('.news-search-chip');

        chipButtons.forEach((button) => {
            button.addEventListener('click', function () {
                const section = this.getAttribute('data-section') || '';
                if (sectionInput) {
                    sectionInput.value = section;
                }

                chipButtons.forEach((chip) => chip.classList.remove('is-active'));
                this.classList.add('is-active');
            });
        });
    }

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
