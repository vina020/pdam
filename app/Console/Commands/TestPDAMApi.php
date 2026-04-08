<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PDAMApiService;

class TestPDAMApi extends Command
{
    protected $signature = 'pdam:test-api {no_pelanggan}';
    protected $description = 'Test PDAM API connection';

    public function handle(PDAMApiService $pdamApi)
    {
        $noPelanggan = $this->argument('no_pelanggan');
        
        $this->info("Testing API for: {$noPelanggan}");
        
        $result = $pdamApi->getTagihan($noPelanggan);
        
        $this->info("Raw Result:");
        dump($result);
    }
}