<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ScreenScraper\ScreenScraperClient;

class ScreenScraperController extends Controller
{
    public function search(Request $request, $systemId, $romName)
    {
        try {
            $crc = $request->query('crc32', '');
            $cacheKey = "screenscraper_game_{$systemId}_{$romName}" . ($crc ? "_{$crc}" : "");
            
            // Cache the metadata for a year to minimize API calls
            $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addYear(), function () use ($systemId, $romName) {
                $devId = env('SCREENSCRAPER_DEV_ID');
                $devPassword = env('SCREENSCRAPER_DEV_PASSWORD');
                $softname = urlencode(env('SCREENSCRAPER_SOFTNAME', 'Retro Gaming App'));
                $ssid = env('SCREENSCRAPER_USERNAME');
                $sspassword = env('SCREENSCRAPER_PASSWORD');
                
                $url = "https://api.screenscraper.fr/api2/jeuInfos.php";
                
                $crc = request()->query('crc32');
                
                $params = [
                    'devid' => $devId,
                    'devpassword' => $devPassword,
                    'softname' => $softname,
                    'output' => 'json',
                    'ssid' => $ssid,
                    'sspassword' => $sspassword,
                    'romnom' => $romName,
                    'systemeid' => $systemId,
                    'romtype' => 'rom'
                ];
                
                if (!empty($crc)) {
                    $params['crc'] = $crc;
                }
                
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->get($url, $params);
                
                if (!$response->successful()) {
                    throw new \Exception("Failed to fetch game data: HTTP " . $response->status());
                }
                
                $json = $response->json();
                
                if (isset($json['header']['error']) && !empty($json['header']['error'])) {
                    throw new \Exception("API Error: " . $json['header']['error']);
                }
                
                if (!isset($json['response'])) {
                    throw new \Exception("Invalid API response format");
                }
                
                // Map medias to use our proxy to prevent leaking credentials
                if (isset($json['response']['jeu']['medias'])) {
                    foreach ($json['response']['jeu']['medias'] as &$media) {
                        if (isset($media['url'])) {
                            $parsedUrl = parse_url($media['url']);
                            parse_str($parsedUrl['query'] ?? '', $query);
                            
                            // Remove sensitive data
                            unset($query['devid'], $query['devpassword'], $query['softname'], $query['ssid'], $query['sspassword']);
                            
                            // Extract endpoint (e.g., mediaJeu.php)
                            $endpoint = basename($parsedUrl['path']);
                            $query['endpoint'] = $endpoint;
                            $query['format'] = $media['format'] ?? 'png';
                            
                            // Generate local proxy URL
                            $media['url'] = route('screenscraper.proxy', $query);
                        }
                    }
                }
                
                return $json['response'];
            });
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ScreenScraper API Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSystems()
    {
        try {
            // Cache systems list for 24 hours
            $systems = \Illuminate\Support\Facades\Cache::remember('screenscraper_systems', now()->addDay(), function () {
                $ctx = stream_context_create(["ssl"=>["verify_peer"=>false,"verify_peer_name"=>false]]);
                $devId = env('SCREENSCRAPER_DEV_ID');
                $devPassword = env('SCREENSCRAPER_DEV_PASSWORD');
                $softname = urlencode(env('SCREENSCRAPER_SOFTNAME', 'Retro Gaming App'));
                $ssid = env('SCREENSCRAPER_USERNAME');
                $sspassword = env('SCREENSCRAPER_PASSWORD');
                
                $url = "https://api.screenscraper.fr/api2/systemesListe.php?devid={$devId}&devpassword={$devPassword}&softname={$softname}&output=json&ssid={$ssid}&sspassword={$sspassword}";
                
                $response = file_get_contents($url, false, $ctx);
                if (!$response) {
                    throw new \Exception("Failed to fetch systems");
                }
                
                $data = json_decode($response, true);
                $systems = $data['response']['systemes'] ?? [];
                
                // Map the name so the frontend's JS (.nomsysteme) doesn't break
                foreach ($systems as &$sys) {
                    $noms = $sys['noms'] ?? [];
                    $sys['nomsysteme'] = $noms['nom_eu'] ?? $noms['nom_us'] ?? $noms['nom_recalbox'] ?? 'Unknown System ' . $sys['id'];
                }
                
                return $systems;
            });
            
            return response()->json($systems);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function proxy(Request $request)
    {
        $endpoint = $request->query('endpoint');
        if (!$endpoint) {
            abort(400, 'Missing endpoint');
        }

        $devId = env('SCREENSCRAPER_DEV_ID');
        $devPassword = env('SCREENSCRAPER_DEV_PASSWORD');
        $softname = urlencode(env('SCREENSCRAPER_SOFTNAME', 'Retro Gaming App'));
        $ssid = env('SCREENSCRAPER_USERNAME');
        $sspassword = env('SCREENSCRAPER_PASSWORD');

        $queryParams = $request->except(['endpoint', 'format']);
        $queryParams['devid'] = $devId;
        $queryParams['devpassword'] = $devPassword;
        $queryParams['softname'] = $softname;
        $queryParams['ssid'] = $ssid;
        $queryParams['sspassword'] = $sspassword;

        $url = "https://api.screenscraper.fr/api2/{$endpoint}?" . http_build_query($queryParams);

        $ext = strtolower($request->query('format', 'png'));
        $mime = 'image/png';
        if (in_array($ext, ['mp4', 'mkv', 'avi'])) $mime = 'video/mp4';
        elseif (in_array($ext, ['jpg', 'jpeg'])) $mime = 'image/jpeg';
        elseif ($ext === 'svg') $mime = 'image/svg+xml';
        elseif ($ext === 'pdf') $mime = 'application/pdf';

        return response()->stream(function () use ($url) {
            $ctx = stream_context_create(["ssl"=>["verify_peer"=>false,"verify_peer_name"=>false]]);
            readfile($url, false, $ctx);
        }, 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
}
