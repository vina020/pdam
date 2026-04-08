<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pengaduan - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/pengaduan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
                <div class="header-left">
                    <h1><i class="fas fa-headset"></i> Kelola Pengaduan</h1>
                    <p>Kelola dan tanggapi pengaduan pelanggan</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card blue">
                    <i class="fas fa-inbox"></i>
                    <div>
                        <span class="stat-label">Total Pengaduan</span>
                        <span class="stat-value" id="totalPengaduan">0</span>
                    </div>
                </div>
                <div class="stat-card orange">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span class="stat-label">Pending</span>
                        <span class="stat-value" id="pendingPengaduan">0</span>
                    </div>
                </div>
                <div class="stat-card purple">
                    <i class="fas fa-spinner"></i>
                    <div>
                        <span class="stat-label">Proses</span>
                        <span class="stat-value" id="prosesPengaduan">0</span>
                    </div>
                </div>
                <div class="stat-card green">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <span class="stat-label">Selesai</span>
                        <span class="stat-value" id="selesaiPengaduan">0</span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-list"></i>
                        <h3>Daftar Pengaduan</h3>
                    </div>
                    <div class="table-actions">
                        <select id="filterStatus" class="filter-select" onchange="loadPengaduan()">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Cari pengaduan...">
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat data...</p>
                </div>

                <!-- Table -->
                <div id="tableContainer" style="display: none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="150">No. Pengaduan</th>
                                <th width="120">No. Pelanggan</th>
                                <th>Nama</th>
                                <th width="150">Jenis</th>
                                <th width="120">Status</th>
                                <th width="150">Tanggal</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data akan diisi via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum Ada Pengaduan</h3>
                    <p>Belum ada pengaduan yang masuk</p>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="pagination-container" style="display: none;">
                    <div class="pagination-info">
                        Menampilkan <strong id="showingFrom">0</strong> - <strong id="showingTo">0</strong> dari <strong id="totalData">0</strong> data
                    </div>
                    <div class="pagination-buttons" id="paginationButtons">
                        <!-- Buttons akan diisi via JS -->
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Detail & Tanggapi -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-box large">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Pengaduan</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Content akan diisi via JS -->
                </div>

                <!-- Form Tanggapan -->
                <div class="tanggapan-section">
                    <h4><i class="fas fa-reply"></i> Tanggapan</h4>
                    <form id="tanggapanForm">
                        <input type="hidden" id="pengaduanId">
                        
                        <div class="form-group">
                            <label for="status">
                                <i class="fas fa-info-circle"></i>
                                Update Status
                            </label>
                            <select id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggapan">
                                <i class="fas fa-comment"></i>
                                Tanggapan
                            </label>
                            <textarea id="tanggapan" name="tanggapan" rows="4" placeholder="Tulis tanggapan untuk pengaduan ini..."></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeModal()">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Simpan Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/pengaduan.js') }}"></script>
</body>
</html>