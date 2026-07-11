<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items.game'])->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Shipped,Cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', "Order #{$order->id} status updated to {$validated['status']}.");
    }
}
