<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Pelayanan - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/maklumat-pelayanan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar Component -->
    @include('component.sidebar')
    
    <!-- Breadcrumb -->
    <nav class="breadcrumbs">
        <div class="container">
            <ul>
                <li><a href="{{ route('homepage') }}">Beranda</a></li>
                <li><a href="#">Informasi</a></li>
                <li class="active">Maklumat Pelayanan</li>
            </ul>
        </div>
    </nav>

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
                    <i class="fas fa-shield-alt"></i>
                    <span>Komitmen Pelayanan</span>
                </div>
                <h1 class="hero-title-modern">Maklumat Pelayanan</h1>
                <p class="hero-subtitle-modern">
                    Komitmen kami untuk memberikan pelayanan terbaik kepada masyarakat Kabupaten Magetan
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            
            <!-- Intro Section -->
            <div class="section-modern">
                <div class="maklumat-intro">
                    <div class="intro-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="intro-content">
                        <h2>Kami Berjanji Memberikan Pelayanan Terbaik</h2>
                        <p>PDAM Kabupaten Magetan berkomitmen untuk memberikan pelayanan air bersih yang berkualitas, cepat, dan profesional kepada seluruh masyarakat. Berikut adalah janji pelayanan kami kepada Anda.</p>
                    </div>
                </div>
            </div>

            <!-- Maklumat Cards -->
            <div class="maklumat-cards">
                
                <!-- Card 1: Standar Pelayanan -->
                <div class="maklumat-card">
                    <div class="card-header">
                        <div class="card-icon blue">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3>Standar Pelayanan</h3>
                    </div>
<div class="card-body">
    <ul class="maklumat-list">
        @forelse($data['standar_pelayanan'] as $item)
        <li>
            <i class="{{ $item->icon ?: 'fas fa-check-circle' }}"></i>
            <div>
                <strong>{{ $item->judul }}</strong>
                @if($item->deskripsi)
                <span>{{ $item->deskripsi }}</span>
                @endif
            </div>
        </li>
        @empty
        <li>
            <i class="fas fa-info-circle"></i>
            <div>
                <span>Belum ada data standar pelayanan</span>
            </div>
        </li>
        @endforelse
    </ul>
</div>
                </div>

                <!-- Card 2: Kualitas Air -->
                <div class="maklumat-card">
                    <div class="card-header">
                        <div class="card-icon cyan">
                            <i class="fas fa-tint"></i>
                        </div>
                        <h3>Kualitas Air</h3>
                    </div>
                    <div class="card-body">
    <ul class="maklumat-list">
        @forelse($data['kualitas_air'] ?? [] as $item)
        <li>
            <i class="{{ $item->icon ?: 'fas fa-check-circle' }}"></i>
            <div>
                <strong>{{ $item->judul }}</strong>
                @if($item->deskripsi)
                <span>{{ $item->deskripsi }}</span>
                @endif
            </div>
        </li>
        @empty
        <li><i class="fas fa-info-circle"></i><div><span>Belum ada data kualitas air</span></div></li>
        @endforelse
    </ul>
</div>
                </div>

                <!-- Card 3: Hak Pelanggan -->
                <div class="maklumat-card">
                    <div class="card-header">
                        <div class="card-icon green">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3>Hak Pelanggan</h3>
                    </div>
                    <div class="card-body">
    <ul class="maklumat-list">
        @forelse($data['hak_pelanggan'] ?? [] as $item)
        <li>
            <i class="{{ $item->icon ?: 'fas fa-check-circle' }}"></i>
            <div>
                <strong>{{ $item->judul }}</strong>
                @if($item->deskripsi)
                <span>{{ $item->deskripsi }}</span>
                @endif
            </div>
        </li>
        @empty
        <li><i class="fas fa-info-circle"></i><div><span>Belum ada data hak pelanggan</span></div></li>
        @endforelse
    </ul>
</div>
                </div>

                <!-- Card 4: Kewajiban Pelanggan -->
                <div class="maklumat-card">
                    <div class="card-header">
                        <div class="card-icon orange">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3>Kewajiban Pelanggan</h3>
                    </div>
                    <div class="card-body">
    <ul class="maklumat-list">
        @forelse($data['kewajiban_pelanggan'] ?? [] as $item)
        <li>
            <i class="{{ $item->icon ?: 'fas fa-check-circle' }}"></i>
            <div>
                <strong>{{ $item->judul }}</strong>
                @if($item->deskripsi)
                <span>{{ $item->deskripsi }}</span>
                @endif
            </div>
        </li>
        @empty
        <li><i class="fas fa-info-circle"></i><div><span>Belum ada data kewajiban pelanggan</span></div></li>
        @endforelse
    </ul>
</div>
                </div>

            </div>

           <!-- Card 5: Sanksi -->
<div class="maklumat-card">
    <div class="card-header">
        <div class="card-icon red">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Sanksi Pelanggaran</h3>
    </div>
    <div class="card-body">
        <ul class="maklumat-list">
            @forelse($data['sanksi'] ?? [] as $item)
            <li>
                <i class="{{ $item->icon ?: 'fas fa-exclamation-triangle' }}"></i>
                <div>
                    <strong>{{ $item->judul }}</strong>
                    @if($item->deskripsi)
                    <span>{{ $item->deskripsi }}</span>
                    @endif
                </div>
            </li>
            @empty
            <li>
                <i class="fas fa-info-circle"></i>
                <div>
                    <span>Belum ada data sanksi</span>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
</div>

<!-- Card 6: Pengaduan -->
<div class="maklumat-card">
    <div class="card-header">
        <div class="card-icon purple">
            <i class="fas fa-headset"></i>
        </div>
        <h3>Saluran Pengaduan</h3>
    </div>
    <div class="card-body">
        <ul class="maklumat-list">
            @forelse($data['pengaduan'] ?? [] as $item)
            <li>
                <i class="{{ $item->icon ?: 'fas fa-headset' }}"></i>
                <div>
                    <strong>{{ $item->judul }}</strong>
                    @if($item->deskripsi)
                    <span>{{ $item->deskripsi }}</span>
                    @endif
                </div>
            </li>
            @empty
            <li>
                <i class="fas fa-info-circle"></i>
                <div>
                    <span>Belum ada data saluran pengaduan</span>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
</div>
    
            <!-- CTA Download -->
            <div class="cta-modern">
                <div class="cta-content-modern">
                    <div class="cta-icon-large">
                        <i class="fas fa-download"></i>
                    </div>
                    <h2>Download Maklumat Pelayanan</h2>
                    <p>Dapatkan salinan lengkap Maklumat Pelayanan PDAM Magetan dalam format PDF</p>
                    <div class="cta-buttons-modern">
                        <a href="#" class="btn-modern btn-primary-modern">
                            <i class="fas fa-file-pdf"></i>
                            Download PDF
                        </a>
                        <a href="{{ route('layanan.pengaduan') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-comment-alt"></i>
                            Ajukan Pengaduan
                        </a>
                    </div>
                    <div class="cta-help-text">
                        <i class="fas fa-shield-alt"></i>
                        <span>Kepuasan Anda adalah prioritas kami</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @include('component.footer')

    <script src="{{ asset('js/maklumat-pelayanan.js') }}"></script>
</div>
</body>
</html>