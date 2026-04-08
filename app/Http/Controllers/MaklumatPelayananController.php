<?php

namespace App\Http\Controllers;
use App\Models\MaklumatPelayanan;

use Illuminate\Http\Request;

class MaklumatPelayananController extends Controller
{
    public function adminIndex() {
        return view('admin.maklumat-pelayanan');
    }

    public function adminGetAll(Request $request)
{
    try {
        \Log::info('AdminGetAll called', [
            'request' => $request->all()
        ]);

        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', 'all');

        $query = MaklumatPelayanan::orderBy('kategori')->orderBy('urutan');

        // Filter search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($kategori !== 'all') {
            $query->where('kategori', $kategori);
        }

        $data = $query->paginate($perPage);

        \Log::info('Data fetched', ['count' => $data->count()]);

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
    'total' => MaklumatPelayanan::count(),
    'aktif' => MaklumatPelayanan::where('aktif', true)->count(),
    'standar_pelayanan' => MaklumatPelayanan::where('kategori', 'standar_pelayanan')->count(),
    'kualitas_air' => MaklumatPelayanan::where('kategori', 'kualitas_air')->count(),
    'hak_pelanggan' => MaklumatPelayanan::where('kategori', 'hak_pelanggan')->count(),
    'kewajiban_pelanggan' => MaklumatPelayanan::where('kategori', 'kewajiban_pelanggan')->count(),
    'sanksi' => MaklumatPelayanan::where('kategori', 'sanksi')->count(),
    'pengaduan' => MaklumatPelayanan::where('kategori', 'pengaduan')->count(),
]
        ]);

    } catch (\Exception $e) {
        \Log::error('AdminGetAll error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    public function adminGetDetail($id) {
        $data = MaklumatPelayanan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success'=> true,
            'data' => $data
        ]);
    }

    public function adminStore(Request $request)
{
    try {
        \Log::info('Store request:', $request->all());

        $validated = $request->validate([
            'kategori' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean'
        ]);

        \Log::info('Validated:', $validated);

        $data = MaklumatPelayanan::create($validated);

        \Log::info('Created:', $data->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Store error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
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
            $data = MaklumatPelayanan::find($id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kategori' => 'required|string',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'icon' => 'nullable|string',
                'color' => 'nullable|string',
                'urutan' => 'nullable|integer',
                'aktif' => 'nullable|boolean'
            ]);

            $data->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
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
        $data = MaklumatPelayanan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    // Toggle aktif
    public function adminToggleAktif($id)
    {
        $data = MaklumatPelayanan::find($id);

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

public function index() 
{

    // Ambil data berdasarkan kategori
    $data = [
        'standar_pelayanan' => MaklumatPelayanan::where('kategori', 'standar_pelayanan')
            //->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
        
        'kualitas_air' => MaklumatPelayanan::where('kategori', 'kualitas_air')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
        
        'hak_pelanggan' => MaklumatPelayanan::where('kategori', 'hak_pelanggan')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
        
        'kewajiban_pelanggan' => MaklumatPelayanan::where('kategori', 'kewajiban_pelanggan')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
        
        'sanksi' => MaklumatPelayanan::where('kategori', 'sanksi')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
        
        'pengaduan' => MaklumatPelayanan::where('kategori', 'pengaduan')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get(),
    ];

    return view('informasi.maklumat-pelayanan', compact('data'));
}

}
