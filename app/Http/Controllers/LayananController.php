<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasangBaru;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    /**
     * Tampilkan halaman info pasang baru
     */
    public function pasangBaru()
    {
        return view('layanan.pasang-baru');
    }

    /**
     * Tampilkan form pendaftaran pasang baru
     */
    public function formPendaftaran()
    {
        return view('layanan.form-pendaftaran');
    }

    /* Submit form pasang baru */
    public function pasangBaruSubmit(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            // Data Pemohon
            'nama_pemohon' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'alamat_pemohon' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            
            // Lokasi Pemasangan
            'jalan' => 'required|string|max:255',
            'nomor_rumah' => 'required|string|max:50',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'daya_listrik' => 'required|string|max:50',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            
            // Upload Dokumen
            'dokumen_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_pbb' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_listrik' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_domisili' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Agreement
            'agree_terms' => 'required|accepted'
        ], [
            // Custom error messages
            'nama_pemohon.required' => 'Nama pemohon wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'nik.digits' => 'NIK harus 16 digit',
            'no_telepon.required' => 'Nomor telepon wajib diisi',
            'dokumen_ktp.required' => 'Dokumen KTP wajib diupload',
            'dokumen_ktp.max' => 'Ukuran file KTP maksimal 2MB',
            'dokumen_kk.required' => 'Dokumen Kartu Keluarga wajib diupload',
            'dokumen_pbb.required' => 'Dokumen PBB wajib diupload',
            'dokumen_listrik.required' => 'Dokumen rekening listrik wajib diupload',
            'dokumen_domisili.required' => 'Dokumen surat domisili wajib diupload',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan'
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor registrasi
            $nomorRegistrasi = $this->generateNomorRegistrasi();

            // Upload dokumen-dokumen
            $dokumenPaths = [];
            $dokumenFields = ['dokumen_ktp', 'dokumen_kk', 'dokumen_pbb', 'dokumen_listrik', 'dokumen_domisili'];
            
            foreach ($dokumenFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '_' . Str::slug($validated['nama_pemohon']) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('public/dokumen_pasang_baru', $filename);
                    $dokumenPaths[$field] = str_replace('public/', '', $path);
                }
            }

            // Simpan data ke database
            $pasangBaru = PasangBaru::create([
                // Nomor Registrasi
                'nomor_registrasi' => $nomorRegistrasi,
                
                // Data Pemohon
                'nama_pemohon' => $validated['nama_pemohon'],
                'nik' => $validated['nik'],
                'alamat_pemohon' => $validated['alamat_pemohon'],
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'] ?? null,
                
                // Lokasi Pemasangan
                'jalan' => $validated['jalan'],
                'nomor_rumah' => $validated['nomor_rumah'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'kecamatan' => $validated['kecamatan'],
                'kelurahan' => $validated['kelurahan'],
                'daya_listrik' => $validated['daya_listrik'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                
                // Dokumen
                'dokumen_ktp' => $dokumenPaths['dokumen_ktp'] ?? null,
                'dokumen_kk' => $dokumenPaths['dokumen_kk'] ?? null,
                'dokumen_pbb' => $dokumenPaths['dokumen_pbb'] ?? null,
                'dokumen_listrik' => $dokumenPaths['dokumen_listrik'] ?? null,
                'dokumen_domisili' => $dokumenPaths['dokumen_domisili'] ?? null,
                
                // Status
                'status' => 'pending', // pending, verifikasi, survei, approved, ditolak
                'tanggal_pengajuan' => now(),
            ]);

            DB::commit();
            
return response()->json([
    'success' => true,
    'message' => 'Permohonan pasang baru berhasil diajukan! Nomor registrasi Anda: ' . $nomorRegistrasi,
    'nomor_registrasi' => $nomorRegistrasi
]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($dokumenPaths as $path) {
                if (Storage::exists('public/' . $path)) {
                    Storage::delete('public/' . $path);
                }
            }

return response()->json([
    'success' => false,
    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
], 500);
        }
    }

    /**
     * Generate nomor registrasi unik
     */
    private function generateNomorRegistrasi()
    {
        $prefix = 'PB'; // Pasang Baru
        $tahun = date('Y');
        $bulan = date('m');
        
        // Ambil nomor urut terakhir bulan ini
        $lastNumber = PasangBaru::whereYear('tanggal_pengajuan', $tahun)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->count();
        
        $urutan = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}/{$tahun}{$bulan}/{$urutan}";
        // Format: PB/202401/0001
    }

    /**
     * Cek status permohonan
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_registrasi' => 'required|string'
        ]);

        $permohonan = PasangBaru::where('nomor_registrasi', $request->nomor_registrasi)->first();

        if (!$permohonan) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor registrasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_registrasi' => $permohonan->nomor_registrasi,
                'nama_pemohon' => $permohonan->nama_pemohon,
                'status' => $permohonan->status,
                'tanggal_pengajuan' => $permohonan->tanggal_pengajuan->format('d F Y'),
                'alamat_pemasangan' => "{$permohonan->jalan} No. {$permohonan->nomor_rumah}, RT {$permohonan->rt}/RW {$permohonan->rw}, {$permohonan->kelurahan}, {$permohonan->kecamatan}"
            ]
        ]);
    }

    /* Admin: List semua permohonan */
    public function adminListPermohonan()
    {
        $permohonan = PasangBaru::orderBy('tanggal_pengajuan', 'desc')->paginate(20);
        
        return view('admin.pasang-baru.index', compact('permohonan'));
    }

    /* Admin: Detail permohonan */
    public function adminDetailPermohonan($id)
    {
        $permohonan = PasangBaru::findOrFail($id);
        
        return view('admin.pasang-baru.detail', compact('permohonan'));
    }

    /* Admin: Update status permohonan */
    public function adminUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verifikasi,survei,approved,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $permohonan = PasangBaru::findOrFail($id);
        $permohonan->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status permohonan berhasil diupdate');
    }
    /**
 * Display informasi sambungan page
 */
public function informasiSambungan()
{
    return view('layanan.informasi-sambungan');
}

/**
 * Check sambungan status
 */
public function cekSambungan(Request $request)
{
    \Log::info('Request received:', $request->all());
    $request->validate([
        'search_type' => 'required|in:no_registrasi,nik',
        'search_value' => 'required|string'
    ]);

    $searchType = $request->search_type;
    $searchValue = $request->search_value;
    \Log::info('Search:', ['type' => $searchType, 'value' => $searchValue]);

    // Find application
    if ($searchType === 'no_registrasi') {
        $application = PasangBaru::where('nomor_registrasi', $searchValue)->first();
    } else {
        $application = PasangBaru::where('nik', $searchValue)->first();
    }

    \Log::info('Found:', ['app' => $application]);

    if (!$application) {
        return response()->json([
            'success' => false,
            'message' => 'Data permohonan tidak ditemukan. Pastikan nomor registrasi atau NIK yang Anda masukkan benar.'
        ]);
    }

    // Build timeline
    $timeline = $this->buildTimeline($application);

    return response()->json([
    'success' => true,
    'application' => [
        'no_registrasi' => $application->nomor_registrasi,
        'tanggal_pengajuan' => $application->tanggal_pengajuan->format('d M Y'),
        'nama' => $application->nama_pemohon,
        'nik' => $application->nik,
        'no_telp' => $application->no_telepon,
        'email' => $application->email ?? '-',
        'alamat_pemasangan' => "{$application->jalan} No. {$application->nomor_rumah}, RT {$application->rt}/RW {$application->rw}, {$application->kelurahan}, {$application->kecamatan}",
        'jenis_sambungan' => 'Sambungan Baru', 
        'golongan' => 'Rumah Tangga', 
        'status' => $application->status,
        'status_label' => $this->getStatusLabel($application->status),
        'status_description' => $this->getStatusDescription($application->status),
        // 'estimasi_selesai' => null, 
        // 'petugas' => null, 
        // 'no_pelanggan' => null, 
        'timeline' => $timeline,
        'documents' => []
    ]
]);
}

/**
 * Build timeline for application
 */
private function buildTimeline($application)
{
    $timeline = [
        [
            'status' => 'pending',
            'title' => 'Permohonan Diterima',
            'date' => $application->created_at->format('d M Y'),
            'description' => 'Permohonan sambungan Anda telah diterima dan menunggu verifikasi.'
        ],
        [
            'status' => 'verifikasi',
            'title' => 'Sedang Diverifikasi',
            'date' => $application->tanggal_diproses ? $application->tanggal_diproses->format('d M Y') : null,
            'description' => 'Permohonan sedang diverifikasi oleh petugas kami.'
        ],
        [
            'status' => 'survei',
            'title' => 'Survei Lapangan',
            'date' => $application->tanggal_survey ? $application->tanggal_survey->format('d M Y') : null,
            'description' => 'Petugas melakukan survey ke lokasi pemasangan.'
        ],
    ];
    if ($application->status === 'ditolak') {
        $timeline[] = [
            'status' => 'ditolak',
            'title' => 'Permohonan Ditolak',
            'date' => $application->tanggal_approved ? $application->tanggal_approved->format('d M Y') : null,
            'description' => $application->catatan ?? 'Permohonan Anda tidak dapat disetujui. Silakan hubungi customer service untuk informasi lebih lanjut.'
        ];
    } else {
        $timeline[] = [
            'status' => 'approved',
            'title' => 'Permohonan Disetujui',
            'date' => $application->tanggal_disetujui ? $application->tanggal_disetujui->format('d M Y') : null,
            'description' => 'Permohonan Anda telah disetujui dan siap untuk pemasangan.'
        ];
        $timeline[] = [
            'status' => 'selesai',
            'title' => 'Selesai',
            'date' => $application->tanggal_pemasangan ? $application->tanggal_pemasangan->format('d M Y') : null,
            'description' => 'Instalasi sambungan air telah selesai dipasang.'
        ];
    }

    return $timeline;
}

/**
 * Get status label
 */
private function getStatusLabel($status)
{
    $labels = [
        'pending' => 'Menunggu Verifikasi',
        'verifikasi' => 'Sedang Diverifikasi',
        'survei' => 'Survei Lapangan',
        'approved' => 'Disetujui',
        'pemasangan' => 'Proses Pemasangan',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak'
    ];

    return $labels[$status] ?? 'Status Tidak Diketahui';
}

/**
 * Get status description
 */
private function getStatusDescription($status)
{
    $descriptions = [
        'pending' => 'Permohonan Anda sedang menunggu verifikasi dari petugas kami. Kami akan segera menghubungi Anda.',
        'verifikasi' => 'Permohonan Anda sedang dalam proses verifikasi. Tim kami akan menghubungi Anda untuk konfirmasi lebih lanjut.',
        'survei' => 'Petugas kami sedang melakukan survei ke lokasi pemasangan untuk memastikan kelayakan instalasi.',
        'approved' => 'Selamat! Permohonan Anda telah disetujui. Kami akan segera mengatur jadwal pemasangan.',
        'selesai' => 'Sambungan air Anda telah aktif dan siap digunakan. Terima kasih telah menggunakan layanan PDAM Magetan.',
        'ditolak' => 'Mohon maaf, permohonan Anda tidak dapat disetujui. Silakan hubungi customer service untuk informasi lebih lanjut.'
    ];

    return $descriptions[$status] ?? 'Status Tidak Diketahui.';
}

/**
 * Get documents for application
 */
private function getDocuments($application)
{
    $documents = [];

    if ($application->status === 'disetujui' || $application->status === 'selesai') {
        if ($application->surat_persetujuan_path) {
            $documents[] = [
                'name' => 'Surat Persetujuan',
                'size' => '245 KB',
                'url' => asset('storage/' . $application->surat_persetujuan_path)
            ];
        }

        if ($application->status === 'selesai' && $application->berita_acara_path) {
            $documents[] = [
                'name' => 'Berita Acara Pemasangan',
                'size' => '312 KB',
                'url' => asset('storage/' . $application->berita_acara_path)
            ];
        }
    }

    return $documents;
}
/**
 * Display maklumat pelayanan page
 */
public function maklumatPelayanan()
{
    return view('layanan.maklumat-pelayanan');
}

/**
 * Display tarif air page
 */
public function tarifAir()
{
    return view('layanan.tarif-air');
}

/**
 * Admin: Get all data dengan pagination dan filter
 */
public function adminGetAllPasangBaru(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $search = $request->get('search', '');
    $status = $request->get('status', '');
    $kecamatan = $request->get('kecamatan', '');

    $query = PasangBaru::orderBy('created_at', 'desc');

    // Filter search
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_pemohon', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%")
              ->orWhere('nomor_registrasi', 'like', "%{$search}%");
        });
    }

    // Filter status
    if ($status) {
        $query->where('status', $status);
    }

    // Filter kecamatan
    if ($kecamatan) {
        $query->where('kecamatan', $kecamatan);
    }

    $data = $query->paginate($perPage);

    // Get stats
    $stats = [
        'pending' => PasangBaru::where('status', 'pending')->count(),
        'verifikasi' => PasangBaru::where('status', 'verifikasi')->count(),
        'survei' => PasangBaru::where('status', 'survei')->count(),
        'approved' => PasangBaru::where('status', 'approved')->count(),
        'ditolak' => PasangBaru::where('status', 'ditolak')->count(),
        'selesai'=> PasangBaru::where('status', 'selesai')->count(),
    ];

    return response()->json([
        'success' => true,
        'data' => $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor_registrasi' => $item->nomor_registrasi,
                'nama_pemohon' => $item->nama_pemohon,
                'nik' => $item->nik,
                'no_telepon' => $item->no_telepon,
                'jalan' => $item->jalan,
                'nomor_rumah' => $item->nomor_rumah,
                'rt' => $item->rt,
                'rw' => $item->rw,
                'kelurahan' => $item->kelurahan,
                'kecamatan' => $item->kecamatan,
                'status' => $item->status,
                'tanggal_pengajuan' => $item->tanggal_pengajuan->format('d M Y H:i'),
            ];
        }),
        'pagination' => [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
        ],
        'stats' => $stats
    ]);
}

/**
 * Admin: Get detail pasang baru
 */
public function adminDetailPasangBaru($id)
{
    $data = PasangBaru::find($id);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $data->id,
            'nomor_registrasi' => $data->nomor_registrasi,
            'nama_pemohon' => $data->nama_pemohon,
            'nik' => $data->nik,
            'alamat_pemohon' => $data->alamat_pemohon,
            'no_telepon' => $data->no_telepon,
            'email' => $data->email,
            'jalan' => $data->jalan,
            'nomor_rumah' => $data->nomor_rumah,
            'rt' => $data->rt,
            'rw' => $data->rw,
            'kelurahan' => $data->kelurahan,
            'kecamatan' => $data->kecamatan,
            'daya_listrik' => $data->daya_listrik,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'dokumen_ktp' => $data->dokumen_ktp,
            'dokumen_kk' => $data->dokumen_kk,
            'dokumen_pbb' => $data->dokumen_pbb,
            'dokumen_listrik' => $data->dokumen_listrik,
            'dokumen_domisili' => $data->dokumen_domisili,
            'status' => $data->status,
            'catatan' => $data->catatan,
            'tanggal_pengajuan' => $data->tanggal_pengajuan->format('d M Y H:i'),
            'tanggal_verifikasi' => $data->tanggal_verifikasi ? $data->tanggal_verifikasi->format('d M Y H:i') : null,
            'tanggal_survei' => $data->tanggal_survei ? $data->tanggal_survei->format('d M Y H:i') : null,
            'tanggal_approved' => $data->tanggal_approved ? $data->tanggal_approved->format('d M Y H:i') : null,
        ]
    ]);
}

/**
 * Admin: Update status pasang baru
 */
public function adminUpdateStatusPasangBaru(Request $request, $id)
{
    $data = PasangBaru::find($id);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    $request->validate([
        'status' => 'required|in:pending,verifikasi,survei,approved,ditolak,selesai',
        'catatan' => 'nullable|string'
    ]);

    // Update status dengan method dari model
    $data->updateStatus($request->status, $request->catatan);

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diupdate'
    ]);
}

/**
 * Admin: Delete pasang baru
 */
public function adminDeletePasangBaru($id)
{
    $data = PasangBaru::find($id);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    // Check if can be deleted
    if (!$data->canBeDeleted()) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak dapat dihapus. Hanya data dengan status pending yang dapat dihapus.'
        ], 403);
    }

    $data->delete();

    return response()->json([
        'success' => true,
        'message' => 'Data berhasil dihapus'
    ]);
}

/**
 * Admin: Get list kecamatan
 */
public function getKecamatanList()
{
    $kecamatan = PasangBaru::select('kecamatan')
        ->distinct()
        ->orderBy('kecamatan')
        ->pluck('kecamatan');

    return response()->json([
        'success' => true,
        'data' => $kecamatan
    ]);
}

}