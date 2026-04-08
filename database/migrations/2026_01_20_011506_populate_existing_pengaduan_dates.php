<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // Fix tanggal_pengaduan yang salah (lebih besar dari created_at)
    DB::table('pengaduan')
        ->whereRaw('tanggal_pengaduan > created_at')
        ->update([
            'tanggal_pengaduan' => DB::raw('created_at')
        ]);

    // Update pengaduan yang statusnya 'proses'
    DB::table('pengaduan')
        ->where('status', 'proses')
        ->whereNull('tanggal_diproses')
        ->update([
            'tanggal_diproses' => DB::raw('updated_at')
        ]);

    // Update pengaduan yang statusnya 'selesai'
    DB::table('pengaduan')
        ->where('status', 'selesai')
        ->whereNull('tanggal_selesai')
        ->update([
            'tanggal_diproses' => DB::raw('COALESCE(tanggal_diproses, updated_at)'),
            'tanggal_selesai' => DB::raw('updated_at')
        ]);
}
};
