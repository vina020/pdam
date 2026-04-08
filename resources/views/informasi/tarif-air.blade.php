<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Air - PDAM Magetan</title>
    <link rel="stylesheet" href="{{ asset('css/layanan-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tarif-air.css') }}">
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
                <li class="active">Tarif Air</li>
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
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Informasi Tarif</span>
                </div>
                <h1 class="hero-title-modern">Tarif Air PDAM Magetan</h1>
                <p class="hero-subtitle-modern">
                    Informasi lengkap mengenai tarif air bersih yang berlaku untuk seluruh wilayah Kabupaten Magetan
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="layanan-content-modern">
        <div class="container">
            
            <!-- Intro Section -->
            <div class="section-modern">
                <div class="tarif-intro">
                    <div class="intro-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="intro-content">
                        <h2>Tarif Berkeadilan untuk Semua</h2>
                        <p>Tarif air PDAM Magetan ditetapkan berdasarkan Peraturan Daerah dengan mempertimbangkan keadilan, kemampuan masyarakat, dan biaya operasional. Tarif dibedakan berdasarkan kategori pelanggan untuk memastikan pelayanan yang merata dan berkeadilan.</p>
                        <div class="intro-badges">
                            <span class="badge-modern blue">
                                <i class="fas fa-certificate"></i>
                                Sesuai Perda
                            </span>
                            <span class="badge-modern green">
                                <i class="fas fa-check-circle"></i>
                                Tarif Terjangkau
                            </span>
                            <span class="badge-modern orange">
                                <i class="fas fa-balance-scale"></i>
                                Adil & Transparan
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kalkulator Tarif -->
            <div class="section-modern">
                <div class="section-header-modern">
                    <div class="section-icon-badge">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h2>Kalkulator Tarif</h2>
                        <p>Hitung estimasi tagihan air Anda</p>
                    </div>
                </div>

                <div class="calculator-box">
                    <div class="calculator-form">
                        <div class="form-group-calc">
                            <label>
                                <i class="fas fa-layer-group"></i>
                                Golongan Pelanggan
                            </label>
                            <select id="golongan" class="form-input-calc">
                                <option value="">-- Pilih Golongan --</option>
                                <option value="sosial">Sosial (Rumah Ibadah, Panti, Sekolah)</option>
                                <option value="rumah_tangga_1">Rumah Tangga I (0-10 m³)</option>
                                <option value="rumah_tangga_2">Rumah Tangga II (11-20 m³)</option>
                                <option value="rumah_tangga_3">Rumah Tangga III (21-30 m³)</option>
                                <option value="rumah_tangga_4">Rumah Tangga IV (>30 m³)</option>
                                <option value="niaga_kecil">Niaga Kecil</option>
                                <option value="niaga_besar">Niaga Besar</option>
                                <option value="industri">Industri</option>
                                <option value="instansi">Instansi Pemerintah</option>
                            </select>
                        </div>

                        <div class="form-group-calc">
                            <label>
                                <i class="fas fa-tachometer-alt"></i>
                                Pemakaian Air (m³)
                            </label>
                            <input type="number" id="pemakaian" class="form-input-calc" placeholder="Masukkan jumlah m³" min="0">
                        </div>

                        <button onclick="hitungTarif()" class="btn-calculate">
                            <i class="fas fa-calculator"></i>
                            Hitung Tagihan
                        </button>
                    </div>

                    <div class="calculator-result" id="calculatorResult" style="display: none;">
                        <div class="result-header">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h3>Estimasi Tagihan</h3>
                        </div>
                        <div class="result-breakdown">
                            <div class="breakdown-item">
                                <span>Pemakaian Air</span>
                                <strong id="result-pemakaian">0 m³</strong>
                            </div>
                            <div class="breakdown-item">
                                <span>Tarif per m³</span>
                                <strong id="result-tarif">Rp 0</strong>
                            </div>
                            <div class="breakdown-item">
                                <span>Biaya Pemakaian</span>
                                <strong id="result-biaya">Rp 0</strong>
                            </div>
                            <div class="breakdown-item">
                                <span>Biaya Admin</span>
                                <strong>Rp 2.500</strong>
                            </div>
                            <div class="breakdown-item">
                                <span>Biaya Pemeliharaan</span>
                                <strong>Rp 1.000</strong>
                            </div>
                            <div class="breakdown-total">
                                <span>Total Tagihan</span>
                                <strong id="result-total">Rp 0</strong>
                            </div>
                        </div>
                        <div class="result-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Perhitungan ini adalah estimasi. Tagihan aktual dapat berbeda tergantung kondisi lapangan dan kebijakan yang berlaku.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Tarif -->
            <div class="section-modern">
                <div class="section-header-modern">
                    <div class="section-icon-badge">
                        <i class="fas fa-table"></i>
                    </div>
                    <div>
                        <h2>Daftar Tarif Air</h2>
                        <p>Tarif yang berlaku berdasarkan golongan pelanggan</p>
                    </div>
                </div>

                <!-- Tarif Tabs -->
                <div class="tarif-tabs">
                    <button class="tab-btn active" onclick="switchTab('all')">
                        <i class="fas fa-th-list"></i>
                        Semua Tarif
                    </button>
                    <button class="tab-btn" onclick="switchTab('sosial')">
                        <i class="fas fa-hands-helping"></i>
                        Sosial
                    </button>
                    <button class="tab-btn" onclick="switchTab('rumah_tangga')">
                        <i class="fas fa-home"></i>
                        Rumah Tangga
                    </button>
                    <button class="tab-btn" onclick="switchTab('niaga')">
                        <i class="fas fa-store"></i>
                        Niaga
                    </button>
                    <button class="tab-btn" onclick="switchTab('lainnya')">
                        <i class="fas fa-ellipsis-h"></i>
                        Lainnya
                    </button>
                </div>

                <!-- Tarif Tables -->
                <div class="tarif-content">
                    
                    <!-- Tab All/Sosial -->
                    <div class="tarif-table-container" data-tab="all">
                        <!-- sosial -->
                         @if($tarifByKategori['sosial']->count() > 0)
                        <div class="tarif-category">
                            <div class="category-header">
                                <div class="category-icon sosial">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                                <div>
                                    <h3>Golongan Sosial</h3>
                                    <p>Rumah ibadah, panti asuhan, sekolah negeri</p>
                                </div>
                            </div>
                            <div class="tarif-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Blok Pemakaian</th>
                                            <th>Tarif per m³</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarifByKategori['sosial'] ?? [] as $tarif)
                                        <tr>
                                            <td>{{ $tarif->blok_pemakaian }} @if($tarif->sub_kategori) <span class="sub-label">({{ $tarif->sub_kategori }})</span> @endif</td>
                                            <td class="price">Rp {{ number_format($tarif->tarif_per_m3, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- rumah tangga -->
                        @if($tarifByKategori['rumah_tangga']->count() > 0)
                        <div class="tarif-category">
                            <div class="category-header">
                                <div class="category-icon rumah-tangga">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <h3>Golongan Rumah Tangga</h3>
                                    <p>Pelanggan perumahan dan tempat tinggal</p>
                                </div>
                            </div>
                            <div class="tarif-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Blok Pemakaian</th>
                                            <th>Tarif per m³</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarifByKategori['rumah_tangga'] ?? [] as $tarif)
                                        <tr>
                                            <td>{{ $tarif->blok_pemakaian }} @if($tarif->sub_kategori) <span class="sub-label">({{ $tarif->sub_kategori }})</span> @endif</td>
                                            <td class="price">Rp {{ number_format($tarif->tarif_per_m3, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        
                        <!-- niaga -->
                        @if($tarifByKategori['niaga']->count() > 0)
                        <div class="tarif-category">
                            <div class="category-header">
                                <div class="category-icon niaga">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div>
                                    <h3>Golongan Niaga</h3>
                                    <p>Usaha perdagangan dan jasa</p>
                                </div>
                            </div>
                            <div class="tarif-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Tarif per m³</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarifByKategori['niaga'] ?? [] as $tarif)
                                        <tr>
                                            <td>{{ $tarif->blok_pemakaian }} @if($tarif->sub_kategori) <span class="sub-label">({{ $tarif->sub_kategori }})</span> @endif</td>
                                            <td class="price">Rp {{ number_format($tarif->tarif_per_m3, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- indusri -->
                        @if($tarifByKategori['industri']->count() > 0)
                        <div class="tarif-category">
                            <div class="category-header">
                                <div class="category-icon industri">
                                    <i class="fas fa-industry"></i>
                                </div>
                                <div>
                                    <h3>Golongan Industri</h3>
                                    <p>Pabrik dan usaha industri</p>
                                </div>
                            </div>
                            <div class="tarif-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Tarif per m³</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarifByKategori['industri'] ?? [] as $tarif)
                                        <tr>
                                            <td>{{ $tarif->sub_kategori ?? 'Industri' }}</td>
                                            <td class="price">Rp {{ number_format($tarif->tarif_per_m3, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- instansi -->
                        @if($tarifByKategori['instansi']->count() > 0)
                        <div class="tarif-category">
                            <div class="category-header">
                                <div class="category-icon instansi">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <h3>Golongan Instansi</h3>
                                    <p>Kantor pemerintah dan BUMN</p>
                                </div>
                            </div>
                            <div class="tarif-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Tarif per m³</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tarifByKategori['instansi'] ?? [] as $tarif)
                                        <tr>
                                            <td>{{ $tarif->sub_kategori ?? 'Instansi Pemerintah' }}</td>
                                            <td class="price">Rp {{ number_format($tarif->tarif_per_m3, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Biaya Lain-lain -->
            <div class="section-modern">
                <div class="section-header-modern">
                    <div class="section-icon-badge">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <h2>Biaya Lain-lain</h2>
                        <p>Biaya tambahan yang dikenakan</p>
                    </div>
                </div>

                <div class="biaya-grid">
                    <div class="biaya-card">
                        <div class="biaya-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4>Biaya Administrasi</h4>
                        <div class="biaya-amount">Rp 2.500</div>
                        <p>Per bulan untuk semua golongan</p>
                    </div>

                    <div class="biaya-card">
                        <div class="biaya-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4>Biaya Pemeliharaan</h4>
                        <div class="biaya-amount">Rp 1.000</div>
                        <p>Per bulan untuk maintenance</p>
                    </div>

                    <div class="biaya-card">
                        <div class="biaya-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>Denda Keterlambatan</h4>
                        <div class="biaya-amount">2%</div>
                        <p>Per bulan dari total tagihan</p>
                    </div>

                    <div class="biaya-card">
                        <div class="biaya-icon">
                            <i class="fas fa-plug"></i>
                        </div>
                        <h4>Biaya Sambung Kembali</h4>
                        <div class="biaya-amount">Rp 50.000</div>
                        <p>Untuk pemasangan kembali</p>
                    </div>
                </div>
            </div>

            <!-- Info Penting -->
            <div class="section-modern">
                <div class="info-important">
                    <div class="info-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informasi Penting</h3>
                    </div>
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-check"></i>
                            Tarif yang tercantum sudah termasuk PPN 11%
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Pembayaran dapat dilakukan sebelum tanggal 20 setiap bulan
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Denda keterlambatan dikenakan setelah tanggal jatuh tempo
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Pemutusan sambungan dilakukan jika tunggakan lebih dari 2 bulan
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Tarif dapat berubah sewaktu-waktu sesuai kebijakan pemerintah daerah
                        </li>
                    </ul>
                </div>
            </div>

            <!-- CTA -->
            <div class="cta-modern">
                <div class="cta-content-modern">
                    <div class="cta-icon-large">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h2>Ada Pertanyaan Tentang Tarif?</h2>
                    <p>Hubungi customer service kami untuk informasi lebih lanjut mengenai tarif air dan cara pembayaran</p>
                    <div class="cta-buttons-modern">
                        <a href="tel:03511234567" class="btn-modern btn-primary-modern">
                            <i class="fas fa-phone"></i>
                            Hubungi Kami
                        </a>
                        <a href="{{ route('layanan.cek-tagihan') }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-file-invoice"></i>
                            Cek Tagihan
                        </a>
                    </div>
                    <div class="cta-help-text">
                        <i class="fas fa-clock"></i>
                        <span>Layanan customer service: Senin - Jumat, 07:30 - 16:00 WIB</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @include('component.footer')

    <script src="{{ asset('js/tarif-air.js') }}"></script>
</div>
</body>
</html>