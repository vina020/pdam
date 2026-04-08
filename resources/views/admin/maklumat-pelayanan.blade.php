<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Maklumat Pelayanan - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/berita.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/maklumat-pelayanan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
                <div class="header-left">
                    <h1><i class="fas fa-shield-alt"></i> Kelola Maklumat Pelayanan</h1>
                    <p>Kelola komitmen dan standar pelayanan PDAM</p>
                </div>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Maklumat
                </button>
            </div>

            <!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">
        <i class="fas fa-list"></i>
        </div>
        <div>
            <span class="stat-label">Total Maklumat</span>
            <span class="stat-value" id="totalItems">0</span>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">
        <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <span class="stat-label">Aktif</span>
            <span class="stat-value" id="totalAktif">0</span>
        </div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon">
        <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
            <span class="stat-label">Standar Pelayanan</span>
            <span class="stat-value" id="totalStandar">0</span>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">
        <i class="fas fa-tint"></i>
        </div>
        <div>
            <span class="stat-label">Kualitas Air</span>
            <span class="stat-value" id="totalKualitas">0</span>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">
        <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <span class="stat-label">Hak Pelanggan</span>
            <span class="stat-value" id="totalHak">0</span>
        </div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon">
        <i class="fas fa-clipboard-list"></i>
        </div>
        <div>
            <span class="stat-label">Kewajiban</span>
            <span class="stat-value" id="totalKewajiban">0</span>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">
        <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <span class="stat-label">Sanksi</span>
            <span class="stat-value" id="totalSanksi">0</span>
        </div>
    </div>
    <div class="stat-card gray">
        <div class="stat-icon">
        <i class="fas fa-headset"></i>
        </div>
        <div>
            <span class="stat-label">Pengaduan</span>
            <span class="stat-value" id="totalPengaduan">0</span>
        </div>
    </div>
</div>

            <!-- Table Section -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-list"></i>
                        <h3>Daftar Maklumat Pelayanan</h3>
                    </div>
                    <div class="table-actions">
                        <div class="filter-box">
                            <i class="fas fa-filter"></i>
                            <select id="kategoriFilter">
                                <option value="all">Semua Kategori</option>
                                <option value="standar_pelayanan">Standar Pelayanan</option>
                                <option value="kualitas_air">Kualitas Air</option>
                                <option value="hak_pelanggan">Hak Pelanggan</option>
                                <option value="kewajiban_pelanggan">Kewajiban Pelanggan</option>
                                <option value="sanksi">Sanksi</option>
                                <option value="pengaduan">Pengaduan</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Cari item...">
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
                                <th width="150">Kategori</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th width="80">Icon</th>
                                <th width="80">Urutan</th>
                                <th width="100">Status</th>
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
                    <h3>Belum Ada Data</h3>
                    <p>Klik tombol "Tambah Item" untuk membuat maklumat pelayanan baru</p>
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

    <!-- Modal Add/Edit -->
    <div class="modal-overlay" id="formModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-plus"></i> Tambah Maklumat</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="maklumatForm">
                    <input type="hidden" id="itemId">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kategori">
                                <i class="fas fa-tag"></i>
                                Kategori <span class="required">*</span>
                            </label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="standar_pelayanan">Standar Pelayanan</option>
                                <option value="kualitas_air">Kualitas Air</option>
                                <option value="hak_pelanggan">Hak Pelanggan</option>
                                <option value="kewajiban_pelanggan">Kewajiban Pelanggan</option>
                                <option value="sanksi">Sanksi</option>
                                <option value="pengaduan">Pengaduan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="urutan">
                                <i class="fas fa-sort-numeric-up"></i>
                                Urutan
                            </label>
                            <input type="number" id="urutan" name="urutan" min="0" value="0" placeholder="0">
                        </div>

                        <div class="form-group full-width">
                            <label for="judul">
                                <i class="fas fa-heading"></i>
                                Judul <span class="required">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required placeholder="Masukkan judul">
                        </div>

                        <div class="form-group full-width">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i>
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="icon">
                                <i class="fas fa-icons"></i>
                                Icon (Font Awesome)
                            </label>
                            <input type="text" id="icon" name="icon" placeholder="fas fa-check-circle">
                            <small>Contoh: fas fa-check-circle</small>
                        </div>

                        <div class="form-group">
                            <label for="color">
                                <i class="fas fa-palette"></i>
                                Warna
                            </label>
                            <select id="color" name="color">
                                <option value="">Default</option>
                                <option value="blue">Biru</option>
                                <option value="cyan">Cyan</option>
                                <option value="green">Hijau</option>
                                <option value="orange">Orange</option>
                                <option value="red">Merah</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label class="checkbox-label">
                                <input type="checkbox" id="aktif" name="aktif" checked>
                                <span>Aktifkan Maklumat ini</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal()">
                            <i class="fas fa-times"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/maklumat-pelayanan.js') }}"></script>
</body>
</html>