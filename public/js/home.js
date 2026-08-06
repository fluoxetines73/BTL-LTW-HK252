if (typeof Swiper === 'undefined') {
    console.warn('Swiper CDN not loaded, carousels disabled');
} else {
    document.addEventListener('DOMContentLoaded', function() {
        const homeSearchForm = document.querySelector('.homepage-search-form--modal');
        if (homeSearchForm) {
            const sectionInput = homeSearchForm.querySelector('#home-search-section');
            const chipButtons = homeSearchForm.querySelectorAll('.homepage-search-chip');

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

        const homepageContainer = document.querySelector('.homepage-carousel');
        if (homepageContainer) {
            const homepageSwiper = new Swiper('.homepage-carousel', {
                autoplay: {
                    delay: 4500,
                    pauseOnMouseEnter: true
                },
                loop: true,
                pagination: {
                    el: '.homepage-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.homepage-button-next',
                    prevEl: '.homepage-button-prev'
                },
                breakpoints: {
                    375: { slidesPerView: 1, spaceBetween: 10 },
                    768: { slidesPerView: 2, spaceBetween: 15 },
                    1024: { slidesPerView: 4, spaceBetween: 20 }
                },
                simulateTouch: true,
                touchRatio: 1,
                a11y: { enabled: true }
            });
        }

        const newsletterForm = document.querySelector('#newsletter-form');
        if (newsletterForm) {
            let errorDiv = newsletterForm.querySelector('.error-message');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger error-message';
                errorDiv.style.display = 'none';
                errorDiv.setAttribute('role', 'alert');
                newsletterForm.insertBefore(errorDiv, newsletterForm.firstChild);
            }

            let successDiv = newsletterForm.querySelector('.success-message');
            if (!successDiv) {
                successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success success-message';
                successDiv.style.display = 'none';
                successDiv.setAttribute('role', 'alert');
                newsletterForm.insertBefore(successDiv, newsletterForm.firstChild.nextSibling);
            }

            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[name="email"]').value.trim();
                const errorDiv = this.querySelector('.error-message');
                const successDiv = this.querySelector('.success-message');
                
                errorDiv.style.display = 'none';
                successDiv.style.display = 'none';
                
                if (!email) {
                    errorDiv.textContent = 'Email required';
                    errorDiv.style.display = 'block';
                    return;
                }
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    errorDiv.textContent = 'Please enter a valid email';
                    errorDiv.style.display = 'block';
                    return;
                }
                
                successDiv.textContent = 'Thanks for subscribing!';
                successDiv.style.display = 'block';
                
                this.querySelector('input[name="email"]').value = '';
                
                setTimeout(() => {
                    successDiv.style.display = 'none';
                }, 3000);
            });
        }
    });
}
