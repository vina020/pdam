<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mappers\TagihanMapper;
use Illuminate\Support\Facades\Cache;

class PDAMApiService
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('PDAM_API_URL'); 
        $this->apiKey = env('PDAM_API_KEY');
    }

    public function getTagihan($searchValue, $searchType = 'no_pelanggan')
    {
        // Cek cache dulu (valid 5 menit)
        $cacheKey = 'tagihan_api_' . $searchType . '_' . $searchValue;
        
        $cached = Cache::get($cacheKey);
        if ($cached) {
            \Log::info("✓ Returning cached data for: {$searchValue} (type: {$searchType})");
            return $cached;
        }
        
        try {
            \Log::info("→ Fetching from API for: {$searchValue} (type: {$searchType})");
            
            // Build query parameters based on search type
            $queryParams = $this->buildQueryParams($searchValue, $searchType);
            
            // Timeout 3 detik + retry 2x
            $response = Http::timeout(3)
                ->retry(2, 100) // Retry 2x dengan delay 100ms
                ->get($this->apiUrl, $queryParams);

            if ($response->successful()) {
                $data = $response->json();

                \Log::info("API Response Structure: " . json_encode($data));
                
                if (isset($data['kode_pesan']) && $data['kode_pesan'] == 200) {
                    $result = TagihanMapper::fromApiToLocal($data);
                    
                    // Cache selama 5 menit (gunakan file cache, bukan database)
                    Cache::put($cacheKey, $result, now()->addMinutes(5));
                    
                    \Log::info("✓ API success, cached for: {$searchValue} (type: {$searchType})");
                    return $result;
                }
            }

            \Log::warning("⚠ API returned non-200 code for: {$searchValue} (type: {$searchType})");
            return null;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error("✗ API Connection timeout for {$searchValue}: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            \Log::error("✗ API Error for {$searchValue}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Build query parameters based on search type
     */
    private function buildQueryParams($searchValue, $searchType)
    {
        // Jika API PDAM support multiple search types
        switch ($searchType) {
            case 'nosambungan':
            case 'no_pelanggan':
                // Parameter 'n' untuk nomor pelanggan/sambungan
                return ['n' => $searchValue];
                
            case 'nama':
                // Jika API support search by nama, gunakan parameter yang sesuai
                // Sesuaikan dengan dokumentasi API PDAM
                return [
                    'nama' => $searchValue
                    // atau 'name' => $searchValue, tergantung API
                ];
                
            default:
                return ['n' => $searchValue];
        }
    }
}