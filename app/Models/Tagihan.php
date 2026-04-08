<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_pelanggan',
        'nosambungan',
        'nama_pelanggan',
        'alamat',
        'periode',
        'meter_awal',
        'meter_akhir',
        'pemakaian',
        'tarif_per_m3',
        'biaya_pemakaian',
        'biaya_admin',
        'biaya_pemeliharaan',
        'total_tagihan',
        'tanggal_jatuh_tempo',
        'status',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    // Accessor untuk format periode
    public function getPeriodeFormatAttribute()
    {
        $date = Carbon::createFromFormat('Y-m', $this->periode);
        return $date->locale('id')->isoFormat('MMMM YYYY');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'belum_bayar' => '<span class="status-badge warning">Belum Dibayar</span>',
            'sudah_bayar' => '<span class="status-badge success">Lunas</span>',
            'terlambat' => '<span class="status-badge danger">Terlambat</span>',
            default => '<span class="status-badge secondary">Unknown</span>',
        };
    }

    // Check apakah tagihan terlambat
    public function isTerlambat()
    {
        if ($this->status === 'belum_bayar' && $this->tanggal_jatuh_tempo < now()) {
            return true;
        }
        return false;
    }

    // Hitung denda keterlambatan (2% per bulan)
    public function getDenda()
    {
        if ($this->isTerlambat()) {
            $hariTerlambat = now()->diffInDays($this->tanggal_jatuh_tempo);
            $bulanTerlambat = ceil($hariTerlambat / 30);
            return $this->total_tagihan * 0.02 * $bulanTerlambat;
        }
        return 0;
    }

    // Total yang harus dibayar (termasuk denda)
    public function getTotalBayarAttribute()
    {
        return $this->total_tagihan + $this->getDenda();
    }
}