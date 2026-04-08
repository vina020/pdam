<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Struktur Organisasi</title>
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
                    <li class="active">Struktur Organisasi</li>
                </ul>
            </div>
        </nav>
<div class="dewan-pengawas-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <p class="subtitle">Struktur Organisasi</p>
                <h1 class="hero-title">Struktur Organisasi PDAM Magetan</h1>
                <p class="hero-description">
                    Struktur organisasi PDAM Kabupaten Magetan yang tersusun secara sistematis 
                    untuk menjalankan tugas dan fungsi pelayanan air bersih kepada masyarakat
                </p>
            </div>
        </div>
    </section>

    <!-- Organization Chart Section -->
    <section class="org-chart-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">ORGANIZATIONAL CHART</h2>
                <p class="section-subtitle">Bagan Struktur Organisasi PDAM Kabupaten Magetan</p>
            </div>

            <!-- Chart Container -->
            <div class="org-chart">
                <!-- Level 1 - Dewan Pengawas -->
                <div class="org-level level-1">
                    <div class="org-card chairman">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-dewan.jpg') }}" alt="Dewan Pengawas">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Dewan Pengawas</h3>
                            <p class="card-position">Badan Pengawas</p>
                            <div class="card-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <p class="card-description">
                                Melakukan pengawasan terhadap pengelolaan perusahaan yang dilaksanakan oleh Direksi
                            </p>
                        </div>
                    </div>
                </div>

                <div class="connector-line vertical"></div>

                <!-- Level 2 - Direktur Utama -->
                <div class="org-level level-1">
                    <div class="org-card chairman">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-dirut.jpg') }}" alt="Direktur Utama">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ir. Bambang Suryanto, M.M.</h3>
                            <p class="card-position">Direktur Utama</p>
                            <div class="card-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <p class="card-description">
                                Memimpin dan mengelola seluruh operasional perusahaan serta bertanggung jawab atas pencapaian visi dan misi perusahaan
                            </p>
                        </div>
                    </div>
                </div>

                <div class="connector-line vertical"></div>

                <!-- Level 3 - Bagian Umum & Sekretaris -->
                <div class="org-level level-2">
                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-bagian-umum.jpg') }}" alt="Bagian Umum">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Drs. Sutrisno</h3>
                            <p class="card-position">Bagian Umum</p>
                            <div class="card-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <p class="card-description">
                                Mengelola administrasi umum, kepegawaian, dan logistik perusahaan
                            </p>
                        </div>
                    </div>

                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/avatar-sekretaris.jpg') }}" alt="Sekretaris Perusahaan">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sri Wahyuni, S.H.</h3>
                            <p class="card-position">Sekretaris Perusahaan</p>
                            <div class="card-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <p class="card-description">
                                Mengelola kesekretariatan, kehumasan, dan dokumentasi perusahaan
                            </p>
                        </div>
                    </div>
                </div>

                <div class="connector-line vertical"></div>

                <!-- Level 4 - Kepala Bidang -->
                <div class="org-level level-3">
                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-keuangan.jpg') }}" alt="Kepala Bidang Keuangan">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Agus Wijaya, S.E., M.Ak.</h3>
                            <p class="card-position">Kabid Keuangan</p>
                            <div class="card-icon small">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <p class="card-description">
                                Mengelola keuangan, akuntansi, dan perencanaan anggaran
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-teknik.jpg') }}" alt="Kepala Bidang Teknik">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ir. Hadi Purnomo</h3>
                            <p class="card-position">Kabid Teknik</p>
                            <div class="card-icon small">
                                <i class="fas fa-tools"></i>
                            </div>
                            <p class="card-description">
                                Mengelola infrastruktur, pemeliharaan, dan pengembangan jaringan
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-produksi.jpg') }}" alt="Kepala Bidang Produksi">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Eko Prasetyo, S.T.</h3>
                            <p class="card-position">Kabid Produksi</p>
                            <div class="card-icon small">
                                <i class="fas fa-industry"></i>
                            </div>
                            <p class="card-description">
                                Mengelola produksi dan distribusi air bersih kepada pelanggan
                            </p>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-pelanggan.jpg') }}" alt="Kepala Bidang Pelanggan">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Siti Aminah, S.Sos.</h3>
                            <p class="card-position">Kabid Pelanggan</p>
                            <div class="card-icon small">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="card-description">
                                Mengelola hubungan dan pelayanan kepada pelanggan
                            </p>
                        </div>
                    </div>
                </div>

                <div class="connector-line vertical"></div>

                <!-- Level 5 - Sub Bagian -->
                <div class="org-level level-3">
                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Akuntansi">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Akuntansi</h3>
                            <p class="card-position">Pencatatan & Pelaporan</p>
                            <div class="card-icon small">
                                <i class="fas fa-calculator"></i>
                            </div>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Perencanaan">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Perencanaan</h3>
                            <p class="card-position">Program & Anggaran</p>
                            <div class="card-icon small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Instalasi">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Instalasi</h3>
                            <p class="card-position">Pemeliharaan & Perbaikan</p>
                            <div class="card-icon small">
                                <i class="fas fa-wrench"></i>
                            </div>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Distribusi">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Distribusi</h3>
                            <p class="card-position">Pengaliran Air</p>
                            <div class="card-icon small">
                                <i class="fas fa-water"></i>
                            </div>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Meter">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Meter</h3>
                            <p class="card-position">Pembacaan & Kalibrasi</p>
                            <div class="card-icon small">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="org-card staff">
                        <div class="card-header">
                            <div class="avatar-circle small">
                                <img src="{{ asset('images/avatar-default.jpg') }}" alt="Sub Bagian Hubungan Pelanggan">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Sub Bag. Hublang</h3>
                            <p class="card-position">Layanan Pelanggan</p>
                            <div class="card-icon small">
                                <i class="fas fa-headset"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="responsibilities-section">
        <div class="container">
            <h2 class="section-title-center">Fungsi Masing-Masing Bidang</h2>
            <div class="responsibilities-grid">
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Bidang Keuangan</h3>
                    <p>Mengelola keuangan, akuntansi, perpajakan, dan perencanaan anggaran perusahaan secara transparan dan akuntabel</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Bidang Teknik</h3>
                    <p>Bertanggung jawab atas pembangunan, pemeliharaan, dan pengembangan infrastruktur jaringan air bersih</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Bidang Produksi</h3>
                    <p>Mengelola proses produksi air bersih dari sumber hingga distribusi ke pelanggan dengan kualitas terjamin</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Bidang Pelanggan</h3>
                    <p>Menangani layanan pelanggan, pengaduan, pemasangan baru, dan hubungan masyarakat secara profesional</p>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/struktur-organisasi.js') }}"></script>
</body>
</html>