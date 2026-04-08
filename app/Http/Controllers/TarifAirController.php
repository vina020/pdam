<?php

namespace App\Http\Controllers;
use App\Models\TarifAir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifAirController extends Controller
{
    // Halaman admin
    public function adminIndex()
    {
        return view('admin.tarif-air');
    }

    // Get all data
    public function adminGetAll(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', 'all');

        $query = TarifAir::orderBy('kategori')->orderBy('urutan');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('blok_pemakaian', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('sub_kategori', 'like', "%{$search}%");
            });
        }

        if ($kategori !== 'all') {
            $query->where('kategori', $kategori);
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
            ],
            'stats' => [
                'total' => TarifAir::count(),
                'aktif' => TarifAir::where('aktif', true)->count(),
                'sosial' => TarifAir::where('kategori', 'sosial')->count(),
                'rumah_tangga' => TarifAir::where('kategori', 'rumah_tangga')->count(),
                'niaga' => TarifAir::where('kategori', 'niaga')->count(),
                'industri' => TarifAir::where('kategori', 'industri')->count(),
            ]
        ]);
    }

    // Get detail
    public function adminGetDetail($id)
    {
        $data = TarifAir::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // Store
    public function adminStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'kategori' => 'required|string',
                'sub_kategori' => 'nullable|string',
                'blok_pemakaian' => 'required|string',
                'min_pemakaian' => 'required|integer|min:0',
                'max_pemakaian' => 'nullable|integer',
                'tarif_per_m3' => 'required|integer|min:0',
                'keterangan' => 'nullable|string',
                'urutan' => 'nullable|integer',
                'aktif' => 'nullable|boolean'
            ]);

            $data = TarifAir::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tarif berhasil ditambahkan',
                'data' => $data
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update
    public function adminUpdate(Request $request, $id)
    {
        try {
            $data = TarifAir::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kategori' => 'required|string',
                'sub_kategori' => 'nullable|string',
                'blok_pemakaian' => 'required|string',
                'min_pemakaian' => 'required|integer|min:0',
                'max_pemakaian' => 'nullable|integer',
                'tarif_per_m3' => 'required|integer|min:0',
                'keterangan' => 'nullable|string',
                'urutan' => 'nullable|integer',
                'aktif' => 'nullable|boolean'
            ]);

            $data->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tarif berhasil diupdate',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete
    public function adminDelete($id)
    {
        $data = TarifAir::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarif berhasil dihapus'
        ]);
    }

    // Toggle aktif
    public function adminToggleAktif($id)
    {
        $data = TarifAir::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->aktif = !$data->aktif;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'data' => $data
        ]);
    }

    public function showTarifPublic() {
        $tarifList = DB::table('tarif_air')
        ->where('aktif', true)
        ->orderBy('kategori')
        ->orderBy('urutan')
        ->get();

        $tarifByKategori = [
            'sosial' => $tarifList->where('kategori', 'sosial'),
            'rumah_tangga' => $tarifList->where('kategori', 'rumah_tangga'),
            'niaga' => $tarifList->where('kategori', 'niaga'),
            'industri' => $tarifList->where('kategori', 'industri'),
            'instansi' => $tarifList->where('kategori', 'instansi'),
        ];

        return view('informasi.tarif-air', compact('tarifByKategori'));
        }

public function getTarifPublicAPI() {
    $tarif = DB::table('tarif_air')
    ->where('aktif', true)
    ->orderBy('kategori')
    ->orderBy('urutan')
    ->get();

    return response()->json([
        'success' => true,
        'data' => $tarif
    ]);
}
}
