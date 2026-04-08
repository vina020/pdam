<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    /**
     * Tampilkan form pengaduan
     */
    public function form()
    {
        return view('layanan.form-pengaduan');
    }

    /**
     * Submit pengaduan
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'no_pelanggan' => 'required|string|exists:pelanggan,no_pelanggan',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_whatsapp' => 'required|string|max:20',
            'jenis_pengaduan' => 'required|string',
            'informasi_pengaduan' => 'required|string|min:20',
        ], [
            'no_pelanggan.required' => 'Nomor pelanggan wajib diisi',
            'no_pelanggan.exists' => 'Nomor pelanggan tidak ditemukan',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi',
            'jenis_pengaduan.required' => 'Jenis pengaduan wajib dipilih',
            'informasi_pengaduan.required' => 'Informasi pengaduan wajib diisi',
            'informasi_pengaduan.min' => 'Informasi pengaduan minimal 20 karakter',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor pengaduan
            $nomorPengaduan = $this->generateNomorPengaduan();

            // Simpan pengaduan
            $pengaduan = Pengaduan::create([
                'nomor_pengaduan' => $nomorPengaduan,
                'no_pelanggan' => $validated['no_pelanggan'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'alamat' => $validated['alamat'],
                'no_whatsapp' => $validated['no_whatsapp'],
                'jenis_pengaduan' => $validated['jenis_pengaduan'],
                'informasi_pengaduan' => $validated['informasi_pengaduan'],
                'status' => 'pending',
                'tanggal_pengaduan' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil dikirim! Nomor pengaduan Anda: ' . $nomorPengaduan . '. Tim kami akan segera menindaklanjuti.',
                'nomor_pengaduan' => $nomorPengaduan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate nomor pengaduan unik
     */
    private function generateNomorPengaduan()
    {
        $prefix = 'ADU';
        $tahun = date('Y');
        $bulan = date('m');

        $lastNumber = Pengaduan::whereYear('tanggal_pengaduan', $tahun)
            ->whereMonth('tanggal_pengaduan', $bulan)
            ->count();

        $urutan = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tahun}{$bulan}/{$urutan}";
        // Format: ADU/202601/0001
    }

    /**
     * Cek status pengaduan
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_pengaduan' => 'required|string'
        ]);

        $pengaduan = Pengaduan::where('nomor_pengaduan', $request->nomor_pengaduan)->first();

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor pengaduan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
                'nama_lengkap' => $pengaduan->nama_lengkap,
                'jenis_pengaduan' => $pengaduan->jenis_pengaduan,
                'status' => $pengaduan->status,
                'tanggal_pengaduan' => $pengaduan->tanggal_pengaduan->format('d F Y H:i'),
                'tanggapan' => $pengaduan->tanggapan
            ]
        ]);
    }

/* cek pengaduan user */
public function cekStatusPage () {
return view('layanan.cek-status-pengaduan');
}

public function cekStatusPengaduan(Request $request) {
$request->validate([
    'search_type' => 'required|in:nomor_pengaduan,no_pelanggan',
    'search_value' => 'required|string'
]);
$searchType = $request->search_type;
$searchValue = $request->search_value;

if ($searchType == 'nomor_pengaduan') {
    $pengaduan = Pengaduan::where('nomor_pengaduan', $searchValue)->first();
} else {
    $pengaduan = Pengaduan :: where('no_pelanggan', $searchValue)
    ->orderBy('tanggal_pengaduan', 'desc')
    ->first();
}
if (!$pengaduan) {
    return response()->json([
        'success' => false,
        'message' => 'Data pengaduan tidak ditemukan. Pastikan nomor yang Anda masukkan benar'
    ]);
}
$timeline = $this->buildPengaduanTimeline($pengaduan);
return response()->json([
    'success' => true,
    'pengaduan' => [
        'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
            'tanggal_pengaduan' => $pengaduan->tanggal_pengaduan->format('d M Y'),
            'nama' => $pengaduan->nama_lengkap,
            'no_pelanggan' => $pengaduan->no_pelanggan,
            'no_whatsapp' => $pengaduan->no_whatsapp,
            'alamat' => $pengaduan->alamat,
            'jenis_pengaduan' => $pengaduan->jenis_pengaduan,
            'informasi_pengaduan' => $pengaduan->informasi_pengaduan,
            'status' => $pengaduan->status,
            'status_label' => $this->getPengaduanStatusLabel($pengaduan->status),
            'status_description' => $this->getPengaduanStatusDescription($pengaduan->status),
            'tanggapan' => $pengaduan->tanggapan ?? 'Belum ada tanggapan',
            'timeline' => $timeline,
    ]
]);
}

private function buildPengaduanTimeline($pengaduan) {
    $timeline = [
        [
            'status' => 'pending',
            'title' => 'Pengaduan Diterima',
            'date' => $pengaduan->tanggal_pengaduan->format('d M Y'),
            'description' => 'Pengaduan Anda telah diterima dan menunggu verifikasi dari petugas kami.'
        ],
        [
            'status' => 'proses',
            'title' => 'Sedang Diproses',
            'date' => $pengaduan->tanggal_diproses ? $pengaduan->tanggal_diproses->format('d M Y') : null,
            'description' => 'Pengaduan Anda sedang ditindaklanjuti oleh tim kami.'
        ],
        [
            'status' => 'selesai',
            'title' => 'Selesai',
            'date' => $pengaduan->tanggal_selesai ? $pengaduan->tanggal_selesai->format('d M Y') : null,
            'description' => 'Pengaduan telah selesai ditindaklanjuti.'
        ],
    ];
    return $timeline;
}

private function getPengaduanStatusLabel($status) {
    $labels = [
        'pending' => 'Menunggu Verifikasi',
        'proses' => 'Sedang Diproses',
        'selesai' => 'Selesai Ditindaklanjuti'
    ];
    return $labels[$status] ?? 'Status Tidak Diketahui';
}

private function getPengaduanStatusDescription($status) {
    $description = [
        'pending' => 'Pengaduan Anda sedang menunggu verifikasi. Tim kami akan segera menghubungi Anda untuk konfirmasi lebih lanjut.',
        'proses' => 'Pengaduan Anda sedang dalam proses penanganan. Tim kami sedang bekerja untuk menyelesaikan masalah Anda.',
        'selesai' => 'Pengaduan Anda telah selesai ditindaklanjuti. Terima kasih atas kesabaran Anda.'
    ];
    return $description[$status] ?? 'Status Tidak Diketahui';
}

/**
 * Halaman admin pengaduan
 */
public function adminIndex()
{
    return view('admin.pengaduan');
}

/**
 * Get all pengaduan untuk admin (dengan pagination)
 */
public function adminGetAll(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $search = $request->get('search', '');
    $status = $request->get('status', 'all');

    $query = Pengaduan::orderBy('tanggal_pengaduan', 'desc');

    // Filter search
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nomor_pengaduan', 'like', "%{$search}%")
              ->orWhere('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('no_pelanggan', 'like', "%{$search}%");
        });
    }

    // Filter status
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    $pengaduan = $query->paginate($perPage);

    return response()->json([
        'success' => true,
        'data' => $pengaduan->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor_pengaduan' => $item->nomor_pengaduan,
                'no_pelanggan' => $item->no_pelanggan,
                'nama_lengkap' => $item->nama_lengkap,
                'alamat' => $item->alamat,
                'no_whatsapp' => $item->no_whatsapp,
                'jenis_pengaduan' => $item->jenis_pengaduan,
                'informasi_pengaduan' => $item->informasi_pengaduan,
                'status' => $item->status,
                'tanggapan' => $item->tanggapan ?? '-',
                'tanggal_pengaduan' => $item->tanggal_pengaduan->format('d M Y H:i'),
            ];
        }),
        'pagination' => [
            'current_page' => $pengaduan->currentPage(),
            'last_page' => $pengaduan->lastPage(),
            'per_page' => $pengaduan->perPage(),
            'total' => $pengaduan->total(),
        ],
        'stats' => [
            'total' => Pengaduan::count(),
            'pending' => Pengaduan::where('status', 'pending')->count(),
            'proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
        ]
    ]);
}

/**
 * Get detail pengaduan
 */
public function adminGetDetail($id)
{
    $pengaduan = Pengaduan::find($id);

    if (!$pengaduan) {
        return response()->json([
            'success' => false,
            'message' => 'Pengaduan tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $pengaduan->id,
            'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
            'no_pelanggan' => $pengaduan->no_pelanggan,
            'nama_lengkap' => $pengaduan->nama_lengkap,
            'alamat' => $pengaduan->alamat,
            'no_whatsapp' => $pengaduan->no_whatsapp,
            'jenis_pengaduan' => $pengaduan->jenis_pengaduan,
            'informasi_pengaduan' => $pengaduan->informasi_pengaduan,
            'status' => $pengaduan->status,
            'tanggapan' => $pengaduan->tanggapan ?? '-',
            'tanggal_pengaduan' => $pengaduan->tanggal_pengaduan->format('d M Y H:i'),
            'updated_at' => $pengaduan->updated_at->format('d M Y H:i'),
        ]
    ]);
}

/**
 * Update status pengaduan
 */
public function adminUpdateStatus(Request $request, $id)
{
    \Log::info('=== MASUK METHOD ===', [
        'id' => $id,
        'all_request' => $request->all(),
        'status' => $request->status,
        'tanggapan' => $request->tanggapan
    ]);
    
    try {
        $validated = $request->validate([
            'status' => 'required|in:pending,proses,selesai',
            'tanggapan' => 'nullable|string|max:1000',
        ]);
        
        \Log::info('Validasi berhasil', ['validated' => $validated]);
        
        $pengaduan = Pengaduan::find($id);
        
        if (!$pengaduan) {
            \Log::error('Pengaduan tidak ditemukan', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }
        
        \Log::info('Pengaduan ditemukan, mulai update');
        
        // Simpan status lama untuk pengecekan
        $oldStatus = $pengaduan->status;
        $newStatus = $validated['status'];
        
        // Update data dasar
        $pengaduan->status = $newStatus;
        $pengaduan->tanggapan = $validated['tanggapan'];
        
        // Update tanggal spesifik berdasarkan perubahan status
        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'proses' && !$pengaduan->tanggal_diproses) {
                $pengaduan->tanggal_diproses = now();
            }
            
            if ($newStatus === 'selesai' && !$pengaduan->tanggal_selesai) {
                $pengaduan->tanggal_selesai = now();
                // Juga set tanggal_ditanggapi jika belum ada
                if (!$pengaduan->tanggal_ditanggapi) {
                    $pengaduan->tanggal_ditanggapi = now();
                }
            }
        }
        
        $pengaduan->save();
        
        \Log::info('Update selesai', ['pengaduan' => $pengaduan]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status pengaduan berhasil diupdate'
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validasi gagal', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Update error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Delete pengaduan
 */
public function adminDelete($id)
{
    $pengaduan = Pengaduan::find($id);

    if (!$pengaduan) {
        return response()->json([
            'success' => false,
            'message' => 'Pengaduan tidak ditemukan'
        ], 404);
    }

    $pengaduan->delete();

    return response()->json([
        'success' => true,
        'message' => 'Pengaduan berhasil dihapus'
    ]);
}
}