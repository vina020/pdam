/**
 * History Page JavaScript
 * Handles animations and interactions for the History page
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all functions
    initScrollAnimations();
    initParallaxEffect();
    initSmoothScroll();
    initTimelineAnimation();
    
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
        const sections = document.querySelectorAll('.about-section, .story-highlight, .mission-section, .timeline-section');
        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = `all 0.8s ease ${index * 0.1}s`;
            observer.observe(section);
        });

        // Observe timeline items
        const timelineItems = document.querySelectorAll('.timeline-item');
        timelineItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = `all 0.6s ease ${index * 0.2}s`;
            observer.observe(item);
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
     * Parallax effect on hero section
     */
    function initParallaxEffect() {
        const heroBackground = document.querySelector('.hero-background img');
        
        if (heroBackground) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * 0.5;
                
                if (scrolled < window.innerHeight) {
                    heroBackground.style.transform = `translate3d(0, ${rate}px, 0)`;
                }
            });
        }
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href !== '#' && href !== '#learn-more' && href !== '#rediscover' && href !== '#mission') {
                    e.preventDefault();
                    
                    const target = document.querySelector(href);
                    if (target) {
                        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
                        const startPosition = window.pageYOffset;
                        const distance = targetPosition - startPosition - 80;
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
                }
            });
        });
    }

    /**
     * Timeline animation on scroll
     */
    function initTimelineAnimation() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        timelineItems.forEach(item => {
            observer.observe(item);
        });
    }

    /**
     * Image lazy loading with fade effect
     */
    function initImageLoading() {
        const images = document.querySelectorAll('img');
        
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
     * Hover effect for buttons
     */
    function initButtonEffects() {
        const buttons = document.querySelectorAll('.btn-learn-more, .btn-highlight, .btn-mission');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });
    }

    initButtonEffects();

    /**
     * Add floating animation to highlight section background
     */
    function initFloatingAnimation() {
        const highlightSection = document.querySelector('.story-highlight');
        
        if (highlightSection) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const sectionTop = highlightSection.offsetTop;
                const windowHeight = window.innerHeight;
                
                if (scrolled > sectionTop - windowHeight && scrolled < sectionTop + highlightSection.offsetHeight) {
                    const relativeScroll = scrolled - sectionTop + windowHeight;
                    const rotation = relativeScroll * 0.05;
                    
                    const beforeElement = window.getComputedStyle(highlightSection, '::before');
                    if (beforeElement) {
                        highlightSection.style.setProperty('--rotation', `${rotation}deg`);
                    }
                }
            });
        }
    }

    initFloatingAnimation();

    /**
     * Intersection Observer for fade-in effects
     */
    function initFadeInObserver() {
        const fadeElements = document.querySelectorAll('.about-text p, .mission-text');
        
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
            threshold: 0.1
        });

        fadeElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            observer.observe(element);
        });
    }

    initFadeInObserver();

    /**
     * Add counter animation for timeline dates
     */
    function initCounterAnimation() {
        const dates = document.querySelectorAll('.timeline-date');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const dateText = entry.target.textContent;
                    const year = parseInt(dateText);
                    
                    if (!isNaN(year)) {
                        let currentYear = year - 20;
                        const increment = year > 2000 ? 2 : 1;
                        
                        const counter = setInterval(() => {
                            currentYear += increment;
                            entry.target.textContent = currentYear;
                            
                            if (currentYear >= year) {
                                entry.target.textContent = dateText;
                                clearInterval(counter);
                            }
                        }, 30);
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        dates.forEach(date => {
            observer.observe(date);
        });
    }

    initCounterAnimation();

    /**
     * Console log for debugging
     */
    console.log('History page initialized successfully');
});