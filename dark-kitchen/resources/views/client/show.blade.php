<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Chifa Mi Hogar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-900 text-neutral-100 min-h-screen pb-28 font-sans antialiased">

    <!-- Barra Superior -->
    <div class="bg-neutral-950/80 backdrop-blur border-b border-neutral-800 px-4 py-3 flex items-center justify-between sticky top-0 z-20">
        <a href="{{ route('home') }}" class="text-xs font-bold text-neutral-300 flex items-center gap-1 hover:text-white">
            ← Volver a la Carta
        </a>
        <span class="font-extrabold text-[11px] uppercase tracking-wider text-amber-400">Personalizar Plato</span>
    </div>

    <main class="max-w-lg mx-auto">
        <!-- Foto -->
        <div class="w-full h-60 bg-neutral-950 relative overflow-hidden border-b border-neutral-800">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-5xl opacity-30">🍲</div>
            @endif
        </div>

        <div class="p-4 bg-neutral-800/80 border-b border-neutral-800">
            <h1 class="text-xl font-black text-white">{{ $product->name }}</h1>
            <p class="text-2xl font-black text-amber-400 mt-1">S/ {{ number_format($product->price, 2) }} <span class="text-xs text-neutral-400 font-normal">c/u</span></p>
            <p class="text-xs text-neutral-300 mt-2 leading-relaxed">{{ $product->description ?? 'Elaborado fresco al momento en wok de alta potencia.' }}</p>
        </div>

        <!-- Cantidad de Platos -->
        <div class="p-4 bg-neutral-800/50 border-b border-neutral-800 flex items-center justify-between">
            <div>
                <span class="font-bold text-sm text-white block">¿Cuántos platos deseas?</span>
                <span class="text-[11px] text-neutral-400">Cantidad de {{ $product->name }}</span>
            </div>
            <div class="flex items-center gap-3 bg-neutral-900 border border-neutral-700 p-1.5 rounded-xl">
                <button type="button" id="btn-dish-minus" class="w-8 h-8 rounded-lg bg-neutral-800 font-black text-white hover:bg-neutral-700 flex items-center justify-center transition active:scale-95">-</button>
                <span id="dish-qty" class="font-black text-base w-6 text-center text-amber-400">1</span>
                <button type="button" id="btn-dish-plus" class="w-8 h-8 rounded-lg bg-neutral-800 font-black text-white hover:bg-neutral-700 flex items-center justify-center transition active:scale-95">+</button>
            </div>
        </div>

        <!-- Adicionales Independientes -->
        @if(count($product->addons) > 0)
            <div class="p-4 bg-neutral-800/30">
                <h2 class="font-bold text-sm text-white mb-0.5">Adicionales / Extras</h2>
                <p class="text-xs text-neutral-400 mb-3">Elige cuántas porciones adicionales deseas en total:</p>

                <div class="space-y-2.5">
                    @php foreach($product->addons as$addon): @endphp
                        <div class="flex items-center justify-between p-3 bg-neutral-800 border border-neutral-700/70 rounded-xl">
                            <div>
                                <p class="text-sm font-bold text-white">{{ $addon->name }}</p>
                                <p class="text-xs text-amber-400 font-semibold">+ S/ {{ number_format($addon->price, 2) }} c/u</p>
                            </div>

                            <div class="flex items-center gap-2 bg-neutral-900 border border-neutral-700 p-1 rounded-lg">
                                <button type="button" onclick="changeAddonQty({{ $addon->id }}, -1)" class="w-7 h-7 rounded bg-neutral-800 text-white font-black hover:bg-neutral-700 flex items-center justify-center text-sm">-</button>
                                <span id="addon-qty-{{ $addon->id }}" class="font-bold text-xs w-5 text-center text-white">0</span>
                                <button type="button" onclick="changeAddonQty({{ $addon->id }}, 1)" class="w-7 h-7 rounded bg-neutral-800 text-white font-black hover:bg-neutral-700 flex items-center justify-center text-sm">+</button>
                            </div>
                        </div>
                    @php endforeach; @endphp
                </div>
            </div>
        @endif
    </main>

    <!-- Botón Añadir Fijo -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-neutral-950/95 backdrop-blur border-t border-neutral-800 shadow-2xl z-20">
        <div class="max-w-lg mx-auto">
            <button id="add-to-cart-btn" class="w-full bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-black py-3.5 px-4 rounded-xl shadow-lg flex items-center justify-between transition active:scale-95">
                <span>Agregar a la Orden</span>
                <span class="text-amber-200">S/ <span id="total-display">{{ number_format($product->price, 2) }}</span></span>
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