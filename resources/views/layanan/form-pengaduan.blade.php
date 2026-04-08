<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengaduan Pelanggan - PDAM Magetan</title>
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
                <li class="active">Pengaduan Pelanggan</li>
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
                    <i class="fas fa-comment-dots"></i>
                    <span>Pengaduan</span>
                </div>
                <h1 class="hero-title-modern">Form Pengaduan Pelanggan</h1>
                <p class="hero-subtitle-modern">Sampaikan keluhan atau masalah yang Anda alami terkait layanan PDAM</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            <div class="content-grid-modern">
                <div class="section-modern full-width">
                    <div class="info-card-modern" style="border-left: 4px solid #dc3545;">
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div class="info-icon-modern red">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div>
                                <h4>Informasi Penting</h4>
                                <p>Pastikan Anda mengisi semua data dengan benar dan lengkap. Pengaduan akan ditindaklanjuti maksimal 3x24 jam kerja. Untuk pengaduan darurat (kebocoran besar, pencemaran air), segera hubungi call center kami di <strong>0351-123456</strong>.</p>
                            </div>
                        </div>
                    </div>

                    <form id="pengaduanForm" method="POST" action="{{ route('layanan.pengaduan.submit') }}" style="margin-top: 40px;">
                        @csrf

                        <div class="section-header-modern" style="margin-bottom: 30px;">
                            <div class="section-icon-badge">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h2>Data Pengaduan</h2>
                                <p>Isi formulir pengaduan dengan lengkap dan jelas</p>
                            </div>
                        </div>

                        <div class="form-row-modern">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-user"></i> Nama Lengkap
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input-modern" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda" required>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-id-badge"></i> Nomor Pelanggan
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input-modern" id="no_pelanggan" name="no_pelanggan" placeholder="Contoh: PEL/2024/0001" required>
                                <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                                    <i class="fas fa-info-circle"></i> Nomor pelanggan terdapat pada tagihan bulanan Anda
                                </small>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-map-marker-alt"></i> Alamat Lengkap
                                <span class="required">*</span>
                            </label>
                            <textarea class="form-input-modern" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap lokasi pengaduan" required></textarea>
                        </div>

                        <div class="form-row-modern">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fab fa-whatsapp"></i> Nomor WhatsApp
                                    <span class="required">*</span>
                                </label>
                                <input type="tel" class="form-input-modern" id="no_whatsapp" name="no_whatsapp" placeholder="08xxxxxxxxxx" required>
                                <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                                    <i class="fas fa-info-circle"></i> Kami akan menghubungi via WhatsApp untuk update pengaduan
                                </small>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-list-ul"></i> Jenis Pengaduan
                                    <span class="required">*</span>
                                </label>
                                <select class="form-input-modern" id="jenis_pengaduan" name="jenis_pengaduan" required>
                                    <option value="">-- Pilih Jenis Pengaduan --</option>
                                    <option value="Kualitas Air">Kualitas Air (keruh, berbau, berasa)</option>
                                    <option value="Tekanan Air Rendah">Tekanan Air Rendah</option>
                                    <option value="Air Tidak Mengalir">Air Tidak Mengalir</option>
                                    <option value="Kebocoran Pipa">Kebocoran Pipa</option>
                                    <option value="Meter Air Rusak">Meter Air Rusak</option>
                                    <option value="Tagihan">Masalah Tagihan</option>
                                    <option value="Pelayanan Petugas">Keluhan Pelayanan Petugas</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-comment-alt"></i> Informasi Pengaduan
                                <span class="required">*</span>
                            </label>
                            <textarea class="form-input-modern" id="informasi_pengaduan" name="informasi_pengaduan" rows="6" placeholder="Jelaskan masalah yang Anda alami secara detail. Sertakan informasi seperti kapan masalah mulai terjadi, frekuensi kejadian, dan dampak yang dirasakan." required></textarea>
                            <small style="color: #6c757d; font-size: 12px; margin-top: 5px; display: block;">
                                <i class="fas fa-info-circle"></i> Minimal 20 karakter. Semakin detail informasi, semakin cepat kami dapat membantu Anda.
                            </small>
                        </div>

                        <div class="info-card-modern" style="border-left: 4px solid #28a745; margin-top: 30px;">
                            <div style="display: flex; gap: 15px; align-items: flex-start;">
                                <div class="info-icon-modern green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h4>Tips Pengaduan Efektif</h4>
                                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                        <li>Jelaskan kronologi masalah dengan rinci</li>
                                        <li>Sertakan waktu kejadian (tanggal dan jam)</li>
                                        <li>Sebutkan dampak yang Anda rasakan</li>
                                        <li>Jika ada, sertakan foto via WhatsApp setelah pengaduan terkirim</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern checkbox-group-modern" style="margin-top: 30px;">
                            <label class="checkbox-container-modern">
                                <input type="checkbox" name="agree_terms" id="agree_terms" required>
                                <span class="checkmark-modern"></span>
                                <span class="checkbox-text-modern">
                                    Saya menyatakan bahwa informasi yang saya sampaikan adalah benar dan dapat dipertanggungjawabkan. Saya memahami bahwa pengaduan palsu dapat dikenakan sanksi sesuai peraturan yang berlaku.
                                </span>
                            </label>
                        </div>

                        <div class="form-navigation-modern" style="margin-top: 40px;">
                            <button type="submit" class="btn-modern btn-primary-modern btn-submit-modern" style="color:white">
                                <i class="fas fa-paper-plane"></i> Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Divider sebelum CTA -->
<div style="margin: 60px 0 40px; border-top: 2px solid #e9ecef;"></div>

<!-- CTA Cek Status -->
<div class="info-card-modern" style="border-left: 4px solid #0d6efd; background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 202, 240, 0.05));">
    <div style="display: flex; gap: 20px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div style="display: flex; gap: 20px; align-items: flex-start; flex: 1;">
            <div class="info-icon-modern" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                <i class="fas fa-search"></i>
            </div>
            <div>
                <h4 style="color: #0d6efd; margin-bottom: 8px;">Sudah Pernah Mengajukan Pengaduan?</h4>
                <p style="margin: 0; color: #6c757d;">Cek status dan perkembangan penanganan pengaduan Anda secara real-time dengan memasukkan nomor pengaduan atau nomor pelanggan.</p>
            </div>
        </div>
        <a href="{{ route('layanan.cek-status-pengaduan') }}" class="btn-modern btn-primary-modern" style="white-space: nowrap; color: #0d6efd; text-decoration: none;">
            <i class="fas fa-search"></i> Cek Status Pengaduan
        </a>
    </div>
</div>
        </div>
    </section>

    <script src="{{ asset('js/pengaduan.js') }}"></script>
</body>
</html>