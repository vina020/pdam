<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Tagihan - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cek-tagihan.css') }}">
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
                <li class="active">Cek Tagihan</li>
            </ul>
        </div>
    </div>

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
                    <i class="fas fa-file-invoice"></i>
                    <span>Cek Tagihan Online</span>
                </div>
                <h1 class="hero-title-modern">Cek Tagihan Air Anda</h1>
                <p class="hero-subtitle-modern">
                    Cek tagihan air bulanan Anda dengan mudah dan cepat
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
<section class="layanan-content-modern">
    <div class="container">
        
        <!-- Search Section (Always Visible) -->
        <div class="search-section">
            <div class="search-card">
                <div class="search-header">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h2>Cari Tagihan Anda</h2>
                        <p>Masukkan nomor pelanggan untuk melihat riwayat tagihan dan status pembayaran</p>
                    </div>
                </div>
                
                <form id="cekTagihanForm" class="search-form">
    @csrf
    <div class="form-group">
        <label class="form-label">
            <i class="fas fa-search"></i>
            <span>Cari Berdasarkan</span>
        </label>
        <select id="search_type" name="search_type" class="form-control" style="margin-bottom: 15px;">
            <option value="no_pelanggan">Nomor Pelanggan</option>
            <option value="nosambungan">Nomor Sambungan</option>
            <option value="nama">Nama Pelanggan</option>
        </select>
        <small id="search_example" class="form-text text-muted" style="margin-bottom: 10px;">
            Pilih tipe pencarian terlebih dahulu
        </small>
        <input
            type="text"
            id="search_value"
            name="search_value"
            class="form-control"
            placeholder="Masukkan data pencarian....."
            required
        >
        <small class="form-hint">
            <i class="fas fa-info-circle"></i>
            Nomor pelanggan dapat ditemukan pada struk pembayaran
        </small>
    </div>
    
    <button type="submit" class="btn-search">
        <i class="fas fa-search"></i>
        <span>Cek Tagihan</span>
    </button>
    
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="loading-indicator" style="display: none;">
        <div class="loading-spinner-custom"></div>
        <p>Memuat data tagihan...</p>
    </div>

    <!-- Error Message -->
    <div id="errorMessage" class="error-box" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i> 
        <span id="errorText"></span>
    </div>
</form>
            </div>
        </div>

        <!-- Result Divider -->
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
                <i class="fas fa-file-invoice"></i>
            </div>
            <h3>Belum Ada Data</h3>
            <p>Masukkan nomor pelanggan untuk melihat tagihan</p>
        </div>

        <!-- Error State -->
        <div id="errorState" class="state-content" style="display: none;">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>Data Tidak Ditemukan</h3>
            <p id="errorMessageState"></p>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="results-content" style="display: none;">
            
            <!-- Result Header -->
            <div class="result-header">
                <div class="header-left">
                    <div class="header-badge">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3>Informasi Tagihan</h3>
                        <div class="header-meta">
                            <span id="meta-pelanggan"><i class="fas fa-user"></i> <span id="info-no-pelanggan-meta"></span></span>
                            <span><i class="fas fa-calendar"></i> <span id="current-date"></span></span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button onclick="location.reload()" class="btn-icon" title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button onclick="printInvoice()" class="btn-icon" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="label">No. Pelanggan</span>
                            <span class="value" id="info-no-pelanggan"></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Nama Lengkap</span>
                            <span class="value" id="info-nama"></span>
                        </div>
                        <div class="info-item full-width">
                            <span class="label">Alamat</span>
                            <span class="value" id="info-alamat"></span>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-money-bill-wave"></i> Ringkasan Tagihan</h4>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="label">Total Belum Dibayar</span>
                            <span class="value" id="total-belum-bayar" style="color: #dc3545; font-size: 1.5rem;"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tagihan List -->
            <div class="invoice-section" style="padding: 0 40px 30px;">
                <h4><i class="fas fa-list-ul"></i> Rincian Tagihan</h4>
                <div id="tagihanList">
                    <!-- Items will be inserted here -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="invoice-actions" style="padding: 0 40px 40px;">
                <button onclick="printInvoice()" class="btn-action btn-print">
                    <i class="fas fa-print"></i>
                    Cetak Invoice
                </button>
                <button onclick="downloadPDF()" class="btn-action btn-download">
                    <i class="fas fa-download"></i>
                    Download PDF
                </button>
            </div>

        </div>

    </div>
</section>

    <!-- Footer -->
    @include('component.footer')

    <!-- Modal Detail -->
    <div class="modal" id="detailModal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Tagihan</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cek-tagihan.js') }}"></script>
    <script>
        function updateExample() {
            const select = document.getElementById('search_type');
            const exampleText = document.getElementById('search_example');
            
            const examples = {
                'no_pelanggan': 'Contoh: 100000',
                'nosambungan': 'Contoh: K2-00158',
                'nama': 'Contoh: Budi Santoso'
            };
            
            exampleText.textContent = examples[select.value] || 'Pilih tipe pencarian terlebih dahulu';
        }
</script>
</body>
</html>