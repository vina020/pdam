<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\MaklumatPelayananController;
use App\Http\Controllers\TarifAirController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PengaturanController;

//test
use App\Http\Controllers\ServiceController;

Route::get('/cek-bayar', [ServiceController::class, 'cekBayar']);

//auth (TIDAK PERLU LOGIN)
Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('registrasi.form');
Route::post('/registrasi', [AuthController::class, 'registrasi'])->name('registrasi');
Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('registrasi');
Route::post('/registrasi', [AuthController::class, 'register'])->name('registrasi.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password.post');

// Profile routes (HARUS LOGIN)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AuthController::class, 'update'])->name('profile.update');
});

// Halaman Beranda (TIDAK PERLU LOGIN)
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Halaman Layanan (HARUS LOGIN)
Route::middleware('auth')->group(function () {
    Route::get('/layanan', function () {
        return view('layanan');
    })->name('layanan');
});

// Halaman Tentang Kami/Profile (TIDAK PERLU LOGIN)
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/visi-misi', function () {
    return view('profil.visi-misi');
})->name('visi-misi');

Route::get('/sejarah', function () {
    return view('profil.sejarah');
})->name('sejarah');

Route::get('/dewan-pengawas', function () {
    return view('profil.dewan-pengawas');
})->name('dewan-pengawas');

Route::get('/struktur-organisasi', function () {
    return view('profil.struktur-organisasi');
})->name('struktur-organisasi');

Route::get('/susunan-direksi', function () {
    return view('profil.susunan-direksi');
})->name('susunan-direksi');

// Halaman Kontak (TIDAK PERLU LOGIN)
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// Halaman Informasi (TIDAK PERLU LOGIN - Bisa diakses tanpa login)
Route::get('/informasi', function () {
    return view('informasi');
})->name('informasi');

// Newsletter (POST) (TIDAK PERLU LOGIN)
Route::post('/newsletter/subscribe', function () {
    // Logic untuk subscribe newsletter
    return back()->with('success', 'Terima kasih telah berlangganan!');
})->name('newsletter.subscribe');

// berita homepage (TIDAK PERLU LOGIN)
Route::get('/berita', [BeritaController::class, 'page'])->name('berita');

// API endpoints (TIDAK PERLU LOGIN)
Route::get('/api/berita/latest', [BeritaController::class, 'getLatest']);
Route::get('/api/berita/all', [BeritaController::class, 'index']);
Route::get('/api/berita/{id}', [BeritaController::class, 'show']);

// API Routes untuk Berita (TIDAK PERLU LOGIN)
Route::get('/api/berita/all', [App\Http\Controllers\BeritaController::class, 'index'])
    ->name('api.berita.index');
Route::get('/api/berita/{id}', [App\Http\Controllers\BeritaController::class, 'show'])
    ->name('api.berita.show');

//Informasi (TIDAK PERLU LOGIN - Semua submenu informasi bisa diakses)
Route::get('/informasi/berita', function () {
    return view('informasi.berita');
})->name('berita');

Route::get('/informasi/maklumat-pelayanan', [MaklumatPelayananController::class, 'index'])
    ->name('maklumat-pelayanan');

Route::get('/informasi/tarif-air', [TarifAirController::class, 'showTarifPublic'])
    ->name('informasi.tarif-air');

Route::get('/api/tarif-air/public', [TarifAirController::class, 'getTarifPublicAPI']);

//Layanan (HARUS LOGIN - Semua submenu layanan harus login)
Route::middleware('auth')->group(function () {
    Route::get('/layanan/pasang-baru', function () {
        return view('layanan.pasang-baru');
    })->name('layanan.pasang-baru');

    // Sub menu layanan
    Route::get('/layanan/cek-tagihan', [TagihanController::class, 'index'])->name('layanan.cek-tagihan');
    Route::post('/layanan/cek-tagihan/cek', [TagihanController::class, 'cek'])->name('layanan.cek-tagihan.cek');
    Route::get('/layanan/cek-tagihan/detail/{id}', [TagihanController::class, 'detail'])->name('layanan.cek-tagihan.detail');

    Route::get('/pasang-baru', function () {
        return view('pasang-baru');
    })->name('pasang-baru');

    Route::get('/pasang-baru', [LayananController::class, 'pasangBaru'])
            ->name('pasang-baru');

    //layanan form pendaftaran//
    Route::get('/layanan/form-pendaftaran', [LayananController::class, 'formPendaftaran'])
        ->name('layanan.form-pendaftaran');
        
    // Form pendaftaran
    Route::get('/pasang-baru/daftar', [LayananController::class, 'formPendaftaran'])
        ->name('pasang-baru.form');
        
    // Submit form
    Route::post('/layanan/pasang-baru/submit', [LayananController::class, 'pasangBaruSubmit'])
        ->name('layanan.pasang-baru.submit');
    
    // Cek status permohonan (API)
    Route::post('/layanan/pasang-baru/cek-status', [LayananController::class, 'cekStatus'])
        ->name('layanan.pasang-baru.cek-status');

    // pengaduan //
    Route::prefix('layanan')->group(function () {
        // Form pengaduan
        Route::get('/pengaduan', [PengaduanController::class, 'form'])
            ->name('layanan.pengaduan');
            
        // Submit pengaduan
        Route::post('/pengaduan/submit', [PengaduanController::class, 'submit'])
            ->name('layanan.pengaduan.submit');
            
        // Cek status pengaduan
        Route::get('/cek-status-pengaduan', [PengaduanController::class, 'cekStatusPage'])
            ->name('layanan.cek-status-pengaduan');

        Route::post('/cek-status-pengaduan/cek', [PengaduanController::class, 'cekStatusPengaduan'])
            ->name('layanan.cek-status-pengaduan.cek');
    });

    // Informasi Sambungan Routes
    Route::get('/layanan/informasi-sambungan', [LayananController::class, 'informasiSambungan'])
        ->name('informasi-sambungan');
    Route::post('/layanan/informasi-sambungan/cek', [LayananController::class, 'cekSambungan'])
        ->name('layanan.cek-sambungan');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Cek Tagihan Routes
    Route::prefix('cek-tagihan')->group(function () {
        // Halaman cek tagihan
        Route::get('/', [App\Http\Controllers\Admin\CekTagihanController::class, 'index'])
            ->name('admin.cek-tagihan.index');
        
        // Search tagihan (AJAX)
        Route::post('/search', [App\Http\Controllers\Admin\CekTagihanController::class, 'search'])
            ->name('admin.cek-tagihan.search');
    });
    
    // Tagihan Routes (untuk detail)
    Route::prefix('tagihan')->group(function () {
        
        // Get detail tagihan (AJAX)
        Route::get('/{id}/detail', [App\Http\Controllers\Admin\CekTagihanController::class, 'detail'])
            ->name('admin.tagihan.detail');
    });
    
    // Pelanggan Routes (untuk view detail customer)
    Route::prefix('pelanggan')->group(function () {
    });
    
    Route::get('/pelanggan/aktif', [App\Http\Controllers\Admin\PelangganController::class, 'getAktif']);

    // Admin Berita Routes
    Route::prefix('berita')->group(function () {
        Route::get('/', [App\Http\Controllers\BeritaController::class, 'adminIndex'])
            ->name('admin.berita');
        Route::get('/data', [App\Http\Controllers\BeritaController::class, 'adminGetAll'])
            ->name('admin.berita.data');
        Route::post('/store', [App\Http\Controllers\BeritaController::class, 'store'])
            ->name('admin.berita.store');
        Route::post('/update/{id}', [App\Http\Controllers\BeritaController::class, 'update'])
            ->name('admin.berita.update');
        Route::delete('/delete/{id}', [App\Http\Controllers\BeritaController::class, 'destroy'])
            ->name('admin.berita.delete');
    });

    // Admin Pasang Baru Routes
    Route::prefix('pasang-baru')->group(function () {
        // Halaman utama
        Route::get('/', function() {
            return view('admin.pasang-baru');
        })->name('admin.pasang-baru.index');
        Route::get('/data', [App\Http\Controllers\LayananController::class, 'adminGetAllPasangBaru'])
            ->name('admin.pasang-baru.data');
        Route::get('/kecamatan', [App\Http\Controllers\LayananController::class, 'getKecamatanList'])
            ->name('admin.pasang-baru.kecamatan');
        Route::get('/{id}', [App\Http\Controllers\LayananController::class, 'adminDetailPasangBaru'])
            ->name('admin.pasang-baru.show');
        Route::post('/update-status/{id}', [App\Http\Controllers\LayananController::class, 'adminUpdateStatusPasangBaru'])
            ->name('admin.pasang-baru.update-status');
        Route::delete('/delete/{id}', [App\Http\Controllers\LayananController::class, 'adminDeletePasangBaru'])
            ->name('admin.pasang-baru.delete');
    });

    // Admin Pengaduan Routes
    Route::prefix('pengaduan')->group(function () {
        Route::get('/', [App\Http\Controllers\PengaduanController::class, 'adminIndex'])
            ->name('admin.pengaduan');
        Route::get('/data', [App\Http\Controllers\PengaduanController::class, 'adminGetAll'])
            ->name('admin.pengaduan.data');
        Route::get('/detail/{id}', [App\Http\Controllers\PengaduanController::class, 'adminGetDetail'])
            ->name('admin.pengaduan.detail');
        Route::post('/update-status/{id}', [App\Http\Controllers\PengaduanController::class, 'adminUpdateStatus'])
            ->name('admin.pengaduan.update-status');
        Route::delete('/delete/{id}', [App\Http\Controllers\PengaduanController::class, 'adminDelete'])
            ->name('admin.pengaduan.delete');
    });

    // Admin Routes - Maklumat Pelayanan
    Route::prefix('maklumat-pelayanan')->group(function () {
        Route::get('/', [MaklumatPelayananController::class, 'adminIndex'])->name('admin.maklumat-pelayanan');
        Route::get('/get-all', [MaklumatPelayananController::class, 'adminGetAll'])->name('admin.maklumat-pelayanan.get-all');
        Route::get('/get-detail/{id}', [MaklumatPelayananController::class, 'adminGetDetail'])->name('admin.maklumat-pelayanan.get-detail');
        Route::post('/store', [MaklumatPelayananController::class, 'adminStore'])->name('admin.maklumat-pelayanan.store');
        Route::put('/update/{id}', [MaklumatPelayananController::class, 'adminUpdate'])->name('admin.maklumat-pelayanan.update');
        Route::delete('/delete/{id}', [MaklumatPelayananController::class, 'adminDelete'])->name('admin.maklumat-pelayanan.delete');
        Route::post('/toggle-aktif/{id}', [MaklumatPelayananController::class, 'adminToggleAktif'])->name('admin.maklumat-pelayanan.toggle-aktif');
    });

    // Admin Routes - Tarif Air
    Route::prefix('tarif-air')->group(function () {
        Route::get('/', [TarifAirController::class, 'adminIndex'])->name('admin.tarif-air');
        Route::get('/get-all', [TarifAirController::class, 'adminGetAll'])->name('admin.tarif-air.get-all');
        Route::get('/get-detail/{id}', [TarifAirController::class, 'adminGetDetail'])->name('admin.tarif-air.get-detail');
        Route::post('/store', [TarifAirController::class, 'adminStore'])->name('admin.tarif-air.store');
        Route::put('/update/{id}', [TarifAirController::class, 'adminUpdate'])->name('admin.tarif-air.update');
        Route::delete('/delete/{id}', [TarifAirController::class, 'adminDelete'])->name('admin.tarif-air.delete');
        Route::post('/toggle-aktif/{id}', [TarifAirController::class, 'adminToggleAktif'])->name('admin.tarif-air.toggle-aktif');
    });

    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/stats', [DashboardAdminController::class, 'getStats']);
    Route::get('/dashboard/chart-pengaduan', [DashboardAdminController::class, 'getChartPengaduan']);
    Route::get('/dashboard/chart-pendaftaran', [DashboardAdminController::class, 'getChartPendaftaran']);
    Route::get('/dashboard/recent-activities', [DashboardAdminController::class, 'getRecentActivities']);

    // Pengaturan Akun Routes
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::post('/pengaturan/update-profil', [PengaturanController::class, 'updateProfile'])->name('admin.pengaturan.update-profil');
    Route::post('/pengaturan/upload-foto', [PengaturanController::class, 'uploadFoto'])->name('admin.pengaturan.upload-foto');
    Route::post('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('admin.pengaturan.update-password');
    Route::post('/pengaturan/update-notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('admin.pengaturan.update-notifikasi');
    Route::post('/pengaturan/update-tampilan', [PengaturanController::class, 'updateTampilan'])->name('admin.pengaturan.update-tampilan');
    Route::post('/pengaturan/update-preferensi', [PengaturanController::class, 'updatePreferensi'])->name('admin.pengaturan.update-preferensi');
    Route::post('/pengaturan/logout-all', [PengaturanController::class, 'logoutAll'])->name('admin.pengaturan.logout-all');
    Route::get('/tagihan', [App\Http\Controllers\Admin\CekTagihanController::class, 'index'])->name('admin.tagihan');
    Route::post('/tagihan/preview', [App\Http\Controllers\Admin\CekTagihanController::class, 'previewTagihan'])->name('admin.tagihan.preview');
    Route::post('/tagihan/store', [App\Http\Controllers\Admin\CekTagihanController::class, 'store'])->name('admin.tagihan.store');
    Route::get('/tagihan/all', [App\Http\Controllers\Admin\CekTagihanController::class, 'getAll'])->name('admin.tagihan.all');
});

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'userIndex'])->name('user.user-pengaturan');
    Route::post('/pengaturan/update-profil', [PengaturanController::class, 'userUpdateProfile'])->name('user.pengaturan.update-profil');
    Route::post('/pengaturan/upload-foto', [PengaturanController::class, 'userUploadFoto'])->name('user.pengaturan.upload-foto');
    Route::post('/pengaturan/update-password', [PengaturanController::class, 'userUpdatePassword'])->name('user.pengaturan.update-password');
    Route::post('/pengaturan/update-notifikasi', [PengaturanController::class, 'userUpdateNotifikasi'])->name('user.pengaturan.update-notifikasi');
    Route::post('/pengaturan/update-tampilan', [PengaturanController::class, 'userUpdateTampilan'])->name('user.pengaturan.update-tampilan');
    Route::post('/pengaturan/update-preferensi', [PengaturanController::class, 'userUpdatePreferensi'])->name('user.pengaturan.update-preferensi');
    Route::post('/pengaturan/logout-all', [PengaturanController::class, 'userLogoutAll'])->name('user.pengaturan.logout-all');
});