<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/registrasi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <section class="register-section">
        <div class="container">
            <div class="register-wrapper">
                
                <!-- LEFT SIDE - FORM -->
                <div class="register-form-container">
                    <div class="form-header">
                        <img src="{{ asset('images/logo pdam.png') }}" alt="Logo PDAM" class="logo">
                        <h2>Daftar Akun</h2>
                        <p>Buat akun baru untuk mengakses layanan</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error">
                            @foreach($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('registrasi.post') }}" method="POST" id="registerForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nama_lengkap">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" id="email" name="email" placeholder="contoh@email.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="no_telepon">
                                <i class="fas fa-phone"></i>
                                No. Telepon
                            </label>
                            <input type="tel" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxxx" value="{{ old('no_telepon') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan">
                                <i class="fas fa-map"></i>
                                Kecamatan
                            </label>
                            <select id="kecamatan" name="kecamatan" required>
                                <option value="">-- Pilih Kecamatan --</option>
                                <option value="Magetan" {{ old('kecamatan') == 'Magetan' ? 'selected' : '' }}>Magetan</option>
                                <option value="Plaosan" {{ old('kecamatan') == 'Plaosan' ? 'selected' : '' }}>Plaosan</option>
                                <option value="Maospati" {{ old('kecamatan') == 'Maospati' ? 'selected' : '' }}>Maospati</option>
                                <option value="Poncol" {{ old('kecamatan') == 'Poncol' ? 'selected' : '' }}>Poncol</option>
                                <option value="Parang" {{ old('kecamatan') == 'Parang' ? 'selected' : '' }}>Parang</option>
                                <option value="Lembeyan" {{ old('kecamatan') == 'Lembeyan' ? 'selected' : '' }}>Lembeyan</option>
                                <option value="Takeran" {{ old('kecamatan') == 'Takeran' ? 'selected' : '' }}>Takeran</option>
                                <option value="Kawedanan" {{ old('kecamatan') == 'Kawedanan' ? 'selected' : '' }}>Kawedanan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan">
                                <i class="fas fa-map-marker-alt"></i>
                                Kelurahan
                            </label>
                            <input type="text" id="kelurahan" name="kelurahan" placeholder="Masukkan kelurahan" value="{{ old('kelurahan') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="alamat_lengkap">
                                <i class="fas fa-map-marker-alt"></i>
                                Detail Alamat
                            </label>
                            <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i>
                                    Password
                                </label>
                                <div class="password-input">
                                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                                    <button type="button" class="toggle-password" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i>
                                    Konfirmasi Password
                                </label>
                                <div class="password-input">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" required>
                                    <button type="button" class="toggle-password" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Kekuatan Password</span>
                        </div>

                        <div class="form-group checkbox-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="agree_terms" required>
                                <span class="checkbox-text">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-register">
                            Daftar Sekarang
                        </button>

                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <div class="social-register">
                            <button type="button" class="btn btn-google">
                                <img src="{{ asset('images/logo-google.png') }}" alt="Google" class="google-logo">
                                Daftar dengan Google
                            </button>
                        </div>

                        <div class="login-link">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
                        </div>
                    </form>
                </div>

                <!-- RIGHT SIDE - IMAGE -->
                <div class="register-image">
                    <img src="{{ asset('images/bg.png') }}" alt="Background" class="bg-image">
                    <div class="image-content">
                        <h2>Bergabung Bersama Kami</h2>
                        <p>Dapatkan kemudahan akses layanan PDAM Magetan</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Modal Nomor Pelanggan -->
<!-- Modal Nomor Pelanggan -->
@if(session('show_modal') && session('nomor_pelanggan'))
<div id="nomorPelangganModal" class="modal-overlay" style="display: flex;">
    <div class="modal-content welcome-modal">
        <i class="fas fa-check-circle success-icon"></i>
        <h1>Selamat Datang!</h1>
        <p class="subtitle">Registrasi Anda berhasil, {{ session('nama_pelanggan') }}!</p>

        <div class="info-card">
            <div class="info-label">Nomor Pelanggan Anda</div>
            <div class="nomor-pelanggan" id="nomorPelanggan">{{ session('nomor_pelanggan') }}</div>
            <button class="copy-btn" onclick="copyNomor()">
                <i class="fas fa-copy"></i> Salin Nomor
            </button>
        </div>

        <div class="warning-box">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                <strong>PENTING:</strong> Simpan nomor pelanggan ini dengan baik. Anda akan memerlukan nomor ini untuk pembayaran tagihan, pengajuan pengaduan, permintaan informasi layanan, dan akses ke portal pelanggan.
            </p>
        </div>

        <button onclick="closeModalAndRedirect()" class="btn-continue">
            <i class="fas fa-arrow-right"></i> Lanjutkan ke Beranda
        </button>
    </div>
</div>
@endif
    @include('component.footer')
    <!-- Load file JS external -->
    <script src="{{ asset('js/registrasi.js') }}"></script>

<!-- Script inline -->
<script>
function closeModalAndRedirect() {
    window.location.href = "{{ route('homepage') }}";
}
</script>
</body>
</html>