<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Pedido - Chifa Mi Hogar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-900 text-neutral-100 min-h-screen pb-16 font-sans antialiased">

    <!-- Barra Superior -->
    <div class="bg-neutral-950/80 backdrop-blur border-b border-neutral-800 px-4 py-3 flex items-center justify-between sticky top-0 z-20">
        <a href="{{ route('home') }}" class="text-xs font-bold text-neutral-300 hover:text-white flex items-center gap-1">
            ← Seguir Pidiendo
        </a>
        <span class="font-extrabold text-[11px] uppercase tracking-wider text-amber-400">Finalizar Pedido</span>
    </div>

    <main class="max-w-md mx-auto px-4 mt-5">
        <!-- Resumen de Productos -->
        <div class="bg-neutral-800/80 border border-neutral-700 rounded-2xl p-4 shadow-lg mb-5">
            <h2 class="font-bold text-sm text-amber-400 border-b border-neutral-700/60 pb-2 mb-3">Resumen de tu Orden</h2>
            
            <div id="cart-summary" class="divide-y divide-neutral-700/50 text-xs">
                <!-- Se llena dinámicamente con JS -->
            </div>

            <div class="border-t border-neutral-700/60 pt-3 mt-3 text-xs space-y-1.5">
                <div class="flex justify-between text-neutral-400">
                    <span>Subtotal platos:</span>
                    <span class="text-neutral-200">S/ <span id="summary-subtotal">0.00</span></span>
                </div>
                <div class="flex justify-between text-neutral-400">
                    <span>Costo Delivery:</span>
                    <span class="text-neutral-200">S/ <span id="summary-delivery">0.00</span></span>
                </div>
                <div class="flex justify-between text-base font-black text-white pt-2 border-t border-neutral-700">
                    <span>Total a Pagar:</span>
                    <span class="text-amber-400">S/ <span id="summary-total">0.00</span></span>
                </div>
            </div>
            
            <button type="button" onclick="clearCart()" class="text-[11px] text-red-400 hover:text-red-300 font-semibold underline mt-3">
                Vaciar Carrito
            </button>
        </div>

        <!-- Formulario de Pago y Entrega -->
        <form action="{{ route('order.process') }}" method="POST" id="checkout-form" class="space-y-4">
            @csrf
            <input type="hidden" name="latitude" id="latitude_input">
            <input type="hidden" name="longitude" id="longitude_input">
            <input type="hidden" name="cart_data" id="cart_data_input">
            <input type="hidden" name="delivery_distance" id="delivery_distance" value="0">
            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0.00">

            <!-- Datos de Entrega -->
            <div class="bg-neutral-800/80 border border-neutral-700 rounded-2xl p-4 shadow-lg space-y-3">
                <h2 class="font-bold text-sm text-amber-400 border-b border-neutral-700/60 pb-2">📍 Datos de Entrega</h2>

                <div>
                    <label class="block text-xs font-semibold text-neutral-300 mb-1">Nombre Completo *</label>
                    <input type="text" name="customer_name" required placeholder="Ej: Carlos Carhuancho" 
                           class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-sm text-white placeholder-neutral-500 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-300 mb-1">Teléfono / WhatsApp *</label>
                    <input type="tel" name="customer_phone" required 
                        pattern="[0-9]{9}" maxlength="9" minlength="9"
                        placeholder="Ej: 987654321" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-sm text-white placeholder-neutral-500 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-300 mb-1">Dirección Exacta (Calle, Av, Número, Dpto) *</label>
                    <div class="flex gap-2">
                        <input type="text" id="delivery_address" name="delivery_address" required placeholder="Ej: Av. Las Flores 345" 
                               class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-sm text-white placeholder-neutral-500 focus:ring-amber-500 focus:border-amber-500">
                        
                        <button type="button" onclick="getLocation()" 
                                class="bg-neutral-800 hover:bg-neutral-700 border border-amber-500/50 text-amber-400 font-bold px-3 py-2 rounded-xl text-xs flex items-center gap-1.5 whitespace-nowrap active:scale-95 transition">
                            <span>📍</span> Mi Ubicación
                        </button>
                    </div>
                    <p id="geo-status" class="text-[11px] text-neutral-400 mt-1.5"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-300 mb-1">Referencia</label>
                    <input type="text" name="reference" placeholder="Ej: Altura del paradero 5, portón negro" 
                           class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-sm text-white placeholder-neutral-500 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-neutral-300 mb-1">Indicaciones especiales de cocina</label>
                    <textarea name="notes" rows="2" placeholder="Ej: Bajo en sal, sin cebolla china, ají aparte..." 
                              class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-sm text-white placeholder-neutral-500 focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>
            </div>

            <!-- Selector de Método de Pago -->
            <div class="bg-neutral-800/80 border border-neutral-700 rounded-2xl p-5 shadow-lg space-y-4">
                <h2 class="font-bold text-sm text-amber-400 border-b border-neutral-700/60 pb-2">💳 Método de Pago</h2>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Opción Yape / Plin -->
                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center gap-1.5 transition text-center" 
                           id="label-yape" onclick="selectPaymentMethod('yape')">
                        <input type="radio" name="payment_method" value="yape" checked class="hidden">
                        <span class="text-xl">📱</span>
                        <span class="text-xs font-black text-white">Yape / Plin</span>
                        <span class="text-[10px] text-purple-300">Sin comisiones</span>
                    </label>

                    <!-- Opción Efectivo -->
                    <label class="cursor-pointer border rounded-xl p-3 flex flex-col items-center gap-1.5 transition text-center" 
                           id="label-cash" onclick="selectPaymentMethod('cash')">
                        <input type="radio" name="payment_method" value="cash" class="hidden">
                        <span class="text-xl">💵</span>
                        <span class="text-xs font-black text-white">Efectivo</span>
                        <span class="text-[10px] text-emerald-400">Contra entrega</span>
                    </label>
                </div>

                <!-- Detalle si es YAPE / PLIN -->
                <div id="section-yape" class="bg-neutral-950 border border-purple-500/20 p-4 rounded-xl text-center">
                    <div class="relative inline-block mb-3">
                        @if(!empty($yapeQr))
                            <img src="{{ $yapeQr }}" alt="QR de Pago" class="w-36 h-36 mx-auto rounded-xl bg-white p-2 shadow border border-purple-400/30 object-contain">
                        @else
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $paymentPhone }}" alt="QR de Pago" class="w-32 h-32 mx-auto rounded-xl bg-white p-2 shadow border border-purple-400/30">
                        @endif
                        <span class="block text-[10px] text-neutral-400 mt-1">Escanea o haz captura para transferir</span>
                    </div>

                    <div class="border-t border-neutral-800 pt-3">
                        <p class="text-xs text-neutral-400">Titular: <strong class="text-neutral-200">{{ $paymentName }}</strong></p>
                        
                        <div class="flex items-center justify-center gap-2 mt-2">
                            <span id="payment-phone" class="text-2xl font-black tracking-widest text-amber-400">{{ $paymentPhone }}</span>
                            <button type="button" id="btn-copy" onclick="copyNumber()" class="bg-amber-500 hover:bg-amber-400 text-neutral-950 text-xs font-black px-3 py-1.5 rounded-lg shadow transition flex items-center gap-1 active:scale-95">
                                <span id="copy-icon">📋</span>
                                <span id="copy-text">Copiar</span>
                            </button>
                        </div>

                        <div id="wallet-options" class="hidden mt-3 pt-2 border-t border-neutral-800">
                            <p class="text-[11px] text-emerald-400 font-bold mb-1.5">¡Copiado! Abre tu aplicación:</p>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="yape://" class="bg-[#742284] text-white py-1.5 rounded-lg text-xs font-bold shadow">🟣 Abrir Yape</a>
                                <a href="intent://#Intent;package=com.interbank.mobilebanking;scheme=plin;end;" onclick="tryOpenPlin(event)" class="bg-[#00d2c4] text-neutral-900 py-1.5 rounded-lg text-xs font-black shadow">🟢 Abrir Plin</a>
                            </div>
                        </div>

                        <div class="mt-3 p-2 bg-purple-900/30 rounded-lg border border-purple-500/20 text-[11px] text-purple-200">
                            ℹ️ <em>Al confirmar, te abriremos WhatsApp para que solo nos reenvíes la captura de tu Yape.</em>
                        </div>
                    </div>
                </div>

                <!-- Detalle si es EFECTIVO -->
                <div id="section-cash" class="hidden bg-neutral-950 border border-emerald-500/30 p-4 rounded-xl space-y-2">
                    <p class="text-xs text-neutral-300 font-medium">
                        🛵 Pagarás en efectivo al motorizado cuando te entregue tu pedido.
                    </p>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-300 mb-1">¿Con cuánto vas a pagar? (Para llevarte vuelto)</label>
                        <input type="text" name="cash_amount" placeholder="Ej: Pago con S/ 50 (o Monto exacto)" 
                               class="w-full bg-neutral-900 border-neutral-700 rounded-xl text-xs text-white placeholder-neutral-500">
                    </div>
                </div>
            </div>

            <!-- Botón Final -->
            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-black py-4 rounded-xl shadow-lg text-base transition active:scale-95">
                Confirmar y Enviar Pedido 🚀
            </button>
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
                addonsHtml = item.addons.map(a => `<span class="text-neutral-400 block">+ ${a.name} (S/ ${parseFloat(a.price).toFixed(2)})</span>`).join('');
            }

            summaryContainer.innerHTML += `
                <div class="py-2.5 flex justify-between items-start">
                    <div>
                        <p class="font-bold text-white">${item.quantity}x ${item.name}</p>
                        ${addonsHtml}
                    </div>
                    <span class="font-bold text-amber-400">S/ ${parseFloat(item.total_item_price).toFixed(2)}</span>
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
                labelYape.className = "cursor-pointer border-2 border-purple-500 bg-purple-950/30 rounded-xl p-3 flex flex-col items-center gap-1.5 text-center";
                labelCash.className = "cursor-pointer border border-neutral-700 rounded-xl p-3 flex flex-col items-center gap-1.5 text-center";
                sectionYape.classList.remove('hidden');
                sectionCash.classList.add('hidden');
                document.querySelector('input[name="payment_method"][value="yape"]').checked = true;
            } else {
                labelCash.className = "cursor-pointer border-2 border-emerald-500 bg-emerald-950/30 rounded-xl p-3 flex flex-col items-center gap-1.5 text-center";
                labelYape.className = "cursor-pointer border border-neutral-700 rounded-xl p-3 flex flex-col items-center gap-1.5 text-center";
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
                            status.innerHTML += `<br><span class="text-emerald-400">Dirección aproximada cargada. (Completa el número o lote si falta).</span>`;
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
                status.innerHTML = `Estás a <strong>${distanceKm.toFixed(1)} km</strong>. <span class="text-emerald-400 font-bold">¡Delivery GRATIS!</span>`;
            } else {
                const extraKm = distanceKm - FREE_KM;
                currentDeliveryFee = Math.ceil(extraKm) * EXTRA_KM_FEE;
                status.innerHTML = `Estás a <strong>${distanceKm.toFixed(1)} km</strong>. Tarifa de delivery: <span class="text-amber-400 font-bold">S/ ${currentDeliveryFee.toFixed(2)}</span>`;
            }

            feeDisplay.textContent = currentDeliveryFee.toFixed(2);
            feeInput.value = currentDeliveryFee.toFixed(2);
            totalDisplay.textContent = (subtotal + currentDeliveryFee).toFixed(2);
        }
    </script>
</body>
</html>