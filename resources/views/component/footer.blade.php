<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PDAM Magetan - Registrasi</title>
    {{-- CSS FOOTER (WAJIB) --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Footer Component -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>PDAM Magetan</h4>
                <p>Perusahaan Daerah Air Minum Kabupaten Magetan, melayani dengan sepenuh hati untuk masyarakat Magetan.</p>
            </div>
            <div class="footer-col">
                <h4>Link Cepat</h4>
                <ul>
                    <li><a href="{{ route('homepage') }}">Beranda</a></li>
                    <li><a href="{{ route('layanan') }}">Layanan</a></li>
                    <li><a href="{{ route('informasi') }}">Informasi</a></li>
                    <li><a href="{{ route('profile') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('kontak') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Jl. Raya Magetan, Jawa Timur</li>
                    <li><i class="fas fa-phone"></i> (0351) 123456</li>
                    <li><i class="fas fa-envelope"></i> info@pdammagetan.com</li>
                    <li><i class="fas fa-clock"></i> Senin - Jumat: 08.00 - 16.00 WIB</li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="footer-newsletter">
                    <p style="margin-top: 20px; margin-bottom: 10px; font-size: 0.9rem;">Berlangganan Newsletter</p>
                    <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Email Anda" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} PDAM Magetan. All rights reserved.</p>
        </div>
    </div>
</footer>
<script src="{{ asset('js/main.js') }}"></script>
</body>