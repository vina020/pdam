/**
 * Vision Mission Page JavaScript
 * Handles animations and interactions for the Vision & Mission page
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize animations
    initScrollAnimations();
    initCardHoverEffects();
    initSmoothScroll();
    
    /**
     * Initialize scroll-triggered animations
     */
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe cards
        const cards = document.querySelectorAll('.story-card, .mission-card, .vision-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    }

    /**
     * Add entrance animation class
     */
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);

    /**
     * Initialize card hover effects
     */
    function initCardHoverEffects() {
        const cards = document.querySelectorAll('.story-card, .mission-card, .vision-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });

        // Story card link hover effect
        const storyLink = document.querySelector('.btn-link');
        if (storyLink) {
            storyLink.addEventListener('mouseenter', function() {
                const arrow = this.querySelector('svg');
                if (arrow) {
                    arrow.style.transform = 'translateX(4px)';
                }
            });

            storyLink.addEventListener('mouseleave', function() {
                const arrow = this.querySelector('svg');
                if (arrow) {
                    arrow.style.transform = 'translateX(0)';
                }
            });
        }
    }

    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScroll() {
        const getStartedBtn = document.querySelector('.btn-primary[href="#mission"]');
        
        if (getStartedBtn) {
            getStartedBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector('#mission');
                
                if (target) {
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
                    const startPosition = window.pageYOffset;
                    const distance = targetPosition - startPosition - 100; // 100px offset
                    const duration = 1000;
                    let start = null;

                    function animation(currentTime) {
                        if (start === null) start = currentTime;
                        const timeElapsed = currentTime - start;
                        const run = ease(timeElapsed, startPosition, distance, duration);
                        window.scrollTo(0, run);
                        if (timeElapsed < duration) requestAnimationFrame(animation);
                    }

                    function ease(t, b, c, d) {
                        t /= d / 2;
                        if (t < 1) return c / 2 * t * t + b;
                        t--;
                        return -c / 2 * (t * (t - 2) - 1) + b;
                    }

                    requestAnimationFrame(animation);
                }
            });
        }
    }

    /**
     * Parallax effect on scroll for hero section
     */
    function initParallaxEffect() {
        const heroSection = document.querySelector('.hero-section');
        
        if (heroSection) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * 0.3;
                heroSection.style.transform = `translate3d(0, ${rate}px, 0)`;
            });
        }
    }

    // Initialize parallax effect
    initParallaxEffect();

    /**
     * Add loading animation for images
     */
    function initImageLoading() {
        const images = document.querySelectorAll('.story-image img');
        
        images.forEach(img => {
            if (!img.complete) {
                img.style.opacity = '0';
                img.addEventListener('load', function() {
                    this.style.transition = 'opacity 0.5s ease';
                    this.style.opacity = '1';
                });
            }
        });
    }

    initImageLoading();

    /**
     * Add pulse animation to CTA button
     */
    function initCTAAnimation() {
        const ctaButton = document.querySelector('.btn-primary');
        
        if (ctaButton) {
            setInterval(() => {
                ctaButton.style.animation = 'none';
                setTimeout(() => {
                    ctaButton.style.animation = 'pulse 2s ease-in-out';
                }, 10);
            }, 5000);
        }
    }

    // Add pulse animation keyframes
    const pulseStyle = document.createElement('style');
    pulseStyle.textContent = `
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
            }
            50% {
                box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
            }
        }
    `;
    document.head.appendChild(pulseStyle);

    initCTAAnimation();

    /**
     * Console log for debugging
     */
    console.log('Vision & Mission page initialized successfully');
});