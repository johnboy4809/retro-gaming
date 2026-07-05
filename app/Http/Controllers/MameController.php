<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mame;
use App\Models\ArcadeBoard;
use App\Models\Order;

class MameController extends Controller
{
    public function index(Request $request)
    {
        $group = $request->input('group', 'arcade');
        $system = $request->input('system', 'mame');

        if ($group !== 'arcade') {
            $mames = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $stats = ['total' => 0, 'bios' => 0, 'clones' => 0, 'chds' => 0];
            $hardwareBoards = collect([]);
            return view('dashboard', compact('mames', 'stats', 'hardwareBoards', 'group', 'system'));
        }

        if ($system === 'fbneo') {
            $query = Mame::whereIn('rom', function ($q) {
                $q->select('rom')->from('fbneo');
            })->with('arcadeBoard');
        } else {
            $query = Mame::with('arcadeBoard');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('rom', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('driver', 'like', "%{$search}%")
                  ->orWhereHas('arcadeBoard', function($hwQ) use ($search) {
                      $hwQ->where('board', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('use_bios')) {
            $query->where('use_bios', $request->input('use_bios') === '1');
        }
        if ($request->filled('use_chds')) {
            $query->where('use_chds', $request->input('use_chds') === '1');
        }
        if ($request->filled('hardware_board')) {
            $query->whereHas('arcadeBoard', function($hwQ) use ($request) {
                $hwQ->where('id', $request->input('hardware_board'));
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'full_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $allowedSorts = ['rom', 'full_name', 'year', 'manufacturer'];
        
        if ($sortBy === 'hardware_board') {
            $query->leftJoin('arcade_boards', 'mame.driver', '=', 'arcade_boards.driver')
                  ->select('mame.*')
                  ->orderBy('arcade_boards.board', $sortOrder === 'desc' ? 'desc' : 'asc');
        } elseif (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('full_name', 'asc');
        }

        $mames = $query->paginate(30)->withQueryString();

        // Calculate Stats
        if ($system === 'fbneo') {
            $baseStatsQuery = Mame::whereIn('rom', function ($q) {
                $q->select('rom')->from('fbneo');
            });
        } else {
            $baseStatsQuery = Mame::query();
        }

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'bios' => (clone $baseStatsQuery)->where('use_bios', true)->count(),
            'clones' => (clone $baseStatsQuery)->whereNotNull('cloneof')->where('cloneof', '!=', '')->count(),
            'chds' => (clone $baseStatsQuery)->where('use_chds', true)->count(),
        ];

        $hardwareBoards = ArcadeBoard::orderBy('board')->get();

        return view('dashboard', compact('mames', 'stats', 'hardwareBoards', 'group', 'system'));
    }

    public function update(Request $request, Mame $mame)
    {
        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:20',
            'manufacturer' => 'nullable|string|max:150',
            'driver' => 'nullable|string|max:100',
            'sourcefile' => 'nullable|string|max:100',
        ]);

        // Map request checkbox/boolean inputs
        $validated['use_bios'] = $request->boolean('use_bios');
        $validated['use_chds'] = $request->boolean('use_chds');

        $mame->update($validated);

        return redirect()->back()->with('success', 'MAME ROM updated successfully!');
    }

    public function ordersIndex(Request $request)
    {
        $orders = Order::with(['user', 'items.mame'])->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function orderUpdateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Shipped,Cancelled',
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
