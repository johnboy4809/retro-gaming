<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snes;

class SnesController extends Controller
{
    /**
     * Display the SNES ROM browser.
     */
    public function index(Request $request)
    {
        $query = Snes::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('rom', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        // Region filter
        if ($request->filled('region')) {
            $query->where('region', $request->input('region'));
        }

        // Sorting
        $sortBy    = $request->input('sort_by', 'rom');
        $sortOrder = $request->input('sort_order', 'asc');
        $allowed   = ['rom', 'region', 'release_date', 'size_mb'];

        $query->orderBy(
            in_array($sortBy, $allowed) ? $sortBy : 'rom',
            $sortOrder === 'desc' ? 'desc' : 'asc'
        );

        $roms = $query->paginate(30)->withQueryString();

        // Stats
        $stats = [
            'total'   => Snes::count(),
            'regions' => Snes::distinct('region')->whereNotNull('region')->count('region'),
        ];

        // Distinct regions for filter dropdown
        $regions = Snes::distinct()->whereNotNull('region')->orderBy('region')->pluck('region');

        return view('admin.snes', compact('roms', 'stats', 'regions'));
    }
}
