<?php

namespace App\Http\Controllers;

use App\Models\SubPlatform;
use Illuminate\Http\Request;

abstract class BaseGameController extends Controller
{
    /**
     * Get the model class this controller manages.
     */
    abstract protected function getModelClass(): string;

    public function index(Request $request, $subPlatformId)
    {
        $subPlatform = SubPlatform::with('platform')->findOrFail($subPlatformId);
        $modelClass = $this->getModelClass();
        
        $query = $modelClass::where('sub_platform_id', $subPlatformId);

        // Searching
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rom', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('metadata', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'title');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['rom', 'title', 'release_date', 'size_mb'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('title', 'asc');
        }

        $games = $query->paginate(50)->withQueryString();

        $stats = [
            'total' => $games->total(),
            'bios' => 0,
            'clones' => 0,
            'chds' => 0
        ];

        $hardwareBoards = [];
        
        $platformSlug = $subPlatform->platform->slug;
        $routePrefix = null;
        if ($platformSlug === 'arcade') $routePrefix = 'arcade';
        elseif ($platformSlug === 'console') $routePrefix = 'console';
        elseif ($platformSlug === 'handhelds') $routePrefix = 'handheld';
        elseif ($platformSlug === 'home_computer') $routePrefix = 'computer';

        $system = $subPlatform->slug; // e.g., 'mame', 'chds'

        return view('admin.games.index', compact('subPlatform', 'games', 'stats', 'hardwareBoards', 'routePrefix', 'system'));
    }

    public function update(Request $request, $subPlatformId, $gameId)
    {
        $subPlatform = SubPlatform::findOrFail($subPlatformId);
        $modelClass = $this->getModelClass();
        $game = $modelClass::where('sub_platform_id', $subPlatformId)->findOrFail($gameId);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'size_bytes' => 'nullable|integer',
            'is_public' => 'nullable|boolean',
            'metadata' => 'nullable|array'
        ]);

        $updateData = collect($validated)->except('metadata')->toArray();
        if (!empty($updateData)) {
            $game->fill($updateData);
        }

        if ($request->has('metadata')) {
            $currentMetadata = is_string($game->metadata) ? json_decode($game->metadata, true) : ($game->metadata ?? []);
            $game->metadata = array_merge($currentMetadata, $validated['metadata'] ?? []);
        }
        
        $game->save();

        return response()->json([
            'success' => true,
            'game' => $game,
            'message' => 'Game updated successfully'
        ]);
    }

    public function import(Request $request, $subPlatformId)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $subPlatform = SubPlatform::findOrFail($subPlatformId);
        $modelClass = $this->getModelClass();
        
        $path = $request->file('csv_file')->getRealPath();
        
        // Fix Mac line endings
        ini_set('auto_detect_line_endings', true);
        
        $file = fopen($path, 'r');
        $delimiter = ',';
        
        $header = fgetcsv($file, 0, $delimiter);
        if (!$header) {
            return redirect()->back()->withErrors('Invalid CSV format.');
        }
        
        // Remove BOM from the first header column if present
        $header[0] = preg_replace('/^[\xef\xbb\xbf]+/', '', $header[0]);
        
        // If there's only one column containing a semicolon, it's likely semicolon-delimited
        if (count($header) === 1 && strpos($header[0], ';') !== false) {
            fclose($file);
            $file = fopen($path, 'r');
            $delimiter = ';';
            $header = fgetcsv($file, 0, $delimiter);
            $header[0] = preg_replace('/^[\xef\xbb\xbf]+/', '', $header[0]);
        }
        
        // Normalize headers
        $header = array_map(function($h) { 
            $h = trim(strtolower($h));
            
            // Map common alternatives for "rom"
            if (in_array($h, ['file', 'rom name', 'filename', 'game name'])) {
                return 'rom';
            }
            
            // Map common alternatives for "title"
            if (in_array($h, ['name', 'game', 'game title', 'alternate name'])) {
                return 'title';
            }
            
            if (in_array($h, ['size (mb)', 'size', 'size (bytes)', 'size_mb'])) {
                return 'size_bytes';
            }
            
            if (in_array($h, ['release date', 'year', 'release'])) {
                return 'release_date';
            }
            
            return $h;
        }, $header);
        
        $standardColumns = ['rom', 'title', 'size_bytes', 'release_date', 'region', 'crc32'];
        
        $importedCount = 0;
        $skippedCount = 0;
        
        \Log::info("Starting CSV import. Headers:", $header);
        
        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            if (count($header) !== count($row)) {
                \Log::warning("Skipped row due to count mismatch. Header: " . count($header) . " Row: " . count($row), $row);
                continue;
            }
            
            $data = array_combine($header, $row);
            if (empty($data['rom'])) {
                $skippedCount++;
                continue;
            }

            // Isolate metadata by removing standard columns
            $metadata = array_diff_key($data, array_flip($standardColumns));
            
            // Format release_date to valid Y-m-d format for database
            $releaseDate = $data['release_date'] ?? null;
            if (empty(trim((string)$releaseDate))) {
                $releaseDate = null;
            } else {
                try {
                    $releaseDate = \Carbon\Carbon::parse($releaseDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $releaseDate = null; // if invalid date, null it
                }
            }
            
            $gameData = [
                'sub_platform_id' => $subPlatform->id,
                'rom' => $data['rom'],
                'title' => !empty(trim((string)($data['title'] ?? ''))) ? $data['title'] : $data['rom'],
                'size_bytes' => isset($data['size_bytes']) ? (int) $data['size_bytes'] : 0,
                'release_date' => $releaseDate,
                'region' => $data['region'] ?? null,
                'crc32' => $data['crc32'] ?? null,
                'metadata' => $metadata
            ];
            $exists = $modelClass::where('sub_platform_id', $subPlatform->id)
                                 ->where('rom', $data['rom'])
                                 ->exists();
            
            if ($exists) {
                $skippedCount++;
                continue;
            }

            // Metadata is already handled above and stored directly in $gameData['metadata']
            $modelClass::create($gameData);
            
            $importedCount++;
        }
        
        fclose($file);
        
        \Log::info("Finished import. Imported: $importedCount, Skipped Empty Rom: $skippedCount");
        
        return redirect()->back()
            ->with('success', "Successfully imported {$importedCount} games.");
    }
    
    public function destroy($subPlatformId, $gameId)
    {
        $modelClass = $this->getModelClass();
        $game = $modelClass::where('sub_platform_id', $subPlatformId)->findOrFail($gameId);
        $game->delete();
        
        return redirect()->back()
            ->with('success', 'Game deleted successfully.');
    }

    public function bulkDestroy(Request $request, $subPlatformId)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $modelClass = $this->getModelClass();
        
        // We delete only those that belong to the current subPlatform just to be safe
        $deletedCount = $modelClass::where('sub_platform_id', $subPlatformId)
                                   ->whereIn('id', $request->ids)
                                   ->delete();

        return redirect()->back()
            ->with('success', "Successfully deleted {$deletedCount} games.");
    }
}
