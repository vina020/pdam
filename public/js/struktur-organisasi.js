/**
 * Dewan Pengawas Page JavaScript
 * Handles animations and interactions for the Dewan Pengawas page
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all functions
    initScrollAnimations();
    initCardAnimations();
    initHoverEffects();
    initConnectorAnimations();
    
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

        // Observe sections
        const sections = document.querySelectorAll('.org-chart-section, .responsibilities-section');
        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = `all 0.8s ease ${index * 0.1}s`;
            observer.observe(section);
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
     * Initialize organization card animations
     */
    function initCardAnimations() {
        const cards = document.querySelectorAll('.org-card');
        
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) scale(1)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px) scale(0.95)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    }

    /**
     * Initialize hover effects for cards
     */
    function initHoverEffects() {
        const cards = document.querySelectorAll('.org-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                // Add ripple effect
                const ripple = document.createElement('div');
                ripple.classList.add('ripple-effect');
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple effect styles
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple-effect {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(13, 110, 253, 0.1);
                transform: translate(-50%, -50%);
                animation: ripple 0.6s ease-out;
            }
            
            @keyframes ripple {
                to {
                    width: 300px;
                    height: 300px;
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);
    }

    /**
     * Animate connector lines
     */
    function initConnectorAnimations() {
        const connectors = document.querySelectorAll('.connector-line');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'growLine 0.8s ease forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        connectors.forEach(connector => {
            connector.style.transform = 'scaleY(0)';
            connector.style.transformOrigin = 'top';
            observer.observe(connector);
        });

        // Add animation
        const connectorStyle = document.createElement('style');
        connectorStyle.textContent = `
            @keyframes growLine {
                from {
                    transform: scaleY(0);
                }
                to {
                    transform: scaleY(1);
                }
            }
        `;
        document.head.appendChild(connectorStyle);
    }

    /**
     * Animate responsibility cards
     */
    function initResponsibilityCards() {
        const respCards = document.querySelectorAll('.responsibility-card');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        respCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            observer.observe(card);
        });
    }

    initResponsibilityCards();

    /**
     * Add parallax effect to avatar images
     */
    function initParallaxAvatars() {
        const avatars = document.querySelectorAll('.avatar-circle');
        
        window.addEventListener('scroll', function() {
            avatars.forEach((avatar, index) => {
                const rect = avatar.getBoundingClientRect();
                const scrolled = window.pageYOffset;
                
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const speed = 0.05;
                    const yPos = -(scrolled * speed);
                    avatar.querySelector('img').style.transform = `translateY(${yPos}px)`;
                }
            });
        });
    }

    initParallaxAvatars();

    /**
     * Add click event to cards for mobile
     */
    function initMobileCardClick() {
        const cards = document.querySelectorAll('.org-card');
        
        cards.forEach(card => {
            card.addEventListener('click', function() {
                // Toggle active state on mobile
                if (window.innerWidth <= 768) {
                    this.classList.toggle('active');
                    
                    // Remove active from other cards
                    cards.forEach(otherCard => {
                        if (otherCard !== this) {
                            otherCard.classList.remove('active');
                        }
                    });
                }
            });
        });

        // Add active state styles
        const activeStyle = document.createElement('style');
        activeStyle.textContent = `
            .org-card.active {
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 15px 40px rgba(0,0,0,0.2);
                border-color: var(--primary-color);
                z-index: 10;
            }
        `;
        document.head.appendChild(activeStyle);
    }

    initMobileCardClick();

    /**
     * Add loading animation for images
     */
    function initImageLoading() {
        const images = document.querySelectorAll('.avatar-circle img');
        
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
     * Add counter animation for organization levels
     */
    function initLevelCounter() {
        const levels = document.querySelectorAll('.org-level');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add level indicator
                    const levelIndicator = document.createElement('div');
                    levelIndicator.className = 'level-indicator';
                    levelIndicator.textContent = `Level ${index + 1}`;
                    levelIndicator.style.cssText = `
                        position: absolute;
                        top: -30px;
                        left: 50%;
                        transform: translateX(-50%);
                        font-size: 0.8rem;
                        color: var(--primary-color);
                        font-weight: 600;
                        opacity: 0;
                        animation: fadeIn 0.5s ease forwards;
                    `;
                    entry.target.appendChild(levelIndicator);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3
        });

        levels.forEach(level => {
            level.style.position = 'relative';
            observer.observe(level);
        });

        // Add fadeIn animation
        const fadeInStyle = document.createElement('style');
        fadeInStyle.textContent = `
            @keyframes fadeIn {
                to {
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(fadeInStyle);
    }

    initLevelCounter();

    /**
     * Add tooltip on hover for card descriptions
     */
    function initTooltips() {
        const cards = document.querySelectorAll('.org-card');
        
        cards.forEach(card => {
            const description = card.querySelector('.card-description');
            if (description) {
                card.addEventListener('mouseenter', function() {
                    description.style.maxHeight = description.scrollHeight + 'px';
                });
                
                card.addEventListener('mouseleave', function() {
                    description.style.maxHeight = '60px';
                });
            }
        });
    }

    initTooltips();

    /**
     * Console log for debugging
     */
    console.log('Dewan Pengawas page initialized successfully');
    console.log('Total organization cards:', document.querySelectorAll('.org-card').length);
    console.log('Total responsibility cards:', document.querySelectorAll('.responsibility-card').length);
});