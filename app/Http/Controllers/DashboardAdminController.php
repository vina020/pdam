<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getStats()
    {
        // Stats Cards
        $stats = [
            'total_pelanggan' => DB::table('users')->where('role', 'pelanggan')->count(),
            'pendaftaran_baru' => DB::table('pendaftaran_online')
                ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'pengaduan_aktif' => DB::table('pengaduan')
                ->whereIn('status', ['pending', 'proses'])
                ->count(),
            'total_berita' => DB::table('berita')->count(),
            'total_tarif' => DB::table('tarif_air')->where('aktif', true)->count(),
            'pengaduan_selesai' => DB::table('pengaduan')
                ->where('status', 'selesai')
                ->whereMonth('updated_at', Carbon::now()->month)
                ->count(),
            'tagihan_belum_bayar' => DB::table('tagihans')
                ->where('status', 'belum_bayar')
                ->count(),
            'maklumat_aktif' => DB::table('maklumat_pelayanan')
                ->where('aktif', true)
                ->count(),
            'total_views' => DB::table('berita')
                ->sum('views'),
        ];

        // Growth percentages (dibanding bulan lalu)
        $lastMonthPendaftaran = DB::table('pendaftaran_online')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(60))
            ->whereDate('created_at', '<', Carbon::now()->subDays(30))
            ->count();
        
        $stats['pendaftaran_growth'] = $lastMonthPendaftaran > 0 
            ? round((($stats['pendaftaran_baru'] - $lastMonthPendaftaran) / $lastMonthPendaftaran) * 100, 1)
            : 0;

        $lastMonthTagihan = DB::table('tagihans')
            ->where('status', 'belum_bayar')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();

        $stats['tagihan_growth'] = $lastMonthTagihan > 0
            ? round((($stats['tagihan_belum_bayar'] - $lastMonthTagihan) / $lastMonthTagihan) * 100, 1)
            : 0;

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function getChartPengaduan()
    {
        // Data pengaduan 7 hari terakhir
        $data = DB::table('pengaduan')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status breakdown
        $statusBreakdown = DB::table('pengaduan')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'daily' => $data,
            'status' => $statusBreakdown
        ]);
    }

    public function getChartPendaftaran()
    {
        // Pendaftaran per bulan (6 bulan terakhir)
        $data = DB::table('pendaftaran_online')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getRecentActivities()
{
    $activities = [];
    
    // Ambil berita terbaru
    $beritas = \App\Models\Berita::latest()
        ->take(5)
        ->select('id', 'title', 'created_at') 
        ->get()
        ->map(fn($b) => [
            'type' => 'berita',
            'title' => $b->title,
            'time' => $b->created_at->diffForHumans()
        ]);
    
    // Ambil pengaduan terbaru
    $pengaduans = \App\Models\Pengaduan::latest()
        ->take(5)
        ->select('id', 'judul_pengaduan', 'created_at')
        ->get()
        ->map(fn($p) => [
            'type' => 'pengaduan',
            'title' => $p->judul_pengaduan,
            'time' => $p->created_at->diffForHumans()
        ]);
    
    $activities = $beritas->merge($pengaduans)->sortByDesc('time')->take(10);
    
    return response()->json([
        'success' => true,
        'activities' => $activities->values()
    ]);
}
}