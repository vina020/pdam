<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PDAM Magetan - Registrasi</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Navbar Component -->
<header class="header">
    <div class="container">
        <div class="header-content">
    <!-- Logo -->
    <div class="logo">
        <img src="{{ asset('images/logo pdam.png') }}" alt="Logo PDAM Magetan">
        <div class="logo-text">
            <h1>PDAM Magetan</h1>
            <p>Perusahaan Daerah Air Minum</p>
        </div>
    </div>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navigation -->
    <nav class="nav" id="mainNav">
        <ul class="nav-menu">
    <li><a href="{{ route('homepage') }}" class="{{ request()->routeIs('homepage') ? 'active' : '' }}">Beranda</a></li>
    <li><a href="{{ route('layanan') }}" class="{{ request()->routeIs('layanan') ? 'active' : '' }}">Layanan</a></li>

    <!-- Menu dropdown informasi -->
   <li class="profile-menu">
    <a class="profile-toggle {{ request()->routeIs('informasi*') || request()->routeIs('maklumat-pelayanan') ? 'active' : '' }}">
        Informasi
</a>
<ul class="profile-dropdown">
    <li>
        <a href="{{ route('maklumat-pelayanan') }}">
            <i class="fas fa-file-invoice-dollar"></i>
            Maklumat Pelayanan
</a>
</li>
<li>
        <a href="{{ route('informasi.tarif-air') }}">
            <i class="fas fa-tint"></i>
            Tarif Air
</a>
</li>
<li>
        <a href="{{ route('berita') }}">
            <i class="fas fa-newspaper"></i>
           Berita
</a>
</li>
</ul>
</li>

<!-- Menu dropdown profil -->
    <li class="profile-menu">
    <a class="profile-toggle {{ request()->routeIs('profil*') || request()->routeIs('visi-misi') ? 'active' : '' }}">
        Profil
</a>
<ul class="profile-dropdown">
</li>
<li>
        <a href="{{ route('visi-misi') }}">
            <i class="fas fa-compass"></i>
           Visi Misi
</a>
</li>
<li>
        <a href="{{ route('sejarah') }}">
            <i class="fas fa-clock-rotate-left"></i>
           Sejarah
</a>
</li>
<li>
        <a href="{{ route('dewan-pengawas') }}">
            <i class="fas fa-scale-balanced"></i>
           Tata Kelola PDAM
</a>
</li>
</ul>
</li>
    <li><a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'active' : '' }}">Kontak</a></li>
</ul>

        <ul class="nav-auth">
            @guest
                <li><a href="{{ route('login') }}" class="btn-login">Login</style></a></li>
                <li><a href="{{ route('registrasi') }}" class="btn-register" style="font-color:white">Daftar</a></li>
            @endguest

            @auth
                <li class="profile-menu">
                    <a class="profile-toggle">
                        @if(Auth::user()->foto_profil)
                        <img src="{{ asset(Auth::user()->foto_profil) }}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
                        @else
                        <i class="fas fa-user-circle"></i>
                        @endif
                        {{ Auth::user()->nama_lengkap }}
                    </a>
                    <ul class="profile-dropdown">
                        <li>
                <a href="{{ route('user.user-pengaturan') }}" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    Pengaturan
                </a>
            </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </nav>
</div>
</header>
<script>

</script>
<script src="/js/main.js"></script>
</body>