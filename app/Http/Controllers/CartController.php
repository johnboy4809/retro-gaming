<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * View selection builder list (cart).
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = collect();
        
        if (!empty($cart)) {
            foreach ($cart as $key => $details) {
                $modelClass = $details['game_type'];
                $item = $modelClass::find($details['game_id']);
                if ($item) {
                    $item->cart_key = $key;
                    $items->push($item);
                }
            }
        }

        return view('cart', compact('items'));
    }

    /**
     * Add ROM to selection list.
     */
    public function add(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
            'game_type' => 'required|string',
        ]);

        $gameId = $request->input('game_id');
        $gameType = $request->input('game_type');
        
        if (!class_exists($gameType)) {
            abort(400, "Invalid game type");
        }

        $game = $gameType::findOrFail($gameId);
        $cartKey = $gameType . '_' . $gameId;

        $cart = session()->get('cart', []);
        $cart[$cartKey] = [
            'game_type' => $gameType,
            'game_id' => $gameId,
            'rom' => $game->rom,
            'title' => $game->title ?? $game->rom,
        ];

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($cart),
                'message' => "{$game->rom} added to custom drive builder."
            ]);
        }

        return redirect()->back()->with('success', "ROM '{$game->rom}' added to custom drive.");
    }

    /**
     * Remove ROM from selection list.
     */
    public function remove(Request $request)
    {
        $cartKey = $request->input('cart_key');

        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($cart),
                'message' => "ROM removed."
            ]);
        }

        return redirect()->back()->with('success', "ROM removed from selection.");
    }

    /**
     * Submit selection list checkout (checkout).
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->withErrors(['cart' => 'Your selection list is empty. Please select ROMs first!']);
        }

        $validated = $request->validate([
            'drive_type' => 'required|string|max:50',
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        // Add user_id
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Pending';

        // Create Order
        $order = Order::create($validated);

        // Add Items
        $items = [];
        foreach ($cart as $details) {
            $items[] = [
                'order_id' => $order->id,
                'game_type' => $details['game_type'],
                'game_id' => $details['game_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        OrderItem::insert($items);

        // Clear Cart
        session()->forget('cart');

        return redirect()->route('library.index')->with('success', 'Your custom drive order has been submitted successfully! We will begin processing it shortly.');
    }
}
