/**
 * Homepage Swiper Carousel Initialization
 * Initializes 3 carousels: recommendations, ads, coming-soon
 * Features: Auto-play, pause-on-hover, infinite loop, responsive breakpoints
 */

// Verify Swiper is loaded
if (typeof Swiper === 'undefined') {
    console.warn('Swiper CDN not loaded, carousels disabled');
} else {
    // Initialize carousels after DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Homepage quick search modal chips
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

        // Unified homepage carousel (combined slides)
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

        // =====================================================
        // NEWSLETTER FORM VALIDATION
        // =====================================================
        const newsletterForm = document.querySelector('#newsletter-form');
        if (newsletterForm) {
            // Create error message div if it doesn't exist
            let errorDiv = newsletterForm.querySelector('.error-message');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger error-message';
                errorDiv.style.display = 'none';
                errorDiv.setAttribute('role', 'alert');
                newsletterForm.insertBefore(errorDiv, newsletterForm.firstChild);
            }

            // Create success message div if it doesn't exist
            let successDiv = newsletterForm.querySelector('.success-message');
            if (!successDiv) {
                successDiv = document.createElement('div');
                successDiv.className = 'alert alert-success success-message';
                successDiv.style.display = 'none';
                successDiv.setAttribute('role', 'alert');
                newsletterForm.insertBefore(successDiv, newsletterForm.firstChild.nextSibling);
            }

            // Handle form submission
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[name="email"]').value.trim();
                const errorDiv = this.querySelector('.error-message');
                const successDiv = this.querySelector('.success-message');
                
                // Reset messages
                errorDiv.style.display = 'none';
                successDiv.style.display = 'none';
                
                // Validation: Check if email is empty
                if (!email) {
                    errorDiv.textContent = 'Email required';
                    errorDiv.style.display = 'block';
                    return;
                }
                
                // Validation: Check email format with regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    errorDiv.textContent = 'Please enter a valid email';
                    errorDiv.style.display = 'block';
                    return;
                }
                
                // Valid email - show success message
                successDiv.textContent = 'Thanks for subscribing!';
                successDiv.style.display = 'block';
                
                // Clear email input
                this.querySelector('input[name="email"]').value = '';
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successDiv.style.display = 'none';
                }, 3000);
            });
        }
    });
}
