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
        // =====================================================
        // 1. RECOMMENDATIONS CAROUSEL
        // =====================================================
        const recommendationsSwiper = new Swiper('.recommendations-carousel', {
            // Autoplay settings
            autoplay: {
                delay: 4000,                    // 4 second interval
                pauseOnMouseEnter: true         // Pause on hover
            },

            // Loop settings
            loop: true,                         // Infinite loop

            // Pagination (dots)
            pagination: {
                el: '.recommendations-pagination',
                clickable: true
            },

            // Navigation (arrows)
            navigation: {
                nextEl: '.recommendations-button-next',
                prevEl: '.recommendations-button-prev'
            },

            // Responsive breakpoints for slide count
            breakpoints: {
                // Mobile: 375px and up
                375: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                // Tablet: 768px and up
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                // Desktop: 1024px and up
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                }
            },

            // Touch/swipe support (native Swiper feature)
            simulateTouch: true,
            touchRatio: 1,

            // Accessibility
            a11y: {
                enabled: true
            }
        });

        // =====================================================
        // 2. ADS CAROUSEL
        // =====================================================
        const adsSwiper = new Swiper('.ads-carousel', {
            // Autoplay settings
            autoplay: {
                delay: 5000,                    // 5 second interval
                pauseOnMouseEnter: true         // Pause on hover
            },

            // Loop settings
            loop: true,                         // Infinite loop

            // Pagination (dots)
            pagination: {
                el: '.ads-pagination',
                clickable: true
            },

            // Navigation (arrows)
            navigation: {
                nextEl: '.ads-button-next',
                prevEl: '.ads-button-prev'
            },

            // Responsive breakpoints for slide count
            breakpoints: {
                // Mobile: 375px and up
                375: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                // Tablet: 768px and up
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                // Desktop: 1024px and up
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                }
            },

            // Touch/swipe support (native Swiper feature)
            simulateTouch: true,
            touchRatio: 1,

            // Accessibility
            a11y: {
                enabled: true
            }
        });

        // =====================================================
        // 3. COMING SOON CAROUSEL
        // =====================================================
        const comingSoonSwiper = new Swiper('.coming-soon-carousel', {
            // Autoplay settings
            autoplay: {
                delay: 4000,                    // 4 second interval
                pauseOnMouseEnter: true         // Pause on hover
            },

            // Loop settings
            loop: true,                         // Infinite loop

            // Pagination (dots)
            pagination: {
                el: '.coming-soon-pagination',
                clickable: true
            },

            // Navigation (arrows)
            navigation: {
                nextEl: '.coming-soon-button-next',
                prevEl: '.coming-soon-button-prev'
            },

            // Responsive breakpoints for slide count
            breakpoints: {
                // Mobile: 375px and up
                375: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                // Tablet: 768px and up
                768: {
                    slidesPerView: 3,
                    spaceBetween: 15
                },
                // Desktop: 1024px and up
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 20
                }
            },

            // Touch/swipe support (native Swiper feature)
            simulateTouch: true,
            touchRatio: 1,

            // Accessibility
            a11y: {
                enabled: true
            }
        });

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
