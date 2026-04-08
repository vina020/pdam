<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Sejarah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sejarah.css') }}">
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
                <li class="active">Sejarah</li>
            </ul>
        </div>
    </nav>

<div class="history-page">
    <!-- Hero Section -->
     <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <p class="subtitle">About Us</p>
                <h1 class="hero-title">REDISCOVERING THE SEPHARDIC STORY</h1>
                <p class="hero-description">
                    PDAM menyediakan beragam layanan unggulan yang dirancang 
                    untuk memenuhi kebutuhan dan membantu Anda
                </p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">TENTANG PDAM</h2>
                    <div class="about-text">
                        <p>
                        PDAM Kabupaten Magetan telah melayani kebutuhan air minum masyarakat sejak tahun 1905, bermula dari pembangunan saluran air oleh pemerintah kolonial Belanda yang bersumber dari Mata Air Gangging. Sebelum berdiri sebagai PDAM, pengelolaan air minum dilakukan oleh Dinas Saluran Air Minum (SAM) di bawah Pemerintah Kabupaten Magetan. Seiring meningkatnya kebutuhan masyarakat, PDAM resmi didirikan berdasarkan Peraturan Daerah Nomor 4 Tahun 1982 dan mulai beroperasi pada 1 Mei 1983.
                    </p>
                        <p>
                        Sebagai bentuk kesiapan operasional, aset eks Dinas SAM dialihkan menjadi modal dasar PDAM dengan nilai Rp348.148.760. Pengelolaan PDAM terus disesuaikan dengan berbagai regulasi daerah dan nasional untuk menjamin kualitas layanan, tata kelola, serta tarif air minum yang berkeadilan. Struktur organisasi PDAM Kabupaten Magetan saat ini mengacu pada ketentuan nasional sesuai jumlah pelanggan yang dilayani.
                    </p>
                        <p>
                        Pada awal tahun 2021, PDAM Lawu Tirta resmi berubah menjadi PERUMDAM Lawu Tirta berdasarkan Peraturan Daerah Nomor 2 Tahun 2021. Perubahan ini memperkuat posisi hukum dan tata kelola perusahaan sebagai Badan Usaha Milik Daerah. PERUMDAM Lawu Tirta berkomitmen memberikan pelayanan air minum yang berkualitas, berkelanjutan, dan berbasis teknologi kepada masyarakat Kabupaten Magetan.
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Highlight Section -->
    <section class="story-highlight">
        <div class="container">
            <div class="highlight-content">
                <h2 class="highlight-title">AIR BERSIH UNTUK KEHIDUPAN, LAYANAN TERPERCAYA MASYARAKAT MAGETAN</h2>
                <p class="highlight-subtitle">Dari Sejarah Panjang Menuju Pelayanan Air yang Modern dan Andal</p>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-grid">
                <div class="mission-content">
                    <p class="mission-label">TENTANG MISI KAMI</p>
                    <h2 class="mission-title">TELLING THE STORIES OF OUR HERITAGE</h2>
                    <p class="mission-text">
                    PERUMDAM Lawu Tirta berkomitmen meningkatkan kapabilitas, motivasi, dan kinerja sumber daya manusia melalui pengembangan kompetensi serta budaya kerja profesional. SDM yang andal menjadi fondasi utama dalam menjaga kualitas layanan air minum bagi masyarakat Magetan. Dengan dukungan aparatur yang kompeten, perusahaan mampu beradaptasi terhadap perkembangan teknologi dan kebutuhan pelayanan publik.
                </p>
                    <p class="mission-text">
                    Dalam mendukung kinerja organisasi, PERUMDAM Lawu Tirta terus meningkatkan efektivitas dan efisiensi sistem kerja melalui penyempurnaan proses operasional dan tata kelola perusahaan. Penerapan sistem kerja yang terstruktur dan berbasis regulasi bertujuan menciptakan layanan yang tepat waktu, transparan, dan akuntabel. Hal ini menjadi bagian dari upaya perusahaan dalam mewujudkan pelayanan air minum yang berkelanjutan.
                </p>
                <p class="mission-text"> Selain fokus pada pelayanan, PERUMDAM Lawu Tirta juga berupaya meningkatkan kinerja keuangan guna menjamin keberlangsungan usaha. Pengelolaan keuangan yang sehat memungkinkan perusahaan untuk melakukan pengembangan infrastruktur dan peningkatan kualitas layanan. Pada akhirnya, seluruh upaya tersebut diarahkan untuk meningkatkan kepuasan pelanggan sebagai wujud tanggung jawab perusahaan kepada masyarakat.
                </p>
                    <a href="{{ route('visi-misi') }}" class="btn-mission">LEARN MORE</a>
                </div>
                <div class="mission-image">
                    <img src="{{ asset('images/heritage-arch.jpg') }}" alt="Heritage">
                </div>
            </div>
        </div>
    </section>

    <!-- History Timeline Section (Optional) -->
    <section class="timeline-section">
        <div class="container">
            <h2 class="section-title-center">SEJARAH PDAM MAGETAN</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">1975</div>
                    <div class="timeline-content">
                        <h3>Pendirian PDAM</h3>
                        <p>PDAM Kabupaten Magetan didirikan berdasarkan Peraturan Daerah untuk melayani kebutuhan air bersih masyarakat.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">1985</div>
                    <div class="timeline-content">
                        <h3>Ekspansi Layanan</h3>
                        <p>Perluasan jaringan distribusi air bersih ke wilayah-wilayah terpencil di Kabupaten Magetan.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2000</div>
                    <div class="timeline-content">
                        <h3>Modernisasi Sistem</h3>
                        <p>Implementasi sistem manajemen modern dan peningkatan infrastruktur untuk pelayanan yang lebih baik.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2020</div>
                    <div class="timeline-content">
                        <h3>Digitalisasi Pelayanan</h3>
                        <p>Penerapan sistem digital untuk pembayaran dan layanan pelanggan yang lebih efisien.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/sejarah.js') }}"></script>
</body>
</html>