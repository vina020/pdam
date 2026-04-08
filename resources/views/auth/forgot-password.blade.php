<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAM Magetan - Lupa Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <section class="forgot-password-section">
        <div class="container">
            <div class="forgot-password-box">
                <div class="forgot-password-container">
                    <div class="form-header">
                        <img src="{{ asset('images/logo pdam.png') }}" alt="Logo PDAM" class="logo">
                        <h2>Lupa Password?</h2>
                        <p>Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>
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

                    @if(session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="info-box">
                        <p>
                            <i class="fas fa-info-circle"></i>
                            Link reset password akan dikirim ke email Anda dan berlaku selama 60 menit.
                        </p>
                    </div>

                    <form action="{{ route('forgot-password.post') }}" method="POST" id="forgotPasswordForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="contoh@email.com" 
                                value="{{ old('email') }}" 
                                required
                                autofocus
                            >
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Link Reset Password
                        </button>

                        <div class="back-to-login">
                            <a href="{{ route('login') }}" class="back-link">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/forgot-password.js') }}"></script>
</body>
</html>