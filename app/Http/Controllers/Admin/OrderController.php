<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Traemos los pedidos con sus platos y adicionales, los más recientes primero
        $orders = Order::with('items.product')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'nullable|in:received,cooking,on_the_way,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,confirmed,rejected',
            'payment_receipt' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('payment_receipt')) {
            $order->payment_receipt = $request->file('payment_receipt')->store('vouchers', 'public');
            $order->payment_status = 'confirmed';
        }

        if (isset($validated['order_status'])) {
            $order->order_status = $validated['order_status'];
        }

        if (isset($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        }

        $order->save();

        return back()->with('success', "Pedido #{$order->order_code} actualizado.");
    }

}