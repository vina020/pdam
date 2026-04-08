<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Services\PDAMApiService;
use App\Mappers\TagihanMapper;

class SyncTagihanFromAPI extends Command
{

    protected $signature = 'pdam:sync-tagihan {no_pelanggan?}';
    protected $description = 'Sync tagihan dari API PDAM ke database lokal';
    
    public function handle(PDAMApiService $pdamApi)
    {
        $noPelanggan = $this->argument('no_pelanggan');

        if ($noPelanggan) {
            // Sync 1 pelanggan
            $this->syncPelanggan($noPelanggan, $pdamApi);
        } else {
            // Sync semua pelanggan aktif
            $pelanggans = Pelanggan::where('status_pelanggan', 'aktif')->get();
            
            foreach ($pelanggans as $pelanggan) {
                $this->syncPelanggan($pelanggan->no_pelanggan, $pdamApi);
            }
        }

        $this->info('Sync selesai!');
    }

    private function syncPelanggan($noPelanggan, $pdamApi)
{
    $this->info("Syncing: {$noPelanggan}");
    
    try {
        $result = $pdamApi->getTagihan($noPelanggan);

        if ($result && $result['success']) {
            
            if (empty($result['tagihans'])) {
                $this->warn("⚠ {$noPelanggan} - No tagihan data from API");
                return;
            }
            
            $count = 0;
            
            // Convert ke format database
            $records = TagihanMapper::toDatabase($result, $noPelanggan);
            
            foreach ($records as $record) {
                Tagihan::updateOrCreate(
                    [
                        'no_pelanggan' => $noPelanggan,
                        'periode' => $record['periode']
                    ],
                    $record
                );
                $count++;
            }
            
            $this->info("✓ {$noPelanggan} synced ({$count} tagihan)");
            
        } else {
            $this->error("✗ {$noPelanggan} failed: API returned no data");
        }
    } catch (\Exception $e) {
        $this->error("✗ {$noPelanggan} failed: " . $e->getMessage());
        \Log::error("Sync error for {$noPelanggan}: " . $e->getMessage());
    }
}
}
