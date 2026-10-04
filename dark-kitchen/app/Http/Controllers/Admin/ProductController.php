<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id'    => 'nullable|exists:categories,id',
            'addons'         => 'nullable|array',
            'addons.*.name'  => 'nullable|string|max:255',
            'addons.*.price' => 'nullable|numeric|min:0',
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

        // Guardar adicionales solo si tienen nombre escrito
        if (!empty($request->addons)) {
            foreach ($request->addons as $addonData) {
                if (!empty($addonData['name'])) {
                    $product->addons()->create([
                        'name'  => $addonData['name'],
                        'price' => $addonData['price'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Plato creado exitosamente.');
    }

    public function edit(Product $product)
    {
        $product->load('addons');
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category_id'    => 'nullable|exists:categories,id',
            'addons'         => 'nullable|array',
            'addons.*.name'  => 'nullable|string|max:255',
            'addons.*.price' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
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

        // Sincronizar o recrear adicionales
        if ($request->has('addons')) {
            $product->addons()->delete();
            foreach ($validated['addons'] as $addonData) {
                if (!empty($addonData['name'])) {
                    $product->addons()->create([
                        'name'  => $addonData['name'],
                        'price' => $addonData['price'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Plato actualizado.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Plato eliminado.');
    }
}