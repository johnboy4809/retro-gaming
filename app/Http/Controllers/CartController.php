<?php

namespace App\Http\Controllers;

use App\Models\Mame;
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
        $items = [];
        
        if (!empty($cart)) {
            $items = Mame::whereIn('id', array_keys($cart))->with('arcadeBoard')->get();
        }

        return view('cart', compact('items'));
    }

    /**
     * Add ROM to selection list.
     */
    public function add(Request $request)
    {
        $mameId = $request->input('mame_id');
        $mame = Mame::findOrFail($mameId);

        $cart = session()->get('cart', []);
        $cart[$mameId] = [
            'rom' => $mame->rom,
            'full_name' => $mame->full_name,
        ];

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($cart),
                'message' => "{$mame->rom} added to custom drive builder."
            ]);
        }

        return redirect()->back()->with('success', "ROM '{$mame->rom}' added to custom drive.");
    }

    /**
     * Remove ROM from selection list.
     */
    public function remove(Request $request)
    {
        $mameId = $request->input('mame_id');

        $cart = session()->get('cart', []);
        if (isset($cart[$mameId])) {
            unset($cart[$mameId]);
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
        foreach (array_keys($cart) as $mameId) {
            $items[] = [
                'order_id' => $order->id,
                'mame_id' => $mameId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        OrderItem::insert($items);

        // Clear Cart
        session()->forget('cart');

        return redirect('/')->with('success', 'Your custom drive request has been submitted successfully! We will write your drive and post it shortly.');
    }
}
