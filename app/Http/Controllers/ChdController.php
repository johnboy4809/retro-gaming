<?php

namespace App\Http\Controllers;

use App\Models\Chd;
use Illuminate\Http\Request;

class ChdController extends Controller
{
    public function index(Request $request)
    {
        $query = Chd::query();

        if ($request->filled('search')) {
            $query->where('rom', 'like', '%' . $request->search . '%');
        }

        $sortBy = $request->get('sort_by', 'rom');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['rom', 'size_bytes'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('rom', 'asc');
        }

        $chds = $query->paginate(50)->withQueryString();

        return view('admin.chds.index', compact('chds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rom' => 'required|string|max:255|unique:chd,rom',
            'size_bytes' => 'nullable|integer',
        ]);

        Chd::create($validated);

        return redirect()->back()->with('success', 'CHD created successfully.');
    }

    public function update(Request $request, Chd $chd)
    {
        $validated = $request->validate([
            'rom' => 'required|string|max:255|unique:chd,rom,' . $chd->id,
            'size_bytes' => 'nullable|integer',
        ]);

        $chd->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'CHD updated successfully.',
            'chd' => $chd
        ]);
    }

    public function destroy(Chd $chd)
    {
        $chd->delete();
        return redirect()->back()->with('success', 'CHD deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('csv_file')->getRealPath();
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

        $header = array_map(function($h) { 
            $h = trim(strtolower($h)); 
            if ($h === 'size' || $h === 'size_mb') return 'size_bytes';
            return $h;
        }, $header);
        
        $importedCount = 0;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            if (count($header) !== count($row)) continue;
            
            $data = array_combine($header, $row);
            
            if (empty($data['rom'])) continue;

            $exists = Chd::where('rom', $data['rom'])->exists();
            if ($exists) {
                continue;
            }

            Chd::create([
                'rom' => $data['rom'],
                'size_bytes' => isset($data['size_bytes']) ? (int) $data['size_bytes'] : 0
            ]);

            $importedCount++;
        }

        fclose($file);

        return redirect()->back()->with('success', "Successfully imported {$importedCount} CHDs.");
    }

    public function downloadCsvTemplate()
    {
        $headers = ['rom', 'size_bytes'];
        
        $callback = function() use($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=chd_import_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $deletedCount = Chd::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', "Successfully deleted {$deletedCount} CHDs.");
    }
}
