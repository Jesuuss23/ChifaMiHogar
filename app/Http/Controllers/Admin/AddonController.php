<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::latest()->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('addons', 'public');
        }

        Addon::create([
            'name'      => $validated['name'],
            'price'     => $validated['price'],
            'image'     => $imagePath,
            'is_active' => true,
        ]);

        return back()->with('success', 'Adicional creado con éxito.');
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($addon->image && Storage::disk('public')->exists($addon->image)) {
                Storage::disk('public')->delete($addon->image);
            }
            $addon->image = $request->file('image')->store('addons', 'public');
        }

        $addon->name = $validated['name'];
        $addon->price = $validated['price'];
        $addon->is_active = $request->has('is_active');
        $addon->save();

        return back()->with('success', 'Adicional actualizado con éxito.');
    }

    public function destroy(Addon $addon)
    {
        if ($addon->image && Storage::disk('public')->exists($addon->image)) {
            Storage::disk('public')->delete($addon->image);
        }
        $addon->products()->detach();
        $addon->delete();

        return back()->with('success', 'Adicional eliminado correctamente.');
    }
}