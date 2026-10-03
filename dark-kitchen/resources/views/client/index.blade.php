<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chifa Mi Hogar | Dark Kitchen</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-900 text-neutral-100 min-h-screen pb-28 font-sans antialiased">

    <!-- Header / Banner Chifa -->
    <header class="bg-gradient-to-b from-red-700 via-red-800 to-neutral-900 pt-8 pb-6 px-4 text-center border-b border-red-900/40 relative shadow-2xl">
        <div class="max-w-xl mx-auto flex flex-col items-center">
            <!-- Logo Icon -->
            <div class="w-24 h-24 rounded-full bg-neutral-950 border-4 border-amber-400 flex items-center justify-center shadow-2xl mb-3 text-4xl ring-4 ring-red-500/20">
                🥡
            </div>
            
            <h1 class="text-3xl font-black tracking-tight text-white uppercase drop-shadow">
                Chifa <span class="text-amber-400">Mi Hogar</span>
            </h1>
            <p class="text-xs uppercase tracking-widest text-red-200 mt-1 font-semibold">
                Sabor Callejero Tradicional al Wok
            </p>

            <!-- Badge Horario -->
            <div class="mt-4 inline-flex items-center gap-2 bg-neutral-950/80 backdrop-blur border border-amber-500/30 px-4 py-1.5 rounded-full shadow-inner text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-neutral-300">Atención exclusiva:</span>
                <span class="text-amber-300 font-bold">Sábados y Domingos</span>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 mt-6">
        <div class="flex items-center justify-between mb-4 border-b border-neutral-800 pb-3">
            <h2 class="text-lg font-black tracking-wide text-amber-400 flex items-center gap-2">
                <span>🥢</span> NUESTRA CARTA
            </h2>
            <span class="text-xs text-neutral-400">Precios netos sin comisiones</span>
        </div>

        <!-- Grid de Platos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($products as $product)
                <a href="{{ route('product.show', $product->slug) }}" 
                   class="group bg-neutral-800/80 hover:bg-neutral-800 border border-neutral-700/60 hover:border-amber-500/50 rounded-2xl overflow-hidden shadow-lg transition duration-200 flex flex-col justify-between">
                    
                    <!-- Imagen del Plato -->
                    <div class="w-full h-44 bg-neutral-950 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl opacity-40">🍲</div>
                        @endif
                        <span class="absolute top-2 right-2 bg-neutral-950/80 backdrop-blur px-2.5 py-1 rounded-full text-xs font-bold text-amber-400 border border-amber-400/20">
                            Wok Fresh
                        </span>
                    </div>

                    <!-- Información -->
                    <div class="p-4 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="text-base font-bold text-white group-hover:text-amber-400 transition leading-snug">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-neutral-400 mt-1 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'Elaborado con vegetales frescos, sillao especial y el punto ahumado del wok.' }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-neutral-700/50 flex items-center justify-between">
                            <span class="text-lg font-black text-amber-400">
                                S/ {{ number_format($product->price, 2) }}
                            </span>
                            <span class="bg-red-600 hover:bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow transition">
                                Pedir +
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 bg-neutral-800/40 rounded-2xl border border-neutral-800">
                    <p class="text-neutral-400 text-sm">No hay platos disponibles en este momento.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Barra Inferior Flotante del Carrito -->
    <div id="cart-bar" class="fixed bottom-4 left-4 right-4 max-w-lg mx-auto bg-neutral-900/95 backdrop-blur border border-amber-500/40 p-3.5 rounded-2xl shadow-2xl z-50 hidden transition">
        <div class="flex items-center justify-between">
            <div class="pl-2">
                <p class="text-[11px] uppercase tracking-wider text-neutral-400 font-semibold">
                    <span id="cart-count">0</span> ítem(s) en tu orden
                </p>
                <p class="text-lg font-black text-white">
                    Total: <span class="text-amber-400">S/ <span id="cart-total">0.00</span></span>
                </p>
            </div>
            <a href="{{ route('order.checkout') }}" class="bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm shadow-lg flex items-center gap-2 transition active:scale-95">
                Ver Carrito ➔
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('dk_cart') || '[]');
            if (cart.length > 0) {
                let total = 0;
                let count = 0;
                cart.forEach(item => {
                    total += parseFloat(item.total_item_price);
                    count += parseInt(item.quantity);
                });
                document.getElementById('cart-count').textContent = count;
                document.getElementById('cart-total').textContent = total.toFixed(2);
                document.getElementById('cart-bar').classList.remove('hidden');
            }
        });
    </script>
</body>
</html> 