<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajustes del Negocio y Delivery') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Configuración de Pagos -->
                    <div class="border-b pb-5">
                        <h3 class="font-bold text-gray-900 text-base mb-3">📱 Configuración de Yape / Plin</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Número de Yape / Plin *</label>
                                <input type="text" name="payment_phone" value="{{ old('payment_phone', $settings['payment_phone'] ?? '987654321') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre del Titular *</label>
                                <input type="text" name="payment_name" value="{{ old('payment_name', $settings['payment_name'] ?? 'Chifa Mi Hogar') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Imagen del QR Oficial (Yape / Plin)</label>
                            @if(!empty($settings['yape_qr']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['yape_qr']) }}" alt="QR Actual" class="w-28 h-28 object-contain border rounded-lg p-1 bg-gray-50">
                                    <span class="text-[11px] text-gray-500">QR activo actualmente</span>
                                </div>
                            @endif
                            <input type="file" name="yape_qr" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 hover:file:bg-gray-200">
                        </div>
                    </div>

                    <!-- Configuración de Delivery y Geolocalización -->
                    <div class="border-b pb-5">
                        <h3 class="font-bold text-gray-900 text-base mb-3">🛵 Delivery y Ubicación de la Cocina</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">WhatsApp de Pedidos (con código 51) *</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '51987654321') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kilómetros Gratis de Cobertura *</label>
                                <input type="number" step="0.5" name="free_delivery_km" value="{{ old('free_delivery_km', $settings['free_delivery_km'] ?? '3.0') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                                <span class="text-[11px] text-gray-400">Hasta cuántos km el envío es S/ 0.00 (ej: 3.0)</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Costo por KM Adicional (S/) *</label>
                                <input type="number" step="0.50" name="extra_km_fee" value="{{ old('extra_km_fee', $settings['extra_km_fee'] ?? '2.00') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                                <span class="text-[11px] text-gray-400">Tarifa que se suma por cada km extra</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Latitud de la Cocina (SJL) *</label>
                                <input type="text" name="kitchen_lat" value="{{ old('kitchen_lat', $settings['kitchen_lat'] ?? '-12.012500') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Longitud de la Cocina (SJL) *</label>
                                <input type="text" name="kitchen_lng" value="{{ old('kitchen_lng', $settings['kitchen_lng'] ?? '-77.001200') }}" class="w-full border-gray-300 rounded-lg text-sm" required>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-[11px] text-gray-500 italic">
                                    💡 Tip: Abre Google Maps en tu computadora, haz clic derecho sobre tu cocina/local y copia los dos números de coordenadas (ej: -12.0125, -77.0012).
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado del Local -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="store_open" name="store_open" value="1" {{ ($settings['store_open'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="store_open" class="text-sm font-semibold text-gray-800">Local abierto para recibir pedidos</label>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm shadow">
                            Guardar Ajustes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>