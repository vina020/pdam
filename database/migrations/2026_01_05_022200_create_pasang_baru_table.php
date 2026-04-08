<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasang_baru', function (Blueprint $table) {
            $table->id();
            
            // Nomor Registrasi
            $table->string('nomor_registrasi', 50)->unique();
            
            // Data Pemohon
            $table->string('nama_pemohon');
            $table->string('nik', 16);
            $table->text('alamat_pemohon');
            $table->string('no_telepon', 20);
            $table->string('email')->nullable();
            
            // Lokasi Pemasangan
            $table->string('jalan');
            $table->string('nomor_rumah', 50);
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->string('kecamatan', 100);
            $table->string('kelurahan', 100);
            $table->string('daya_listrik', 50);
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            
            // Dokumen (Path ke storage)
            $table->string('dokumen_ktp')->nullable();
            $table->string('dokumen_kk')->nullable();
            $table->string('dokumen_pbb')->nullable();
            $table->string('dokumen_listrik')->nullable();
            $table->string('dokumen_domisili')->nullable();
            
            // Status & Tracking
            $table->enum('status', ['pending', 'verifikasi', 'survei', 'approved', 'ditolak'])
                  ->default('pending');
            $table->text('catatan')->nullable(); // Catatan dari admin
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_survei')->nullable();
            $table->timestamp('tanggal_approved')->nullable();
            
            // User yang memproses (optional, jika ada sistem admin)
            $table->unsignedBigInteger('processed_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete untuk archive
            
            // Indexes
            $table->index('nomor_registrasi');
            $table->index('nik');
            $table->index('status');
            $table->index('tanggal_pengajuan');
            $table->index(['kecamatan', 'kelurahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasang_baru');
    }
};