<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'user_id',
        'no_pelanggan',
        'nama_pelanggan',
        'nik',
        'no_kk',
        'alamat_lengkap',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'kode_pos',
        'no_telepon',
        'email',
        'jenis_pelanggan',
        'status_pelanggan',
        'tanggal_pasang',
        'foto_rumah',
        'foto_ktp',
        'foto_kk',
    ];

    protected $casts = [
        'tanggal_pasang' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'no_pelanggan', 'no_pelanggan');
    }
}