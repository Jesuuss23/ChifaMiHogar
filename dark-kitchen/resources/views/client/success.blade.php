<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pedido Recibido! - Chifa Mi Hogar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-900 text-neutral-100 min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <div class="bg-neutral-800/90 border border-neutral-700/80 rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center backdrop-blur-md relative overflow-hidden">
        <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full flex items-center justify-center mx-auto text-3xl mb-4 shadow-inner">
            ✓
        </div>

        <h1 class="text-2xl font-black text-white uppercase tracking-tight mb-1">
            ¡Pedido <span class="text-amber-400">Recibido!</span>
        </h1>
        <p class="text-xs text-neutral-400 mb-5 leading-relaxed">
            Tu pedido ya fue registrado en nuestro sistema.
        </p>

        <!-- Bloque Código -->
        <div class="bg-neutral-950/70 p-4 rounded-2xl border border-amber-500/20 mb-5">
            <span class="text-[10px] text-neutral-400 uppercase tracking-widest block font-bold">Código de Pedido</span>
            <span class="text-3xl font-black text-amber-400 tracking-wider">#{{ $order->order_code }}</span>
            <span class="text-[11px] text-neutral-400 block mt-1">Total: <strong class="text-white">S/ {{ number_format($order->total, 2) }}</strong></span>
        </div>

        <!-- Instrucción -->
        <div class="bg-neutral-900/60 rounded-xl p-3 border border-neutral-700/50 mb-5 text-left text-xs space-y-1">
            <p class="text-neutral-300 font-semibold flex items-center gap-1.5">
                <span class="text-amber-400">⚡</span> Paso final:
            </p>
            <p class="text-neutral-400 text-[11px] leading-relaxed">
                Toca el botón verde para <strong>enviar el detalle a WhatsApp</strong> y comenzar la preparación.
            </p>
        </div>

        <!-- Botón WhatsApp -->
        <button type="button" onclick="sendOrderToWhatsapp()" 
                class="w-full bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-black py-4 px-4 rounded-2xl shadow-xl flex items-center justify-center gap-2 text-sm mb-3 transition duration-200">
            <span class="text-lg">📲</span>
            <span>Enviar Pedido a WhatsApp</span>
        </button>

        <a href="{{ route('home') }}" class="text-xs text-neutral-400 hover:text-amber-400 font-medium inline-block transition mt-1">
            ← Volver a la carta principal
        </a>
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