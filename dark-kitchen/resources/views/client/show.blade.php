<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Chifa Mi Hogar</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF5EA] text-stone-800 min-h-screen pb-32 font-sans antialiased">

    <!-- Barra Superior -->
    <div class="bg-[#FFFDF8]/90 backdrop-blur border-b border-stone-200 px-4 py-3 flex items-center justify-between sticky top-0 z-20 shadow-sm">
        <a href="{{ route('home') }}" class="text-xs font-bold text-stone-600 flex items-center gap-1 hover:text-red-700 transition">
            ← Volver a la Carta
        </a>
        <span class="font-extrabold text-[11px] uppercase tracking-[0.2em] text-red-700">Personalizar Plato</span>
    </div>

    <main class="max-w-lg mx-auto px-4 pt-4">
        <!-- Foto -->
        <div class="relative w-full h-64 bg-stone-100 overflow-hidden rounded-3xl shadow-xl shadow-stone-400/30 ring-1 ring-stone-900/5">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-6xl opacity-30">🍲</div>
            @endif
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/40 to-transparent"></div>
            <span class="absolute top-3 left-3 bg-[#FFFDF8]/95 backdrop-blur px-3 py-1 rounded-full text-[11px] font-bold text-red-700 shadow">
                🔥 Wok Fresh
            </span>
        </div>

        <!-- Detalle del plato -->
        <div class="relative -mt-8 mx-2 p-5 bg-[#FFFDF8] rounded-3xl shadow-lg shadow-stone-300/50 ring-1 ring-stone-900/5">
            <div class="flex items-start justify-between gap-3">
                <h1 class="font-['Playfair_Display',serif] text-2xl font-extrabold text-stone-900 leading-tight">{{ $product->name }}</h1>
                <div class="text-right shrink-0">
                    <p class="text-2xl font-black text-red-700 leading-none">S/ {{ number_format($product->price, 2) }}</p>
                    <span class="text-[11px] text-stone-500">c/u</span>
                </div>
            </div>

            <div class="flex items-center gap-2 my-3">
                <span class="h-px flex-1 bg-stone-200"></span>
                <span class="w-1.5 h-1.5 rotate-45 bg-amber-500"></span>
                <span class="h-px flex-1 bg-stone-200"></span>
            </div>

            <p class="text-sm text-stone-600 leading-relaxed">{{ $product->description ?? 'Elaborado fresco al momento en wok de alta potencia.' }}</p>

            <div class="flex flex-wrap gap-2 mt-4">
                <span class="text-[11px] font-semibold text-stone-600 bg-stone-100 border border-stone-200 px-2.5 py-1 rounded-full">🥢 Preparado al momento</span>
                <span class="text-[11px] font-semibold text-stone-600 bg-stone-100 border border-stone-200 px-2.5 py-1 rounded-full">🛵 Delivery disponible</span>
            </div>
        </div>

        <!-- Cantidad de Platos -->
        <div class="mt-4 p-4 bg-[#FFFDF8] rounded-3xl ring-1 ring-stone-900/5 shadow-md shadow-stone-300/40 flex items-center justify-between">
            <div>
                <span class="font-bold text-sm text-stone-900 block">¿Cuántos platos deseas?</span>
                <span class="text-[11px] text-stone-500">Cantidad de {{ $product->name }}</span>
            </div>
            <div class="flex items-center gap-3 bg-stone-50 border border-stone-300 p-1.5 rounded-2xl">
                <button type="button" id="btn-dish-minus" class="w-9 h-9 rounded-xl bg-white border border-stone-200 font-black text-stone-700 hover:bg-red-50 hover:text-red-700 hover:border-red-200 flex items-center justify-center transition active:scale-95 shadow-sm">-</button>
                <span id="dish-qty" class="font-black text-lg w-6 text-center text-red-700">1</span>
                <button type="button" id="btn-dish-plus" class="w-9 h-9 rounded-xl bg-white border border-stone-200 font-black text-stone-700 hover:bg-red-50 hover:text-red-700 hover:border-red-200 flex items-center justify-center transition active:scale-95 shadow-sm">+</button>
            </div>
        </div>

        <!-- Adicionales Independientes -->
        @if(count($product->addons) > 0)
            <div class="mt-4 p-4 bg-[#FFFDF8] rounded-3xl ring-1 ring-stone-900/5 shadow-md shadow-stone-300/40">
                <h2 class="font-['Playfair_Display',serif] font-bold text-lg text-red-800 mb-0.5">Adicionales / Extras</h2>
                <p class="text-xs text-stone-500 mb-4">Elige cuántas porciones adicionales deseas en total:</p>

                <div class="space-y-2.5">
                    @php foreach($product->addons as$addon): @endphp
                        <div class="flex items-center justify-between p-3 bg-stone-50 border border-stone-200 rounded-2xl">
                            <div>
                                <p class="text-sm font-bold text-stone-900">{{ $addon->name }}</p>
                                <p class="text-xs text-red-700 font-semibold">+ S/ {{ number_format($addon->price, 2) }} c/u</p>
                            </div>

                            <div class="flex items-center gap-2 bg-white border border-stone-300 p-1 rounded-xl">
                                <button type="button" onclick="changeAddonQty({{ $addon->id }}, -1)" class="w-7 h-7 rounded-lg bg-stone-100 text-stone-700 font-black hover:bg-red-50 hover:text-red-700 flex items-center justify-center text-sm transition">-</button>
                                <span id="addon-qty-{{ $addon->id }}" class="font-bold text-xs w-5 text-center text-stone-900">0</span>
                                <button type="button" onclick="changeAddonQty({{ $addon->id }}, 1)" class="w-7 h-7 rounded-lg bg-stone-100 text-stone-700 font-black hover:bg-red-50 hover:text-red-700 flex items-center justify-center text-sm transition">+</button>
                            </div>
                        </div>
                    @php endforeach; @endphp
                </div>
            </div>
        @endif
    </main>

    <!-- Botón Añadir Fijo -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-[#FFFDF8]/95 backdrop-blur border-t border-stone-200 shadow-2xl shadow-stone-500/30 z-20">
        <div class="max-w-lg mx-auto">
            <button id="add-to-cart-btn" class="w-full bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white font-black py-4 px-5 rounded-2xl shadow-lg shadow-red-900/25 flex items-center justify-between transition active:scale-95">
                <span class="flex items-center gap-2"><span>🛒</span> Agregar a la Orden</span>
                <span class="bg-white/15 px-3 py-1 rounded-full text-amber-100">S/ <span id="total-display">{{ number_format($product->price, 2) }}</span></span>
            </button>
        </div>
    </div>

    <script>
        const basePrice = {{ $product->price }};
        const rawAddons = @json($product->addons);
        let dishQuantity = 1;
        const addonsState = {};

        if (Array.isArray(rawAddons)) {
            rawAddons.forEach(addon => {
                addonsState[addon.id] = {
                    name: addon.name,
                    price: parseFloat(addon.price),
                    qty: 0
                };
            });
        }

        function calculateTotal() {
            let dishesTotal = basePrice * dishQuantity;
            let addonsTotal = 0;

            for (const id in addonsState) {
                addonsTotal += addonsState[id].price * addonsState[id].qty;
            }

            const grandTotal = dishesTotal + addonsTotal;
            document.getElementById('total-display').textContent = grandTotal.toFixed(2);
        }

        document.getElementById('btn-dish-plus').addEventListener('click', () => {
            dishQuantity++;
            document.getElementById('dish-qty').textContent = dishQuantity;
            calculateTotal();
        });

        document.getElementById('btn-dish-minus').addEventListener('click', () => {
            if (dishQuantity > 1) {
                dishQuantity--;
                document.getElementById('dish-qty').textContent = dishQuantity;
                calculateTotal();
            }
        });

        function changeAddonQty(addonId, delta) {
            if (!addonsState[addonId]) return;

            const current = addonsState[addonId].qty;
            const updated = current + delta;

            if (updated >= 0 && updated <= 10) {
                addonsState[addonId].qty = updated;
                document.getElementById(`addon-qty-${addonId}`).textContent = updated;
                calculateTotal();
            }
        }

        document.getElementById('add-to-cart-btn').addEventListener('click', () => {
            const selectedAddons = [];
            let addonsSum = 0;

            for (const id in addonsState) {
                if (addonsState[id].qty > 0) {
                    selectedAddons.push({
                        name: `${addonsState[id].qty}x ${addonsState[id].name}`,
                        price: addonsState[id].price * addonsState[id].qty
                    });
                    addonsSum += addonsState[id].price * addonsState[id].qty;
                }
            }

            const itemTotalPrice = (basePrice * dishQuantity) + addonsSum;

            const cartItem = {
                id: {{ $product->id }},
                name: "{{ $product->name }}",
                price: basePrice,
                quantity: dishQuantity,
                addons: selectedAddons,
                total_item_price: itemTotalPrice
            };

            const cart = JSON.parse(localStorage.getItem('dk_cart') || '[]');
            cart.push(cartItem);
            localStorage.setItem('dk_cart', JSON.stringify(cart));

            window.location.href = "{{ route('home') }}";
        });
    </script>
</body>
</html>