<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function __construct(\App\Services\PDAMApiService $pdamApi)
    {
        $this->pdamApi = $pdamApi;
    }

    public function index()
    {
        $stats = [
            'total_pelanggan' => 0,
            'tagihan_aktif' => 0,
        ];

        return view('layanan.cek-tagihan', compact('stats'));
    }

   public function cek(Request $request)
{
    try {
        // Validasi
        $request->validate([
            'search_type' => 'required|in:no_pelanggan,nosambungan,nama',
            'search_value' => 'required|string'
        ]);

        $searchType = $request->search_type;
        $searchValue = $request->search_value;

        // Query database lokal
        $query = Tagihan::query();

        switch ($searchType) {
            case 'no_pelanggan':
                $query->where('no_pelanggan', $searchValue);
                break;
            case 'nosambungan':
                $query->where('nosambungan', $searchValue);
                break;
            case 'nama':
                $query->where('nama_pelanggan', 'LIKE', "%{$searchValue}%");
                break;
        }

        $tagihansLocal = $query->orderBy('periode', 'desc')->get();

        // Jika ada data lokal
        if ($tagihansLocal->isNotEmpty()) {
            return $this->formatLocalResponse($tagihansLocal);
        }

        // Jika tidak ada data lokal, call API
        \Log::info('No local data found, checking API for: ' . $searchValue . ' (type: ' . $searchType . ')');

        // Skip API call untuk search by nama (API tidak support)
        if ($searchType === 'nama') {
            \Log::info('Search by nama, skipping API call');
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau belum ada tagihan.'
            ], 404);
        }

        // Call API untuk no_pelanggan dan nosambungan
        try {
            \Log::info('Calling PDAM API for: ' . $searchValue . ' (type: ' . $searchType . ')');
            
            if (!$this->pdamApi) {
                \Log::error('PDAMApiService not initialized');
                return response()->json([
                    'success' => false,
                    'message' => 'Service API tidak tersedia.'
                ], 500);
            }
            
            $apiResult = $this->pdamApi->getTagihan($searchValue, $searchType);
            \Log::info('API Result:', ['result' => $apiResult]);
            
            if ($apiResult && isset($apiResult['success']) && $apiResult['success'] && !empty($apiResult['tagihans'])) {
                return response()->json($apiResult);
            }
            
            \Log::warning('API returned empty or unsuccessful result');
            
        } catch (\Exception $e) {
            \Log::error("API call failed: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error API: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }

        // Jika API juga tidak return data
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan atau belum ada tagihan.'
        ], 404);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        \Log::error('TagihanController@cek Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}

    private function formatLocalResponse($tagihans)
    {
        try {
            // Update status terlambat
            foreach ($tagihans as $tagihan) {
                if (method_exists($tagihan, 'isTerlambat') && $tagihan->isTerlambat()) {
                    $tagihan->status = 'terlambat';
                    $tagihan->save();
                }
            }

            // Data pelanggan
            $firstTagihan = $tagihans->first();
            $pelanggan = [
                'no_pelanggan' => $firstTagihan->no_pelanggan ?? '-',
                'nosambungan' => $firstTagihan->nosambungan ?? '-',
                'nama' => $firstTagihan->nama_pelanggan ?? '-',
                'alamat' => $firstTagihan->alamat ?? '-',
            ];

            // Total tagihan belum bayar
            $totalBelumBayar = $tagihans->where('status', '!=', 'sudah_bayar')->sum('total_tagihan');

            // Format data untuk response
            $tagihanList = $tagihans->map(function($tagihan) {
                // Cek method existence
                $denda = method_exists($tagihan, 'getDenda') ? $tagihan->getDenda() : 0;
                $periodeFormat = property_exists($tagihan, 'periode_format') 
                    ? $tagihan->periode_format 
                    : $tagihan->periode;
                $totalBayar = property_exists($tagihan, 'total_bayar')
                    ? $tagihan->total_bayar
                    : ($tagihan->total_tagihan + $denda);

                return [
                    'id' => $tagihan->id,
                    'periode' => $periodeFormat,
                    'periode_raw' => $tagihan->periode,
                    'meter_awal' => $tagihan->meter_awal ?? 0,
                    'meter_akhir' => $tagihan->meter_akhir ?? 0,
                    'pemakaian' => $tagihan->pemakaian ?? 0,
                    'tarif' => number_format($tagihan->tarif_per_m3 ?? 0, 0, ',', '.'),
                    'biaya_pemakaian' => number_format($tagihan->biaya_pemakaian ?? 0, 0, ',', '.'),
                    'biaya_admin' => number_format($tagihan->biaya_admin ?? 0, 0, ',', '.'),
                    'biaya_pemeliharaan' => number_format($tagihan->biaya_pemeliharaan ?? 0, 0, ',', '.'),
                    'total_tagihan' => number_format($tagihan->total_tagihan ?? 0, 0, ',', '.'),
                    'total_tagihan_raw' => $tagihan->total_tagihan ?? 0,
                    'denda' => $denda,
                    'denda_format' => number_format($denda, 0, ',', '.'),
                    'biaya_pembukaan' => $tagihan->biaya_pembukaan ?? 0,
                    'biaya_pembukaan_format' => number_format($tagihan->biaya_pembukaan ?? 0, 0, ',', '.'),
                    'total_bayar' => number_format($totalBayar, 0, ',', '.'),
                    'total_bayar_raw' => $totalBayar,
                    'jatuh_tempo' => $tagihan->tanggal_jatuh_tempo 
                        ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') 
                        : '-',
                    'status' => $tagihan->status ?? 'belum_bayar',
                    'status_label' => $this->getStatusLabel($tagihan->status ?? 'belum_bayar'),
                    'tanggal_bayar' => $tagihan->tanggal_bayar 
                        ? $tagihan->tanggal_bayar->format('d/m/Y') 
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'pelanggan' => $pelanggan,
                'tagihans' => $tagihanList,
                'total_belum_bayar' => number_format($totalBelumBayar, 0, ',', '.'),
                'total_belum_bayar_raw' => $totalBelumBayar,
                'source' => 'local'
            ]);

        } catch (\Exception $e) {
            \Log::error('formatLocalResponse Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error formatting response: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'belum_bayar' => 'Belum Dibayar',
            'sudah_bayar' => 'Lunas',
            'terlambat' => 'Terlambat',
            default => 'Unknown',
        };
    }

    public function detail($id)
    {
        try {
            $tagihan = Tagihan::findOrFail($id);
            $denda = method_exists($tagihan, 'getDenda') ? $tagihan->getDenda() : 0;

            return response()->json([
                'success' => true,
                'tagihan' => [
                    'id' => $tagihan->id,
                    'no_pelanggan' => $tagihan->no_pelanggan,
                    'nama_pelanggan' => $tagihan->nama_pelanggan,
                    'alamat' => $tagihan->alamat,
                    'periode' => $tagihan->periode_format ?? $tagihan->periode,
                    'meter_awal' => $tagihan->meter_awal ?? 0,
                    'meter_akhir' => $tagihan->meter_akhir ?? 0,
                    'pemakaian' => $tagihan->pemakaian ?? 0,
                    'tarif' => number_format($tagihan->tarif_per_m3 ?? 0, 0, ',', '.'),
                    'biaya_pemakaian' => number_format($tagihan->biaya_pemakaian ?? 0, 0, ',', '.'),
                    'biaya_admin' => number_format($tagihan->biaya_admin ?? 0, 0, ',', '.'),
                    'biaya_pemeliharaan' => number_format($tagihan->biaya_pemeliharaan ?? 0, 0, ',', '.'),
                    'denda' => number_format($denda, 0, ',', '.'),
                    'total_bayar' => number_format(($tagihan->total_tagihan ?? 0) + $denda, 0, ',', '.'),
                    'jatuh_tempo' => $tagihan->tanggal_jatuh_tempo 
                        ? $tagihan->tanggal_jatuh_tempo->format('d F Y') 
                        : '-',
                    'status' => $tagihan->status ?? 'belum_bayar',
                    'status_label' => $this->getStatusLabel($tagihan->status ?? 'belum_bayar'),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('detail Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}