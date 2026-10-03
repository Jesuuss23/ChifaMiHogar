<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Solo mostramos productos activos
        $products = Product::where('is_active', true)->latest()->get();
        $storeOpen = Setting::where('key', 'store_open')->value('value') ?? '1';
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '51987654321';

        return view('client.index', compact('products', 'storeOpen', 'whatsappNumber'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('addons');
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '51987654321';

        return view('client.show', compact('product', 'whatsappNumber'));
    }
}