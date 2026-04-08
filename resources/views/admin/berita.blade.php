<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Berita - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/berita.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
    <div class="header-left">
        <h1><i class="fas fa-newspaper"></i> Kelola Berita</h1>
        <p>Buat, edit, dan kelola berita PDAM</p>
    </div>
    <button class="btn-primary" onclick="openAddModal()">
        <i class="fas fa-plus"></i>
        Tambah Berita
    </button>
</div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                    <div>
                        <span class="stat-label">Total Berita</span>
                        <span class="stat-value" id="totalBerita">0</span>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                    <div>
                        <span class="stat-label">Total Views</span>
                        <span class="stat-value" id="totalViews">0</span>
                    </div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                    <div>
                        <span class="stat-label">Hari Ini</span>
                        <span class="stat-value" id="todayBerita">0</span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-list"></i>
                        <h3>Daftar Berita</h3>
                    </div>
                    <div class="table-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Cari berita...">
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
                                <th width="100">Foto</th>
                                <th>Judul</th>
                                <th width="120">Kategori</th>
                                <th width="120">Penulis</th>
                                <th width="100">Views</th>
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
                    <h3>Belum Ada Berita</h3>
                    <p>Klik tombol "Tambah Berita" untuk membuat berita baru</p>
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
        <div class="modal-box large">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-plus"></i> Tambah Berita</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="beritaForm" enctype="multipart/form-data">
                    <input type="hidden" id="beritaId">
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="judul">
                                <i class="fas fa-heading"></i>
                                Judul Berita <span class="required">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required placeholder="Masukkan judul berita">
                        </div>

                        <div class="form-group">
                            <label for="kategori">
                                <i class="fas fa-tag"></i>
                                Kategori
                            </label>
                            <select id="kategori" name="kategori">
                                <option value="info">Informasi</option>
                                <option value="pengumuman">Pengumuman</option>
                                <option value="layanan">Layanan</option>
                                <option value="event">Event</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="penulis">
                                <i class="fas fa-user-edit"></i>
                                Penulis
                            </label>
                            <input type="text" id="penulis" name="penulis" placeholder="Nama penulis" value="Admin PDAM">
                        </div>

                        <div class="form-group full-width">
                            <label for="foto">
                                <i class="fas fa-image"></i>
                                Foto Berita (Max 2MB)
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                            <div id="imagePreview" class="image-preview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="btn-remove-image" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i>
                                Deskripsi Singkat <span class="required">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" required placeholder="Ringkasan berita (maks 200 karakter)"></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label for="konten">
                                <i class="fas fa-file-alt"></i>
                                Konten Lengkap
                            </label>
                            <textarea id="konten" name="konten" rows="8" placeholder="Tulis konten berita lengkap..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal()">
                            <i class="fas fa-times"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Berita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/berita.js') }}"></script>
</body>
</html>