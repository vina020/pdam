<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\TarifAir;
use Carbon\Carbon;

class CekTagihanController extends Controller
{
    // Halaman admin cek tagihan
    public function index() {
    $stats = [
            'total_pelanggan' => Pelanggan::where('status_pelanggan', 'aktif')->count(),
            'tagihan_aktif' => Tagihan::where('status', 'belum_bayar')->count(),
        ];
        
        return view('admin.cek-tagihan', compact('stats'));
    }

    // Get tarif berdasarkan kategori dan pemakaian
    private function hitungTarif($kategori, $pemakaian)
    {
        // Ambil semua tarif untuk kategori ini, urut dari terkecil
        $tarifList = TarifAir::where('kategori', $kategori)
            ->where('aktif', true)
            ->orderBy('min_pemakaian')
            ->get();

        $totalBiaya = 0;
        $sisaPemakaian = $pemakaian;
        $detailTarif = [];

        foreach ($tarifList as $tarif) {
            if ($sisaPemakaian <= 0) break;

            // Hitung berapa m3 yang masuk di blok ini
            $maxBlok = $tarif->max_pemakaian ?? PHP_INT_MAX;
            $minBlok = $tarif->min_pemakaian;
            $rangeBlok = $maxBlok - $minBlok + 1;

            // Berapa m3 yang akan dihitung di blok ini
            $pemakaianBlok = min($sisaPemakaian, $rangeBlok);

            // Hitung biaya blok ini
            $biayaBlok = $pemakaianBlok * $tarif->tarif_per_m3;
            $totalBiaya += $biayaBlok;

            // Simpan detail untuk response
            $detailTarif[] = [
                'blok' => $tarif->blok_pemakaian,
                'pemakaian' => $pemakaianBlok,
                'tarif' => $tarif->tarif_per_m3,
                'biaya' => $biayaBlok
            ];

            $sisaPemakaian -= $pemakaianBlok;
        }

        return [
            'total_biaya' => $totalBiaya,
            'detail' => $detailTarif,
            'tarif_rata2' => $pemakaian > 0 ? round($totalBiaya / $pemakaian) : 0
        ];
    }

    // API untuk preview perhitungan (dipanggil saat user ketik)
    public function previewTagihan(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required|exists:pelanggan,no_pelanggan',
            'meter_awal' => 'required|integer',
            'meter_akhir' => 'required|integer|gt:meter_awal'
        ]);

        $pelanggan = Pelanggan::where('no_pelanggan', $request->no_pelanggan)->first();
        $pemakaian = $request->meter_akhir - $request->meter_awal;

        // Hitung tarif progresif
        $hasilTarif = $this->hitungTarif($pelanggan->jenis_pelanggan, $pemakaian);

        // Biaya tetap
        $biayaAdmin = 2500;
        $biayaPemeliharaan = 5000;
        $totalTagihan = $hasilTarif['total_biaya'] + $biayaAdmin + $biayaPemeliharaan;

        return response()->json([
            'success' => true,
            'data' => [
                'pemakaian' => $pemakaian,
                'biaya_pemakaian' => $hasilTarif['total_biaya'],
                'tarif_rata2' => $hasilTarif['tarif_rata2'],
                'detail_tarif' => $hasilTarif['detail'],
                'biaya_admin' => $biayaAdmin,
                'biaya_pemeliharaan' => $biayaPemeliharaan,
                'total_tagihan' => $totalTagihan
            ]
        ]);
    }

    // Simpan tagihan
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_pelanggan' => 'required|exists:pelanggan,no_pelanggan',
                'periode' => 'required|date_format:Y-m',
                'meter_awal' => 'required|integer',
                'meter_akhir' => 'required|integer|gt:meter_awal'
            ]);

            // Cek apakah periode ini sudah ada tagihan
            $existing = Tagihan::where('no_pelanggan', $validated['no_pelanggan'])
                ->where('periode', $validated['periode'])
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan untuk periode ini sudah ada'
                ], 422);
            }

            $pelanggan = Pelanggan::where('no_pelanggan', $validated['no_pelanggan'])->first();
            $pemakaian = $validated['meter_akhir'] - $validated['meter_awal'];

            // Hitung tarif progresif
            $hasilTarif = $this->hitungTarif($pelanggan->jenis_pelanggan, $pemakaian);

            // Biaya tetap
            $biayaAdmin = 2500;
            $biayaPemeliharaan = 5000;
            $totalTagihan = $hasilTarif['total_biaya'] + $biayaAdmin + $biayaPemeliharaan;

            // Jatuh tempo 15 hari dari akhir bulan periode
            $periodeTanggal = Carbon::createFromFormat('Y-m', $validated['periode']);
            $jatuhTempo = $periodeTanggal->endOfMonth()->addDays(15);

            // Simpan tagihan
            $tagihan = Tagihan::create([
                'no_pelanggan' => $pelanggan->no_pelanggan,
                'nama_pelanggan' => $pelanggan->nama_pelanggan,
                'alamat' => $pelanggan->alamat_lengkap,
                'periode' => $validated['periode'],
                'meter_awal' => $validated['meter_awal'],
                'meter_akhir' => $validated['meter_akhir'],
                'pemakaian' => $pemakaian,
                'tarif_per_m3' => $hasilTarif['tarif_rata2'],
                'biaya_pemakaian' => $hasilTarif['total_biaya'],
                'biaya_admin' => $biayaAdmin,
                'biaya_pemeliharaan' => $biayaPemeliharaan,
                'total_tagihan' => $totalTagihan,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status' => 'belum_bayar'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil dibuat',
                'data' => $tagihan
            ]);

        } catch (\Exception $e) {
            \Log::error('Error create tagihan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Daftar semua tagihan (untuk admin view)
    public function getAll(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $periode = $request->get('periode', '');

        $query = Tagihan::orderBy('periode', 'desc')
            ->orderBy('no_pelanggan');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('no_pelanggan', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($periode) {
            $query->where('periode', $periode);
        }

        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ]);
    }

        // Search tagihan (AJAX)
    public function search(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required|string'
        ]);

        $noPelanggan = $request->no_pelanggan;
        
        // Dummy data buat testing
        $tagihan = [
            [
                'bulan' => 'Desember 2024',
                'meter_awal' => 100,
                'meter_akhir' => 150,
                'pemakaian' => 50,
                'tagihan' => 250000,
                'status' => 'Belum Bayar'
            ],
            [
                'bulan' => 'November 2024',
                'meter_awal' => 80,
                'meter_akhir' => 100,
                'pemakaian' => 20,
                'tagihan' => 150000,
                'status' => 'Lunas'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $tagihan,
            'no_pelanggan' => $noPelanggan
        ]);
    }
}