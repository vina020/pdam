<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaklumatPelayanan extends Model
{
    use HasFactory;

    protected $table = 'maklumat_pelayanan';

    protected $fillable = [
        'kategori',
        'judul',
        'deskripsi',
        'icon',
        'color',
        'urutan',
        'aktif'
    ];

    public function scopeKategori($query, $kategori) {
        return $query->where('kategori', $kategori);
    }

    public function scopeAktif($query) {
        return $query->where('aktif', true);
    }
}
