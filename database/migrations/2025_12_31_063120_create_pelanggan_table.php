<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('no_pelanggan')->unique();
            $table->string('nama_pelanggan');
            $table->string('nik', 16)->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->text('alamat_lengkap');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->default('Magetan');
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('jenis_pelanggan', [
                'rumah_tangga',
                'usaha',
                'industri',
                'sosial',
                'pemerintah'
            ])->default('rumah_tangga');
            $table->enum('status_pelanggan', ['aktif', 'non_aktif', 'putus'])->default('aktif');
            $table->date('tanggal_pasang')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
