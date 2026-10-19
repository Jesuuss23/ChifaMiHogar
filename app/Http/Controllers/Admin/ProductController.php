<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['addons', 'category'])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $addons = Addon::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'addons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'addons'      => 'nullable|array',
            'addons.*'    => 'exists:addons,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']) . '-' . rand(100, 999),
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'image'       => $imagePath,
            'category_id' => $validated['category_id'] ?? null,
            'is_featured' => $request->has('is_featured'),
            'is_active'   => true,
        ]);

        // Sincronizar adicionales globales seleccionados
        if (!empty($validated['addons'])) {
            $product->addons()->sync($validated['addons']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Plato creado exitosamente.');
    }

    public function edit(Product $product)
    {
        $product->load('addons');
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $addons = Addon::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('product', 'categories', 'addons'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'addons'      => 'nullable|array',
            'addons.*'    => 'exists:addons,id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'category_id' => $validated['category_id'] ?? null,
            'is_featured' => $request->has('is_featured'),
            'is_active'   => $request->has('is_active'),
        ]);

        // Sincronizar adicionales globales (si desmarcó todos, desvincula)
        $product->addons()->sync($validated['addons'] ?? []);

        return redirect()->route('admin.products.index')->with('success', 'Plato actualizado.');
    }

    public function destroy(Product $product)
    {
        // Verificar si el producto tiene ventas registradas en order_items
        $hasOrders = DB::table('order_items')->where('product_id', $product->id)->exists();

        if ($hasOrders) {
            // Preservar historial: solo se desactiva del catálogo
            $product->update(['is_active' => false]);
            return redirect()->route('admin.products.index')
                ->with('success', 'El plato tiene pedidos registrados en el historial: se desactivó de la carta.');
        }

        // Si no tiene ventas, borrar relaciones de extras e imagen física
        $product->addons()->detach();

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Plato eliminado correctamente.');
    }
}