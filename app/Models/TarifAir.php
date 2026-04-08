<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifAir extends Model
{
    protected $table = 'tarif_air';

    protected $fillable = [
        'kategori',
        'sub_kategori',
        'blok_pemakaian',
        'min_pemakaian',
        'max_pemakaian',
        'tarif_per_m3',
        'keterangan',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
        'min_pemakaian' => 'integer',
        'max_pemakaian' => 'integer',
        'tarif_per_m3' => 'integer'
    ];

    // Scope untuk filter kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk data aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Format tarif ke rupiah
    public function getTarifFormatAttribute()
    {
        return 'Rp ' . number_format($this->tarif_per_m3, 0, ',', '.');
    }
}
