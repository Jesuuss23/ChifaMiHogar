<x-app-layout>
<x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bandeja de Pedidos') }}
            </h2>
            <div class="flex items-center gap-2">
                <!-- Botón Instalar App (Solo aparece si el navegador lo permite y no está instalada aún) -->
                <button id="btn-install-pwa" type="button" class="hidden text-xs bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1.5 rounded-lg shadow flex items-center gap-1.5 transition active:scale-95">
                    <span>📲</span> Instalar App en Celular
                </button>

                <button onclick="location.reload()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-1.5 rounded-lg border flex items-center gap-1 transition">
                    🔄 Actualizar Pedidos
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b text-gray-500 uppercase text-xs">
                                    <th class="py-3 px-4">Código / Cliente</th>
                                    <th class="py-3 px-4">Entrega / Dirección</th>
                                    <th class="py-3 px-4">Detalle del Pedido</th>
                                    <th class="py-3 px-4">Total</th>
                                    <th class="py-3 px-4">Método y Pago</th>
                                    <th class="py-3 px-4">Estado Cocina</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-sm">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 align-top">
                                        <!-- Código y Datos del Cliente -->
                                        <td class="py-4 px-4">
                                            <span class="font-black text-red-600 block text-base">#{{ $order->order_code }}</span>
                                            <span class="font-bold text-gray-800 block mt-1">{{ $order->customer_name }}</span>
                                            <a href="https://wa.me/51{{ $order->customer_phone }}" target="_blank" class="text-xs text-green-600 hover:underline flex items-center gap-1 mt-0.5 font-medium">
                                                📲 {{ $order->customer_phone }}
                                            </a>
                                            <span class="text-[11px] text-gray-400 block mt-1">{{ $order->created_at->format('d/m H:i') }}</span>
                                        </td>

                                        <!-- Dirección -->
                                        <td class="py-4 px-4 max-w-xs">
                                            <p class="text-xs text-gray-800 font-medium leading-relaxed">{{ $order->delivery_address }}</p>
                                            @if($order->reference)
                                                <p class="text-[11px] text-gray-500 mt-1 italic">Ref: {{ $order->reference }}</p>
                                            @endif
                                            @if($order->notes)
                                                <div class="mt-2 bg-amber-50 text-amber-800 p-2 rounded text-[11px] border border-amber-200">
                                                    <strong>Nota cocina:</strong> {{ $order->notes }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Items pedidos -->
                                        <td class="py-4 px-4">
                                            <ul class="text-xs space-y-1">
                                                @foreach($order->items as $item)
                                                    <li class="font-medium text-gray-800">
                                                        <strong>{{ $item->quantity }}x</strong> {{ $item->product ? $item->product->name : 'Plato' }}
                                                        @if(!empty($item->selected_addons))
                                                            <div class="pl-3 text-[11px] text-gray-500">
                                                                @foreach($item->selected_addons as $addon)
                                                                    <span>+ {{ $addon['name'] }}</span><br>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <!-- Total -->
                                        <td class="py-4 px-4 font-black text-gray-900 whitespace-nowrap">
                                            S/ {{ number_format($order->total, 2) }}
                                        </td>

                                        <!-- Método y Estado de Pago -->
                                        <td class="py-4 px-4">
                                            <!-- Indicador de Método de Pago -->
                                            <div class="mb-2">
                                                @if($order->payment_method === 'cash')
                                                    <span class="inline-block bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2 py-0.5 rounded">
                                                        💵 Efectivo: {{ $order->cash_amount ?? 'Monto exacto' }}
                                                    </span>
                                                @else
                                                    <span class="inline-block bg-purple-100 text-purple-800 text-[11px] font-bold px-2 py-0.5 rounded">
                                                        📱 Yape / Plin
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Comprobante / Voucher -->
                                            @if($order->payment_receipt)
                                                <div class="mb-2">
                                                    <button type="button" onclick="openModal('{{ asset('storage/' . $order->payment_receipt) }}')" 
                                                            class="group block relative w-12 h-12 rounded-lg overflow-hidden border border-gray-300 shadow-sm">
                                                        <img src="{{ asset('storage/' . $order->payment_receipt) }}" class="w-full h-full object-cover">
                                                        <span class="absolute inset-0 flex items-center justify-center text-[9px] text-white font-bold bg-black/50 opacity-0 group-hover:opacity-100 transition">Ver</span>
                                                    </button>
                                                </div>
                                            @else
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label class="cursor-pointer text-[11px] text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1">
                                                        <span>📷 Subir captura</span>
                                                        <input type="file" name="payment_receipt" accept="image/*" class="hidden" onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                            @endif

                                            <!-- Selector de Estado de Pago -->
                                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="payment_status" onchange="this.form.submit()" class="text-[11px] py-1 px-2 border-gray-300 rounded font-semibold {{ $order->payment_status === 'confirmed' ? 'text-green-700 bg-green-50' : ($order->payment_status === 'rejected' ? 'text-red-700 bg-red-50' : 'text-amber-700 bg-amber-50') }}">
                                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>⏳ Pendiente</option>
                                                    <option value="confirmed" {{ $order->payment_status === 'confirmed' ? 'selected' : '' }}>✅ Pagado</option>
                                                    <option value="rejected" {{ $order->payment_status === 'rejected' ? 'selected' : '' }}>❌ Rechazado</option>
                                                </select>
                                            </form>
                                        </td>

                                        <!-- Estado de Cocina / Envío -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="order_status" onchange="this.form.submit()" class="text-xs py-1.5 px-3 border-gray-300 rounded-lg font-bold shadow-sm">
                                                    <option value="received" {{ $order->order_status === 'received' ? 'selected' : '' }}>📥 Recibido</option>
                                                    <option value="cooking" {{ $order->order_status === 'cooking' ? 'selected' : '' }}>🔥 En Cocina (Wok)</option>
                                                    <option value="on_the_way" {{ $order->order_status === 'on_the_way' ? 'selected' : '' }}>🛵 En Camino</option>
                                                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>✔️ Entregado</option>
                                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>⛔ Cancelado</option>
                                                </select>
                                            </form>
                                        </td>
                                        <!-- Columna de Delivery / Motorizado -->
                                        <td class="px-4 py-3 text-right">
                                            @if($order->latitude && $order->longitude)
                                                @if($order->order_status === 'delivered')
                                                    <!-- Estado Entregado: Botón secundario/informativo -->
                                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" 
                                                    target="_blank" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 border border-gray-300 text-xs font-semibold transition">
                                                        <span>📍</span> Ver Ubicación (Entregado)
                                                    </a>
                                                @elseif($order->order_status === 'cancelled')
                                                    <!-- Estado Cancelado -->
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-400 text-xs font-medium">
                                                        Pedido cancelado
                                                    </span>
                                                @else
                                                    <!-- Estados Activos (Recibido, En Cocina, En Camino): Botón de Acción -->
                                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->latitude }},{{ $order->longitude }}" 
                                                    target="_blank" 
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                                                        <span>📍</span> Iniciar Entrega (GPS)
                                                    </a>
                                                @endif

                                                <div class="text-[10px] text-gray-400 mt-1 font-mono">
                                                    Coords: {{ number_format($order->latitude, 5) }}, {{ number_format($order->longitude, 5) }}
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Sin GPS</span>
                                            @endif
                                        </td>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-400 text-sm">
                                            No hay pedidos registrados todavía.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver voucher -->
    <div id="voucher-modal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4" onclick="closeModal()">
        <div class="relative max-w-sm w-full bg-white rounded-xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
            <div class="p-3 bg-gray-100 flex justify-between items-center border-b">
                <span class="text-xs font-bold text-gray-700">Comprobante de Pago</span>
                <button type="button" onclick="closeModal()" class="text-gray-500 font-black hover:text-black">&times;</button>
            </div>
            <div class="p-2 max-h-[80vh] overflow-y-auto">
                <img id="modal-img" src="" alt="Voucher de Pago" class="w-full h-auto rounded">
            </div>
        </div>
    </div>

    <script>
        function openModal(src) {
            document.getElementById('modal-img').src = src;
            document.getElementById('voucher-modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('voucher-modal').classList.add('hidden');
        }
    </script>
    <script>
        let deferredPrompt;
        const installBtn = document.getElementById('btn-install-pwa');

        // Captura el evento del navegador cuando la web está lista para ser instalada
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Mostramos el botón en el panel admin
            if (installBtn) {
                installBtn.classList.remove('hidden');
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    installBtn.classList.add('hidden');
                }
                deferredPrompt = null;
            });
        }

        // Si ya está abierta como app instalada, ocultamos el botón
        window.addEventListener('appinstalled', () => {
            if (installBtn) {
                installBtn.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>