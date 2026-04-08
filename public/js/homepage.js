// Animate cards on scroll
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card, .layanan-item, .info-item');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
    });

    // Observe cards
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

    cards.forEach(card => observer.observe(card));
});

// Hero button click tracking
const heroButtons = document.querySelectorAll('.hero-buttons .btn');
heroButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        console.log('Hero button clicked:', btn.textContent);
        // Add analytics tracking here if needed
    });
});

// Card click tracking
const cardLinks = document.querySelectorAll('.card-link');
cardLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        const cardTitle = link.closest('.card').querySelector('h3').textContent;
        console.log('Card clicked:', cardTitle);
        // Add analytics tracking here if needed
    });
});

// Parallax effect for hero section
const hero = document.querySelector('.hero');
if (hero) {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        if (scrolled < hero.offsetHeight) {
            hero.style.transform = `translateY(${scrolled * 0.15}px)`;
        }
    });
}

// Counter animation for statistics (if you add them later)
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target.toLocaleString('id-ID');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start).toLocaleString('id-ID');
        }
    }, 16);
}

// Info items hover effect enhancement
const infoItems = document.querySelectorAll('.info-item');
infoItems.forEach(item => {
    item.addEventListener('mouseenter', () => {
        const date = item.querySelector('.info-date');
        if (date) {
            date.style.transform = 'scale(1.1) rotate(5deg)';
        }
    });

    item.addEventListener('mouseleave', () => {
        const date = item.querySelector('.info-date');
        if (date) {
            date.style.transform = 'scale(1) rotate(0deg)';
        }
    });
});

// Add transition to info-date
infoItems.forEach(item => {
    const date = item.querySelector('.info-date');
    if (date) {
        date.style.transition = 'transform 0.3s ease';
    }
});

// Layanan items interaction
const layananItems = document.querySelectorAll('.layanan-item');
layananItems.forEach(item => {
    item.addEventListener('click', () => {
        const title = item.querySelector('h3').textContent;
        console.log('Layanan clicked:', title);
        // You can add navigation or modal here
    });

    // Add cursor pointer
    item.style.cursor = 'pointer';
});

// Auto-scroll info items (optional carousel effect)
let currentInfoIndex = 0;
const autoScrollInfo = () => {
    const infoGrid = document.querySelector('.info-grid');
    const items = document.querySelectorAll('.info-item');
    
    if (items.length > 0) {
        currentInfoIndex = (currentInfoIndex + 1) % items.length;
        // This is just a log, you can implement actual carousel if needed
        console.log('Current info index:', currentInfoIndex);
    }
};

// ==================== FETCH NEWS FROM API ====================
async function loadNews() {
    const newsGrid = document.getElementById('newsGrid');
    
    if (!newsGrid) return;
    
    // Show loading
    newsGrid.innerHTML = '<div class="loading-spinner">Memuat berita...</div>';
    
    try {
        // Ganti '/api/berita' dengan endpoint API kamu
        const response = await fetch('/api/berita?limit=5');
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        // Kalau data kosong
        if (!data || data.length === 0) {
            newsGrid.innerHTML = '<p style="color: white; text-align: center; grid-column: 1/-1;">Belum ada berita tersedia</p>';
            return;
        }
        
        // Generate news cards
        newsGrid.innerHTML = data.map(news => `
            <div class="news-card" onclick="window.location.href='/berita/${news.id}'">
                <img src="${news.image || news.foto || '/images/placeholder.jpg'}" 
                     alt="${news.title || news.judul}" 
                     class="news-image"
                     onerror="this.src='/images/placeholder.jpg'">
                <div class="news-content">
                    <div class="news-date">${formatDate(news.created_at || news.tanggal)}</div>
                    <h3 class="news-title">${news.title || news.judul}</h3>
                    <p class="news-excerpt">${news.excerpt || news.deskripsi || news.isi.substring(0, 100) + '...'}</p>
                </div>
            </div>
        `).join('');
        
    } catch (error) {
        console.error('Error loading news:', error);
        newsGrid.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; color: white;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Gagal memuat berita. Silakan refresh halaman.</p>
            </div>
        `;
    }
}

// Format date helper
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    
    // Format: DD-MM-YYYY HH:MM:SS
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}

// Load news on page load
document.addEventListener('DOMContentLoaded', loadNews);

// ==================== HERO SLIDER ====================
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');
    let currentSlide = 0;
    let autoSlideInterval;

    function showSlide(n) {
        // Remove active from all
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Handle wrap around
        currentSlide = (n + slides.length) % slides.length;
        
        // Add active to current
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    // Button click events
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Dots click events
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    // Auto slide every 5 seconds
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Start auto slide
    startAutoSlide();

    // Pause on hover
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mouseenter', stopAutoSlide);
        hero.addEventListener('mouseleave', startAutoSlide);
    }
});

// Load news on page load
document.addEventListener('DOMContentLoaded', loadNews);

// Quick actions (cek tagihan, pembayaran, etc)
const quickActionCards = document.querySelectorAll('.info-cards .card');
quickActionCards.forEach(card => {
    card.addEventListener('click', (e) => {
        if (!e.target.closest('.card-link')) {
            const link = card.querySelector('.card-link');
            if (link) {
                link.click();
            }
        }
    });
});

// Add pulse animation to important cards
const importantCards = document.querySelectorAll('.card');
if (importantCards.length > 0) {
    // Add pulse to first card (Cek Tagihan) as example
    const firstCard = importantCards[0];
    setInterval(() => {
        firstCard.style.animation = 'pulse 1s ease';
        setTimeout(() => {
            firstCard.style.animation = '';
        }, 1000);
    }, 5000);
}

// berita section
document.addEventListener('DOMContentLoaded', function(){
    LoadHomeBerita();
});

async function LoadHomeBerita() {
    const newsGrid = document.getElementById('newsGrid');

    try {
        const response = await fetch('/api/berita/latest?limit=5');

        if (!response.ok) {
            throw new Error('Gagal memuat berita');
        }

        const data = await response.json();

        newsGrid.innerHTML = '';

        if (data.length == 0) {
            newsGrid.innerHTML = '<p style="text-align: center; grid-column:1/-1;">Belum ada berita tersedia</p>';
            return;
        }
         newsGrid.innerHTML = data.map(news => createHomeNewsCard(news)).join('');
    } catch (error) {
        console.error('Error loading berita:', error);
        newsGrid.innerHTML = '<p style="text-align: center; grid-column: 1/-1; color: #999;">Gagal memuat berita</p>';
    }
}

function createHomeNewsCard(news){
    const date = new Date(news.created_at);
    const formattedDate = date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const cleanExcerpt = (news.excerpt || '').trim().substring(0, 200);
    return `
        <div class="news-card">
            <div class="news-image">
                <img src="${news.image || 'https://via.placeholder.com/400x250'}" alt="${news.title}">
            </div>
            <div class="news-content">
                <div class="news-meta">
                    <span class="news-meta-item">
                        <i class="fas fa-calendar"></i>
                        ${formattedDate}
                    </span>
                </div>
                <h3 class="news-title">${news.title}</h3>
                <p class="news-excerpt">${news.excerpt}</p>
                <a href="/berita#berita-${news.id}" class="btn-read-more">
                    Baca Selengkapnya
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    `;
}

async function openBeritaModal(newsID) {
    try {
        const response = await fetch(`/api/berita/${newsID}`);

        if (!response.ok) {
            throw new Error('Gagal memuat detail berita');
        }

        const result = await response.json();
        const news = result.data;

        const modal = document.getElementById('beritaModal');
        const modalBody = document.getElementById('modalBody');

        const date = new Date(news.date);
        const formattedDate = date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        modalBody.innerHTML = `
            <div class="modal-berita-header">
                <img src="${news.image || 'https://via.placeholder.com/800x400'}" alt="${news.title}">
            </div>
            <div class="modal-berita-content">
                <div class="modal-berita-meta">
                    <span class="modal-meta-item">
                        <i class="fas fa-calendar"></i>
                        ${formattedDate}
                    </span>
                    <span class="modal-meta-item">
                        <i class="fas fa-user"></i>
                        ${news.author}
                    </span>
                </div>
                <h2 class="modal-berita-title">${news.title}</h2>
                <div class="modal-berita-text">
                    ${formatBeritaContent(news.content)}
                </div>
                <div class="modal-berita-footer">
                    <div>
                        <strong>Bagikan:</strong>
                    </div>
                    <div class="modal-share-buttons">
                        <button class="share-btn facebook" onclick="shareToFacebook('${news.title}')">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="share-btn twitter" onclick="shareToTwitter('${news.title}')">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="share-btn whatsapp" onclick="shareToWhatsApp('${news.title}')">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Error loading berita detail: ', error);
        alert('Gagal memuat data berita');
    }
}

function closeBeritaModel() {
    const modal = document.getElementById('beritaModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function formatBeritaContent(content) {
    return content.split('\n\n').map(p => `<p>${p}</p>`).join('');
}

function shareToFacebook(title) {
    const url = window.location.href;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
}

function shareToTwitter(title) {
    const url = window.location.href();
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
}

function shareToWhatsApp(title) {
    const url = window.location.href();
    window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
}

window.onclick = function(event) {
    const modal = document.getElementById('beritaModal');
    if (event.target === modal) {
        closeBeritaModel();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBeritaModel();
    }
});

// Add pulse animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }
`;
document.head.appendChild(style);

console.log('Home page scripts loaded successfully');