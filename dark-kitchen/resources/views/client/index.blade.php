<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chifa Mi Hogar | Dark Kitchen</title>
    <!-- Favicon Chifa Mi Hogar -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF5EA] text-stone-800 min-h-screen pb-32 font-sans antialiased">
@if(!$storeOpen)
    <!-- BANNER Y BLOQUEO DE LOCAL CERRADO -->
    <div class="fixed inset-0 z-[60] bg-stone-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-[#FFFDF8] ring-1 ring-stone-900/5 max-w-sm w-full rounded-3xl shadow-2xl overflow-hidden text-center">
            <div class="h-2 bg-gradient-to-r from-red-800 via-red-600 to-red-800"></div>

            <div class="p-6 space-y-4">
                <div class="w-16 h-16 bg-red-50 text-red-600 border border-red-200 rounded-2xl flex items-center justify-center text-3xl mx-auto">
                    🔒
                </div>

                <div>
                    <h3 class="font-['Playfair_Display',serif] text-xl font-extrabold text-stone-900">Fuera de servicio por hoy</h3>
                    <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                        Nuestra cocina ya cerró la atención de pedidos por el día de hoy. ¡Gracias por preferirnos!
                    </p>
                </div>

                <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-stone-600">
                    🍜 <span class="font-bold text-red-700">¿Quieres saber cuándo abrimos?</span><br>
                    Únete a nuestra comunidad para ver el menú diario y ofertas exclusivas.
                </div>

                <a href="{{ $whatsappCommunityUrl }}" target="_blank"
                   class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-black py-3.5 px-4 rounded-xl shadow-lg text-sm transition active:scale-95 flex items-center justify-center gap-2">
                    <span>💬</span> Unirme a la Comunidad de WhatsApp
                </a>

                <p class="text-[11px] text-stone-400">
                    Atención en San Juan de Lurigancho
                </p>
            </div>
        </div>
    </div>
@endif
    <!-- Header / Banner Chifa -->
    <header class="relative bg-gradient-to-b from-red-800 via-red-700 to-red-700 pt-10 pb-16 px-4 text-center rounded-b-[2.5rem] shadow-xl overflow-hidden">
        <!-- Patrón decorativo -->
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0); background-size: 18px 18px;"></div>
        <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-amber-400/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-16 w-56 h-56 rounded-full bg-black/10 blur-2xl pointer-events-none"></div>

        <div class="relative max-w-xl mx-auto flex flex-col items-center">
            <!-- Logo Icon -->
            <!-- Logo -->
            <div class="w-24 h-24 rounded-full bg-[#FFFDF8] border-4 border-amber-400 flex items-center justify-center shadow-2xl mb-4 ring-8 ring-white/10 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Chifa Mi Hogar" class="w-full h-full object-contain p-1.5">
            </div>

            <h1 class="font-['Playfair_Display',serif] text-4xl font-extrabold tracking-tight text-white drop-shadow">
                Chifa <span class="text-amber-300">Mi Hogar</span>
            </h1>

            <div class="flex items-center gap-3 mt-2">
                <span class="h-px w-10 bg-amber-300/60"></span>
                <p class="text-[11px] uppercase tracking-[0.25em] text-red-100 font-semibold">
                    Sabor tradicional al wok
                </p>
                <span class="h-px w-10 bg-amber-300/60"></span>
            </div>

            <!-- Badge Horario -->
            <div class="mt-5 inline-flex items-center gap-2 bg-[#FFFDF8] border border-amber-300 px-4 py-2 rounded-full shadow-lg text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-stone-600">Atención exclusiva:</span>
                <span class="text-red-700 font-bold">Sábados y Domingos</span>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4">
        <!-- Franja de beneficios -->
        <div class="relative z-10 -mt-9 grid grid-cols-3 gap-2 bg-[#FFFDF8] rounded-2xl shadow-lg shadow-stone-300/50 ring-1 ring-stone-900/5 p-3 text-center">
            <div class="px-1">
                <div class="text-xl">🔥</div>
                <p class="text-[11px] font-bold text-stone-800 mt-0.5">Wok al momento</p>
                <p class="text-[10px] text-stone-500">Siempre fresco</p>
            </div>
            <div class="px-1 border-x border-stone-200">
                <div class="text-xl">🛵</div>
                <p class="text-[11px] font-bold text-stone-800 mt-0.5">Delivery</p>
                <p class="text-[10px] text-stone-500">A tu puerta</p>
            </div>
            <div class="px-1">
                <div class="text-xl">📱</div>
                <p class="text-[11px] font-bold text-stone-800 mt-0.5">Yape / Plin</p>
                <p class="text-[10px] text-stone-500">Sin comisiones</p>
            </div>
        </div>

        <!-- Título de sección -->
        <div class="mt-8 mb-5 text-center">
            <p class="text-[11px] uppercase tracking-[0.3em] text-amber-700 font-bold">Nuestra</p>
            <h2 class="font-['Playfair_Display',serif] text-2xl font-bold text-red-800 flex items-center justify-center gap-2">
                <span>🥢</span> Carta
            </h2>
            <div class="flex items-center justify-center gap-2 mt-2">
                <span class="h-px w-12 bg-stone-300"></span>
                <span class="w-1.5 h-1.5 rotate-45 bg-red-700"></span>
                <span class="h-px w-12 bg-stone-300"></span>
            </div>
            <p class="text-xs text-stone-500 mt-2">Precios netos sin comisiones</p>
        </div>

        <!-- Grid de Platos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @forelse($products as $product)
                <a href="{{ route('product.show', $product->slug) }}"
                   class="group bg-[#FFFDF8] ring-1 ring-stone-900/5 rounded-3xl p-2.5 shadow-md shadow-stone-300/40 hover:shadow-xl hover:shadow-stone-300/60 hover:-translate-y-0.5 transition duration-300 flex flex-col justify-between">

                    <!-- Imagen del Plato -->
                    <div class="w-full h-44 bg-stone-100 relative overflow-hidden rounded-2xl">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl opacity-40">🍲</div>
                        @endif
                        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/30 to-transparent"></div>
                        <span class="absolute top-2.5 left-2.5 bg-[#FFFDF8]/95 backdrop-blur px-2.5 py-1 rounded-full text-[11px] font-bold text-red-700 shadow-sm">
                            🔥 Wok Fresh
                        </span>
                    </div>

                    <!-- Información -->
                    <div class="px-2 pt-3 pb-1 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="font-['Playfair_Display',serif] text-lg font-bold text-stone-900 group-hover:text-red-700 transition leading-snug">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'Elaborado con vegetales frescos, sillao especial y el punto ahumado del wok.' }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-dashed border-stone-300 flex items-center justify-between">
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-stone-400 font-semibold">Precio</span>
                                <span class="text-xl font-black text-red-700 leading-none">
                                    S/ {{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            <span class="bg-red-700 group-hover:bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-md shadow-red-900/20 transition">
                                Pedir +
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 bg-[#FFFDF8] rounded-3xl ring-1 ring-stone-900/5">
                    <p class="text-stone-500 text-sm">No hay platos disponibles en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Pie -->
        <footer class="mt-10 text-center">
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="h-px w-10 bg-stone-300"></span>
                <span class="text-lg">🥡</span>
                <span class="h-px w-10 bg-stone-300"></span>
            </div>
            <p class="font-['Playfair_Display',serif] text-sm font-bold text-red-800">Chifa Mi Hogar</p>
            <p class="text-[11px] text-stone-500 mt-0.5">Atendemos Sábados y Domingos</p>
        </footer>
    </main>

    <!-- Barra Inferior Flotante del Carrito -->
    <div id="cart-bar" class="fixed bottom-4 left-4 right-4 max-w-lg mx-auto bg-[#FFFDF8]/95 backdrop-blur ring-1 ring-red-200 p-3 rounded-2xl shadow-2xl shadow-stone-500/30 z-50 hidden transition">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-11 h-11 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center text-xl">🛒</div>
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-stone-500 font-semibold">
                        <span id="cart-count">0</span> ítem(s) en tu orden
                    </p>
                    <p class="text-lg font-black text-stone-900 leading-tight">
                        Total: <span class="text-red-700">S/ <span id="cart-total">0.00</span></span>
                    </p>
                </div>
            </div>
            <a href="{{ route('order.checkout') }}" class="bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white font-extrabold px-5 py-3 rounded-xl text-sm shadow-lg shadow-red-900/20 flex items-center gap-2 transition active:scale-95">
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