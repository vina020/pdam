<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Beranda</title>
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
     @include('component.navbar')

    <!-- Hero Section -->
    <section class="hero">
    <!-- Image Slider Background -->
    <div class="hero-slider">
        <div class="slide active">
            <img src="{{ asset('images/hero1.jpg') }}" alt="Hero 1">
        </div>
        <div class="slide">
            <img src="{{ asset('images/hero2.jpg') }}" alt="Hero 2">
        </div>
        <div class="slide">
            <img src="{{ asset('images/hero3.jpg') }}" alt="Hero 3">
        </div>
    </div>
    
    <!-- Overlay gelap biar text keliatan -->
    <div class="hero-overlay"></div>
    
    <!-- Navigation Arrows -->
    <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
    
    <!-- Dots Indicator -->
    <div class="slider-dots">
        <span class="dot active" data-slide="0"></span>
        <span class="dot" data-slide="1"></span>
        <span class="dot" data-slide="2"></span>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <h2 class="hero-title">Melayani dengan Sepenuh Hati</h2>
            <p class="hero-subtitle">Menyediakan air bersih berkualitas untuk masyarakat Magetan</p>
            <div class="hero-buttons">
                <a href="#layanan" class="btn btn-primary">Layanan Kami</a>
                <a href="#kontak" class="btn btn-secondary">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

    <!-- Layanan Section -->
    <section class="layanan" id="layanan">
        <div class="container">
            <div class="section-header">
                <h2>Layanan Kami</h2>
                <p>Berbagai layanan untuk kemudahan pelanggan</p>
            </div>
            <div class="layanan-grid">
                <div class="layanan-item">
                    <i class="fas fa-faucet"></i>
                    <h3>Pemasangan Baru</h3>
                    <p>Layanan pemasangan sambungan air baru untuk rumah tinggal dan usaha</p>
                </div>
                <div class="layanan-item">
                    <i class="fas fa-wrench"></i>
                    <h3>Perbaikan & Pemeliharaan</h3>
                    <p>Perbaikan meter air, pipa bocor, dan pemeliharaan rutin</p>
                </div>
                <div class="layanan-item">
                    <i class="fas fa-exchange-alt"></i>
                    <h3>Balik Nama</h3>
                    <p>Proses balik nama pelanggan untuk perpindahan kepemilikan</p>
                </div>
                <div class="layanan-item">
                    <i class="fas fa-phone-volume"></i>
                    <h3>Call Center 24/7</h3>
                    <p>Layanan informasi dan pengaduan pelanggan sepanjang waktu</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Section -->
    <section class="informasi" id="informasi">
    <div class="container">
        <div class="section-header">
            <h2>Informasi Terkini</h2>
            <p style="color: white">Berita dan pengumuman terbaru dari PDAM Magetan</p>
        </div>
        
        <!-- Kosong -->
        <div class="news-grid news-grid-5col" id="newsGrid">
            <!-- Loading indicator -->
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                Memuat berita...
            </div>
        </div>
        
        <div class="view-more">
            <a href="{{ route('berita') }}" class="btn btn-primary">
                Lihat Lebih Banyak <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
    <!-- Footer -->
   @include('component.footer')

   <!-- Modal Detail Berita -->
    <div class="modal" id="beritaModal">
        <div class="modal-overlay" onclick="closeBeritaModal()"></div>
        <div class="modal-content modal-berita">
            <button class="modal-close" onclick="closeBeritaModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-body" id="modalBody">
                <!-- Content will be inserted by JS -->
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/homepage.js') }}"></script>
</body>
</html>