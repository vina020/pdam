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
        Schema::table('pengaduan', function (Blueprint $table) {
        $table->datetime('tanggal_diproses')->nullable()->after('status');
        $table->datetime('tanggal_selesai')->nullable()->after('tanggal_diproses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
        $table->dropColumn(['tanggal_diproses', 'tanggal_selesai']);
        });
    }
};
