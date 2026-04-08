<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pasang Baru - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/pasang-baru.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')
    <div class="main-content">
    <div class="admin-container">
        
    <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <h1><i class="fas fa-water"></i> Kelola Pengajuan Pasang Baru</h1>
                <p>Kelola dan validasi pengajuan pemasangan baru dari pelanggan</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card orange">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 id="statPending">0</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="fas fa-file-circle-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="statVerifikasi">0</h3>
                    <p>Dalam Verifikasi</p>
                </div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 id="statSurvei">0</h3>
                    <p>Survei Lapangan</p>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="statApproved">0</h3>
                    <p>Disetujui</p>
                </div>
            </div>
            <div class="stat-card red">
    <div class="stat-icon">
        <i class="fas fa-xmark"></i>
    </div>
    <div class="stat-info">
        <h3 id="statDitolak">0</h3>
        <p>Ditolak</p>
    </div>
</div>
            <div class="stat-card teal">
    <div class="stat-icon">
        <i class="fas fa-check-double"></i>
    </div>
    <div class="stat-info">
        <h3 id="statSelesai">0</h3>
        <p>Selesai</p>
    </div>
</div>
        </div>

        <!-- Filter & Search -->
        <div class="table-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama, NIK, atau nomor registrasi...">
            </div>
            <div class="filter-group">
                <select id="filterStatus" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="verifikasi">Verifikasi</option>
                    <option value="survei">Survei</option>
                    <option value="approved">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="selesai">Selesai</option>
                </select>
                <select id="filterKecamatan" class="filter-select">
                    <option value="">Semua Kecamatan</option>
                </select>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memuat data...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fas fa-inbox"></i>
            <h3>Tidak ada data</h3>
            <p>Belum ada pengajuan pasang baru</p>
        </div>

        <!-- Table Container -->
        <div id="tableContainer" class="table-container" style="display: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>No. Registrasi</th>
                        <th>Data Pemohon</th>
                        <th>Alamat Pemasangan</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="pagination-container" style="display: none;">
            <div class="pagination-info">
                Menampilkan <span id="showingFrom">0</span> - <span id="showingTo">0</span> dari <span id="totalData">0</span> data
            </div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fas fa-info-circle"></i> Detail Pengajuan</h2>
                <button class="close-btn" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Update Status</h2>
                <button class="close-btn" onclick="closeStatusModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="updateId">
                    <div class="form-group">
                        <label>Status Baru</label>
                        <select id="newStatus" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="verifikasi">Verifikasi</option>
                            <option value="survei">Survei Lapangan</option>
                            <option value="approved">Setujui</option>
                            <option value="ditolak">Tolak</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="catatan" class="form-control" rows="4" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeStatusModal()">Batal</button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="{{ asset('js/admin/pasang-baru.js') }}"></script>
</body>
</html>