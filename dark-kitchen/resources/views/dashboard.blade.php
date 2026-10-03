<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-9 w-auto rounded-lg object-contain bg-gray-100 p-0.5 border" alt="Logo">
                <div>
                    <h2 class="font-black text-xl text-gray-800 leading-tight">
                        Panel de Control - Cocina
                    </h2>
                    <p class="text-xs text-gray-500">Resumen y operaciones en vivo de San Juan de Lurigancho</p>
                </div>
            </div>

            <!-- Badge Estado del Local -->
            <div>
                @if($storeOpen == '1')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        LOCAL ABIERTO
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-300">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        LOCAL CERRADO
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. TARJETAS DE MÉTRICAS DEL DÍA -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Ventas de Hoy -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ventas de Hoy</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">S/ {{ number_format($todaySales, 2) }}</h3>
                        <span class="text-[11px] text-gray-400">Solo pagos confirmados</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                        💰
                    </div>
                </div>

                <!-- Pedidos Pendientes -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Por Atender</p>
                        <h3 class="text-2xl font-black text-red-600 mt-1">{{ $todayPendingCount }}</h3>
                        <span class="text-[11px] text-gray-400">Recién recibidos</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl font-bold">
                        🔥
                    </div>
                </div>

                <!-- Pedidos Totales Hoy -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pedidos</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $todayOrders->count() }}</h3>
                        <span class="text-[11px] text-gray-400">Órdenes registradas hoy</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                        📋
                    </div>
                </div>

                <!-- Entregados Hoy -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Entregados</p>
                        <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $todayDeliveredCount }}</h3>
                        <span class="text-[11px] text-gray-400">Con entrega completada</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        🛵
                    </div>
                </div>
            </div>

            <!-- 2. ACCESOS RÁPIDOS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.orders.index') }}" class="p-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white rounded-2xl shadow-md flex items-center justify-between transition group">
                    <div>
                        <h4 class="font-extrabold text-base">Ir a Bandeja de Pedidos</h4>
                        <p class="text-xs text-red-100 mt-0.5">Control de cocina y motorizados</p>
                    </div>
                    <span class="text-2xl group-hover:translate-x-1 transition">🛵 →</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="p-4 bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 rounded-2xl shadow-sm flex items-center justify-between transition group">
                    <div>
                        <h4 class="font-extrabold text-base">Ajustes y Tarifas</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Km gratis, Yape y GPS cocina</p>
                    </div>
                    <span class="text-2xl group-hover:translate-x-1 transition">⚙️ →</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="p-4 bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 rounded-2xl shadow-sm flex items-center justify-between transition group">
                    <div>
                        <h4 class="font-extrabold text-base">Ver Menú de Clientes</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Abrir la carta pública</p>
                    </div>
                    <span class="text-2xl group-hover:translate-x-1 transition">👀 ↗</span>
                </a>
            </div>

            <!-- 3. TABLA DE ÚLTIMOS PEDIDOS Y POPULARES -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Últimos Pedidos -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-extrabold text-gray-900 text-base">Últimos Pedidos Registrados</h3>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-red-600 hover:underline">Ver todos</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 border-b uppercase text-[10px]">
                                    <th class="pb-2">Código</th>
                                    <th class="pb-2">Cliente</th>
                                    <th class="pb-2">Total</th>
                                    <th class="pb-2">Estado</th>
                                    <th class="pb-2 text-right">Hora</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-black text-red-600">#{{ $order->order_code }}</td>
                                        <td class="py-3 font-semibold text-gray-800">{{ $order->customer_name }}</td>
                                        <td class="py-3 font-bold text-gray-900">S/ {{ number_format($order->total, 2) }}</td>
                                        <td class="py-3">
                                            @if($order->order_status === 'received')
                                                <span class="bg-red-50 text-red-700 font-bold px-2 py-0.5 rounded text-[10px]">📥 Recibido</span>
                                            @elseif($order->order_status === 'cooking')
                                                <span class="bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded text-[10px]">🔥 En Wok</span>
                                            @elseif($order->order_status === 'on_the_way')
                                                <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-[10px]">🛵 En Camino</span>
                                            @elseif($order->order_status === 'delivered')
                                                <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">✔️ Entregado</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 font-bold px-2 py-0.5 rounded text-[10px]">{{ $order->order_status }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-right text-gray-400">{{ $order->created_at->format('H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-400">Aún no hay pedidos hoy.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Platos Más Vendidos -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 text-base mb-4">Platos Estrella ⭐</h3>
                    
                    <div class="space-y-3">
                        @forelse($topProducts as $top)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-base font-black text-amber-600">#{{ $loop->iteration }}</span>
                                    <div>
                                        <p class="font-bold text-xs text-gray-800">{{ $top->product->name ?? 'Plato' }}</p>
                                        <span class="text-[10px] text-gray-400">S/ {{ number_format($top->product->price ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                <span class="bg-white px-2 py-1 rounded-lg border text-xs font-black text-gray-700 shadow-sm">
                                    {{ $top->total_qty }} vendidos
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Aún no hay suficientes ventas registradas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>