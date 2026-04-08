<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Akun - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengaturan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
                <div class="header-left">
                    <h1><i class="fas fa-user-cog"></i> Pengaturan Akun</h1>
                    <p>Kelola informasi profil dan preferensi akun Anda</p>
                </div>
            </div>

            <div class="settings-container">
                <!-- Sidebar Menu -->
                <div class="pengaturan-sidebar">
                    <div class="pengaturan-menu">
                        <button class="pengaturan-menu-item active" data-tab="profil">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </button>
                        <button class="pengaturan-menu-item" data-tab="keamanan">
                            <i class="fas fa-shield-alt"></i>
                            <span>Keamanan</span>
                        </button>
                        <button class="pengaturan-menu-item" data-tab="notifikasi">
                            <i class="fas fa-bell"></i>
                            <span>Notifikasi</span>
                        </button>
                        <button class="pengaturan-menu-item" data-tab="tampilan">
                            <i class="fas fa-palette"></i>
                            <span>Tampilan</span>
                        </button>
                        <button class="pengaturan-menu-item" data-tab="preferensi">
                            <i class="fas fa-sliders-h"></i>
                            <span>Preferensi</span>
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="settings-content">
                    
                    <!-- Tab Profil -->
                    <div class="tab-content active" id="profil">
                        <div class="content-header">
                            <h2><i class="fas fa-user"></i> Profil Saya</h2>
                            <p>Kelola informasi profil dan foto akun Anda</p>
                        </div>

                        <div class="card">
                            <!-- Foto Profil -->
                            <div class="profile-photo-section">
                                <div class="photo-wrapper">
                                    <div class="photo-preview">
                                        <img src="{{ Auth::user()->foto_url }}" 
                                        alt="Foto Profil" id="photoPreview">
                                        <div class="photo-overlay">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    </div>
                                    <input type="file" id="photoInput" accept="image/*" style="display: none;">
                                </div>
                                <div class="photo-info">
                                    <h3>{{ Auth::user()->nama }}</h3>
                                    <p class="role-badge">{{ Auth::user()->role }}</p>
                                    <button class="btn-upload" onclick="document.getElementById('photoInput').click()">
                                        <i class="fas fa-upload"></i> Upload Foto
                                    </button>
                                    <small>JPG, PNG maksimal 2MB</small>
                                </div>
                            </div>

                            <!-- Form Profil -->
                            <form id="profilForm" class="settings-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-user"></i> Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-envelope"></i> Email</label>
                                        <input type="email" name="email" value="{{ Auth::user()->email }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-phone"></i> No. Telepon</label>
                                        <input type="tel" name="no_telepon" value="{{ Auth::user()->no_telepon ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-building"></i> Jabatan</label>
                                        <input type="text" name="jabatan" value="{{ Auth::user()->jabatan ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea name="alamat" rows="3">{{ Auth::user()->alamat ?? '' }}</textarea>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tab Keamanan -->
                    <div class="tab-content" id="keamanan">
                        <div class="content-header">
                            <h2><i class="fas fa-shield-alt"></i> Keamanan Akun</h2>
                            <p>Kelola password dan keamanan akun Anda</p>
                        </div>

                        <!-- Ubah Password -->
                        <div class="card">
                            <h3><i class="fas fa-key"></i> Ubah Password</h3>
                            <form id="passwordForm" class="settings-form">
                                <div class="form-group">
                                    <label><i class="fas fa-lock"></i> Password Lama</label>
                                    <div class="password-input">
                                        <input type="password" name="password_lama" id="oldPassword" required>
                                        <button type="button" class="toggle-password" onclick="togglePassword('oldPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-lock"></i> Password Baru</label>
                                        <div class="password-input">
                                            <input type="password" name="password_baru" id="newPassword" required>
                                            <button type="button" class="toggle-password" onclick="togglePassword('newPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <span class="strength-text" id="strengthText">Lemah</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
                                        <div class="password-input">
                                            <input type="password" name="password_konfirmasi" id="confirmPassword" required>
                                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Aktivitas Login -->
                        <div class="card">
                            <h3><i class="fas fa-history"></i> Aktivitas Login Terakhir</h3>
                            <div class="login-activity">
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>Login Berhasil</h4>
                                        <p>{{ Auth::user()->last_login ?? 'Tidak ada data' }}</p>
                                        <small>IP: {{ Request::ip() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Notifikasi -->
                    <div class="tab-content" id="notifikasi">
                        <div class="content-header">
                            <h2><i class="fas fa-bell"></i> Pengaturan Notifikasi</h2>
                            <p>Atur notifikasi yang ingin Anda terima</p>
                        </div>

                        <div class="card">
                            <form id="notifikasiForm" class="settings-form">
                                <div class="notif-group">
                                    <h3><i class="fas fa-envelope"></i> Email</h3>
                                    <div class="notif-item">
                                        <div class="notif-info">
                                            <h4>Notifikasi Berita</h4>
                                            <p>Terima email saat ada berita baru</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_berita" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="notif-item">
                                        <div class="notif-info">
                                            <h4>Notifikasi Pengaduan</h4>
                                            <p>Terima email saat ada pengaduan baru</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_pengaduan" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="notif-group">
                                    <h3><i class="fas fa-desktop"></i> Browser</h3>
                                    <div class="notif-item">
                                        <div class="notif-info">
                                            <h4>Notifikasi Push</h4>
                                            <p>Terima notifikasi langsung di browser</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="push_notif">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Simpan Preferensi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tab Tampilan -->
                    <div class="tab-content" id="tampilan">
                        <div class="content-header">
                            <h2><i class="fas fa-palette"></i> Tampilan & Tema</h2>
                            <p>Sesuaikan tampilan dashboard sesuai preferensi Anda</p>
                        </div>

                        <div class="card">
                            <form id="tampilanForm" class="settings-form">
                                <div class="form-group">
                                    <label><i class="fas fa-moon"></i> Tema Warna</label>
                                    <div class="theme-options">
                                        <div class="theme-item active" data-theme="light">
                                            <div class="theme-preview light">
                                                <i class="fas fa-sun"></i>
                                            </div>
                                            <span>Terang</span>
                                        </div>
                                        <div class="theme-item" data-theme="dark">
                                            <div class="theme-preview dark">
                                                <i class="fas fa-moon"></i>
                                            </div>
                                            <span>Gelap</span>
                                        </div>
                                        <div class="theme-item" data-theme="auto">
                                            <div class="theme-preview auto">
                                                <i class="fas fa-adjust"></i>
                                            </div>
                                            <span>Auto</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-paint-brush"></i> Warna Aksen</label>
                                    <div class="color-options">
                                        <button type="button" class="color-item active" data-color="blue" style="background: #0d6efd;"></button>
                                        <button type="button" class="color-item" data-color="cyan" style="background: #0dcaf0;"></button>
                                        <button type="button" class="color-item" data-color="green" style="background: #198754;"></button>
                                        <button type="button" class="color-item" data-color="orange" style="background: #fd7e14;"></button>
                                        <button type="button" class="color-item" data-color="purple" style="background: #6f42c1;"></button>
                                        <button type="button" class="color-item" data-color="red" style="background: #dc3545;"></button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-text-height"></i> Ukuran Font</label>
                                    <select name="font_size">
                                        <option value="small">Kecil</option>
                                        <option value="medium" selected>Sedang</option>
                                        <option value="large">Besar</option>
                                    </select>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Simpan Tampilan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tab Preferensi -->
                    <div class="tab-content" id="preferensi">
                        <div class="content-header">
                            <h2><i class="fas fa-sliders-h"></i> Preferensi Umum</h2>
                            <p>Atur preferensi penggunaan sistem</p>
                        </div>

                        <div class="card">
                            <form id="preferensiForm" class="settings-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-language"></i> Bahasa</label>
                                        <select name="bahasa">
                                            <option value="id" selected>Indonesia</option>
                                            <option value="en">English</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-clock"></i> Zona Waktu</label>
                                        <select name="timezone">
                                            <option value="Asia/Jakarta" selected>WIB (Jakarta)</option>
                                            <option value="Asia/Makassar">WITA (Makassar)</option>
                                            <option value="Asia/Jayapura">WIT (Jayapura)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar"></i> Format Tanggal</label>
                                        <select name="date_format">
                                            <option value="d/m/Y" selected>DD/MM/YYYY</option>
                                            <option value="m/d/Y">MM/DD/YYYY</option>
                                            <option value="Y-m-d">YYYY-MM-DD</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-list-ol"></i> Item Per Halaman</label>
                                        <select name="items_per_page">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Simpan Preferensi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Danger Zone -->
                        <div class="card danger-zone">
                            <h3><i class="fas fa-exclamation-triangle"></i> Zona Bahaya</h3>
                            <p>Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan</p>
                            
                            <div class="danger-actions">
                                <button class="btn-danger" onclick="confirmLogout()">
                                    <i class="fas fa-sign-out-alt"></i> Logout dari Semua Perangkat
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
     @include('component.footer')

    <script src="{{ asset('js/pengaturan.js') }}"></script>
</body>
</html>