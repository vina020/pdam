<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    DB::statement("ALTER TABLE pasang_baru MODIFY COLUMN status ENUM('pending', 'verifikasi', 'survei', 'approved', 'ditolak', 'selesai') DEFAULT 'pending'");
}

public function down()
{
    DB::statement("ALTER TABLE pasang_baru MODIFY COLUMN status ENUM('pending', 'verifikasi', 'survei', 'approved', 'ditolak') DEFAULT 'pending'");
}
};
