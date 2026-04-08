<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function page() {
        return view('informasi.berita');
    }
    public function getLatest(Request $request)
    {
        $limit = $request->get('limit', 5);

        $berita = Berita::orderBy('id', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->judul,
                    'image' => $item->foto,
                    'excerpt' => $item->deskripsi,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json($berita);
    }

    public function index()
    {
        $berita = Berita::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->judul,
                    'excerpt' => $item->deskripsi,
                    'content' => $item->konten ?? $item->deskripsi, // sesuaikan nama kolom
                    'category' => $item->kategori ?? 'info', // sesuaikan nama kolom
                    'image' => $item->foto,
                    'author' => $item->penulis ?? 'Admin PDAM', // sesuaikan nama kolom
                    'date' => $item->created_at->format('Y-m-d'),
                    'views' => $item->views ?? 0, // sesuaikan nama kolom
                ];
            });

        return response()->json(['data' => $berita]);
    }

    public function show($id)
    {
        $berita = Berita::find($id);
        
        if (!$berita) {
            return response()->json(['error' => 'Berita tidak ditemukan'], 404);
        }

        $berita->increment('views');

        return response()->json([
            'data' => [
                'id' => $berita->id,
                'title' => $berita->judul,
                'excerpt' => $berita->deskripsi,
                'content' => $berita->konten ?? $berita->deskripsi,
                'category' => $berita->kategori ?? 'info',
                'image' => $berita->foto,
                'author' => $berita->penulis ?? 'Admin PDAM',
                'date' => $berita->created_at->format('Y-m-d'),
                'views' => $berita->views ?? 0,
            ]
        ]);
    }
    // Halaman admin berita
    public function adminIndex()
    {
        return view('admin.berita');
    }

    // Get all berita untuk admin (dengan pagination)
    public function adminGetAll(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = Berita::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $berita = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $berita->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'foto' => $item->foto,
                    'kategori' => $item->kategori ?? 'info',
                    'penulis' => $item->penulis ?? 'Admin PDAM',
                    'views' => $item->views ?? 0,
                    'created_at' => $item->created_at->format('d M Y H:i'),
                ];
            }),
            'pagination' => [
                'current_page' => $berita->currentPage(),
                'last_page' => $berita->lastPage(),
                'per_page' => $berita->perPage(),
                'total' => $berita->total(),
            ]
        ]);
    }

    // Store berita baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'konten' => 'nullable|string',
            'kategori' => 'nullable|string',
            'penulis' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'konten', 'kategori', 'penulis']);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('berita', $filename, 'public');
            $data['foto'] = 'storage/' . $path;
        }

        $berita = Berita::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan',
            'data' => $berita
        ]);
    }

    // Update berita
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'konten' => 'nullable|string',
            'kategori' => 'nullable|string',
            'penulis' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'konten', 'kategori', 'penulis']);

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($berita->foto) {
                $oldPath = str_replace('storage/', '', $berita->foto);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('berita', $filename, 'public');
            $data['foto'] = 'storage/' . $path;
        }

        $berita->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diupdate',
            'data' => $berita
        ]);
    }

    // Delete berita
    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        // Hapus foto
        if ($berita->foto) {
            $path = str_replace('storage/', '', $berita->foto);
            Storage::disk('public')->delete($path);
        }

        $berita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus'
        ]);}
}