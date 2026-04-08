<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Dewan Pengawas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/struktur-organisasi.css') }}">
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
                    <li><a href="#">Tata Kelola PDAM</a></li>
                    <li class="active">Dewan Pengawas</li>
                </ul>
            </div>
        </nav>

<div class="dewan-pengawas-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <p class="subtitle">Struktur Organisasi</p>
                <h1 class="hero-title">Dewan Pengawas PDAM Magetan</h1>
                <p class="hero-description">
                    Dewan Pengawas PDAM Kabupaten Magetan bertugas melakukan pengawasan terhadap 
                    pengelolaan perusahaan yang dilaksanakan oleh Direksi
                </p>
            </div>
        </div>
    </section>

    <!-- Organization Chart Section -->
    <section class="org-chart-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">ORGANIZATIONAL CHART</h2>
                <p class="section-subtitle">Struktur Dewan Pengawas dan Tim Manajemen</p>
            </div>

            <!-- Chart Container -->
            <div class="org-chart">
                <!-- Top Level - Ketua -->
                <div class="org-level level-1">
                    <div class="org-card chairman">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-chairman.jpg') }}" alt="Ketua Dewan Pengawas">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Dr. Ahmad Suryadi, M.M.</h3>
                            <p class="card-position">Ketua Dewan Pengawas</p>
                            <div class="card-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <p class="card-description">
                                Memimpin dan mengkoordinasikan seluruh kegiatan pengawasan terhadap 
                                manajemen dan operasional PDAM
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="connector-line vertical"></div>

                <!-- Second Level - Anggota Utama -->
                <div class="org-level level-2">
                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-member1.jpg') }}" alt="Anggota 1">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ir. Siti Nurjanah</h3>
                            <p class="card-position">Anggota Dewan Pengawas</p>
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p class="card-description">
                                Mengawasi aspek teknis operasional dan pemeliharaan infrastruktur 
                                air bersih
                            </p>
                        </div>
                    </div>

                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-member2.jpg') }}" alt="Anggota 2">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Drs. Bambang Wijaya, M.Si.</h3>
                            <p class="card-position">Anggota Dewan Pengawas</p>
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p class="card-description">
                                Mengawasi aspek keuangan, administrasi, dan tata kelola perusahaan
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="connector-line vertical"></div>

                <!-- Third Level - Tim Pendukung -->
                <div class="org-level level-3">
                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-staff1.jpg') }}" alt="Staff 1">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Rina Wijayanti, S.E.</h3>
                            <p class="card-position">Sekretaris Dewan</p>
                            <div class="card-icon small">
                                <i class="fas fa-cog"></i>
                            </div>
                            <p class="card-description">
                                Mengelola administrasi dan dokumentasi kegiatan Dewan Pengawas
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-staff2.jpg') }}" alt="Staff 2">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Agus Prasetyo, S.T.</h3>
                            <p class="card-position">Koordinator Teknis</p>
                            <div class="card-icon small">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <p class="card-description">
                                Mengkoordinasikan aspek teknis dan operasional pengawasan
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-staff3.jpg') }}" alt="Staff 3">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Dewi Lestari, S.H.</h3>
                            <p class="card-position">Koordinator Legal</p>
                            <div class="card-icon small">
                                <i class="fas fa-cog"></i>
                            </div>
                            <p class="card-description">
                                Menangani aspek hukum dan kepatuhan regulasi perusahaan
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-staff4.jpg') }}" alt="Staff 4">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Hadi Susanto, S.Ak.</h3>
                            <p class="card-position">Koordinator Keuangan</p>
                            <div class="card-icon small">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <p class="card-description">
                                Mengawasi dan menganalisis laporan keuangan perusahaan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tugas dan Wewenang Section -->
    <section class="responsibilities-section">
        <div class="container">
            <h2 class="section-title-center">Tugas dan Wewenang</h2>
            <div class="responsibilities-grid">
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Pengawasan Operasional</h3>
                    <p>Melakukan pengawasan terhadap pengelolaan dan operasional perusahaan yang dilaksanakan oleh Direksi</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Evaluasi Laporan</h3>
                    <p>Mengevaluasi laporan berkala dan tahunan serta memberikan pendapat dan saran kepada Bupati</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Kepatuhan Hukum</h3>
                    <p>Memastikan kepatuhan perusahaan terhadap peraturan perundang-undangan dan anggaran dasar</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Monitoring Kinerja</h3>
                    <p>Memantau dan mengevaluasi kinerja perusahaan secara berkala untuk memastikan pencapaian target</p>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/struktur-organisasi.js') }}"></script>
</body>
</html>