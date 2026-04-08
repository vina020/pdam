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
        Schema::create('tarif_air', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); 
            $table->string('sub_kategori')->nullable();
            $table->string('blok_pemakaian'); 
            $table->integer('min_pemakaian')->default(0);
            $table->integer('max_pemakaian')->nullable(); 
            $table->integer('tarif_per_m3');
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_air');
    }
};
