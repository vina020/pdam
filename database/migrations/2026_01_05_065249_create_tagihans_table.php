<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pelanggan');
            $table->string('nama_pelanggan');
            $table->string('alamat');
            $table->string('periode');
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->integer('pemakaian');
            $table->decimal('tarif_per_m3', 10, 2);
            $table->decimal('biaya_pemakaian', 10, 2);
            $table->decimal('biaya_admin', 10, 2)->default(2500);
            $table->decimal('biaya_pemeliharaan', 10, 2)->default(5000);
            $table->decimal('total_tagihan', 10, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'sudah_bayar', 'terlambat'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index('no_pelanggan');
            $table->index('periode');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};