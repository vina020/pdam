<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar-Admin PDAM Magetan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
/* === SIDEBAR COMPONENT CSS === */

/* === SIDEBAR === */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 280px;
    background: white;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    transition: all 0.3s ease;
    z-index: 1000;
}

body.has-sidebar {
    margin-left: 280px;
    transition: margin-left 0.3s ease;
}

/* Ketika sidebar di-collapse */
body.sidebar-collapsed {
    margin-left: 0;
}

body.sidebar-collapsed .sidebar {
    left: -280px;
}

body.sidebar-collapsed .sidebar-toggle {
    left: 10px;
}

.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(13, 110, 253, 0.3);
    border-radius: 10px;
}

/* === LOGO SECTION === */
.sidebar-logo {
    padding: 25px 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    text-align: center;
}

.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.logo-img {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    overflow: hidden;
}

/* Jika pakai gambar logo */
.logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 5px;
}

.logo-text h3 {
    font-size: 1.1rem;
    color: #212529;
    margin-bottom: 2px;
}

.logo-text p {
    font-size: 0.75rem;
    color: #6c757d;
}

/* === PROFILE SECTION === */
.sidebar-profile {
    padding: 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.profile-card {
    background: linear-gradient(135deg, #0d6efd, #17a2b8);
    padding: 20px;
    border-radius: 12px;
    color: white;
    text-align: center;
}

.profile-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: white;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #0d6efd;
    border: 3px solid rgba(255, 255, 255, 0.3);
    overflow: hidden;
}

/* Avatar dengan gambar */
.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Profile Guest (belum login) */
.profile-card.profile-guest {
    background: linear-gradient(135deg, #6c757d, #495057);
}

.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 15px;
}

.btn-profile-action {
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    background: rgba(255, 255, 255, 0.2);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-profile-action:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.btn-profile-action.btn-outline {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.btn-profile-action.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.8);
}

.profile-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.profile-id {
    font-size: 0.85rem;
    opacity: 0.9;
}

/* === MENU SECTION === */
.sidebar-menu {
    padding: 15px 0;
}

.menu-title {
    padding: 10px 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.menu-item {
    margin: 2px 10px;
}

.menu-link {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 15px;
    color: #212529;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.menu-link i {
    font-size: 1.2rem;
    color: #0d6efd;
    width: 25px;
    text-align: center;
}

.menu-link:hover {
    background: rgba(13, 110, 253, 0.08);
    transform: translateX(5px);
}

.menu-link.active {
    background: linear-gradient(135deg, #0d6efd, #17a2b8);
    color: white;
}

.menu-link.active i {
    color: white;
}

/* === TOGGLE BUTTON === */
.sidebar-toggle {
    position: fixed;
    left: 290px;
    top: 20px;
    width: 40px;
    height: 40px;
    background: #0d6efd;
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    z-index: 1001;
}

.sidebar-toggle:hover {
    background: #0a58ca;
    transform: scale(1.1);
}

.sidebar-toggle i {
    transition: transform 0.3s ease;
}

/* === COLLAPSED STATE === */
.sidebar.collapsed {
    left: -280px;
}

.sidebar-toggle.collapsed {
    left: 10px;
}

.sidebar-toggle.collapsed i {
    transform: rotate(180deg);
}

/* Body class untuk menggeser semua content */
body.sidebar-collapsed .sidebar {
    left: -280px;
}

body.sidebar-collapsed .sidebar-toggle {
    left: 10px;
}

/* === RESPONSIVE === */
@media (max-width: 1024px) {
    /* Mobile - sidebar hidden by default */
    .sidebar {
        left: -280px;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-toggle {
        left: 10px;
    }

    .sidebar-toggle.active {
        left: 290px;
    }

    /* Mobile - no body margin */
    body.has-sidebar {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
    }

    body.sidebar-collapsed {
        margin-left: 0;
    }

    @media (max-width: 1024px) {
        body.has-sidebar{
            margin-left: 0;
        }
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
}

/* === SUBMENU STYLES === */
.menu-item.has-submenu {
    position: relative;
}

.menu-link.menu-toggle {
    position: relative;
}

.menu-link.menu-toggle .submenu-arrow {
    position: absolute;
    right: 15px;
}

.submenu-arrow {
    font-size: 0.8rem !important;
    transition: transform 0.3s ease;
    margin-left: auto;
    width: auto !important;
}

.menu-item.has-submenu.active .submenu-arrow {
    transform: rotate(180deg);
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.menu-item.has-submenu.active .submenu {
    max-height: 500px;
}

.submenu-item {
    margin: 2px 10px 2px 20px;
}

.submenu-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px 10px 35px;
    color: #495057;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    position: relative;
}

.submenu-link::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #0d6efd;
    opacity: 0.5;
}

.submenu-link i {
    font-size: 1rem;
    color: #0d6efd;
    width: 20px;
    text-align: center;
}

.submenu-link:hover {
    background: rgba(13, 110, 253, 0.08);
    transform: translateX(5px);
    padding-left: 40px;
}

.submenu-link.active {
    background: rgba(13, 110, 253, 0.15);
    color: #0d6efd;
    font-weight: 600;
}

.submenu-link.active::before {
    opacity: 1;
    background: #0d6efd;
}
    </style>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <div class="logo-container">
                <div class="logo-img">
                <img src="{{ asset('images/logo pdam.png') }}" alt="PDAM Logo">
            </div>
                <div class="logo-text">
                    <h3>PDAM</h3>
                    <p>Kab. Magetan</p>
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="sidebar-profile">
            @auth
            <div class="profile-card">
                <div class="profile-avatar">
                    @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
                    @else
                    <i class="fas fa-user"></i>
                    @endif
                </div> 
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <div class="profile-id">
                    @if(Auth::user()->pelanggan && Auth::user()->pelanggan->no_pelanggan)
                    {{ Auth::user()->pelanggan->no_pelanggan }}
                    @else
                    {{ Auth::user()->email }}
                    @endif
                </div>
            </div>
            @else
        <!-- Jika user belum login (Guest) -->
        <div class="profile-card profile-guest">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="profile-name">Tamu</div>
            <div class="profile-actions">
                <a href="{{ route('login') }}" class="btn-profile-action">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </a>
                <a href="{{ route('registrasi') }}" class="btn-profile-action btn-outline">
                    <i class="fas fa-user-plus"></i>
                    Daftar
                </a>
            </div>
        </div>
    @endauth
</div>
        </div>

        <!-- Menu Section -->
        <nav class="sidebar-menu">
            <div class="menu-title">Admin</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-table-columns"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <div class="menu-title">Layanan Utama</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('admin.pasang-baru.index') }}" class="menu-link {{ request()->routeIs('admin.pasang-baru.index') ? 'active' : '' }}">
                        <i class="fas fa-faucet"></i>
                        <span>Pasang Baru</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.pengaduan') }}" class="menu-link {{ request()->routeIs('admin.pengaduan') ? 'active' : '' }}" >
                        <i class="fas fa-headset"></i>
                        <span>Pengaduan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.tagihan') }}" class="menu-link {{ request()->routeIs('admin.tagihan') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>Cek Tagihan</span>
                    </a>
                </li>
            </ul>

            <div class="menu-title">Informasi</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('admin.maklumat-pelayanan') }}" class="menu-link {{ request()->routeIs('admin.maklumat-pelayanan') ? 'active' : '' }}">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Maklumat Pelayanan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.tarif-air') }}" class="menu-link {{ request()->routeIs('admin.tarif-air') ? 'active' : '' }}">
                        <i class="fas fa-tint"></i>
                        <span>Tarif Air</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.berita') }}" class="menu-link {{ request()->routeIs('admin.berita') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita</span>
                    </a>
                </li>
            </ul>

            <div class="menu-title">Akun</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('admin.pengaturan') }}" class="menu-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" onclick="confirmLogoutNormal()" class="menu-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </button>
    <script>

// Cache sidebar state
let sidebarInitialized = false;

document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah sidebar udah pernah di-init
    if (sidebarInitialized) {
        console.log('Sidebar udah diinit, skip...');
        return;
    }
    
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Restore sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true') {
        sidebar.classList.add('collapsed');
        sidebarToggle.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
    }

    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth > 1024) {
            // Desktop: collapse/expand
            sidebar.classList.toggle('collapsed');
            sidebarToggle.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        } else {
            // Mobile: show/hide
            sidebar.classList.toggle('active');
            sidebarToggle.classList.toggle('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('active');
            }
        }
    });

    // Close sidebar when clicking overlay (mobile)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarToggle.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('active');
                sidebarToggle.classList.remove('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
            } else {
                sidebar.classList.remove('collapsed');
                sidebarToggle.classList.remove('collapsed');
                document.body.classList.remove('sidebar-collapsed');
            }
        }, 250);
    });

    // Close sidebar on mobile when clicking menu link
    const menuLinks = document.querySelectorAll('.menu-link');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('active');
                sidebarToggle.classList.remove('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
            }
        });
    });

    // Submenu Toggle
    const menuToggles = document.querySelectorAll('.menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            const parentItem = this.closest('.menu-item');

            // Cek apakah ini parent menu yang punya submenu
            if (parentItem.classList.contains('has-submenu')) {
                e.preventDefault(); // Prevent hanya untuk parent menu
                
                const allMenuItems = document.querySelectorAll('.menu-item.has-submenu');
                
                // Close other submenus
                allMenuItems.forEach(item => {
                    if (item !== parentItem) {
                        item.classList.remove('active');
                    }
                });
                
                // Toggle current submenu
                parentItem.classList.toggle('active');
            }
            // Kalau bukan parent (submenu-link), biarkan pindah halaman normal
        });
    });

    // Set active submenu on page load
    const currentPath = window.location.pathname;
    const submenuLinks = document.querySelectorAll('.submenu-link');
    submenuLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // Open parent submenu
            const parentSubmenu = link.closest('.menu-item.has-submenu');
            if (parentSubmenu) {
                parentSubmenu.classList.add('active');
            }
        }
    });
    
    // Mark as initialized
    sidebarInitialized = true;
});

// Listen ke Turbo navigation
document.addEventListener('turbo:load', function() {
    console.log('Turbo navigation detected');
    
    // Restore sidebar state dari localStorage
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (sidebar && sidebarToggle) {
        const sidebarState = localStorage.getItem('sidebarCollapsed');
        if (sidebarState === 'true') {
            sidebar.classList.add('collapsed');
            sidebarToggle.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }
        
        // Re-apply active menu setelah Turbo load
        const currentPath = window.location.pathname;
        const allLinks = document.querySelectorAll('.menu-link, .submenu-link');
        
        allLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                
                // Buka parent submenu kalo link ada di submenu
                const parentSubmenu = link.closest('.menu-item.has-submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('active');
                }
            }
        });
    }
});

// Confirm Logout
function confirmLogoutNormal() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        // Buat form POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        
        // Tambah CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>