<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Pedido - Chifa Mi Hogar</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF5EA] text-stone-800 min-h-screen pb-16 font-sans antialiased">

    <!-- Barra Superior -->
    <div class="bg-[#FFFDF8]/90 backdrop-blur border-b border-stone-200 px-4 py-3 flex items-center justify-between sticky top-0 z-20 shadow-sm">
        <a href="{{ route('home') }}" class="text-xs font-bold text-stone-600 hover:text-red-700 flex items-center gap-1 transition">
            ← Seguir Pidiendo
        </a>
        <span class="font-extrabold text-[11px] uppercase tracking-[0.2em] text-red-700">Finalizar Pedido</span>
    </div>

    <!-- Encabezado -->
    <div class="bg-gradient-to-b from-red-800 to-red-700 text-center pt-6 pb-10 px-4 rounded-b-[2rem] shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0); background-size: 18px 18px;"></div>
        <div class="relative">
            <p class="text-[11px] uppercase tracking-[0.3em] text-amber-200 font-semibold">Último paso</p>
            <h1 class="font-['Playfair_Display',serif] text-2xl font-extrabold text-white mt-1">Completa tu Pedido</h1>
            <p class="text-xs text-red-100 mt-1">Revisa tu orden, indica tu entrega y elige cómo pagar</p>
        </div>
    </div>

    <main class="max-w-md mx-auto px-4 -mt-6 relative z-10">
        <!-- Resumen de Productos -->
        <div class="bg-[#FFFDF8] ring-1 ring-stone-900/5 rounded-3xl p-5 shadow-lg shadow-stone-300/50 mb-5">
            <div class="flex items-center gap-2.5 border-b border-stone-200 pb-3 mb-3">
                <span class="w-7 h-7 rounded-full bg-red-700 text-white text-xs font-black flex items-center justify-center shadow">1</span>
                <h2 class="font-['Playfair_Display',serif] font-bold text-lg text-red-800">Resumen de tu Orden</h2>
            </div>

            <div id="cart-summary" class="divide-y divide-dashed divide-stone-300 text-xs">
                <!-- Se llena dinámicamente con JS -->
            </div>

            <div class="border-t border-stone-200 pt-3 mt-3 text-xs space-y-1.5">
                <div class="flex justify-between text-stone-500">
                    <span>Subtotal platos:</span>
                    <span class="text-stone-800">S/ <span id="summary-subtotal">0.00</span></span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span>Costo Delivery:</span>
                    <span class="text-stone-800">S/ <span id="summary-delivery">0.00</span></span>
                </div>
                <div class="flex justify-between items-center text-base font-black text-stone-900 pt-3 mt-1 border-t border-dashed border-stone-300">
                    <span>Total a Pagar:</span>
                    <span class="text-red-700 text-xl">S/ <span id="summary-total">0.00</span></span>
                </div>
            </div>

            <button type="button" onclick="clearCart()" class="text-[11px] text-red-600 hover:text-red-800 font-semibold underline mt-3">
                Vaciar Carrito
            </button>
        </div>

        <!-- Formulario de Pago y Entrega -->
        <form action="{{ route('order.process') }}" method="POST" id="checkout-form" class="space-y-5">
            @csrf
            <input type="hidden" name="latitude" id="latitude_input">
            <input type="hidden" name="longitude" id="longitude_input">
            <input type="hidden" name="cart_data" id="cart_data_input">
            <input type="hidden" name="delivery_distance" id="delivery_distance" value="0">
            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0.00">

            <!-- Datos de Entrega -->
            <div class="bg-[#FFFDF8] ring-1 ring-stone-900/5 rounded-3xl p-5 shadow-lg shadow-stone-300/50 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-stone-200 pb-3">
                    <span class="w-7 h-7 rounded-full bg-red-700 text-white text-xs font-black flex items-center justify-center shadow">2</span>
                    <h2 class="font-['Playfair_Display',serif] font-bold text-lg text-red-800">📍 Datos de Entrega</h2>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Nombre Completo *</label>
                    <input type="text" name="customer_name" required placeholder="Ej: Carlos Carhuancho"
                           class="w-full bg-stone-50 border-stone-300 rounded-xl text-sm text-stone-800 placeholder-stone-400 py-2.5 focus:bg-white focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Teléfono / WhatsApp *</label>
                    <input type="tel" name="customer_phone" required
                        pattern="[0-9]{9}" maxlength="9" minlength="9"
                        placeholder="Ej: 987654321"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full bg-stone-50 border-stone-300 rounded-xl text-sm text-stone-800 placeholder-stone-400 py-2.5 focus:bg-white focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-stone-700">Dirección Exacta (Calle, Av, Número, Dpto) *</label>
                        <span class="text-[10px] text-amber-700 font-bold bg-amber-100 border border-amber-200 px-2 py-0.5 rounded-full">
                            ⭐ 100% Precisión
                        </span>
                    </div>

                    <div class="flex gap-2 items-stretch">
                        <input type="text" id="delivery_address" name="delivery_address" required placeholder="Ej: Av. Las Flores 345, Dpto 201"
                            class="w-full bg-stone-50 border-stone-300 rounded-xl text-sm text-stone-800 placeholder-stone-400 py-2.5 focus:bg-white focus:ring-red-500 focus:border-red-500">

                        <!-- Botón GPS con tooltip/burbuja flotante -->
                        <div class="relative shrink-0 flex items-center">
                            <button type="button" onclick="getLocation()"
                                    class="h-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-bold px-3 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-md shadow-red-900/20 active:scale-95 transition">
                                <span class="text-base">📍</span> Mi Ubicación
                            </button>
                        </div>
                    </div>

                    <!-- Nota inferior con beneficio directo -->
                    <p class="text-[11px] text-stone-500 mt-1.5 flex items-center gap-1">
                        <span class="text-red-600">💡</span> 
                        <span>Toca <strong>"Mi Ubicación"</strong> para calcular la distancia exacta y que tu chifa llegue caliente sin demoras.</span>
                    </p>
                    
                    <p id="geo-status" class="text-[11px] font-medium text-stone-600 mt-1"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Referencia</label>
                    <input type="text" name="reference" placeholder="Ej: Altura del paradero 5, portón negro"
                           class="w-full bg-stone-50 border-stone-300 rounded-xl text-sm text-stone-800 placeholder-stone-400 py-2.5 focus:bg-white focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Indicaciones especiales de cocina</label>
                    <textarea name="notes" rows="2" placeholder="Ej: Bajo en sal, sin cebolla china, ají aparte..."
                              class="w-full bg-stone-50 border-stone-300 rounded-xl text-sm text-stone-800 placeholder-stone-400 focus:bg-white focus:ring-red-500 focus:border-red-500"></textarea>
                </div>
            </div>

            <!-- Selector de Método de Pago -->
            <div class="bg-[#FFFDF8] ring-1 ring-stone-900/5 rounded-3xl p-5 shadow-lg shadow-stone-300/50 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-stone-200 pb-3">
                    <span class="w-7 h-7 rounded-full bg-red-700 text-white text-xs font-black flex items-center justify-center shadow">3</span>
                    <h2 class="font-['Playfair_Display',serif] font-bold text-lg text-red-800">💳 Método de Pago</h2>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Opción Yape / Plin -->
                    <label class="cursor-pointer border rounded-2xl p-3.5 flex flex-col items-center gap-1.5 transition text-center"
                           id="label-yape" onclick="selectPaymentMethod('yape')">
                        <input type="radio" name="payment_method" value="yape" checked class="hidden">
                        <span class="text-2xl">📱</span>
                        <span class="text-xs font-black text-stone-900">Yape / Plin</span>
                        <span class="text-[10px] text-purple-700">Sin comisiones</span>
                    </label>

                    <!-- Opción Efectivo -->
                    <label class="cursor-pointer border rounded-2xl p-3.5 flex flex-col items-center gap-1.5 transition text-center"
                           id="label-cash" onclick="selectPaymentMethod('cash')">
                        <input type="radio" name="payment_method" value="cash" class="hidden">
                        <span class="text-2xl">💵</span>
                        <span class="text-xs font-black text-stone-900">Efectivo</span>
                        <span class="text-[10px] text-emerald-700">Contra entrega</span>
                    </label>
                </div>

                <!-- Detalle si es YAPE / PLIN -->
                <div id="section-yape" class="bg-purple-50/70 border border-purple-200 p-5 rounded-2xl text-center">
                    
                    <!-- Contenedor del QR más grande -->
                    <div class="relative inline-block mb-2 group">
                        @php
                            $qrUrl = !empty($yapeQr) ? $yapeQr : "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$paymentPhone}";
                        @endphp
                        
                        <img id="qr-image" src="{{ $qrUrl }}" alt="QR de Pago" 
                            onclick="openQrModal()"
                            class="w-48 h-48 mx-auto rounded-2xl bg-white p-3 shadow-lg border-2 border-purple-300 object-contain cursor-pointer active:scale-95 transition">
                            
                        <span class="block text-[11px] font-semibold text-purple-700 mt-2">
                            🔍 Toca el QR para ampliarlo
                        </span>
                    </div>

                    <!-- Botones de Acción para el QR -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <button type="button" onclick="openQrModal()" 
                                class="bg-purple-100 hover:bg-purple-200 text-purple-900 border border-purple-300 text-xs font-bold py-2 px-3 rounded-xl transition flex items-center gap-1 active:scale-95">
                            <span>🔎</span> Ampliar
                        </button>

                        <button type="button" onclick="downloadQr('{{ $qrUrl }}')" 
                                class="bg-purple-700 hover:bg-purple-800 text-black text-xs font-bold py-2 px-3 rounded-xl shadow transition flex items-center gap-1 active:scale-95">
                            <span>📥</span> Guardar QR
                        </button>
                    </div>

                    <div class="border-t border-dashed border-purple-300 pt-3">
                        <p class="text-xs text-stone-500">Titular: <strong class="text-stone-800">{{ $paymentName }}</strong></p>

                        <div class="flex items-center justify-center gap-2 mt-2">
                            <span id="payment-phone" class="text-2xl font-black tracking-widest text-red-700">{{ $paymentPhone }}</span>
                            <button type="button" id="btn-copy" onclick="copyNumber()" class="bg-red-700 hover:bg-red-600 text-white text-xs font-black px-3.5 py-2 rounded-xl shadow transition flex items-center gap-1.5 active:scale-95">
                                <span id="copy-icon">📋</span>
                                <span id="copy-text">Copiar</span>
                            </button>
                        </div>

                        <div class="mt-4 p-3 bg-purple-100/70 rounded-xl border border-purple-200 text-xs text-purple-950 text-left space-y-1">
                            <p class="font-bold flex items-center gap-1.5">💡 ¿Cómo pagar fácil?</p>
                            <p>1. <strong>Copia el número</strong> o <strong>guarda el QR</strong>.</p>
                            <p>2. Abre tu <strong>Yape</strong> o <strong>Plin</strong> y realiza el pago.</p>
                            <p>3. Confirma abajo y reenvíanos la captura por WhatsApp.</p>
                        </div>
                    </div>
                </div>

                <!-- Modal para ver el QR en Pantalla Completa -->
                <div id="qr-modal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex flex-col items-center justify-center p-4">
                    <div class="bg-white p-5 rounded-3xl max-w-xs w-full text-center relative shadow-2xl animate-fade-in">
                        <button type="button" onclick="closeQrModal()" class="absolute top-3 right-3 text-stone-400 hover:text-stone-700 text-xl font-black w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center">
                            ✕
                        </button>
                        
                        <p class="font-['Playfair_Display',serif] font-bold text-lg text-purple-900 mb-1">Escanear QR</p>
                        <p class="text-[11px] text-stone-500 mb-3">Puedes tomar captura ahora o guardarlo</p>
                        
                        <img src="{{ $qrUrl }}" alt="QR Ampliado" class="w-64 h-64 mx-auto rounded-2xl border border-purple-200 p-2 shadow-inner bg-stone-50 object-contain">

                        <div class="mt-4 flex gap-2">
                            <button type="button" onclick="downloadQr('{{ $qrUrl }}')" class="flex-1 bg-purple-700 hover:bg-purple-800 text-black text-xs font-bold py-2.5 rounded-xl shadow">
                                📥 Guardar Imagen
                            </button>
                            <button type="button" onclick="closeQrModal()" class="flex-1 bg-stone-200 hover:bg-stone-300 text-stone-800 text-xs font-bold py-2.5 rounded-xl">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Detalle si es EFECTIVO -->
                <div id="section-cash" class="hidden bg-emerald-50/70 border border-emerald-200 p-4 rounded-2xl space-y-2">
                    <p class="text-xs text-stone-700 font-medium">
                        🛵 Pagarás en efectivo al motorizado cuando te entregue tu pedido.
                    </p>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1.5">¿Con cuánto vas a pagar? (Para llevarte vuelto)</label>
                        <input type="text" name="cash_amount" placeholder="Ej: Pago con S/ 50 (o Monto exacto)"
                               class="w-full bg-white border-stone-300 rounded-xl text-xs text-stone-800 placeholder-stone-400 py-2.5 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
            </div>

            @if(!$storeOpen)
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl text-center text-xs space-y-2">
                    <p class="font-bold text-sm">⛔ Lo sentimos, el local acaba de cerrar.</p>
                    <p>No estamos recibiendo nuevos pedidos en este momento.</p>
                    <a href="{{ $whatsappCommunityUrl }}" target="_blank" class="inline-block mt-2 font-bold text-white bg-red-700 hover:bg-red-600 px-4 py-2 rounded-xl text-xs shadow">
                        Ir a la Comunidad de WhatsApp
                    </a>
                </div>
            @else
                <!-- Botón Final -->
                <button type="submit" class="w-full bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white font-black py-4 rounded-2xl shadow-xl shadow-red-900/25 text-base transition active:scale-95">
                    Confirmar y Enviar Pedido 🚀
                </button>
            @endif

            <p class="text-center text-[11px] text-stone-500">Gracias por elegir Chifa Mi Hogar 🥡</p>
        </form>
    </main>

    <script>
        const cart = JSON.parse(localStorage.getItem('dk_cart') || '[]');

        if (cart.length === 0) {
            window.location.href = "{{ route('home') }}";
        }

        const summaryContainer = document.getElementById('cart-summary');
        let subtotal = 0;

        cart.forEach((item) => {
            subtotal += parseFloat(item.total_item_price);
            let addonsHtml = '';
            if (item.addons && item.addons.length > 0) {
                addonsHtml = item.addons.map(a => `<span class="text-stone-500 block">+ ${a.name} (S/ ${parseFloat(a.price).toFixed(2)})</span>`).join('');
            }

            summaryContainer.innerHTML += `
                <div class="py-3 flex justify-between items-start">
                    <div>
                        <p class="font-bold text-stone-900 text-[13px]">${item.quantity}x ${item.name}</p>
                        ${addonsHtml}
                    </div>
                    <span class="font-bold text-red-700 text-[13px]">S/ ${parseFloat(item.total_item_price).toFixed(2)}</span>
                </div>
            `;
        });

        // Configuración recibida dinámicamente desde el panel Admin
        const KITCHEN_LAT = {{ $kitchenLat }};
        const KITCHEN_LNG = {{ $kitchenLng }};
        const FREE_KM = {{ $freeKm }};
        const EXTRA_KM_FEE = {{ $extraKmFee }};
        let currentDeliveryFee = 0.00;

        document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('summary-delivery').textContent = currentDeliveryFee.toFixed(2);
        document.getElementById('summary-total').textContent = subtotal.toFixed(2);
        document.getElementById('cart_data_input').value = JSON.stringify(cart);

        function clearCart() {
            localStorage.removeItem('dk_cart');
            window.location.href = "{{ route('home') }}";
        }

        document.getElementById('checkout-form').addEventListener('submit', () => {
            setTimeout(() => {
                localStorage.removeItem('dk_cart');
            }, 1000);
        });

        function copyNumber() {
            const phone = document.getElementById('payment-phone').innerText.trim();

            navigator.clipboard.writeText(phone).then(() => {
                const copyText = document.getElementById('copy-text');
                const copyIcon = document.getElementById('copy-icon');
                const walletOptions = document.getElementById('wallet-options');

                copyText.innerText = "¡Copiado!";
                copyIcon.innerText = "✅";
                walletOptions.classList.remove('hidden');

                setTimeout(() => {
                    copyText.innerText = "Copiar";
                    copyIcon.innerText = "📋";
                }, 3000);
            }).catch(err => {
                console.error('Error al copiar:', err);
            });
        }

        function tryOpenPlin(event) {
            setTimeout(() => {
                alert("Abre la app de tu banco (Interbank, BBVA o Scotiabank) y pega el número copiado.");
            }, 1500);
        }

        function selectPaymentMethod(method) {
            const labelYape = document.getElementById('label-yape');
            const labelCash = document.getElementById('label-cash');
            const sectionYape = document.getElementById('section-yape');
            const sectionCash = document.getElementById('section-cash');

            if (method === 'yape') {
                labelYape.className = "cursor-pointer border-2 border-purple-500 bg-purple-50 rounded-2xl p-3.5 flex flex-col items-center gap-1.5 text-center shadow-md shadow-purple-200/60 transition";
                labelCash.className = "cursor-pointer border border-stone-300 bg-white hover:border-stone-400 rounded-2xl p-3.5 flex flex-col items-center gap-1.5 text-center transition";
                sectionYape.classList.remove('hidden');
                sectionCash.classList.add('hidden');
                document.querySelector('input[name="payment_method"][value="yape"]').checked = true;
            } else {
                labelCash.className = "cursor-pointer border-2 border-emerald-500 bg-emerald-50 rounded-2xl p-3.5 flex flex-col items-center gap-1.5 text-center shadow-md shadow-emerald-200/60 transition";
                labelYape.className = "cursor-pointer border border-stone-300 bg-white hover:border-stone-400 rounded-2xl p-3.5 flex flex-col items-center gap-1.5 text-center transition";
                sectionCash.classList.remove('hidden');
                sectionYape.classList.add('hidden');
                document.querySelector('input[name="payment_method"][value="cash"]').checked = true;
            }
        }

        selectPaymentMethod('yape');

        function getLocation() {
            const status = document.getElementById('geo-status');
            const addressInput = document.getElementById('delivery_address');
            status.textContent = "Obteniendo tu ubicación GPS...";

            if (!navigator.geolocation) {
                status.textContent = "Tu navegador no soporta geolocalización. Ingresa tu dirección manualmente.";
                return;
            }

            navigator.geolocation.getCurrentPosition(async position => {
                const clientLat = position.coords.latitude;
                const clientLng = position.coords.longitude;

                // 1. Guardar en los inputs ocultos para enviarlos al backend
                document.getElementById('latitude_input').value = clientLat;
                document.getElementById('longitude_input').value = clientLng;

                // 2. Calcular la distancia y la tarifa
                const distanceKm = getDistanceFromLatLonInKm(KITCHEN_LAT, KITCHEN_LNG, clientLat, clientLng);
                calculateDeliveryFee(distanceKm);

                // 3. Geocodificación inversa con OpenStreetMap (Nominatim)
                try {
                    status.innerHTML += " <br><span>Buscando tu calle...</span>";
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${clientLat}&lon=${clientLng}&accept-language=es`);
                    const data = await response.json();

                    if (data && data.address) {
                        const road = data.address.road || data.address.pedestrian || data.address.suburb || '';
                        const neighbourhood = data.address.neighbourhood || data.address.residential || '';
                        const cityDistrict = data.address.city_district || data.address.suburb || 'San Juan de Lurigancho';

                        let formattedAddress = [road, neighbourhood, cityDistrict].filter(Boolean).join(', ');

                        if (formattedAddress) {
                            addressInput.value = formattedAddress;
                            status.innerHTML += `<br><span class="text-emerald-700">Dirección aproximada cargada. (Completa el número o lote si falta).</span>`;
                        }
                    }
                } catch (e) {
                    console.log("No se pudo obtener el texto de la calle:", e);
                }

            }, error => {
                status.textContent = "No pudimos acceder a tu GPS. Por favor activa la ubicación del celular.";
            }, {
                enableHighAccuracy: true,
                timeout: 10000
            });
        }

        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radio de la Tierra en km
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLon = (lon2 - lon1) * (Math.PI / 180);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function calculateDeliveryFee(distanceKm) {
            const status = document.getElementById('geo-status');
            const feeDisplay = document.getElementById('summary-delivery');
            const feeInput = document.getElementById('delivery_fee_input');
            const distanceInput = document.getElementById('delivery_distance');
            const totalDisplay = document.getElementById('summary-total');

            distanceInput.value = distanceKm.toFixed(1);

            if (distanceKm <= FREE_KM) {
                currentDeliveryFee = 0.00;
                status.innerHTML = `Estás a <strong>${distanceKm.toFixed(1)} km</strong>. <span class="text-emerald-700 font-bold">¡Delivery GRATIS!</span>`;
            } else {
                const extraKm = distanceKm - FREE_KM;
                currentDeliveryFee = Math.ceil(extraKm) * EXTRA_KM_FEE;
                status.innerHTML = `Estás a <strong>${distanceKm.toFixed(1)} km</strong>. Tarifa de delivery: <span class="text-red-700 font-bold">S/ ${currentDeliveryFee.toFixed(2)}</span>`;
            }

            feeDisplay.textContent = currentDeliveryFee.toFixed(2);
            feeInput.value = currentDeliveryFee.toFixed(2);
            totalDisplay.textContent = (subtotal + currentDeliveryFee).toFixed(2);
        }

        // Abrir modal de QR ampliado
        function openQrModal() {
            document.getElementById('qr-modal').classList.remove('hidden');
        }

        // Cerrar modal
        function closeQrModal() {
            document.getElementById('qr-modal').classList.add('hidden');
        }

        // Descargar el QR al carrete/fotos del celular
        async function downloadQr(url) {
            try {
                const res = await fetch(url);
                const blob = await res.blob();
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = "QR-Pago-ChifaMiHogar.png";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(blobUrl);

                alert("¡QR guardado en tu galería de fotos!");
            } catch (e) {
                // Fallback si la imagen viene de otro dominio restringido
                window.open(url, '_blank');
            }
        }
    </script>
</body>
</html>