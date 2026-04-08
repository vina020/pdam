<?php

namespace App\Mappers;

use Carbon\Carbon;

class TagihanMapper
{
    /**
     * Convert API PDAM format ke format sistem lokal
     */
    public static function fromApiToLocal($apiData)
    {
        // Extract data pelanggan
        $pelanggan = [
            'no_pelanggan' => $apiData['nopelanggan'] ?? '-',
            'nosambungan' => $apiData['nosambungan'] ?? '-',
            'nama' => $apiData['nama'] ?? '-',
            'alamat' => $apiData['alamat'] ?? '-',
        ];

        // Convert tagihan air
        $tagihans = [];
        
        if (isset($apiData['tagihan']) && is_array($apiData['tagihan'])) {
            foreach ($apiData['tagihan'] as $item) {
                // Format periode dari bulan/tahun jadi "YYYY-MM"
                $periode = sprintf('%04d-%02d', $item['tahun'], $item['bulan']);
                
                // Format periode untuk display "Januari 2025"
                $periodeDisplay = self::formatPeriode($item['bulan'], $item['tahun']);
                $biayaTambahan = self::getBiayaTambahanById($apiData['tagihan_nonair'] ?? []);

                $tagihans[] = [
                    'id' => null,
                    'no_pelanggan' => $pelanggan['no_pelanggan'],
                    'nosambungan' => $pelanggan['nosambungan'],   
                    'nama_pelanggan' => $pelanggan['nama'],
                    'periode' => $periodeDisplay,
                    'periode_raw' => $periode,
                    'meter_awal' => $item['stanawal'],
                    'meter_akhir' => $item['stanakhir'],
                    'pemakaian' => $item['pakai'],
                    'total_tagihan' => number_format($item['nominal'], 0, ',', '.'),
                    'total_tagihan_raw' => $item['nominal'],
                    'total_bayar' => number_format($item['nominal'] + $biayaTambahan['denda'] + $biayaTambahan['biaya_pembukaan'], 0, ',', '.'),
                    'total_bayar_raw' => $item['nominal'] + $biayaTambahan['denda'] + $biayaTambahan['biaya_pembukaan'],
                    'status' => 'belum_bayar',
                    'status_label' => 'Belum Dibayar',
                    'tarif' => '0',
                    'biaya_pemakaian' => '0',
                    'biaya_admin' => '0',
                    'biaya_pemeliharaan' => '0',
                    'denda' => $biayaTambahan['denda'],
                    'denda_format' => number_format($biayaTambahan['denda'], 0, ',', '.'),
                    'biaya_pembukaan' => $biayaTambahan['biaya_pembukaan'],
                    'biaya_pembukaan_format' => number_format($biayaTambahan['biaya_pembukaan'], 0, ',', '.'),
                    'jatuh_tempo' => '-',
                    'tanggal_bayar' => null,
                ];
            }
        }

        // Hitung total belum bayar
        $totalBelumBayar = array_sum(array_column($apiData['tagihan'] ?? [], 'nominal'));

        return [
            'success' => true,
            'pelanggan' => $pelanggan,
            'tagihans' => $tagihans,
            'total_belum_bayar' => number_format($totalBelumBayar, 0, ',', '.'),
            'total_belum_bayar_raw' => $totalBelumBayar
        ];
    }
    
    private static function getBiayaTambahanById($biayaLain)
{
    $result = [
        'denda' => 0,
        'biaya_pembukaan' => 0
    ];
    
    if (!is_array($biayaLain)) {
        return $result;
    }
    
    foreach ($biayaLain as $biaya) {
        if ($biaya['id'] == '03') {
            $result['denda'] = $biaya['nominal'];
        } elseif ($biaya['id'] == '04') {
            $result['biaya_pembukaan'] = $biaya['nominal'];
        }
    }
    
    return $result;
}
    /**
     * Format periode (bulan, tahun) jadi "Januari 2025"
     */
    private static function formatPeriode($bulan, $tahun)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return ($namaBulan[$bulan] ?? 'Unknown') . ' ' . $tahun;
    }

    /**
     * Convert data API untuk sync ke database
     */
    public static function toDatabase($apiData, $noPelanggan)
    {
        $records = [];
        
        if (isset($apiData['tagihan']) && is_array($apiData['tagihan'])) {
            foreach ($apiData['tagihan'] as $item) {
                $periode = sprintf('%04d-%02d', $item['tahun'], $item['bulan']);
                
                $records[] = [
                    'no_pelanggan' => $noPelanggan,
                    'nosambungan' => $apiData['nosambungan'] ?? '-',
                    'nama_pelanggan' => $apiData['nama'] ?? '-',
                    'alamat' => $apiData['alamat'] ?? '-',
                    'periode' => $periode,
                    'meter_awal' => $item['stanawal'],
                    'meter_akhir' => $item['stanakhir'],
                    'pemakaian' => $item['pakai'],
                    'tarif_per_m3' => 0,
                    'biaya_pemakaian' => 0,
                    'biaya_admin' => 0,
                    'biaya_pemeliharaan' => 0,
                    'total_tagihan' => $item['nominal'],
                    'status' => 'belum_bayar',
                    'tanggal_jatuh_tempo' => Carbon::now()->addDays(15),
                ];
            }
        }
        
        return $records;
    }
}