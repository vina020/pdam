<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function cekBayar()
{
    $result = collect([
        (object) ['tglbayar' => now()->format('Y-m-d')], // bulan ini
        (object) ['tglbayar' => '2024-10-01'],
    ]);

    foreach ($result as $row) {
        if (
            date('m', strtotime($row->tglbayar)) == date('m') &&
            date('Y', strtotime($row->tglbayar)) == date('Y')
        ) {
            \Log::info('SUDAH BAYAR BULAN INI');
            return ['kode_pesan' => 'sudah bayar'];
        }
    }

    \Log::info('BELUM BAYAR BULAN INI');
    return ['kode_pesan' => 'belum bayar'];
}


}
