<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Informasi Sambungan - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/informasi-sambungan.css') }}">
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
                <li><a href="{{ route('layanan') }}">Layanan</a></li>
                <li class="active">Informasi Sambungan</li>
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
                    <i class="fas fa-info-circle"></i>
                    <span>Tracking Sambungan</span>
                </div>
                <h1 class="hero-title-modern">Informasi Permohonan Sambungan</h1>
                <p class="hero-subtitle-modern">
                    Lacak status permohonan sambungan air baru Anda secara real-time
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            <!-- 2 Column Layout -->
            <div class="sambungan-layout">
                
                <!-- LEFT COLUMN - Info Cards -->
                <div class="left-column">
                    
                    <!-- Status Info -->
                    <div class="info-card">
                        <h4><i class="fas fa-tasks"></i> Status Permohonan</h4>
                        <div class="status-list">
                            <div class="status-item">
                                <div class="status-icon pending">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Pending</strong>
                                    <span>Menunggu verifikasi</span>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon verifikasi">
                                    <i class="fas fa-file-circle-check"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Diverifikasi</strong>
                                    <span>Sudah diverifikasi</span>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon survei">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Survey</strong>
                                    <span>Proses survey lapangan</span>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon approved">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Disetujui</strong>
                                    <span>Permohonan disetujui</span>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon selesai">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Selesai</strong>
                                    <span>Sambungan aktif</span>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon ditolak">
                                    <i class="fas fa-xmark"></i>
                                </div>
                                <div class="status-info">
                                    <strong>Ditolak</strong>
                                    <span>Pengajuan sambungan ditolak</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="info-card">
                        <h4><i class="fas fa-question-circle"></i> Cara Cek Status</h4>
                        <ol>
                            <li>Masukkan nomor registrasi atau NIK</li>
                            <li>Klik tombol "Cek Status"</li>
                            <li>Informasi detail akan ditampilkan</li>
                            <li>Anda dapat melihat progress permohonan</li>
                        </ol>
                    </div>

                    <!-- Contact Card -->
                    <div class="contact-card">
                        <h4><i class="fas fa-headset"></i> Butuh Bantuan?</h4>
                        <p>Hubungi customer service kami</p>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(0351) 123-4567</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>cs@pdammagetan.com</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN - Search & Results -->
                <div class="right-column">
                    
                    <!-- Unified Card -->
                    <div class="unified-card">
                        
                        <!-- Search Section -->
                        <div class="search-section">
                            <div class="search-header">
                                <i class="fas fa-search"></i>
                                <h2>Cek Status Sambungan</h2>
                                <p>Masukkan nomor registrasi atau NIK Anda</p>
                            </div>

                            <form id="sambunganForm" class="search-form">
                                @csrf
                                <div class="search-input-group">
                                    <div class="form-group">
                                        <label for="search_type">
                                            <i class="fas fa-filter"></i>
                                            Cari Berdasarkan
                                        </label>
                                        <select id="search_type" name="search_type" class="form-select">
                                            <option value="no_registrasi">Nomor Registrasi</option>
                                            <option value="nik">NIK</option>
                                        </select>
                                    </div>

                                    <div class="form-group flex-grow">
                                        <label for="search_value">
                                            <i class="fas fa-keyboard"></i>
                                            <span id="searchLabel">Nomor Registrasi</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="search_value" 
                                            name="search_value" 
                                            placeholder="Masukkan nomor registrasi..."
                                            required
                                            autocomplete="off"
                                        >
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn-search-full">
                                    <i class="fas fa-search"></i>
                                    <span>Cek Status</span>
                                </button>
                            </form>

                            <div class="search-info">
                                <i class="fas fa-info-circle"></i>
                                <p>Nomor registrasi dapat ditemukan pada bukti permohonan sambungan Anda</p>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div id="resultDivider" class="result-divider" style="display: none;"></div>

                        <!-- Loading State -->
                        <div id="loadingState" class="state-content" style="display: none;">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <h3>Mencari Data...</h3>
                            <p>Mohon tunggu sebentar</p>
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="state-content">
                            <div class="empty-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Belum Ada Pencarian</h3>
                            <p>Masukkan nomor registrasi atau NIK untuk memulai pencarian</p>
                        </div>

                        <!-- Error State -->
                        <div id="errorState" class="state-content" style="display: none;">
                            <div class="error-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>Data Tidak Ditemukan</h3>
                            <p id="errorMessage"></p>
                        </div>

                        <!-- Results Section -->
                        <div id="resultsSection" class="results-content" style="display: none;">
                            
                            <!-- Application Info -->
                            <div class="application-header">
                                <div class="app-number">
                                    <i class="fas fa-file-alt"></i>
                                    <div>
                                        <span class="label">No. Registrasi</span>
                                        <strong id="app-no-reg"></strong>
                                    </div>
                                </div>
                                <div class="app-date">
                                    <i class="fas fa-calendar"></i>
                                    <div>
                                        <span class="label">Tanggal Pengajuan</span>
                                        <strong id="app-date"></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Applicant Info -->
                            <div class="applicant-info">
                                <h4><i class="fas fa-user"></i> Data Pemohon</h4>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="label">Nama</span>
                                        <strong id="info-nama"></strong>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">NIK</span>
                                        <strong id="info-nik"></strong>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">No. Telepon</span>
                                        <strong id="info-telp"></strong>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Email</span>
                                        <strong id="info-email"></strong>
                                    </div>
                                    <div class="info-item full-width">
                                        <span class="label">Alamat Pemasangan</span>
                                        <strong id="info-alamat"></strong>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Jenis Sambungan</span>
                                        <strong id="info-jenis"></strong>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Golongan</span>
                                        <strong id="info-golongan"></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Progress -->
                            <div class="status-progress">
                                <h4><i class="fas fa-tasks"></i> Progress Permohonan</h4>
                                <div class="progress-timeline" id="progressTimeline">
                                    <!-- Timeline will be inserted here -->
                                </div>
                            </div>

                            <!-- Current Status Card -->
                            <div class="current-status-card">
                                <div class="status-header">
                                    <h4>Status Saat Ini</h4>
                                    <span class="status-badge" id="current-status"></span>
                                </div>
                                <p id="status-description"></p>
                                <div class="status-meta" id="status-meta">
                                    <!-- Meta info will be inserted here -->
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div id="documentsSection" class="documents-section" style="display: none;">
                                <h4><i class="fas fa-file-download"></i> Dokumen</h4>
                                <div id="documentList" class="document-list">
                                    <!-- Documents will be inserted here -->
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button onclick="printInfo()" class="btn-action btn-print">
                                    <i class="fas fa-print"></i>
                                    Cetak Info
                                </button>
                                <button onclick="refreshStatus()" class="btn-action btn-refresh">
                                    <i class="fas fa-sync-alt"></i>
                                    Refresh Status
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    @include('component.footer')

    <script src="{{ asset('js/informasi-sambungan.js') }}"></script>
</body>
</html>