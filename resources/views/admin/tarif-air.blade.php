<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Tarif Air - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/tarif-air.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
                <div class="header-left">
                    <h1><i class="fas fa-money-bill-wave"></i> Kelola Tarif Air</h1>
                    <p>Kelola tarif air berdasarkan golongan pelanggan</p>
                </div>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Tarif
                </button>
            </div>

            <!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">
        <i class="fas fa-list"></i>
    </div>
        <div>
            <span class="stat-label">Total Tarif</span>
            <span class="stat-value" id="totalTarif">0</span>
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
        <i class="fas fa-home"></i>
        </div>
        <div>
            <span class="stat-label">Rumah Tangga</span>
            <span class="stat-value" id="totalRumahTangga">0</span>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">
        <i class="fas fa-store"></i>
        </div>
        <div>
            <span class="stat-label">Niaga</span>
            <span class="stat-value" id="totalNiaga">0</span>
        </div>
    </div>
    
    <div class="stat-card purple">
        <div class="stat-icon">
        <i class="fa-solid fa-industry"></i>
        </div>
        <div>
            <span class="stat-label">Industri</span>
            <span class="stat-value" id="totalIndustri">0</span>
        </div>
    </div>
    <div class="stat-card teal">
        <div class="stat-icon">
        <i class="fas fa-building"></i>
        </div>
        <div>
            <span class="stat-label">Instansi</span>
            <span class="stat-value" id="totalInstansi">0</span>
        </div>
    </div>
    <div class="stat-card gray">
        <div class="stat-icon">
        <i class="fa-solid fa-people-group"></i>
        </div>
        <div>
            <span class="stat-label">Sosial</span>
            <span class="stat-value" id="totalSosial">0</span>
        </div>
    </div>
</div>

            <!-- Table Section -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-table"></i>
                        <h3>Daftar Tarif Air</h3>
                    </div>
                    <div class="table-actions">
                        <div class="filter-box">
                            <i class="fas fa-filter"></i>
                            <select id="kategoriFilter">
                                <option value="all">Semua Kategori</option>
                                <option value="sosial">Sosial</option>
                                <option value="rumah_tangga">Rumah Tangga</option>
                                <option value="niaga">Niaga</option>
                                <option value="industri">Industri</option>
                                <option value="instansi">Instansi</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Cari tarif...">
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
                                <th width="150">Sub Kategori</th>
                                <th width="150">Blok Pemakaian</th>
                                <th width="120">Min (m³)</th>
                                <th width="120">Max (m³)</th>
                                <th width="150">Tarif/m³</th>
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
                    <p>Klik tombol "Tambah Tarif" untuk membuat tarif baru</p>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="pagination-container" style="display: none;">
                    <div class="pagination-info">
                        Menampilkan <strong id="showingFrom">0</strong> - <strong id="showingTo">0</strong> dari <strong id="totalData">0</strong> data
                    </div>
                    <div class="pagination-buttons" id="paginationButtons"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Add/Edit -->
    <div class="modal-overlay" id="formModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-plus"></i> Tambah Tarif</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="tarifForm">
                    <input type="hidden" id="tarifId">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kategori">
                                <i class="fas fa-tag"></i>
                                Kategori <span class="required">*</span>
                            </label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="sosial">Sosial</option>
                                <option value="rumah_tangga">Rumah Tangga</option>
                                <option value="niaga">Niaga</option>
                                <option value="industri">Industri</option>
                                <option value="instansi">Instansi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sub_kategori">
                                <i class="fas fa-tags"></i>
                                Sub Kategori
                            </label>
                            <input type="text" id="sub_kategori" name="sub_kategori" placeholder="Contoh: Rumah Tangga I">
                        </div>

                        <div class="form-group full-width">
                            <label for="blok_pemakaian">
                                <i class="fas fa-layer-group"></i>
                                Blok Pemakaian <span class="required">*</span>
                            </label>
                            <input type="text" id="blok_pemakaian" name="blok_pemakaian" required placeholder="Contoh: 0-10 m³">
                        </div>

                        <div class="form-group">
                            <label for="min_pemakaian">
                                <i class="fas fa-arrow-down"></i>
                                Min Pemakaian (m³) <span class="required">*</span>
                            </label>
                            <input type="number" id="min_pemakaian" name="min_pemakaian" required min="0" value="0">
                        </div>

                        <div class="form-group">
                            <label for="max_pemakaian">
                                <i class="fas fa-arrow-up"></i>
                                Max Pemakaian (m³)
                            </label>
                            <input type="number" id="max_pemakaian" name="max_pemakaian" min="0" placeholder="Kosongkan untuk unlimited">
                        </div>

                        <div class="form-group full-width">
                            <label for="tarif_per_m3">
                                <i class="fas fa-money-bill-wave"></i>
                                Tarif per m³ (Rp) <span class="required">*</span>
                            </label>
                            <input type="number" id="tarif_per_m3" name="tarif_per_m3" required min="0" placeholder="Contoh: 1500">
                        </div>

                        <div class="form-group full-width">
                            <label for="keterangan">
                                <i class="fas fa-align-left"></i>
                                Keterangan
                            </label>
                            <textarea id="keterangan" name="keterangan" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="urutan">
                                <i class="fas fa-sort-numeric-up"></i>
                                Urutan
                            </label>
                            <input type="number" id="urutan" name="urutan" min="0" value="0">
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="aktif" name="aktif" checked>
                                <span>Aktifkan tarif ini</span>
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

    <script src="{{ asset('js/admin/tarif-air.js') }}"></script>
</body>
</html>