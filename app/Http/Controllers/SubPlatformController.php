<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubPlatformController extends Controller
{
    public function create(Request $request)
    {
        $platforms = \App\Models\Platform::orderBy('order_index')->get();
        return view('admin.sub_platforms.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'order_index' => 'required|integer',
            'is_active' => 'boolean',
            'screenscraper_id' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Check uniqueness for platform_id + slug
        $exists = \App\Models\SubPlatform::where('platform_id', $validated['platform_id'])
                                         ->where('slug', $validated['slug'])
                                         ->exists();
        if ($exists) {
            return back()->withErrors(['slug' => 'The slug has already been taken for this platform.'])->withInput();
        }

        $subPlatform = \App\Models\SubPlatform::create($validated);
        
        $platform = \App\Models\Platform::find($validated['platform_id']);
        return redirect()->route('admin.master-platform', $platform->slug)->with('success', 'Sub-Platform created successfully.');
    }

    public function edit(\App\Models\SubPlatform $subPlatform)
    {
        $platforms = \App\Models\Platform::orderBy('order_index')->get();
        return view('admin.sub_platforms.edit', compact('subPlatform', 'platforms'));
    }

    public function update(Request $request, \App\Models\SubPlatform $subPlatform)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'order_index' => 'required|integer',
            'is_active' => 'boolean',
            'screenscraper_id' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Check uniqueness for platform_id + slug
        $exists = \App\Models\SubPlatform::where('platform_id', $validated['platform_id'])
                                         ->where('slug', $validated['slug'])
                                         ->where('id', '!=', $subPlatform->id)
                                         ->exists();
        if ($exists) {
            return back()->withErrors(['slug' => 'The slug has already been taken for this platform.'])->withInput();
        }

        $subPlatform->update($validated);
        
        $platform = \App\Models\Platform::find($validated['platform_id']);
        return redirect()->route('admin.master-platform', $platform->slug)->with('success', 'Sub-Platform updated successfully.');
    }

    public function destroy(\App\Models\SubPlatform $subPlatform)
    {
        $platformSlug = $subPlatform->platform->slug;
        $subPlatform->delete();
        
        return redirect()->route('admin.master-platform', $platformSlug)
                         ->with('success', 'Sub-Platform deleted successfully.');
    }

    public function downloadCsvTemplate()
    {
        $headers = ['rom', 'title', 'size_bytes', 'release_date', 'region', 'crc32', 'developer', 'publisher', 'genre', 'players'];
        
        $callback = function() use($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=game_import_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}
