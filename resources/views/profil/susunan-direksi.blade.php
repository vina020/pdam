<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Susunan Direksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/susunan-direksi.css') }}">
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
                    <li class="active">Susunan Direksi</li>
                </ul>
            </div>
        </nav>
        
<div class="susunan-direksi-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <p class="subtitle">Manajemen Perusahaan</p>
                <h1 class="hero-title">Susunan Direksi PDAM Magetan</h1>
                <p class="hero-description">
                    Direksi PDAM Kabupaten Magetan yang profesional dan berpengalaman dalam mengelola 
                    perusahaan daerah air minum untuk melayani masyarakat dengan optimal
                </p>
            </div>
        </div>
    </section>

    <!-- Organization Chart Section -->
    <section class="org-chart-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">SUSUNAN DIREKSI</h2>
                <p class="section-subtitle">Periode 2023 - 2028</p>
            </div>

            <!-- Chart Container -->
            <div class="org-chart">
                <!-- Top Level - Direktur Utama -->
                <div class="org-level level-1">
                    <div class="org-card chairman">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/dirut.jpg') }}" alt="Direktur Utama">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ir. Bambang Suryanto, M.M.</h3>
                            <p class="card-position">Direktur Utama</p>
                            <div class="card-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <p class="card-description">
                                Memimpin dan mengkoordinasikan seluruh kegiatan operasional perusahaan, 
                                menetapkan kebijakan strategis, dan bertanggung jawab penuh atas pencapaian 
                                visi misi perusahaan kepada Bupati Magetan
                            </p>
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>Masa Jabatan: 2023-2028</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>dirut@pdammagetan.co.id</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connector Line -->
                <div class="connector-line vertical"></div>

                <!-- Second Level - Direktur Teknik & Direktur Umum -->
                <div class="org-level level-2">
                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/dirtek.jpg') }}" alt="Direktur Teknik">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ir. Hendra Wijaya, M.T.</h3>
                            <p class="card-position">Direktur Teknik</p>
                            <div class="card-icon">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                            <p class="card-description">
                                Bertanggung jawab atas pengelolaan teknis operasional, pemeliharaan 
                                infrastruktur, produksi dan distribusi air bersih, serta pengembangan 
                                jaringan perpipaan
                            </p>
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i class="fas fa-phone"></i>
                                    <span>(0351) 891234</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>dirtek@pdammagetan.co.id</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="org-card member">
                        <div class="card-header">
                            <div class="avatar-circle">
                                <img src="{{ asset('images/dirum.jpg') }}" alt="Direktur Umum">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Dra. Siti Rahayu, M.M.</h3>
                            <p class="card-position">Direktur Umum & Keuangan</p>
                            <div class="card-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <p class="card-description">
                                Mengelola administrasi umum, keuangan, akuntansi, kepegawaian, 
                                hubungan pelanggan, dan memastikan pengelolaan keuangan perusahaan 
                                berjalan transparan dan akuntabel
                            </p>
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i class="fas fa-phone"></i>
                                    <span>(0351) 891235</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>dirum@pdammagetan.co.id</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Director Profiles Detail -->
            <div class="director-profiles">
                <div class="profile-detail-card">
                    <div class="profile-header">
                        <div class="profile-avatar-large">
                            <img src="{{ asset('images/dirut.jpg') }}" alt="Direktur Utama">
                        </div>
                        <div class="profile-info">
                            <h3>Ir. Bambang Suryanto, M.M.</h3>
                            <p class="position">Direktur Utama</p>
                            <p class="period">Periode: 2023 - 2028</p>
                        </div>
                    </div>
                    <div class="profile-content">
                        <div class="profile-section">
                            <h4><i class="fas fa-graduation-cap"></i> Riwayat Pendidikan</h4>
                            <ul>
                                <li>S2 Magister Manajemen - Universitas Gadjah Mada (2015)</li>
                                <li>S1 Teknik Sipil - Institut Teknologi Sepuluh Nopember (2005)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-briefcase"></i> Pengalaman Kerja</h4>
                            <ul>
                                <li>Direktur Utama PDAM Kab. Magetan (2023 - Sekarang)</li>
                                <li>Kepala Bidang Teknik PDAM Kab. Magetan (2018 - 2023)</li>
                                <li>Kepala Sub Bagian Perencanaan (2010 - 2018)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-award"></i> Prestasi</h4>
                            <ul>
                                <li>Penghargaan PDAM Kinerja Baik dari Kementerian PUPR (2024)</li>
                                <li>Sertifikasi Manajemen Air Minum Profesional (2020)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="profile-detail-card">
                    <div class="profile-header">
                        <div class="profile-avatar-large">
                            <img src="{{ asset('images/dirtek.jpg') }}" alt="Direktur Teknik">
                        </div>
                        <div class="profile-info">
                            <h3>Ir. Hendra Wijaya, M.T.</h3>
                            <p class="position">Direktur Teknik</p>
                            <p class="period">Periode: 2023 - 2028</p>
                        </div>
                    </div>
                    <div class="profile-content">
                        <div class="profile-section">
                            <h4><i class="fas fa-graduation-cap"></i> Riwayat Pendidikan</h4>
                            <ul>
                                <li>S2 Magister Teknik Sipil - Institut Teknologi Bandung (2016)</li>
                                <li>S1 Teknik Lingkungan - Universitas Brawijaya (2008)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-briefcase"></i> Pengalaman Kerja</h4>
                            <ul>
                                <li>Direktur Teknik PDAM Kab. Magetan (2023 - Sekarang)</li>
                                <li>Kepala Bidang Produksi PDAM Kab. Magetan (2019 - 2023)</li>
                                <li>Kepala Instalasi Pengolahan Air (2012 - 2019)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-award"></i> Keahlian</h4>
                            <ul>
                                <li>Ahli K3 Konstruksi - Kementerian PUPR</li>
                                <li>Sertifikat Ahli Teknik Penyehatan & Plumbing</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="profile-detail-card">
                    <div class="profile-header">
                        <div class="profile-avatar-large">
                            <img src="{{ asset('images/dirum.jpg') }}" alt="Direktur Umum">
                        </div>
                        <div class="profile-info">
                            <h3>Dra. Siti Rahayu, M.M.</h3>
                            <p class="position">Direktur Umum & Keuangan</p>
                            <p class="period">Periode: 2023 - 2028</p>
                        </div>
                    </div>
                    <div class="profile-content">
                        <div class="profile-section">
                            <h4><i class="fas fa-graduation-cap"></i> Riwayat Pendidikan</h4>
                            <ul>
                                <li>S2 Magister Manajemen - Universitas Airlangga (2017)</li>
                                <li>S1 Ekonomi Akuntansi - Universitas Sebelas Maret (2007)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-briefcase"></i> Pengalaman Kerja</h4>
                            <ul>
                                <li>Direktur Umum & Keuangan PDAM Kab. Magetan (2023 - Sekarang)</li>
                                <li>Kepala Bidang Keuangan PDAM Kab. Magetan (2018 - 2023)</li>
                                <li>Kepala Sub Bagian Akuntansi (2011 - 2018)</li>
                            </ul>
                        </div>
                        <div class="profile-section">
                            <h4><i class="fas fa-award"></i> Sertifikasi</h4>
                            <ul>
                                <li>Certified Public Accountant (CPA)</li>
                                <li>Certified Internal Auditor (CIA)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Responsibilities Section -->
    <section class="responsibilities-section">
        <div class="container">
            <h2 class="section-title-center">Tugas dan Tanggung Jawab Direksi</h2>
            <div class="responsibilities-grid">
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Pengelolaan Perusahaan</h3>
                    <p>Memimpin dan mengelola perusahaan sesuai maksud dan tujuan serta kegiatan usaha perusahaan</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3>Perencanaan Strategis</h3>
                    <p>Menyusun rencana jangka panjang dan rencana kerja anggaran tahunan perusahaan</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Laporan Kinerja</h3>
                    <p>Menyampaikan laporan berkala tentang perkembangan perusahaan kepada Bupati dan Dewan Pengawas</p>
                </div>
                <div class="responsibility-card">
                    <div class="resp-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Representasi</h3>
                    <p>Mewakili perusahaan di dalam dan di luar pengadilan serta menandatangani kontrak dan perjanjian</p>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/struktur-organisasi.js') }}"></script>
</body>
</html>