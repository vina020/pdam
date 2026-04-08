<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'nomor_pengaduan',
        'no_pelanggan',
        'nama_lengkap',
        'alamat',
        'no_whatsapp',
        'jenis_pengaduan',
        'informasi_pengaduan',
        'status',
        'tanggapan',
        'tanggal_pengaduan',
        'tanggal_ditanggapi',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_pengaduan' => 'datetime',
        'tanggal_ditanggapi' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'no_pelanggan', 'no_pelanggan');
    }
}