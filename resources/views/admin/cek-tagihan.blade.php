<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Tagihan - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin-cek-tagihan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">

        <!-- Input Meteran Section (TAMBAHKAN INI) -->
<div class="input-meteran-section">
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="header-left">
                <i class="fas fa-plus-circle"></i>
                <div>
                    <h2>Input Tagihan Bulanan</h2>
                    <p>Input meteran pelanggan untuk periode bulan ini</p>
                </div>
            </div>
            <button class="btn-toggle-form" onclick="toggleInputForm()">
                <i class="fas fa-chevron-down" id="toggleIcon"></i>
            </button>
        </div>
        
        <div class="card-body-modern" id="inputFormBody">
            <form id="inputMeteranForm">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Pilih Pelanggan</label>
                        <select name="no_pelanggan" id="no_pelanggan" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <!-- Akan diisi via JavaScript atau backend -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Periode</label>
                        <input type="month" name="periode" id="periode" class="form-control" required value="{{ date('Y-m') }}">
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="info-box" id="infoPelanggan" style="display:none;">
                    <h4>Informasi Pelanggan</h4>
                    <div class="info-grid">
                        <div><strong>Nama:</strong> <span id="display-nama">-</span></div>
                        <div><strong>Alamat:</strong> <span id="display-alamat">-</span></div>
                        <div><strong>Golongan:</strong> <span id="display-golongan">-</span></div>
                    </div>
                </div>

                <!-- Input Meteran -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-tachometer-alt"></i> Meter Awal (m³)</label>
                        <input type="number" name="meter_awal" id="meter_awal" class="form-control" placeholder="0" required>
                        <small>Angka meter bulan lalu</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-tachometer-alt"></i> Meter Akhir (m³)</label>
                        <input type="number" name="meter_akhir" id="meter_akhir" class="form-control" placeholder="0" required>
                        <small>Angka meter bulan ini</small>
                    </div>
                </div>

                <!-- Preview Box -->
                <div class="preview-box" id="previewBox" style="display:none;">
                    <h4><i class="fas fa-calculator"></i> Preview Tagihan</h4>
                    <table class="preview-table">
                        <tr>
                            <td>Pemakaian</td>
                            <td><strong><span id="preview-pemakaian">0</span> m³</strong></td>
                        </tr>
                        <tr>
                            <td>Tarif Rata-rata</td>
                            <td>Rp <span id="preview-tarif">0</span>/m³</td>
                        </tr>
                        <tr>
                            <td>Biaya Pemakaian</td>
                            <td>Rp <span id="preview-biaya">0</span></td>
                        </tr>
                        <tr>
                            <td>Biaya Admin</td>
                            <td>Rp <span id="preview-admin">2,500</span></td>
                        </tr>
                        <tr>
                            <td>Biaya Pemeliharaan</td>
                            <td>Rp <span id="preview-pemeliharaan">5,000</span></td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>TOTAL TAGIHAN</strong></td>
                            <td><strong>Rp <span id="preview-total">0</span></strong></td>
                        </tr>
                    </table>
                    <div id="detail-tarif" class="detail-tarif"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-save"></i>
                        Simpan Tagihan
                    </button>
                    <button type="reset" class="btn-secondary-modern" onclick="resetForm()">
                        <i class="fas fa-redo"></i>
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
            
            <!-- Search Hero Section -->
            <div class="search-hero">
                <div class="search-hero-content">
                    <div class="hero-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h1>Cek Tagihan Pelanggan</h1>
                    <p>Cari tagihan berdasarkan nomor pelanggan atau nama</p>
                    
                    <form id="adminCekTagihanForm" class="search-form-hero">
                        @csrf
                        <div class="search-input-wrapper">
                            <select id="search_type" name="search_type" class="search-type-select">
                                <option value="no_pelanggan">No. Pelanggan</option>
                                <option value="nama">Nama</option>
                            </select>
                            <input 
                                type="text" 
                                id="search_value" 
                                name="search_value" 
                                placeholder="Masukkan nomor pelanggan atau nama..."
                                required
                            >
                            <button type="submit" class="btn-search-hero">
                                <i class="fas fa-search"></i>
                                Cari
                            </button>
                        </div>
                    </form>
                    
                    <div class="search-stats">
                        <div class="stat-badge">
                            <i class="fas fa-users"></i>
                            <span>{{ number_format($stats['total_pelanggan'], 0, ',', '.') }} Pelanggan</span>
                        </div>
                        <div class="stat-badge">
                            <i class="fas fa-file-invoice"></i>
                            <span>{{ number_format($stats['tagihan_aktif'], 0, ',', '.') }} Tagihan Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="state-section" style="display: none;">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <h3>Mencari data tagihan...</h3>
            </div>

            <!-- Error State -->
            <div id="errorState" class="state-section" style="display: none;">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h3>Data tidak ditemukan</h3>
                <p id="errorMessage">Silakan coba dengan nomor pelanggan atau nama lain</p>
                <button onclick="resetSearch()" class="btn-retry">
                    <i class="fas fa-redo"></i>
                    Cari Lagi
                </button>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="results-section" style="display: none;">
                
                <!-- Customer Info Card -->
                <div class="customer-card">
                    <div class="customer-card-header">
                        <div class="customer-avatar-large">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="customer-info-main">
                            <h2 id="customer-nama">-</h2>
                            <div class="customer-meta-row">
                                <span class="meta-item">
                                    <i class="fas fa-id-card"></i>
                                    <strong id="customer-no">-</strong>
                                </span>
                                <span class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="customer-alamat">-</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="summary-row">
                        <div class="summary-item blue">
                            <i class="fas fa-receipt"></i>
                            <div>
                                <span class="summary-label">Total Tagihan</span>
                                <span class="summary-number" id="total-tagihan">0</span>
                            </div>
                        </div>
                        <div class="summary-item orange">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span class="summary-label">Belum Dibayar</span>
                                <span class="summary-number" id="total-belum-bayar">Rp 0</span>
                            </div>
                        </div>
                        <div class="summary-item green">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <span class="summary-label">Sudah Dibayar</span>
                                <span class="summary-number" id="total-sudah-bayar">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-bar">
                    <div class="filters-left">
                        <h3><i class="fas fa-list"></i> Daftar Tagihan</h3>
                        <span class="count-badge" id="result-count">0 tagihan</span>
                    </div>
                    <div class="filters-right">
                        <select id="filterStatus" class="filter-select" onchange="filterTagihan()">
                            <option value="all">Semua Status</option>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="sudah_bayar">Sudah Dibayar</option>
                            <option value="terlambat">Terlambat</option>
                        </select>
                        <select id="sortBy" class="filter-select" onchange="filterTagihan()">
                            <option value="periode_desc">Terbaru</option>
                            <option value="periode_asc">Terlama</option>
                            <option value="total_desc">Nilai Tertinggi</option>
                            <option value="total_asc">Nilai Terendah</option>
                        </select>
                    </div>
                </div>

                <!-- Tagihan Cards Grid -->
                <div class="tagihan-grid" id="tagihanList">
                    <!-- Cards akan muncul di sini via JS -->
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal-overlay" id="detailModal" onclick="closeModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header-simple">
                <h3><i class="fas fa-file-invoice"></i> Detail Tagihan</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-simple" id="modalBody">
                <!-- Content via JS -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/admin-cek-tagihan.js') }}"></script>
</body>
</html>