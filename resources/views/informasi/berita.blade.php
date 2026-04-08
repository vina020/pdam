<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/berita.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar Component -->
    @include('component.sidebar')

        <!-- Breadcrumb -->
        <nav class="breadcrumbs">
            <div class="container">
                <ul>
                    <li><a href="{{ route('homepage') }}">Beranda</a></li>
                    <li><a href="{{ route('informasi') }}">Informasi</a></li>
                    <li class="active">Berita</li>
                </ul>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="layanan-hero-modern">
            <div class="hero-background">
                <div class="hero-shape shape-1"></div>
                <div class="hero-shape shape-2"></div>
                <div class="hero-shape shape-3"></div>
            </div>
            <div class="container">
                <div class="hero-content-modern">
                    <div class="hero-badge">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita & Pengumuman</span>
                    </div>
                    <h1 class="hero-title-modern">Berita Terkini PDAM Magetan</h1>
                    <p class="hero-subtitle-modern">
                        Informasi terbaru seputar layanan, pengumuman, dan kegiatan PDAM Magetan
                    </p>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="layanan-content-modern">
            <div class="container">

                <!-- Filter & Search -->
                <div class="section-modern  berita-container">
                    <div class="berita-filter-bar">
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchBerita" placeholder="Cari berita..." class="search-input">
                        </div>
                        <div class="filter-categories">
                            <button class="category-btn active" data-category="all">
                                <i class="fas fa-th-large"></i>
                                Semua
                            </button>
                            <button class="category-btn" data-category="pengumuman">
                                <i class="fas fa-bullhorn"></i>
                                Pengumuman
                            </button>
                            <button class="category-btn" data-category="info">
                                <i class="fas fa-info-circle"></i>
                                Informasi
                            </button>
                            <button class="category-btn" data-category="kegiatan">
                                <i class="fas fa-calendar-alt"></i>
                                Kegiatan
                            </button>
                        </div>
                        </div>
                        <div id="loadingState" class="loading-state">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <p>Memuat berita...</p>
                    </div>
                    <div id="emptyState" class="empty-state" style="display: none;">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>Tidak Ada Berita</h3>
                        <p>Belum ada berita yang tersedia saat ini</p>
                    </div>
                    <div id="newsGrid" class="news-grid" style="display: none;">
                        <!-- Items will be inserted here by JS -->
                    </div>
                    <div id="paginationContainer" class="pagination-container" style="display: none;">
                        <button class="pagination-btn" id="prevPage">
                            <i class="fas fa-chevron-left"></i>
                            Sebelumnya
                        </button>
                        <div class="pagination-info" id="paginationInfo">
                            Halaman <strong>1</strong> dari <strong>1</strong>
                        </div>
                        <button class="pagination-btn" id="nextPage">
                            Selanjutnya
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>    
                        
            <!-- CTA Section -->
                <div class="cta-modern">
                    <div class="cta-content-modern">
                        <div class="cta-icon-large">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h2>Dapatkan Update Berita</h2>
                        <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru langsung ke email Anda</p>
                        <div class="cta-buttons-modern">
                            <a href="#" class="btn-modern btn-primary-modern" onclick="subscribeNewsletter(); return false;">
                                <i class="fas fa-bell"></i>
                                Berlangganan
                            </a>
                            <a href="{{ route('kontak') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-phone"></i>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Footer -->
        @include('component.footer')
    </div>

    <!-- Modal Detail Berita -->
    <div class="modal" id="beritaModal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content modal-berita">
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-body" id="modalBody">
                <!-- Content will be inserted by JS -->
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/berita/all';
    </script>
    <script src="{{ asset('js/berita.js') }}"></script>
</body>
</html>