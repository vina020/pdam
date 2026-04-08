<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-wrapper">
                
                <!-- FORM CONTAINER (KIRI) -->
                <div class="login-form-container">
                    <div class="form-header">
                        <img src="{{ asset('images/logo pdam.png') }}" alt="Logo PDAM" class="logo">
                        <h2>Masuk</h2>
                        <p>Masuk ke akun Anda</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                        @csrf
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email atau No. Pelanggan
                            </label>
                            <input type="text" id="email" name="email" placeholder="Masukkan email atau nomor pelanggan" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-options">
                            <label class="checkbox-container">
                                <input type="checkbox" name="remember">
                                <span class="checkmark"></span>
                                Ingat Saya
                            </label>
                            <a href="{{ route('forgot-password') }}" class="forgot-password">Lupa Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Masuk
                        </button>

                        <div class="divider">
                            <span>atau</span>
                        </div>

                        <div class="social-login">
                            <button type="button" class="btn btn-google">
                                <img src="{{ asset('images/logo-google.png') }}" alt="Google" class="google-logo">
                                Login dengan Google
                            </button>
                        </div>

                        <div class="register-link">
                            Belum punya akun? <a href="{{ route('registrasi') }}">Daftar Sekarang</a>
                        </div>
                    </form>
                </div>

                <!-- IMAGE CONTAINER (KANAN) -->
                <div class="login-image">
                    <div class="image-overlay">
                        <h2>Selamat Datang Kembali</h2>
                        <p>Akses layanan PDAM Magetan dengan mudah dan cepat</p>
                        <div class="features">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Cek Tagihan Online</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Pembayaran Digital</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Riwayat Transaksi</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Pengaduan Online</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('component.footer')

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>