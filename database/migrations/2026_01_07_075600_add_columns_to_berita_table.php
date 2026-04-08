<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('berita', function (Blueprint $table) {
            // Cek kolom mana yang belum ada, tambah ini:
            $table->text('konten')->nullable()->after('deskripsi'); // isi lengkap
            $table->string('kategori')->default('info')->after('konten'); // info/pengumuman/kegiatan
            $table->string('penulis')->default('Admin PDAM')->after('kategori');
            $table->integer('views')->default(0)->after('penulis');
        });
    }

    public function down()
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropColumn(['konten', 'kategori', 'penulis', 'views']);
        });
    }
};