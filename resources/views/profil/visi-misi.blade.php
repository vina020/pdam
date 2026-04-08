<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Visi Misi</title>
    <link rel="stylesheet" href="{{ asset('css/visi-misi.css') }}">
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
                <li><a href="#">Profil</a></li>
                <li class="active">Visi Misi</li>
            </ul>
        </div>
    </nav>

<div class="vision-mission-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <p class="subtitle">About Us</p>
                <h1 class="hero-title">Senyum Anda Bahagia Kami</h1>
                <p class="hero-description">
                    PDAM menyediakan beragam layanan unggulan yang dirancang 
                    untuk memenuhi kebutuhan dan membantu Anda
                </p>
                <a href="#mission" class="btn-primary">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Vision Mission Section -->
    <section class="vision-mission-section">
        <div class="container">
            <div class="vision-mission-grid">
                <!-- Our Story Card -->
                <div class="story-card">
                    <div class="story-image">
                        <img src="{{ asset('images/team-meeting.jpg') }}" alt="Our Story">
                        <div class="story-overlay">
                            <h2 class="card-title">Sejarah Perusahaan</h2>
                            <p class="card-description">
                                Pelayanan air minum secara perpipaan di Magetan sudah dikenal 
                                sejak tahun 1905 (jaman Belanda), yaitu dengan dibangunnya 
                                saluran air minum dalam Kota Magetan yang diambil dari sumber Gangging.
                            </p>
                            <a href="{{ route('sejarah') }}" class="btn-link">
                                Lihat Selengkapnya 
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mission Card -->
                <div class="mission-card" id="mission">
                    <div class="card-content">
                        <h2 class="card-title">Visi</h2>
                        <p class="card-description">
                            Mewujudkan Perumdam Lawu Tirta Kabupaten Magetan
                            Yang Profesional dan Membanggakan
                            dalam Pelayanan Air Bersih 
                        </p>
                    </div>
                </div>

                <!-- Vision Card -->
                <div class="vision-card">
                    <div class="card-content">
                        <h2 class="card-title">Misi</h2>
                        <p class="card-description">
                            1. Peningkatan Kapasitas, Motivasi dan Kinerja SD
                            <p class="card-description" >
                                2. Peningkatan Efektifitas dan Efisiensi Sistem Kerja
                            </p>
                            <p class="card-description" >
                            3. Peningkatan Kinerja Keuangan
                            </p>
                            <p class="card-description" >
                            4. Peningkatan Kepuasan Pelanggan
                            </p>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/visi-misi.js') }}"></script>
</body>
</html>