<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OrderCheckoutController extends Controller
{
    public function checkout()
    {
        $settings = Setting::all()->pluck('value', 'key');

        $paymentPhone = $settings['payment_phone'] ?? '987654321';
        $paymentName  = $settings['payment_name'] ?? 'Chifa Mi Hogar';
        $yapeQr       = !empty($settings['yape_qr']) ? asset('storage/' . $settings['yape_qr']) : null;
        
        $kitchenLat   = floatval($settings['kitchen_lat'] ?? -12.012500);
        $kitchenLng   = floatval($settings['kitchen_lng'] ?? -77.001200);
        $freeKm       = floatval($settings['free_delivery_km'] ?? 3.0);
        $extraKmFee   = floatval($settings['extra_km_fee'] ?? 2.00);

        // Validaciones para el bloqueo de local cerrado y link de comunidad
        $storeOpen            = ($settings['store_open'] ?? '1') == '1';
        $whatsappCommunityUrl = $settings['whatsapp_community_url'] ?? '#';

        return view('client.checkout', compact(
            'paymentPhone', 'paymentName', 'yapeQr', 
            'kitchenLat', 'kitchenLng', 'freeKm', 'extraKmFee',
            'storeOpen', 'whatsappCommunityUrl'
        ));
    }

    public function process(Request $request)
    {
        $storeOpen = Setting::where('key', 'store_open')->value('value') ?? '1';
        if ($storeOpen != '1') {
            return redirect()->route('home')->with('store_closed', true);
        }

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:20',
            'delivery_address' => 'required|string|max:255',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'reference'        => 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'cart_data'        => 'required|string',
            'payment_method'   => 'required|in:yape,cash',
            'cash_amount'      => 'nullable|string|max:50',
            'delivery_fee'     => 'required|numeric|min:0',
        ]);

        $cart = json_decode($validated['cart_data'], true);

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'El carrito está vacío.']);
        }

        // Subtotal de los productos
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['total_item_price']);
        }

        $deliveryFee = floatval($validated['delivery_fee']);
        $total = $subtotal + $deliveryFee;
        $orderCode = 'DK-' . strtoupper(Str::random(4));

        // 1. Crear el Pedido en Base de Datos
        $order = Order::create([
            'order_code'       => $orderCode,
            'customer_name'    => $validated['customer_name'],
            'customer_phone'   => $validated['customer_phone'],
            'delivery_address' => $validated['delivery_address'],
            'latitude'         => $request->filled('latitude') ? $request->latitude : null,
            'longitude'        => $request->filled('longitude') ? $request->longitude : null,
            'reference'        => $validated['reference'] ?? null,
            'subtotal'         => $subtotal,
            'delivery_fee'     => $deliveryFee,
            'total'            => $total,
            'payment_method'   => $validated['payment_method'],
            'cash_amount'      => $validated['payment_method'] === 'cash' ? ($validated['cash_amount'] ?? 'Monto exacto') : null,
            'payment_status'   => 'pending',
            'order_status'     => 'received',
            'payment_receipt'  => null,
            'notes'            => $validated['notes'] ?? null,
        ]);

        // 2. Guardar los platos del pedido
        $orderItemsText = "";
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'        => $order->id,
                'product_id'      => $item['id'],
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['price'],
                'subtotal'        => $item['total_item_price'],
                'selected_addons' => $item['addons'] ?? [],
            ]);

            $addonsList = "";
            if (!empty($item['addons'])) {
                $names = array_map(fn($a) => $a['name'], $item['addons']);
                $addonsList = " [" . implode(', ', $names) . "]";
            }
            $orderItemsText .= "• {$item['quantity']}x {$item['name']}{$addonsList}\n";
        }

        // 3. Notificación Instantánea a Telegram (Cocina)
        try {
            $token = env('TELEGRAM_BOT_TOKEN') ?? '8869605276:AAGIALhIvze6eoLmRyvmtcIJIcdGb4q5MZY';
            $chatId = env('TELEGRAM_CHAT_ID') ?? '-1004483383054';

            if ($token && $chatId) {
                $paymentInfo = $order->payment_method === 'cash' 
                    ? "💵 Efectivo (Paga con: " . htmlspecialchars($order->cash_amount ?? 'Monto exacto') . ")" 
                    : "📱 Yape / Plin";

                // Armado del mensaje en formato HTML
                $msg = "🚨 <b>¡NUEVO PEDIDO RECIBIDO!</b> 🚨\n\n";
                $msg .= "<b>Código:</b> #" . htmlspecialchars($order->order_code) . "\n";
                $msg .= "<b>Cliente:</b> " . htmlspecialchars($order->customer_name) . "\n";
                $msg .= "<b>Teléfono:</b> " . htmlspecialchars($order->customer_phone) . "\n";
                $msg .= "<b>Dirección:</b> " . htmlspecialchars($order->delivery_address) . "\n";
                
                if ($order->reference) {
                    $msg .= "<b>Referencia:</b> " . htmlspecialchars($order->reference) . "\n";
                }
                if ($order->notes) {
                    $msg .= "<b>Nota de Cocina:</b> <i>" . htmlspecialchars($order->notes) . "</i>\n";
                }

                $msg .= "\n<b>Platos a Preparar:</b>\n" . htmlspecialchars($orderItemsText) . "\n";
                $msg .= "<b>Delivery:</b> S/ " . number_format($order->delivery_fee, 2) . "\n";
                $msg .= "<b>TOTAL:</b> S/ " . number_format($order->total, 2) . "\n";
                $msg .= "<b>Método de Pago:</b> {$paymentInfo}\n";

                // Limpieza de formato para enlace de WhatsApp del cliente
                $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                $waPhone = (str_starts_with($cleanPhone, '51') && strlen($cleanPhone) === 11) ? $cleanPhone : '51' . $cleanPhone;

                $inlineKeyboard = [
                    [
                        ['text' => '📲 Abrir WhatsApp Cliente', 'url' => "https://wa.me/{$waPhone}"]
                    ]
                ];

                if ($order->latitude && $order->longitude) {
                    $inlineKeyboard[] = [
                        ['text' => '🛵 Ver Ruta en Maps (GPS)', 'url' => "https://www.google.com/maps/dir/?api=1&destination={$order->latitude},{$order->longitude}"]
                    ];
                }

                $response = Http::withoutVerifying()
                    ->timeout(6)
                    ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                        'chat_id'      => $chatId,
                        'text'         => $msg,
                        'parse_mode'   => 'HTML',
                        'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard]),
                    ]);

                if (!$response->successful()) {
                    \Log::error("Fallo Telegram API: " . $response->body());
                }
            }
        } catch (\Throwable $e) {
            \Log::error("Error enviando alerta a Telegram: " . $e->getMessage());
        }

        return redirect()->route('order.success', $order->order_code);
    }

    public function success($orderCode)
    {
        $order = Order::with('items.product')->where('order_code', $orderCode)->firstOrFail();
        $whatsappNumber = Setting::where('key', 'whatsapp_number')->value('value') ?? '51987654321';

        return view('client.success', compact('order', 'whatsappNumber'));
    }
}