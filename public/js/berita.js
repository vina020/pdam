// Berita JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Berita page loaded!');
    
    // Initialize
    initBerita();
    initFilters();
    initPagination();
});

// State Management
let state = {
    allNews: [],
    filteredNews: [],
    currentPage: 1,
    itemsPerPage: 9,
    currentCategory: 'all',
    searchQuery: ''
};

/**
 * Initialize Berita - Load dari API
 */
async function initBerita() {
    try {
        showLoading();
        
        // Fetch dari API
        const response = await fetch(API_URL);
        
        if (!response.ok) {
            throw new Error('Gagal memuat berita');
        }
        
        const data = await response.json();
        
        // Store data
        state.allNews = data.data || data; // Sesuaikan dengan struktur API
        state.filteredNews = state.allNews;
        
        // Render
        renderNews();
        
    } catch (error) {
        console.error('Error loading berita:', error);
        
        // Fallback: Load dummy data untuk development
        loadDummyData();
    }
}

/**
 * Render News Grid
 */
function renderNews() {
    const newsGrid = document.getElementById('newsGrid');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    
    // Hide loading
    loadingState.style.display = 'none';
    
    // Check if empty
    if (state.filteredNews.length === 0) {
        newsGrid.style.display = 'none';
        emptyState.style.display = 'block';
        document.getElementById('paginationContainer').style.display = 'none';
        return;
    }
    
    // Show grid
    emptyState.style.display = 'none';
    newsGrid.style.display = 'grid';
    
    // Calculate pagination
    const startIndex = (state.currentPage - 1) * state.itemsPerPage;
    const endIndex = startIndex + state.itemsPerPage;
    const paginatedNews = state.filteredNews.slice(startIndex, endIndex);
    
    // Render cards
    newsGrid.innerHTML = paginatedNews.map(news => createNewsCard(news)).join('');
    
    // Update pagination
    updatePagination();
}

/**
 * Create News Card HTML
 */
function createNewsCard(news) {
    const categoryClass = news.category || 'info';
    const categoryIcon = getCategoryIcon(categoryClass);
    const categoryLabel = getCategoryLabel(categoryClass);
    
    return `
        <div class="news-card" onclick="openModal(${news.id})">
            <div class="news-image">
                <img src="${news.image}" alt="${news.title}">
                <span class="news-category-badge ${categoryClass}">
                    <i class="${categoryIcon}"></i>
                    ${categoryLabel}
                </span>
            </div>
            <div class="news-content">
                <div class="news-meta">
                    <span class="news-meta-item">
                        <i class="fas fa-calendar"></i>
                        ${formatDate(news.date)}
                    </span>
                    <span class="news-meta-item">
                        <i class="fas fa-eye"></i>
                        ${news.views} views
                    </span>
                </div>
                <h3 class="news-title">${news.title}</h3>
                <p class="news-excerpt">${news.excerpt}</p>
                <div class="news-footer">
                    <div class="news-author">
                        <div class="author-avatar">${getInitials(news.author)}</div>
                        <span class="author-name">${news.author}</span>
                    </div>
                    <button class="btn-read-more">
                        Baca
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

/**
 * Initialize Filters
 */
function initFilters() {
    // Search
    const searchInput = document.getElementById('searchBerita');
    searchInput.addEventListener('input', debounce(function(e) {
        state.searchQuery = e.target.value.toLowerCase();
        filterNews();
    }, 300));
    
    // Category buttons
    const categoryBtns = document.querySelectorAll('.category-btn');
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            categoryBtns.forEach(b => b.classList.remove('active'));
            // Add active to clicked
            this.classList.add('active');
            
            // Filter
            state.currentCategory = this.dataset.category;
            filterNews();
        });
    });
}

/**
 * Filter News
 */
function filterNews() {
    state.filteredNews = state.allNews.filter(news => {
        // Category filter
        const categoryMatch = state.currentCategory === 'all' || news.category === state.currentCategory;
        
        // Search filter
        const searchMatch = state.searchQuery === '' || 
                          news.title.toLowerCase().includes(state.searchQuery) ||
                          news.excerpt.toLowerCase().includes(state.searchQuery);
        
        return categoryMatch && searchMatch;
    });
    
    // Reset to page 1
    state.currentPage = 1;
    
    // Render
    renderNews();
}

/**
 * Initialize Pagination
 */
function initPagination() {
    document.getElementById('prevPage').addEventListener('click', () => {
        if (state.currentPage > 1) {
            state.currentPage--;
            renderNews();
            scrollToTop();
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(state.filteredNews.length / state.itemsPerPage);
        if (state.currentPage < totalPages) {
            state.currentPage++;
            renderNews();
            scrollToTop();
        }
    });
}

/**
 * Update Pagination UI
 */
function updatePagination() {
    const totalPages = Math.ceil(state.filteredNews.length / state.itemsPerPage);
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationInfo = document.getElementById('paginationInfo');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    
    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'flex';
    paginationInfo.innerHTML = `Halaman <strong>${state.currentPage}</strong> dari <strong>${totalPages}</strong>`;
    
    prevBtn.disabled = state.currentPage === 1;
    nextBtn.disabled = state.currentPage === totalPages;
}

/**
 * Open Modal with News Detail
 */
async function openModal(newsId) {
    try {
        const response = await fetch(`/api/berita/${newsId}`);
        
        if (!response.ok) {
            throw new Error('Berita tidak ditemukan');
        }
        
        const result = await response.json();
        const news = result.data;
        
        if (!news) return;
        
        const modal = document.getElementById('beritaModal');
        const modalBody = document.getElementById('modalBody');
        
        const categoryClass = news.category || 'info';
        const categoryIcon = getCategoryIcon(categoryClass);
        const categoryLabel = getCategoryLabel(categoryClass);
        
        modalBody.innerHTML = `
            <div class="modal-berita-header">
                <img src="${news.image}" alt="${news.title}">
                <span class="news-category-badge modal-berita-category ${categoryClass}">
                    <i class="${categoryIcon}"></i>
                    ${categoryLabel}
                </span>
            </div>
            <div class="modal-berita-content">
                <div class="modal-berita-meta">
                    <span class="modal-meta-item">
                        <i class="fas fa-calendar"></i>
                        ${formatDate(news.date)}
                    </span>
                    <span class="modal-meta-item">
                        <i class="fas fa-user"></i>
                        ${news.author}
                    </span>
                    <span class="modal-meta-item">
                        <i class="fas fa-eye"></i>
                        ${news.views} views
                    </span>
                </div>
                <h2 class="modal-berita-title">${news.title}</h2>
                <div class="modal-berita-text">
                    ${formatContent(news.content)}
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
        
        const newsIndex = state.allNews.findIndex(n => n.id === newsId);
        if (newsIndex !== -1) {
            state.allNews[newsIndex].views = news.views;
        }
        
    } catch (error) {
        console.error('Error loading berita detail:', error);
        alert('Gagal memuat detail berita');
    }
}

/**
 * Close Modal
 */
function closeModal() {
    const modal = document.getElementById('beritaModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

/**
 * Helper Functions
 */
function showLoading() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('newsGrid').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';
}

function getCategoryIcon(category) {
    const icons = {
        pengumuman: 'fas fa-bullhorn',
        info: 'fas fa-info-circle',
        kegiatan: 'fas fa-calendar-alt'
    };
    return icons[category] || 'fas fa-info-circle';
}

function getCategoryLabel(category) {
    const labels = {
        pengumuman: 'Pengumuman',
        info: 'Informasi',
        kegiatan: 'Kegiatan'
    };
    return labels[category] || 'Informasi';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}

function formatContent(content) {
    // Split by paragraphs and wrap in <p> tags
    return content.split('\n\n').map(p => `<p>${p}</p>`).join('');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Share Functions
 */
function shareToFacebook(title) {
    const url = window.location.href;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
}

function shareToTwitter(title) {
    const url = window.location.href;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
}

function shareToWhatsApp(title) {
    const url = window.location.href;
    window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
}

/**
 * Subscribe Newsletter
 */
function subscribeNewsletter() {
    // Implement newsletter subscription
    alert('Fitur berlangganan newsletter akan segera hadir!');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('beritaModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

console.log('Berita functionality initialized!');