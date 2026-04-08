<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Hapus semua after() karena kolom alamat tidak ada
        $table->boolean('notif_email_berita')->default(true);
        $table->boolean('notif_email_pengaduan')->default(true);
        $table->boolean('notif_push')->default(true);
        $table->string('theme')->default('light');
        $table->string('accent_color')->default('blue');
        $table->string('font_size')->default('medium');
        $table->string('bahasa')->default('id');
        $table->string('timezone')->default('Asia/Jakarta');
        $table->string('date_format')->default('d/m/Y');
        $table->integer('items_per_page')->default(10);
        
        // ✅ Tambahkan last_login di sini sekalian
        $table->timestamp('last_login')->nullable();
        $table->string('last_login_ip', 45)->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'notif_email_berita',
            'notif_email_pengaduan', 
            'notif_push',
            'theme',
            'accent_color',
            'font_size',
            'bahasa',
            'timezone',
            'date_format',
            'items_per_page',
            'last_login',        // ✅ Tambahkan
            'last_login_ip',     // ✅ Tambahkan
        ]);
    });
}
};
