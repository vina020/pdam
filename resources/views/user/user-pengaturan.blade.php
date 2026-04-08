<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Akun - PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/pengaturan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    /* === MODERN HERO SECTION === */
.layanan-hero-modern {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #0d6efd 0%, #17a2b8 100%);
    padding: 80px 0;
    max-width: 100%;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
}

.hero-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 20s ease-in-out infinite;
}

.hero-shape.shape-1 {
    width: 400px;
    height: 400px;
    background: white;
    top: -100px;
    right: 10%;
    animation-delay: 0s;
}

.hero-shape.shape-2 {
    width: 300px;
    height: 300px;
    background: white;
    bottom: -50px;
    left: 5%;
    animation-delay: 3s;
}

.hero-shape.shape-3 {
    width: 200px;
    height: 200px;
    background: white;
    top: 50%;
    right: 30%;
    animation-delay: 6s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-30px) rotate(180deg);
    }
}

.hero-content-modern {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    color: white;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
    animation: fadeInDown 0.6s ease-out;
}

.hero-title-modern {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.hero-subtitle-modern {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 40px;
    line-height: 1.6;
    animation: fadeInUp 0.6s ease-out 0.4s backwards;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}
</style>
<body>
    @include('component.sidebar')
    <!-- Breadcrumb -->
        <nav class="breadcrumbs">
            <div class="container">
                <ul>
                    <li><a href="{{ route('homepage') }}">Beranda</a></li>
                    <li><a href="#">Akun</a></li>
                    <li class="active">Pengaturan</li>
                </ul>
            </div>
        </nav>
    <section class="layanan-hero-modern">
    <div class="hero-background">
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>
        <div class="hero-shape shape-3"></div>
    </div>
    <div class="container">
        <div class="hero-content-modern">
            <div class="hero-badge">
                <i class="fas fa-user-cog"></i>
                <span>Akun Anda</span>
            </div>
            <h1 class="hero-title-modern">Pengaturan Akun</h1>
            <p class="hero-subtitle-modern">
                Kelola informasi profil dan preferensi akun Anda
            </p>
        </div>
    </div>
</section>

    <div class="main-content">
        <div class="container">
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
                                        <img id="photoPreview" src="{{ Auth::user()->foto_url }}" alt="Profile Photo">
                                        <div class="photo-overlay">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    </div>
                                    <input type="file" id="photoInput" accept="image/*" style="display: none;">
                                </div>
                                <div class="photo-info">
                                    <h3>{{ Auth::user()->nama_lengkap }}</h3>
                                    <p class="role-badge">{{ Auth::user()->role }}</p>
                                    <button class="btn-upload" onclick="document.getElementById('photoInput').click()">
                                        <i class="fas fa-upload"></i> Upload Foto
                                    </button>
                                    <small>JPG, PNG maksimal 2MB</small>
                                </div>
                            </div>

                            <!-- Form Profil -->
                            <form id="profilForm" class="settings-form">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-user"></i> Nama Lengkap</label>
                                        <input type="text" id="nama_lengkap" name="nama" value="{{ Auth::user()->nama_lengkap }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-envelope"></i> Email</label>
                                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-phone"></i> No. Telepon</label>
                                        <input type="tel" id="no_telepon" name="telepon" value="{{ Auth::user()->no_telepon }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-id-card"></i> ID Pelanggan</label>
                                        <input type="text" value="{{ Auth::user()->pelanggan->no_pelanggan ?? '-' }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea id="alamat" name="alamat" rows="3">{{ Auth::user()->pelanggan->alamat_lengkap ?? '' }}</textarea>
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
                                            <h4>Notifikasi Tagihan</h4>
                                            <p>Terima email saat tagihan baru tersedia</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_tagihan" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="notif-item">
                                        <div class="notif-info">
                                            <h4>Notifikasi Pengaduan</h4>
                                            <p>Terima email saat ada update pengaduan</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_pengaduan" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="notif-item">
                                        <div class="notif-info">
                                            <h4>Notifikasi Berita</h4>
                                            <p>Terima email untuk berita dan pengumuman</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_berita">
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
                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('component.footer')

    <script src="{{ asset('js/pengaturan.js') }}"></script>
</body>
</html>