<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mame;
use App\Models\ArcadeBoard;

class LibraryController extends Controller
{
    /**
     * Store the user's selected drive configuration in the session
     * and redirect them to the library index.
     */
    public function setupSession(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|in:pc,mac,pi',
            'size' => 'required|numeric|in:16,32,64',
        ]);

        session([
            'drive_platform' => $validated['platform'],
            'drive_size' => $validated['size'],
        ]);

        return redirect()->route('library.index')->with('success', 'Drive configuration saved. Start selecting games!');
    }

    /**
     * Display the public-facing game catalog.
     */
    public function index(Request $request)
    {
        // Fetch active platforms from the database, including active sub-platforms
        $platforms = \App\Models\Platform::with(['subPlatforms' => function ($q) {
            $q->where('is_active', true)->orderBy('order_index');
        }])->where('is_active', true)->orderBy('order_index')->get();

        $group = $request->input('group', 'arcade');
        $activePlatformModel = $platforms->firstWhere('slug', $group);
        $defaultSystem = $activePlatformModel && $activePlatformModel->subPlatforms->isNotEmpty() 
            ? $activePlatformModel->subPlatforms->first()->slug 
            : 'mame';

        $system = $request->input('system', $defaultSystem);

        $driveSizeGB = session('drive_size', 16);
        $driveSizeMB = $driveSizeGB * 1024;
        $osSizeMB = 9216; // 9GB for OS
        $biosSizeMB = 1024; // 1GB for BIOS
        $usedSizeMB = $osSizeMB + $biosSizeMB;
        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $details) {
                $modelClass = $details['game_type'];
                $item = $modelClass::find($details['game_id']);
                if ($item) {
                    $usedSizeMB += $item->total_size_mb ?? $item->size_mb ?? 0;
                }
            }
        }

        $driveStats = [
            'total_mb' => $driveSizeMB,
            'used_mb' => $usedSizeMB,
            'os_mb' => $osSizeMB,
            'bios_mb' => $biosSizeMB,
            'games_mb' => $usedSizeMB - $osSizeMB - $biosSizeMB,
        ];

        // Calculate statistics based on the selected group
        $stats = [
            'total' => 0,
            'bios' => 0,
            'clones' => 0,
            'chds' => 0
        ];

        $legacyArcadeSystems = ['chd'];
        
        $hardwareBoards = collect([]);
        $manufacturers = collect([]);
        $regions = collect([]);

        if (!in_array($system, $legacyArcadeSystems)) {
            // New Generic Logic
            $subPlatformId = $activePlatformModel->subPlatforms->firstWhere('slug', $system)->id ?? null;
            $platformSlug = $activePlatformModel->slug;
            
            if ($platformSlug === 'arcade') {
                $modelClass = \App\Models\ArcadeGame::class;
                $hardwareBoards = \App\Models\ArcadeBoard::orderBy('board')->get();
                $manufacturers = \App\Models\ArcadeGame::whereNotNull('metadata->manufacturer')
                                    ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(metadata, "$.manufacturer")) as manufacturer')
                                    ->distinct()
                                    ->orderBy('manufacturer')
                                    ->pluck('manufacturer');
            } elseif ($platformSlug === 'console') {
                $modelClass = \App\Models\ConsoleGame::class;
            } elseif ($platformSlug === 'handhelds') {
                $modelClass = \App\Models\HandheldGame::class;
            } elseif ($platformSlug === 'home_computer') {
                $modelClass = \App\Models\ComputerGame::class;
            } else {
                abort(404, "Invalid platform mapping");
            }
            
            $query = $modelClass::where('sub_platform_id', $subPlatformId)
                                ->where('is_public', true);
            
            // Search
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('rom', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%");
                });
            }

            // Arcade Specific Filters
            if ($platformSlug === 'arcade') {
                if ($request->filled('manufacturer')) {
                    $query->where('metadata->manufacturer', $request->input('manufacturer'));
                }
                if ($request->filled('year')) {
                    $decade = str_replace('s', '', $request->input('year'));
                    if (is_numeric($decade)) {
                        $query->whereBetween('release_date', [(int)$decade, (int)$decade + 9]);
                    }
                }
                if ($request->filled('use_bios')) {
                    $query->where('metadata->use_bios', $request->input('use_bios') === '1');
                }
                if ($request->filled('use_chds')) {
                    $query->where('metadata->use_chds', $request->input('use_chds') === '1');
                }
                if ($request->filled('hardware_board')) {
                    $board = \App\Models\ArcadeBoard::find($request->input('hardware_board'));
                    if ($board) {
                        $query->where('metadata->driver', $board->driver);
                    }
                }
            }
            
            // Common Filters
            if ($request->filled('region')) {
                $query->where('region', $request->input('region'));
            }
            
            $regions = $modelClass::where('sub_platform_id', $subPlatformId)
                                  ->whereNotNull('region')
                                  ->where('region', '!=', '')
                                  ->distinct()
                                  ->orderBy('region')
                                  ->pluck('region');
            
            $sortBy = $request->input('sort_by', 'title');
            $sortOrder = $request->input('sort_order', 'asc');
            $allowedSorts = ['title', 'rom', 'size_mb', 'release_date'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('title', 'asc');
            }
            
            $mames = $query->paginate(30)->withQueryString();
            
            $stats = [
                'total' => $modelClass::where('sub_platform_id', $subPlatformId)->count(),
                'bios' => $platformSlug === 'arcade' ? $modelClass::where('sub_platform_id', $subPlatformId)->where('metadata->use_bios', true)->count() : 0,
                'clones' => $platformSlug === 'arcade' ? $modelClass::where('sub_platform_id', $subPlatformId)->whereNotNull('metadata->cloneof')->where('metadata->cloneof', '!=', '-')->count() : 0,
                'chds' => $platformSlug === 'arcade' ? $modelClass::where('sub_platform_id', $subPlatformId)->where('metadata->use_chds', true)->count() : 0
            ];
            
            $activeSubPlatform = $activePlatformModel->subPlatforms->firstWhere('slug', $system);
            
            return view('library.index', compact('mames', 'stats', 'hardwareBoards', 'manufacturers', 'regions', 'group', 'system', 'driveStats', 'platforms', 'activeSubPlatform'));
        }

        if ($system === 'chd') {
            $query = \App\Models\Chd::query();

            // Search
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('rom', 'like', "%{$search}%");
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'rom');
            $sortOrder = $request->input('sort_order', 'asc');
            $allowedSorts = ['rom', 'size'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
            } else {
                $query->orderBy('rom', 'asc');
            }

            $mames = $query->paginate(30)->withQueryString();

            $stats = [
                'total' => \App\Models\Chd::count(),
                'bios' => 0,
                'clones' => 0,
                'chds' => \App\Models\Chd::count(),
            ];

            $activeSubPlatform = null;
            return view('library.index', compact('mames', 'stats', 'hardwareBoards', 'manufacturers', 'group', 'system', 'driveStats', 'platforms', 'activeSubPlatform'));
        }
    }
}
