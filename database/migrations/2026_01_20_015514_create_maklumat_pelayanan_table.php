<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maklumat_pelayanan', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('urutan')->default(0);  // ← Pastikan ini "integer"
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maklumat_pelayanan');
    }
};