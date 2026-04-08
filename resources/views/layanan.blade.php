<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Layanan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    @include('component.navbar')

    <!-- Hero Section -->
    <section class="hero hero-layanan">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h2 class="hero-title">Layanan PDAM Magetan</h2>
                <p class="hero-subtitle">
                    Berbagai layanan untuk mendukung kebutuhan air bersih masyarakat
                </p>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="layanan-page">
        <div class="container">
            <div class="section-header">
                <h2>Daftar Layanan</h2>
                <p>Pilih layanan yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="layanan-wrapper">
                <!-- PANAH KIRI -->
                <button class="slider-btn layanan-prev" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <!-- GRID LAYANAN -->
                <div class="layanan-grid" id="layananGrid">
    <a href="{{ route('layanan.pasang-baru') }}" class="layanan-item">
        <i class="fas fa-faucet"></i>
        <h3>Pasang Baru</h3>
        <p>Layanan pendaftaran pemasangan sambungan air baru.</p>
    </a>

    <a href="{{ route('layanan.pengaduan') }}" class="layanan-item">
        <i class="fas fa-headset"></i>
        <h3>Pengaduan</h3>
        <p>Penyampaian keluhan dan gangguan layanan air.</p>
    </a>

    <a href="{{ route('layanan.cek-tagihan') }}" class="layanan-item">
        <i class="fas fa-file-invoice"></i>
        <h3>Cek Tagihan</h3>
        <p>Informasi tagihan rekening air pelanggan.</p>
    </a>

    <a href="{{ route('informasi-sambungan') }}" class="layanan-item">
        <i class="fas fa-network-wired"></i>
        <h3>Informasi Sambungan</h3>
        <p>Informasi status dan detail sambungan pelanggan.</p>
    </a>
</div>

                <!-- PANAH KANAN -->
                <button class="slider-btn layanan-next" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- DOTS -->
            <div class="slider-dots layanan-dots" id="layananDots"></div>
        </div>
    </section>

    <!-- Footer -->
    @include('component.footer')

    <script src="{{ asset('js/layanan.js') }}"></script>
</body>
</html>