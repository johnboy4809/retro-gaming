<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Models\RomMetadata;

class ArcadeItaliaService
{
    protected string $baseUrl = 'http://adb.arcadeitalia.net/service_scraper.php';

    public function getGameMetadata(string $filename)
    {
        // 1. Check local database cache
        $cached = RomMetadata::where('rom', $filename)->first();
        if ($cached) {
            return $cached->metadata;
        }

        // 2. Fetch from external API if not cached
        $response = Http::get($this->baseUrl, [
            'ajax' => 'query_mame',
            'lang' => 'en',
            'game_name' => $filename
        ]);

        if ($response->successful()) {
            $json = $response->json();
            if ($json) {
                // 3. Cache the response for future requests
                RomMetadata::create([
                    'rom' => $filename,
                    'metadata' => $json,
                ]);
            }
            return $json;
        }

        return null;
    }
}