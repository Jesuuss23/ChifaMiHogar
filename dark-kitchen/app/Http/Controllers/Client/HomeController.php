<?php

namespace App\Http\Controllers\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Configuraciones de la tienda (para que client/index.blade.php no falle)
        $storeOpen = Setting::where('key', 'store_open')->value('value') ?? '1';
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '51987654321';
        $bannerText = Setting::where('key', 'banner_text')->value('value') ?? null;

        // 2. Categorías activas y ordenadas con sus productos activos
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->with(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        // 3. Platos Estrella (Configurados manualmente en el panel)
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        // 4. Los Más Vendidos (Historial real de pedidos)
        $bestSellerIds = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->pluck('product_id');

        $bestSellers = Product::where('is_active', true)
            ->whereIn('id', $bestSellerIds)
            ->get();

        // Fallback: Si la web es nueva y aún no hay pedidos registrados
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('is_active', true)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('client.index', compact(
            'storeOpen',
            'whatsappNumber',
            'bannerText',
            'categories',
            'featuredProducts',
            'bestSellers'
        ));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('addons');
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '51987654321';
        $storeOpen = Setting::where('key', 'store_open')->value('value') ?? '1';
        $whatsappCommunityUrl = Setting::where('key', 'whatsapp_community_url')->value('value') ?? '#';

        return view('client.show', compact('product', 'whatsappNumber', 'storeOpen', 'whatsappCommunityUrl'));
    }
}