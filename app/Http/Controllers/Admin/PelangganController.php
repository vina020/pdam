<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function getAktif()
{
    $pelanggans = Pelanggan::where('status_pelanggan', 'aktif')
        ->select('no_pelanggan', 'nama_pelanggan', 'alamat_lengkap', 'jenis_pelanggan')
        ->orderBy('nama_pelanggan')
        ->get();
    
    return response()->json([
        'success' => true,
        'data' => $pelanggans
    ]);
}
}
