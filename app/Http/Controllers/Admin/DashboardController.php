<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Métricas de hoy
        $todayOrders = Order::whereDate('created_at', $today)->get();
        $todaySales = $todayOrders->where('payment_status', 'confirmed')->sum('total');
        $todayPendingCount = $todayOrders->where('order_status', 'received')->count();
        $todayDeliveredCount = $todayOrders->where('order_status', 'delivered')->count();

        // Últimos 5 pedidos
        $recentOrders = Order::latest()->take(5)->get();

        // Platos más pedidos históricamente
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(4)
            ->get();

        // Estado del local (abierto / cerrado)
        $storeOpen = Setting::where('key', 'store_open')->value('value') ?? '1';

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'todayPendingCount',
            'todayDeliveredCount',
            'recentOrders',
            'topProducts',
            'storeOpen'
        ));
    }
}