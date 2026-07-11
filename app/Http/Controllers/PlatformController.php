<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * Display the subplatforms for a specific master platform.
     */
    public function show($slug)
    {
        $platform = \App\Models\Platform::with('subPlatforms')->where('slug', $slug)->firstOrFail();
        return view('admin.platforms.show', compact('platform'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:platforms',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        \App\Models\Platform::create($validated);

        return redirect()->route('platforms.index')->with('success', 'Platform created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:platforms,slug,' . $platform->id,
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $platform->update($validated);

        return redirect()->route('platforms.index')->with('success', 'Platform updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Platform $platform)
    {
        $platform->delete();
        return redirect()->route('platforms.index')->with('success', 'Platform deleted successfully.');
    }
}
