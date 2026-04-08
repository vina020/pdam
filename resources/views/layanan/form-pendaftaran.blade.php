<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Pasang Baru - PDAM Magetan</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
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
                <li><a href="{{ route('layanan.pasang-baru') }}">Pasang Baru</a></li>
                <li class="active">Form Pendaftaran</li>
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
                    <i class="fas fa-edit"></i>
                    <span>Form Pendaftaran</span>
                </div>
                <h1 class="hero-title-modern">Form Pendaftaran Pasang Baru</h1>
                <p class="hero-subtitle-modern">Lengkapi formulir di bawah ini untuk mengajukan pemasangan sambungan air baru</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            <div class="content-grid-modern">
                <div class="section-modern full-width">
                    <div class="info-card-modern" style="border-left: 4px solid #0d6efd;">
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div class="info-icon-modern blue">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4>Petunjuk Pengisian</h4>
                                <p>Pastikan semua data yang diisi benar dan sesuai dengan dokumen asli. Data yang salah akan memperlambat proses pemasangan. Siapkan dokumen persyaratan dalam format PDF, JPG, atau PNG (maksimal 2MB per file).</p>
                            </div>
                        </div>
                    </div>
                    <form id="pasangBaruForm" method="POST" action="{{ route('layanan.pasang-baru.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Step Indicator -->
                        <!-- Step Indicator -->
                        <div class="form-steps">
                            <div class="step-item active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">Data Pemohon</div>
                            </div>
                            <div class="step-item" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Lokasi Pemasangan</div>
                            </div>
                            <div class="step-item" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Upload Dokumen</div>
                            </div>
                            <div class="step-item" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-label">Verifikasi</div>
                            </div>
                        </div>

                        <!-- Step 1: Data Pemohon -->
                        <div class="form-step-content" id="step-1">
                            <div class="section-header-modern" style="margin-bottom: 30px;">
                                <div class="section-icon-badge">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h2>Data Pemohon</h2>
                                    <p>Isi data diri sesuai dengan KTP</p>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-id-card"></i> Nama Lengkap Pemohon
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input-modern" id="nama_pemohon" name="nama_pemohon" placeholder="Masukkan nama lengkap sesuai KTP" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-id-card"></i> NIK (Nomor Induk Kependudukan)
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input-modern" id="nik" name="nik" placeholder="16 digit NIK" maxlength="16" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-map-marker-alt"></i> Alamat Pemohon (Sesuai KTP)
                                    <span class="required">*</span>
                                </label>
                                <textarea class="form-input-modern" id="alamat_pemohon" name="alamat_pemohon" rows="3" placeholder="Masukkan alamat lengkap sesuai KTP" required></textarea>
                            </div>

                            <div class="form-row-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-phone"></i> Nomor Telepon/HP
                                        <span class="required">*</span>
                                    </label>
                                    <input type="tel" class="form-input-modern" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-input-modern" id="email" name="email" placeholder="email@contoh.com">
                                </div>
                            </div>

                            <div class="form-navigation-modern">
                                <button type="button" class="btn-modern btn-primary-modern" onclick="nextStep(1)">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Lokasi Pemasangan -->
                        <div class="form-step-content" id="step-2" style="display: none;">
                            <div class="section-header-modern" style="margin-bottom: 30px;">
                                <div class="section-icon-badge">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <h2>Lokasi Pemasangan</h2>
                                    <p>Tentukan lokasi untuk pemasangan sambungan air</p>
                                </div>
                            </div>

                            <div class="form-row-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-road"></i> Nama Jalan
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-input-modern" id="jalan" name="jalan" placeholder="Contoh: Jl. Mawar" required>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-hashtag"></i> Nomor Rumah
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-input-modern" id="nomor_rumah" name="nomor_rumah" placeholder="Contoh: 123" required>
                                </div>
                            </div>

                            <div class="form-row-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-home"></i> RT
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-input-modern" id="rt" name="rt" placeholder="Contoh: 001" required>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-home"></i> RW
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-input-modern" id="rw" name="rw" placeholder="Contoh: 002" required>
                                </div>
                            </div>

                            <div class="form-row-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-map"></i> Kecamatan
                                        <span class="required">*</span>
                                    </label>
                                    <select class="form-input-modern" id="kecamatan" name="kecamatan" required>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <option value="Magetan">Magetan</option>
                                        <option value="Plaosan">Plaosan</option>
                                        <option value="Maospati">Maospati</option>
                                        <option value="Poncol">Poncol</option>
                                        <option value="Parang">Parang</option>
                                        <option value="Lembeyan">Lembeyan</option>
                                        <option value="Takeran">Takeran</option>
                                        <option value="Kawedanan">Kawedanan</option>
                                    </select>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-map-marker-alt"></i> Kelurahan/Desa
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-input-modern" id="kelurahan" name="kelurahan" placeholder="Masukkan nama kelurahan" required>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-bolt"></i> Daya Listrik (VA)
                                    <span class="required">*</span>
                                </label>
                                <select class="form-input-modern" id="daya_listrik" name="daya_listrik" required>
                                    <option value="">-- Pilih Daya Listrik --</option>
                                    <option value="450">450 VA</option>
                                    <option value="900">900 VA</option>
                                    <option value="1300">1300 VA</option>
                                    <option value="2200">2200 VA</option>
                                    <option value="3500">3500 VA</option>
                                    <option value="5500">5500 VA & lebih</option>
                                </select>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-map-pin"></i> Tentukan Lokasi Pemasangan di Peta
                                </label>
                                <div class="map-container-modern">
                                    <div id="map" style="height: 400px; border-radius: 16px;"></div>
                                    <p class="map-hint-modern">
                                        <i class="fas fa-info-circle"></i>
                                        Klik pada peta untuk menentukan titik lokasi pemasangan
                                    </p>
                                </div>
                            </div>

                            <div class="form-row-modern">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-crosshairs"></i> Latitude
                                    </label>
                                    <input type="text" class="form-input-modern" id="latitude" name="latitude" placeholder="Otomatis terisi" readonly>
                                </div>

                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-crosshairs"></i> Longitude
                                    </label>
                                    <input type="text" class="form-input-modern" id="longitude" name="longitude" placeholder="Otomatis terisi" readonly>
                                </div>
                            </div>

                            <div class="form-navigation-modern">
                                <button type="button" class="btn-modern btn-secondary-modern" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-modern btn-primary-modern" onclick="nextStep(2)">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Upload Dokumen -->
                        <div class="form-step-content" id="step-3" style="display: none;">
                            <div class="section-header-modern" style="margin-bottom: 30px;">
                                <div class="section-icon-badge">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div>
                                    <h2>Upload Dokumen Persyaratan</h2>
                                    <p>Upload semua dokumen yang diperlukan</p>
                                </div>
                            </div>

                            <div class="info-card-modern" style="border-left: 4px solid #ffc107; margin-bottom: 30px;">
                                <div style="display: flex; gap: 15px; align-items: flex-start;">
                                    <div class="info-icon-modern orange">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <h4>Perhatian</h4>
                                        <p>File harus dalam format PDF, JPG, atau PNG dengan ukuran maksimal 2MB per file. Pastikan dokumen terlihat jelas dan mudah dibaca.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="upload-section-modern">
                                <div class="upload-item-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-id-card"></i> Fotokopi KTP
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-wrapper-modern">
                                        <input type="file" id="dokumen_ktp" name="dokumen_ktp" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this, 'preview-ktp')">
                                        <label for="dokumen_ktp" class="file-upload-label-modern">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Pilih File atau Drag & Drop</span>
                                            <small>PDF, JPG, PNG (Max 2MB)</small>
                                        </label>
                                    </div>
                                    <div class="file-preview-modern" id="preview-ktp" style="display:none;"></div>
                                </div>

                                <div class="upload-item-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-users"></i> Fotokopi Kartu Keluarga
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-wrapper-modern">
                                        <input type="file" id="dokumen_kk" name="dokumen_kk" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this, 'preview-kk')">
                                        <label for="dokumen_kk" class="file-upload-label-modern">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Pilih File atau Drag & Drop</span>
                                            <small>PDF, JPG, PNG (Max 2MB)</small>
                                        </label>
                                    </div>
                                    <div class="file-preview-modern" id="preview-kk"></div>
                                </div>

                                <div class="upload-item-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-file-invoice"></i> Fotokopi PBB
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-wrapper-modern">
                                        <input type="file" id="dokumen_pbb" name="dokumen_pbb" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this, 'preview-pbb')">
                                        <label for="dokumen_pbb" class="file-upload-label-modern">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Pilih File atau Drag & Drop</span>
                                            <small>PDF, JPG, PNG (Max 2MB)</small>
                                        </label>
                                    </div>
                                    <div class="file-preview-modern" id="preview-pbb"></div>
                                </div>

                                <div class="upload-item-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-bolt"></i> Fotokopi Rekening Listrik
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-wrapper-modern">
                                        <input type="file" id="dokumen_listrik" name="dokumen_listrik" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this, 'preview-listrik')">
                                        <label for="dokumen_listrik" class="file-upload-label-modern">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Pilih File atau Drag & Drop</span>
                                            <small>PDF, JPG, PNG (Max 2MB)</small>
                                        </label>
                                    </div>
                                    <div class="file-preview-modern" id="preview-listrik"></div>
                                </div>

                                <div class="upload-item-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-file-alt"></i> Surat Keterangan Domisili
                                        <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-wrapper-modern">
                                        <input type="file" id="dokumen_domisili" name="dokumen_domisili" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this, 'preview-domisili')">
                                        <label for="dokumen_domisili" class="file-upload-label-modern">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Pilih File atau Drag & Drop</span>
                                            <small>PDF, JPG, PNG (Max 2MB)</small>
                                        </label>
                                    </div>
                                    <div class="file-preview-modern" id="preview-domisili"></div>
                                </div>
                            </div>

                            <div class="form-navigation-modern">
                                <button type="button" class="btn-modern btn-secondary-modern" onclick="prevStep(3)">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn-modern btn-primary-modern" onclick="nextStep(3)">
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Verifikasi -->
                        <div class="form-step-content" id="step-4" style="display: none;">
                            <div class="section-header-modern" style="margin-bottom: 30px;">
                                <div class="section-icon-badge">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h2>Verifikasi Data</h2>
                                    <p>Periksa kembali semua informasi yang telah diisi</p>
                                </div>
                            </div>

                            <div class="info-card-modern" style="border-left: 4px solid #0d6efd; margin-bottom: 30px;">
                                <div style="display: flex; gap: 15px; align-items: flex-start;">
                                    <div class="info-icon-modern blue">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h4>Ringkasan Data Anda</h4>
                                        <p>Periksa kembali data yang telah Anda masukkan. Pastikan semua informasi sudah benar sebelum mengirim permohonan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pemohon Summary -->
                            <div class="summary-card-modern">
                                <h4 class="summary-title-modern">
                                    <i class="fas fa-user"></i> Data Pemohon
                                </h4>
                                <div class="summary-list-modern">
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">Nama:</span>
                                        <span class="summary-value-modern" id="verify-nama"></span>
                                    </div>
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">NIK:</span>
                                        <span class="summary-value-modern" id="verify-nik"></span>
                                    </div>
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">Alamat:</span>
                                        <span class="summary-value-modern" id="verify-alamat"></span>
                                    </div>
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">No. Telepon:</span>
                                        <span class="summary-value-modern" id="verify-telp"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi Pemasangan Summary -->
                            <div class="summary-card-modern">
                                <h4 class="summary-title-modern">
                                    <i class="fas fa-map-marked-alt"></i> Lokasi Pemasangan
                                </h4>
                                <div class="summary-list-modern">
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">Alamat:</span>
                                        <span class="summary-value-modern" id="verify-lokasi"></span>
                                    </div>
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">Kecamatan:</span>
                                        <span class="summary-value-modern" id="verify-kecamatan"></span>
                                    </div>
                                    <div class="summary-item-modern">
                                        <span class="summary-label-modern">Daya Listrik:</span>
                                        <span class="summary-value-modern" id="verify-daya"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen Summary -->
                            <div class="summary-card-modern">
                                <h4 class="summary-title-modern">
                                    <i class="fas fa-file-upload"></i> Dokumen Terupload
                                </h4>
                                <div class="summary-list-modern" id="verify-documents">
                                </div>
                            </div>

                            <div class="form-group-modern checkbox-group-modern">
                                <label class="checkbox-container-modern">
                                    <input type="checkbox" name="agree_terms" id="agree_terms" required>
                                    <span class="checkmark-modern"></span>
                                    <span class="checkbox-text-modern">
                                        Saya menyatakan bahwa data yang saya isi adalah benar dan sesuai dengan dokumen asli. Saya bersedia memenuhi semua persyaratan dan ketentuan yang berlaku serta bertanggung jawab penuh atas kebenaran informasi yang diberikan.
                                    </span>
                                </label>
                            </div>

                            <div class="form-navigation-modern">
                                <button type="button" class="btn-modern btn-secondary-modern" onclick="prevStep(4)">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn-modern btn-primary-modern btn-submit-modern" style= "color: white">
                                    <i class="fas fa-paper-plane"></i> Kirim Permohonan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/layanan-detail.js') }}"></script>
</body>
</html>