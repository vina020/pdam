<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pengaduan - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/cek-status-pengaduan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Sidebar Component -->
    @include('component.sidebar')

    <!-- Breadcrumb -->
    <div class="breadcrumbs">
        <div class="container">
            <ul>
                <li><a href="{{ route('homepage') }}">Beranda</a></li>
                <li><a href="{{ route('layanan') }}">Layanan</a></li>
                <li class="active">Cek Status Pengaduan</li>
            </ul>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="status-hero">
        <div class="hero-background">
            <div class="hero-shape shape-1"></div>
            <div class="hero-shape shape-2"></div>
            <div class="hero-shape shape-3"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-search"></i>
                    <span>Lacak Pengaduan</span>
                </div>
                <h1 class="hero-title">Cek Status Pengaduan</h1>
                <p class="hero-subtitle">Pantau perkembangan pengaduan Anda secara real-time</p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-card">
                <div class="search-header">
                    <div class="search-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h2>Lacak Pengaduan Anda</h2>
                        <p>Masukkan nomor pengaduan atau nomor pelanggan untuk melihat status terkini</p>
                    </div>
                </div>

                <form id="pengaduanForm" class="search-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-filter"></i> Cari Berdasarkan
                        </label>
                        <select id="search_type" class="form-control">
                            <option value="nomor_pengaduan">Nomor Pengaduan</option>
                            <option value="no_pelanggan">Nomor Pelanggan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" id="searchLabel">
                            <i class="fas fa-hashtag"></i> Nomor Pengaduan
                        </label>
                        <input 
                            type="text" 
                            id="search_value" 
                            class="form-control" 
                            placeholder="Masukkan nomor pengaduan..."
                            required
                        >
                        <small class="form-hint">
                            <i class="fas fa-info-circle"></i> 
                            Nomor pengaduan dikirimkan via SMS/WhatsApp setelah pengaduan berhasil dibuat
                        </small>
                    </div>

                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i>
                        <span>Cek Status</span>
                    </button>
                </form>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="state-card" style="display: none;">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <h3>Mencari Data Pengaduan...</h3>
                <p>Mohon tunggu sebentar</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="state-card">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Belum Ada Pencarian</h3>
                <p>Masukkan nomor pengaduan atau nomor pelanggan untuk melihat status</p>
            </div>

            <!-- Error State -->
            <div id="errorState" class="state-card error" style="display: none;">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Data Tidak Ditemukan</h3>
                <p id="errorMessage">Nomor yang Anda masukkan tidak ditemukan dalam sistem kami</p>
                <button onclick="location.reload()" class="btn-retry">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
            </div>

            <!-- Divider -->
            <div id="resultDivider" class="result-divider" style="display: none;">
                <span>Hasil Pencarian</span>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="results-section" style="display: none;">
                <!-- Pengaduan Header -->
                <div class="result-header">
                    <div class="header-left">
                        <div class="header-badge">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <h3>Pengaduan</h3>
                            <div class="header-meta">
                                <span id="pengaduan-no-reg"><i class="fas fa-hashtag"></i> -</span>
                                <span id="pengaduan-date"><i class="fas fa-calendar"></i> -</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button onclick="refreshStatus()" class="btn-icon" title="Refresh Status">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button onclick="printInfo()" class="btn-icon" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="fas fa-user"></i> Informasi Pelapor</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Nama Lengkap</span>
                                <span class="value" id="info-nama">-</span>
                            </div>
                            <div class="info-item">
                                <span class="label">No. Pelanggan</span>
                                <span class="value" id="info-pelanggan">-</span>
                            </div>
                            <div class="info-item">
                                <span class="label">No. WhatsApp</span>
                                <span class="value" id="info-whatsapp">-</span>
                            </div>
                            <div class="info-item full-width">
                                <span class="label">Alamat</span>
                                <span class="value" id="info-alamat">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-clipboard-list"></i> Detail Pengaduan</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="label">Jenis Pengaduan</span>
                                <span class="value" id="info-jenis">-</span>
                            </div>
                            <div class="info-item full-width">
                                <span class="label">Informasi Pengaduan</span>
                                <span class="value" id="info-detail" style="white-space: pre-wrap;">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Timeline -->
                <div class="progress-card">
                    <h4><i class="fas fa-stream"></i> Progress Penanganan</h4>
                    <div id="progressTimeline" class="timeline">
                        <!-- Timeline items will be inserted here -->
                    </div>
                </div>

                <!-- Current Status -->
                <div class="status-card">
                    <div class="status-header">
                        <h4><i class="fas fa-info-circle"></i> Status Terkini</h4>
                        <span id="current-status" class="status-badge">-</span>
                    </div>
                    <p id="status-description" class="status-desc">-</p>
                    
                    <!-- Tanggapan -->
                    <div id="tanggapanSection" class="tanggapan-section">
                        <div class="tanggapan-header">
                            <i class="fas fa-comment-dots"></i>
                            <strong>Tanggapan Petugas:</strong>
                        </div>
                        <p id="tanggapan-text">-</p>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="help-content">
                        <h4>Butuh Bantuan?</h4>
                        <p>Jika ada pertanyaan mengenai pengaduan Anda, silakan hubungi Customer Service kami:</p>
                        <div class="contact-options">
                            <a href="tel:0351123456" class="contact-btn">
                                <i class="fas fa-phone"></i>
                                <span>0351-123456</span>
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" class="contact-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>Chat WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('component.footer')
    <script src="{{ asset('js/cek-status-pengaduan.js') }}"></script>
</body>
</html>