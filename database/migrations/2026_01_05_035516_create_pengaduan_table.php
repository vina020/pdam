<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengaduan')->unique();
            $table->string('no_pelanggan');
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->string('no_whatsapp', 20);
            $table->enum('jenis_pengaduan', [
                'Kualitas Air',
                'Tekanan Air Rendah',
                'Air Tidak Mengalir',
                'Kebocoran Pipa',
                'Meter Air Rusak',
                'Tagihan',
                'Pelayanan Petugas',
                'Lainnya'
            ]);
            $table->text('informasi_pengaduan');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('tanggapan')->nullable();
            $table->timestamp('tanggal_pengaduan');
            $table->timestamp('tanggal_ditanggapi')->nullable();
            $table->timestamps();

            $table->foreign('no_pelanggan')
                  ->references('no_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};