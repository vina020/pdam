// Maklumat Pelayanan Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Maklumat Pelayanan page loaded!');
    
    // Smooth scroll for anchor links
    initSmoothScroll();
    
    // Animation on scroll
    initScrollAnimation();
    
    // Copy contact info functionality
    initCopyContact();
});

/**
 * Initialize smooth scroll for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Initialize scroll animations
 */
function initScrollAnimation() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.maklumat-card, .sanksi-card, .channel-item').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
}

/**
 * Initialize copy contact info functionality
 */
function initCopyContact() {
    document.querySelectorAll('.channel-info span').forEach(element => {
        element.style.cursor = 'pointer';
        element.title = 'Klik untuk menyalin';
        
        element.addEventListener('click', function() {
            const text = this.textContent;
            copyToClipboard(text);
            showCopyNotification(this);
        });
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        // Modern approach
        navigator.clipboard.writeText(text).then(() => {
            console.log('Text copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            fallbackCopyTextToClipboard(text);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyTextToClipboard(text);
    }
}

/**
 * Fallback copy method for older browsers
 */
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.top = "-999999px";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        console.log(successful ? 'Text copied' : 'Copy failed');
    } catch (err) {
        console.error('Fallback: Could not copy text: ', err);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Show copy notification
 */
function showCopyNotification(element) {
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'copy-notification';
    notification.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
    
    // Style notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #198754, #20c997);
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Add temporary highlight to clicked element
    const originalBg = element.style.backgroundColor;
    element.style.backgroundColor = '#d1f2eb';
    element.style.transition = 'background-color 0.3s ease';
    
    // Remove notification and reset highlight after delay
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        element.style.backgroundColor = originalBg;
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 2000);
}

/**
 * Download PDF functionality
 */
document.querySelectorAll('a[href="#"]').forEach(link => {
    if (link.textContent.includes('Download PDF')) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Fitur download PDF akan segera tersedia. Silakan hubungi customer service untuk mendapatkan salinan Maklumat Pelayanan.');
        });
    }
});

/**
 * Print page functionality (optional)
 */
function printMaklumat() {
    window.print();
}

// Add keyboard shortcut for print (Ctrl+P)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printMaklumat();
    }
});

// Add animation styles dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    /* Hover effect for copy elements */
    .channel-info span:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }
`;
document.head.appendChild(style);

console.log('Maklumat Pelayanan functionality initialized!');