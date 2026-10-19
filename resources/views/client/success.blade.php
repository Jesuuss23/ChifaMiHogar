<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pedido Recibido! - Chifa Mi Hogar</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF5EA] text-stone-800 min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <div class="max-w-sm w-full">
        <!-- Marca -->
        <div class="text-center mb-5">
            <div class="w-16 h-16 rounded-full bg-[#FFFDF8] border-4 border-amber-400 flex items-center justify-center mx-auto text-3xl shadow-lg ring-4 ring-red-700/10">
                🥡
            </div>
            <p class="font-['Playfair_Display',serif] text-lg font-extrabold text-red-800 mt-2">Chifa <span class="text-amber-600">Mi Hogar</span></p>
        </div>

        <!-- Tarjeta tipo ticket -->
        <div class="bg-[#FFFDF8] ring-1 ring-stone-900/5 rounded-3xl shadow-2xl shadow-stone-400/40 text-center relative overflow-hidden">
            <!-- Franja superior -->
            <div class="h-2 bg-gradient-to-r from-red-800 via-red-600 to-red-800"></div>

            <div class="p-6 pb-5">
                <div class="w-16 h-16 bg-emerald-50 border-2 border-emerald-300 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl mb-4 shadow-inner">
                    ✓
                </div>

                <h1 class="font-['Playfair_Display',serif] text-2xl font-extrabold text-stone-900 tracking-tight mb-1">
                    ¡Pedido <span class="text-red-700">Recibido!</span>
                </h1>
                <p class="text-xs text-stone-500 leading-relaxed">
                    Tu pedido ya fue registrado en nuestro sistema.
                </p>
            </div>

            <!-- Separador tipo ticket -->
            <div class="relative">
                <div class="border-t-2 border-dashed border-stone-300"></div>
                <span class="absolute -left-3 -top-3 w-6 h-6 rounded-full bg-[#FAF5EA]"></span>
                <span class="absolute -right-3 -top-3 w-6 h-6 rounded-full bg-[#FAF5EA]"></span>
            </div>

            <div class="p-6 pt-5">
                <!-- Bloque Código -->
                <div class="bg-red-50/70 p-4 rounded-2xl border border-red-200 mb-5">
                    <span class="text-[10px] text-stone-500 uppercase tracking-[0.25em] block font-bold">Código de Pedido</span>
                    <span class="text-3xl font-black text-red-700 tracking-wider">#{{ $order->order_code }}</span>
                    <span class="text-[11px] text-stone-500 block mt-1">Total: <strong class="text-stone-900">S/ {{ number_format($order->total, 2) }}</strong></span>
                </div>

                <!-- Pasos -->
                <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200 mb-5 text-left space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500 text-white text-xs font-black flex items-center justify-center shrink-0">✓</span>
                        <p class="text-xs text-stone-600 pt-1">Pedido registrado</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-red-700 text-white text-xs font-black flex items-center justify-center shrink-0">2</span>
                        <div>
                            <p class="text-xs text-stone-800 font-bold flex items-center gap-1.5 pt-1"><span class="text-red-700">⚡</span> Paso final:</p>
                            <p class="text-stone-500 text-[11px] leading-relaxed mt-0.5">
                                Toca el botón verde para <strong class="text-stone-700">enviar el detalle a WhatsApp</strong> y comenzar la preparación.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 opacity-60">
                        <span class="w-6 h-6 rounded-full bg-stone-300 text-white text-xs font-black flex items-center justify-center shrink-0">3</span>
                        <p class="text-xs text-stone-600 pt-1">Preparamos y enviamos tu pedido 🛵</p>
                    </div>
                </div>

                <!-- Botón WhatsApp -->
                <button type="button" onclick="sendOrderToWhatsapp()"
                        class="w-full bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-black py-4 px-4 rounded-2xl shadow-lg shadow-emerald-900/25 flex items-center justify-center gap-2 text-sm mb-3 transition duration-200">
                    <span class="text-lg">📲</span>
                    <span>Enviar Pedido a WhatsApp</span>
                </button>

                <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-red-700 font-medium inline-block transition mt-1">
                    ← Volver a la carta principal
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] text-stone-500 mt-4">Gracias por tu preferencia 🥢</p>
    </div>

<script>
        const orderData = @json($order);
        const whatsappPhone = "{{ $whatsappNumber }}";

        function sendOrderToWhatsapp() {
            const lines = [
                `*CHIFA MI HOGAR - PEDIDO #${orderData.order_code}*`,
                "------------------------------------------",
                `*Cliente:* ${orderData.customer_name}`,
                `*Telefono:* ${orderData.customer_phone}`,
                `*Direccion:* ${orderData.delivery_address}`
            ];

            if (orderData.reference) {
                lines.push(`*Referencia:* ${orderData.reference}`);
            }

            if (orderData.notes) {
                lines.push(`*Nota cocina:* ${orderData.notes}`);
            }

            lines.push("------------------------------------------");
            lines.push("*DETALLE DEL PEDIDO:*");

            if (orderData.items && orderData.items.length > 0) {
                orderData.items.forEach(item => {
                    let dishLine = `- ${item.quantity}x ${item.product ? item.product.name : 'Plato'} (S/ ${parseFloat(item.subtotal).toFixed(2)})`;

                    if (item.selected_addons && item.selected_addons.length > 0) {
                        const addonNames = item.selected_addons.map(a => a.name).join(', ');
                        dishLine += ` [${addonNames}]`;
                    }
                    lines.push(dishLine);
                });
            }

            lines.push("------------------------------------------");
            lines.push(`*Delivery:* S/ ${parseFloat(orderData.delivery_fee).toFixed(2)}`);
            lines.push(`*TOTAL A PAGAR:* S/ ${parseFloat(orderData.total).toFixed(2)}`);

            if (orderData.payment_method === 'cash') {
                lines.push(`*Forma de pago:* Efectivo contra entrega (${orderData.cash_amount || 'Monto exacto'})`);
            } else {
                lines.push("*Forma de pago:* Yape / Plin");
                lines.push("_(Adjunto la captura del Yape a continuacion 👇)_");
            }
            lines.push("------------------------------------------");

            const message = lines.join("\n");
            const url = `https://wa.me/${whatsappPhone}?text=${encodeURIComponent(message)}`;

            window.open(url, '_blank');
        }
    </script>
</body>
</html>