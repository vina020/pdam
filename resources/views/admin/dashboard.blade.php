<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Admin PDAM</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    @include('component.sidebar-admin')

    <div class="main-content">
        <div class="content-wrapper">
            
            <!-- Header -->
            <div class="page-header">
                <div class="header-left">
                    <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
                    <p>Ringkasan aktivitas dan statistik PDAM Magetan</p>
                </div>
                <div class="header-right">
                    <div class="date-display">
                        <i class="fas fa-calendar"></i>
                        <span id="currentDate"></span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total Pelanggan</span>
            <span class="stat-value" id="totalPelanggan">0</span>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Pendaftaran Baru</span>
            <span class="stat-value" id="pendaftaranBaru">0</span>
            <span class="stat-growth positive" id="pendaftaranGrowth">
                <i class="fas fa-arrow-up"></i> 0%
            </span>
        </div>
    </div>

    <div class="stat-card red">
        <div class="stat-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Tagihan Belum Bayar</span>
            <span class="stat-value" id="tagihanBelumBayar">0</span>
            <span class="stat-growth negative" id="tagihanGrowth">
                <i class="fas fa-arrow-up"></i> 0%
            </span>
        </div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Pengaduan Aktif</span>
            <span class="stat-value" id="pengaduanAktif">0</span>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total Berita</span>
            <span class="stat-value" id="totalBerita">0</span>
        </div>
    </div>

    <div class="stat-card cyan">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Tarif Aktif</span>
            <span class="stat-value" id="totalTarif">0</span>
        </div>
    </div>

    <div class="stat-card indigo">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Maklumat Aktif</span>
            <span class="stat-value" id="maklumatAktif">0</span>
        </div>
    </div>

    <div class="stat-card pink">
        <div class="stat-icon">
            <i class="fas fa-eye"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total Views Berita</span>
            <span class="stat-value" id="totalViews">0</span>
        </div>
    </div>

    <div class="stat-card teal">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Pengaduan Selesai</span>
            <span class="stat-value" id="pengaduanSelesai">0</span>
            <span class="stat-subtitle">Bulan ini</span>
        </div>
    </div>
</div>

            <!-- Charts Section -->
            <div class="charts-grid">
                
                <!-- Chart Pengaduan -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-bar"></i> Pengaduan 7 Hari Terakhir</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartPengaduan"></canvas>
                    </div>
                </div>

                <!-- Chart Status Pengaduan -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-chart-pie"></i> Status Pengaduan</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartStatusPengaduan"></canvas>
                    </div>
                </div>

            </div>

            <!-- Pendaftaran Chart -->
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-line"></i> Pendaftaran 6 Bulan Terakhir</h3>
                </div>
                <div class="chart-body">
                    <canvas id="chartPendaftaran"></canvas>
                </div>
            </div>

            <!-- Recent Activities & Quick Actions -->
            <div class="bottom-grid">
                
                <!-- Recent Activities -->
                <div class="activity-card">
                    <div class="activity-header">
                        <h3><i class="fas fa-history"></i> Aktivitas Terbaru</h3>
                        <button class="btn-refresh" onclick="loadRecentActivities()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="activity-list" id="activityList">
                        <div class="loading-activities">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Memuat aktivitas...</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions-card">
                    <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                    <div class="quick-actions-grid">
                        <a href="{{ route('admin.berita') }}" class="quick-action-btn blue">
                            <i class="fas fa-newspaper"></i>
                            <span>Kelola Berita</span>
                        </a>
                        <a href="{{ route('admin.pengaduan') }}" class="quick-action-btn orange">
                            <i class="fas fa-headset"></i>
                            <span>Kelola Pengaduan</span>
                        </a>
                        <a href="{{ route('admin.tarif-air') }}" class="quick-action-btn green">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Kelola Tarif</span>
                        </a>
                        <a href="{{ route('admin.pasang-baru.index') }}" class="quick-action-btn purple">
                            <i class="fas fa-user-plus"></i>
                            <span>Pasang Baru</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
</body>
</html>