<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chifa Mi Hogar | Dark Kitchen</title>
    <!-- Favicon Chifa Mi Hogar -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800,900|playfair-display:600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        html { scroll-behavior: smooth; }
    </style>
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

    <!-- Franja de beneficios -->
    <div class="max-w-4xl mx-auto px-4">
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
    </div>

    <!-- 1. CARRUSEL: Los Más Vendidos (Automático) -->
    @if($bestSellers->count() > 0)
    <section class="mt-8 mb-8 px-4 max-w-4xl mx-auto">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xl">🔥</span>
            <h2 class="font-['Playfair_Display',serif] font-black text-xl text-stone-900">Los Más Pedidos</h2>
        </div>

        <!-- Carrusel Horizontal con scroll suave -->
        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar snap-x snap-mandatory">
            @foreach($bestSellers as $item)
                <div class="snap-start shrink-0 w-64 bg-[#FFFDF8] rounded-3xl p-3 shadow-md shadow-stone-300/40 ring-1 ring-stone-900/5 flex flex-col justify-between">
                    <div>
                        <div class="relative">
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/default-plate.jpg') }}" alt="{{ $item->name }}" class="w-full h-32 object-cover rounded-2xl">
                            <span class="absolute top-2 left-2 bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-sm">
                                Top Ventas 🔥
                            </span>
                        </div>
                        <h3 class="font-['Playfair_Display',serif] font-bold text-base text-stone-900 leading-snug mt-3 px-1">{{ $item->name }}</h3>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 px-1 border-t border-dashed border-stone-300">
                        <span class="text-red-700 font-black text-lg">S/ {{ number_format($item->price, 2) }}</span>
                        <a href="{{ route('product.show', $item->slug) }}" class="bg-red-700 hover:bg-red-600 text-white font-bold text-xs px-4 py-2 rounded-full shadow-md shadow-red-900/20 active:scale-95 transition">
                            + Agregar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 2. PLATOS ESTRELLA (Manuales) -->
    @if($featuredProducts->count() > 0)
    <section class="mb-8 px-4 max-w-4xl mx-auto @if($bestSellers->count() == 0) mt-8 @endif">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xl">⭐</span>
            <h2 class="font-['Playfair_Display',serif] font-black text-xl text-stone-900">Especialidades de la Casa</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($featuredProducts as $item)
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 ring-1 ring-amber-200 rounded-3xl p-3 shadow-md shadow-amber-200/40 relative overflow-hidden flex flex-col justify-between">
                    <span class="absolute top-5 right-5 z-10 bg-amber-500 text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow">
                        ESTRELLA ⭐
                    </span>
                    <div>
                        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/default-plate.jpg') }}" alt="{{ $item->name }}" class="w-full h-36 object-cover rounded-2xl">
                        <div class="px-1 mt-3">
                            <h3 class="font-['Playfair_Display',serif] font-bold text-base text-stone-900">{{ $item->name }}</h3>
                            <p class="text-xs text-stone-600 mt-1 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4 px-1 pb-1">
                        <span class="text-red-700 font-black text-lg">S/ {{ number_format($item->price, 2) }}</span>
                        <a href="{{ route('product.show', $item->slug) }}" class="bg-red-800 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-full shadow-md active:scale-95 transition">
                            Pedir
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Título de sección -->
    <div class="text-center mb-4 px-4 @if($bestSellers->count() == 0 && $featuredProducts->count() == 0) mt-8 @endif">
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

    <!-- 3. BARRA FLOTANTE DE FILTROS POR CATEGORÍA -->
    <div class="sticky top-0 z-20 bg-[#FAF5EA]/95 backdrop-blur py-3 px-4 border-b border-stone-200 mb-6 shadow-sm">
        <div class="flex gap-2 overflow-x-auto no-scrollbar max-w-4xl mx-auto">
            <button onclick="filterCategory('all', this)" class="cat-pill bg-red-700 text-white border border-red-700 text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap shadow-sm transition">
                Todos
            </button>
            @foreach($categories as $category)
                <button onclick="filterCategory('cat-{{ $category->id }}', this)" class="cat-pill bg-white text-stone-700 border border-stone-300 text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap hover:border-red-500 transition">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- 4. LISTADO DE PRODUCTOS INDEXADO POR CATEGORÍA -->
    <div class="max-w-4xl mx-auto px-4 space-y-8">
        @forelse($categories as $category)
            @if($category->products->count() > 0)
                <div id="cat-{{ $category->id }}" class="category-group scroll-mt-20">
                    <h3 class="font-['Playfair_Display',serif] text-xl font-black text-red-900 mb-4 pb-2 border-b border-stone-300 flex items-center justify-between">
                        <span>🥢 {{ $category->name }}</span>
                        <span class="text-xs text-stone-400 font-sans font-normal">{{ $category->products->count() }} platos</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->products as $product)
                            <!-- Card normal de plato -->
                            <div class="bg-[#FFFDF8] rounded-2xl p-4 ring-1 ring-stone-900/5 shadow-md shadow-stone-300/30 hover:shadow-lg transition flex gap-3 items-center justify-between">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-stone-900 text-sm">{{ $product->name }}</h4>
                                    <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    <p class="text-red-700 font-black text-base pt-1">S/ {{ number_format($product->price, 2) }}</p>
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="shrink-0 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-black w-10 h-10 rounded-xl flex items-center justify-center text-xl active:scale-90 transition">
                                    +
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-16 bg-[#FFFDF8] rounded-3xl ring-1 ring-stone-900/5">
                <p class="text-stone-500 text-sm">No hay platos disponibles en este momento.</p>
            </div>
        @endforelse
    </div>

    <!-- Pie -->
    <footer class="mt-12 text-center px-4">
        <div class="flex items-center justify-center gap-2 mb-2">
            <span class="h-px w-10 bg-stone-300"></span>
            <span class="text-lg">🥡</span>
            <span class="h-px w-10 bg-stone-300"></span>
        </div>
        <p class="font-['Playfair_Display',serif] text-sm font-bold text-red-800">Chifa Mi Hogar</p>
        <p class="text-[11px] text-stone-500 mt-0.5">Atendemos Sábados y Domingos</p>
    </footer>

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

        function filterCategory(catId, btn) {
            const groups = document.querySelectorAll('.category-group');

            // Estilo de la píldora activa
            document.querySelectorAll('.cat-pill').forEach(p => {
                p.classList.remove('bg-red-700', 'text-white', 'border-red-700');
                p.classList.add('bg-white', 'text-stone-700', 'border-stone-300');
            });
            if (btn) {
                btn.classList.remove('bg-white', 'text-stone-700', 'border-stone-300');
                btn.classList.add('bg-red-700', 'text-white', 'border-red-700');
            }

            if (catId === 'all') {
                groups.forEach(g => g.classList.remove('hidden'));
            } else {
                groups.forEach(g => {
                    if (g.id === catId) {
                        g.classList.remove('hidden');
                        // Scroll suave hasta la categoría
                        g.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        g.classList.add('hidden');
                    }
                });
            }
        }
    </script>
</body>
</html>