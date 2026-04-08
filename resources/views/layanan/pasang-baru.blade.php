<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasang Baru - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar Component -->
    @include('component.sidebar')
    <!-- Breadcrumb -->
<nav class="breadcrumbs">
    <div class="container">
        <ul>
            <li>
                <a href="{{ route('homepage') }}">Beranda</a>
            </li>
            <li>
                <a href="{{ route('layanan') }}">Layanan</a>
            </li>
            <li class="active">Pasang Baru</li>
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
                    <i class="fas fa-faucet"></i>
                    <span>Layanan Unggulan</span>
                </div>
                <h1 class="hero-title-modern" data-aos="fade-up">
                    Pasang Sambungan Air Baru
                </h1>
                <p class="hero-subtitle-modern" data-aos="fade-up" data-aos-delay="100">
                    Proses mudah, cepat, dan transparan untuk pemasangan sambungan air bersih di rumah Anda
                </p>
                <div class="hero-stats" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>7-10 Hari</strong>
                            <span>Waktu Proses</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>100% Digital</strong>
                            <span>Pendaftaran Online</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>1000+</strong>
                            <span>Pelanggan Baru/Tahun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            <!-- Feature Highlight -->
            <div class="feature-highlight" data-aos="fade-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Daftar Online</h3>
                    <p>Tidak perlu datang ke kantor, cukup daftar dari rumah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Data Anda dijamin aman dan terenkripsi</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Dukungan 24/7</h3>
                    <p>Tim customer service siap membantu Anda</p>
                </div>
            </div>

            <!-- Main Content Wrapper -->
            <div class="content-grid-modern">
                <!-- Persyaratan Section -->
                <div class="section-modern" data-aos="fade-right">
                    <div class="section-header-modern">
                        <div class="section-icon-badge">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div>
                            <h2>Persyaratan Dokumen</h2>
                            <p>Siapkan dokumen berikut sebelum mendaftar</p>
                        </div>
                    </div>

                    <div class="requirement-grid">
                        <div class="requirement-card" data-aos="zoom-in" data-aos-delay="100">
                            <div class="req-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="req-content-modern">
                                <h4>KTP Pemohon</h4>
                                <p>Fotokopi KTP yang masih berlaku sesuai domisili tempat pemasangan</p>
                                <span class="req-badge">Wajib</span>
                            </div>
                        </div>

                        <div class="requirement-card" data-aos="zoom-in" data-aos-delay="200">
                            <div class="req-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="req-content-modern">
                                <h4>Kartu Keluarga</h4>
                                <p>Fotokopi Kartu Keluarga untuk verifikasi data kependudukan</p>
                                <span class="req-badge">Wajib</span>
                            </div>
                        </div>

                        <div class="requirement-card" data-aos="zoom-in" data-aos-delay="300">
                            <div class="req-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="req-content-modern">
                                <h4>Bukti PBB</h4>
                                <p>Fotokopi bukti pembayaran PBB tahun terakhir</p>
                                <span class="req-badge">Wajib</span>
                            </div>
                        </div>

                        <div class="requirement-card" data-aos="zoom-in" data-aos-delay="400">
                            <div class="req-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="req-content-modern">
                                <h4>Rekening Listrik</h4>
                                <p>Fotokopi rekening listrik bulan terakhir sebagai bukti domisili</p>
                                <span class="req-badge">Wajib</span>
                            </div>
                        </div>

                        <div class="requirement-card" data-aos="zoom-in" data-aos-delay="500">
                            <div class="req-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="req-content-modern">
                                <h4>Surat Domisili</h4>
                                <p>Surat keterangan domisili dari RT/RW atau kelurahan setempat</p>
                                <span class="req-badge">Wajib</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prosedur Section -->
                <div class="section-modern" data-aos="fade-left">
                    <div class="section-header-modern">
                        <div class="section-icon-badge">
                            <i class="fas fa-route"></i>
                        </div>
                        <div>
                            <h2>Alur Pendaftaran</h2>
                            <p>6 langkah mudah untuk sambungan air baru</p>
                        </div>
                    </div>

                    <div class="timeline-modern">
                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="100">
                            <div class="timeline-marker">
                                <span>1</span>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Pendaftaran Online</h4>
                                    <p>Isi formulir pendaftaran online melalui website PDAM Magetan dengan lengkap dan benar</p>
                                    <span class="timeline-duration">5-10 menit</span>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="200">
                            <div class="timeline-marker">
                                <span>2</span>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fa-solid fa-file-circle-check"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Verifikasi Dokumen</h4>
                                    <p>Tim PDAM akan melakukan verifikasi kelengkapan dan keabsahan dokumen yang Anda upload</p>
                                    <span class="timeline-duration">1-2 hari kerja</span>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="300">
                            <div class="timeline-marker">
                                <span>3</span>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Survei Lokasi</h4>
                                    <p>Teknisi PDAM akan datang ke lokasi untuk survei teknis dan membuat RAB (Rencana Anggaran Biaya)</p>
                                    <span class="timeline-duration">1-3 hari kerja</span>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="400">
                            <div class="timeline-marker">
                                <span>4</span>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Persetujuan RAB</h4>
                                    <p>Anda akan menerima RAB dan dapat menyetujui atau berkonsultasi mengenai biaya pemasangan</p>
                                    <span class="timeline-duration">1 hari kerja</span>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="500">
                            <div class="timeline-marker">
                                <span>5</span>
                                <div class="timeline-connector"></div>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Pembayaran</h4>
                                    <p>Lakukan pembayaran biaya pemasangan sesuai RAB yang telah disepakati melalui channel yang tersedia</p>
                                    <span class="timeline-duration">Setelah persetujuan</span>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item-modern" data-aos="fade-up" data-aos-delay="600">
                            <div class="timeline-marker">
                                <span>6</span>
                            </div>
                            <div class="timeline-card">
                                <div class="timeline-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="timeline-content-modern">
                                    <h4>Pemasangan & Aktivasi</h4>
                                    <p>Tim teknisi melakukan pemasangan pipa dan meteran, dilanjutkan aktivasi sambungan air</p>
                                    <span class="timeline-duration">3-7 hari kerja</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Biaya -->
                <div class="section-modern full-width" data-aos="fade-up">
                    <div class="section-header-modern">
                        <div class="section-icon-badge">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h2>Informasi Biaya</h2>
                            <p>Estimasi biaya pemasangan berdasarkan kategori</p>
                        </div>
                    </div>

                    <div class="pricing-cards">
                        <div class="pricing-card" data-aos="flip-left" data-aos-delay="100">
                            <div class="pricing-header">
                                <i class="fas fa-home"></i>
                                <h3>Rumah Tangga</h3>
                            </div>
                            <div class="pricing-amount">
                                <span class="currency">Rp</span>
                                <span class="price">3-5 Juta</span>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="fas fa-check"></i> Diameter pipa 1/2 inch</li>
                                <li><i class="fas fa-check"></i> Meteran air standar</li>
                                <li><i class="fas fa-check"></i> Instalasi dasar</li>
                                <li><i class="fas fa-check"></i> Biaya administrasi</li>
                            </ul>
                        </div>

                        <div class="pricing-card featured" data-aos="flip-left" data-aos-delay="200">
                            <div class="pricing-badge">Populer</div>
                            <div class="pricing-header">
                                <i class="fas fa-store"></i>
                                <h3>Usaha Kecil</h3>
                            </div>
                            <div class="pricing-amount">
                                <span class="currency">Rp</span>
                                <span class="price">5-8 Juta</span>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="fas fa-check"></i> Diameter pipa 3/4 inch</li>
                                <li><i class="fas fa-check"></i> Meteran air standar</li>
                                <li><i class="fas fa-check"></i> Instalasi standar</li>
                                <li><i class="fas fa-check"></i> Biaya administrasi</li>
                            </ul>
                        </div>

                        <div class="pricing-card" data-aos="flip-left" data-aos-delay="300">
                            <div class="pricing-header">
                                <i class="fas fa-building"></i>
                                <h3>Usaha Besar</h3>
                            </div>
                            <div class="pricing-amount">
                                <span class="currency">Rp</span>
                                <span class="price">8-15 Juta</span>
                            </div>
                            <ul class="pricing-features">
                                <li><i class="fas fa-check"></i> Diameter pipa 1 inch+</li>
                                <li><i class="fas fa-check"></i> Meteran air premium</li>
                                <li><i class="fas fa-check"></i> Instalasi kompleks</li>
                                <li><i class="fas fa-check"></i> Biaya administrasi</li>
                            </ul>
                        </div>
                    </div>

                    <div class="pricing-note">
                        <i class="fas fa-info-circle"></i>
                        <p><strong>Catatan:</strong> Biaya dapat bervariasi tergantung jarak dari pipa induk, kondisi medan, dan spesifikasi teknis. Biaya final akan diinformasikan setelah survei lokasi.</p>
                    </div>
                </div>

                <!-- Ketentuan Tambahan -->
                <div class="section-modern full-width" data-aos="fade-up">
                    <div class="section-header-modern">
                        <div class="section-icon-badge">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h2>Ketentuan & Syarat</h2>
                            <p>Hal-hal penting yang perlu diperhatikan</p>
                        </div>
                    </div>

                    <div class="info-cards-grid">
                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="100">
                            <div class="info-icon-modern blue">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4>Jangkauan Lokasi</h4>
                            <p>Lokasi pemasangan harus berada dalam jangkauan jaringan pipa distribusi PDAM Magetan</p>
                        </div>

                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="200">
                            <div class="info-icon-modern green">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                            <h4>Ruang Meteran</h4>
                            <p>Pemohon harus menyediakan tempat yang mudah diakses untuk instalasi meteran air (minimal 50x50 cm)</p>
                        </div>

                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="300">
                            <div class="info-icon-modern orange">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4>Pembayaran</h4>
                            <p>Pembayaran dilakukan setelah survei dan sebelum pemasangan melalui bank transfer atau di kantor PDAM</p>
                        </div>

                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="400">
                            <div class="info-icon-modern purple">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>Waktu Pemasangan</h4>
                            <p>Waktu pemasangan dapat berubah tergantung kondisi cuaca dan ketersediaan material</p>
                        </div>

                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="500">
                            <div class="info-icon-modern red">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4>Kelengkapan Dokumen</h4>
                            <p>Semua dokumen yang diupload harus jelas, terbaca dengan baik, dan sesuai dengan kondisi asli</p>
                        </div>

                        <div class="info-card-modern" data-aos="fade-right" data-aos-delay="600">
                            <div class="info-icon-modern teal">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Garansi Instalasi</h4>
                            <p>Kami memberikan garansi untuk kualitas pemasangan dan peralatan selama 6 bulan pertama</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="cta-modern" data-aos="zoom-in">
                    <div class="cta-content-modern">
                        <div class="cta-icon-large">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h2>Siap Memasang Sambungan Air Baru?</h2>
                        <p>Proses pendaftaran hanya membutuhkan waktu 5-10 menit. Daftar sekarang dan nikmati layanan air bersih berkualitas dari PDAM Magetan</p>
                        
                        <div class="cta-buttons-modern">
                            <a href="{{ route('layanan.form-pendaftaran') }}" class="btn-modern btn-primary-modern">
                                <i class="fas fa-edit"></i>
                                <span>Daftar Sekarang</span>
                            </a>
                            <a href="{{ route('kontak') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-phone-alt"></i>
                                <span>Hubungi Customer Service</span>
                            </a>
                        </div>

                        <div class="cta-help-text">
                            <i class="fas fa-question-circle"></i>
                            <span>Butuh bantuan? Tim kami siap membantu Anda 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('component.footer')

    <script src="{{ asset('js/layanan-detail.js') }}"></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
    </div>
</body>
</html>