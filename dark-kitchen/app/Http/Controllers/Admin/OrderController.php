<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
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
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existía
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Checkboxes en HTML no envían valor si no están marcados
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Plato actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        // Con SoftDeletes NO borramos la imagen del disco para que el historial
        // y los reportes de ventas no queden con enlaces rotos.
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Plato retirado del menú.');
    }
}