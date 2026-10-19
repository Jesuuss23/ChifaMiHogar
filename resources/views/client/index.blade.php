<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chifa Mi Hogar | Dark Kitchen</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v=2" type="image/x-icon">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800,900|playfair-display:600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        html { scroll-behavior: smooth; }

        /* Celular: tarjeta al 78vw con asomo lateral */
        .bestseller-card, .featured-card {
            width: 78vw;
            max-width: 320px;
        }

        /* En PC / Escritorio (>= 768px): alineado exactamente con la carta (max-w-4xl) */
        @media (min-width: 768px) {
            .bestsellers-track, .featured-track {
                overflow-x: hidden !important;
                scroll-snap-type: none !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .bestseller-card, .featured-card {
                flex: 1 1 0px !important;
                width: auto !important;
                max-width: none !important;
            }
        }
    </style>
</head>

<body class="bg-[#FAF5EA] text-stone-800 min-h-screen pb-32 font-sans antialiased">

    {{-- ============================================================
         BLOQUEO: LOCAL CERRADO
         ============================================================ --}}
    @if(!$storeOpen)
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

                    <a href="{{ $whatsappCommunityUrl ?? '#' }}" target="_blank"
                       class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-black py-3.5 px-4 rounded-xl shadow-lg text-sm transition active:scale-95 flex items-center justify-center gap-2">
                        <span>💬</span> Unirme a la Comunidad de WhatsApp
                    </a>

                    <p class="text-[11px] text-stone-400">Atención en San Juan de Lurigancho</p>
                </div>
            </div>
        </div>
    @endif


    {{-- ============================================================
         ENCABEZADO
         ============================================================ --}}
    <header class="relative bg-gradient-to-b from-red-800 via-red-700 to-red-700 pt-10 pb-16 px-4 text-center rounded-b-[2.5rem] shadow-xl overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0); background-size: 18px 18px;"></div>
        <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-amber-400/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-16 w-56 h-56 rounded-full bg-black/10 blur-2xl pointer-events-none"></div>

        <div class="relative max-w-xl mx-auto flex flex-col items-center">
            <!-- Logo -->
            <div class="w-24 h-24 rounded-full bg-[#FFFDF8] border-4 border-amber-400 flex items-center justify-center shadow-2xl mb-4 ring-8 ring-white/10 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Chifa Mi Hogar" class="w-full h-full object-contain p-1.5">
            </div>

            <!-- Nombre y lema -->
            <h1 class="font-['Playfair_Display',serif] text-4xl font-extrabold tracking-tight text-white drop-shadow">
                Chifa <span class="text-amber-300">Mi Hogar</span>
            </h1>

            <div class="flex items-center gap-3 mt-2">
                <span class="h-px w-10 bg-amber-300/60"></span>
                <p class="text-[11px] uppercase tracking-[0.25em] text-red-100 font-semibold">Sabor tradicional al wok</p>
                <span class="h-px w-10 bg-amber-300/60"></span>
            </div>

            <!-- Horario -->
            <div class="mt-5 inline-flex items-center gap-2 bg-[#FFFDF8] border border-amber-300 px-4 py-2 rounded-full shadow-lg text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-stone-600">Atención exclusiva:</span>
                <span class="text-red-700 font-bold">Sábados y Domingos</span>
            </div>
        </div>
    </header>


    {{-- ============================================================
         FRANJA DE BENEFICIOS
         ============================================================ --}}
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


    {{-- ============================================================
         DESTACADOS (Los Más Pedidos + Especialidades + Título Carta)
         ============================================================ --}}
    <div class="pt-8">

        {{-- 1. Carrusel: Los más pedidos --}}
        @if(isset($bestSellers) && $bestSellers->count() > 0)
            <section class="max-w-4xl mx-auto px-4 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xl">🔥</span>
                    <h2 class="text-lg md:text-xl font-black text-stone-900 tracking-tight">Los Más Pedidos</h2>
                </div>

                <div id="bestsellers-carousel"
                     class="bestsellers-track no-scrollbar"
                     style="display: flex; gap: 14px; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding: 4px 10px 14px 10px;">
                    
                    @foreach($bestSellers->take(3) as $index => $product)
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="bestseller-card block bg-white rounded-2xl overflow-hidden border border-stone-200 shadow-sm hover:shadow-md transition duration-200 hover:-translate-y-0.5"
                           style="scroll-snap-align: center; flex-shrink: 0; text-decoration: none; color: inherit;">
                            
                            <div style="height: 155px; width: 100%; position: relative; overflow: hidden; background-color: #f5f5f4;">
                                <span class="absolute z-10 bg-amber-500 text-white font-black uppercase shadow-md flex items-center gap-1"
                                      style="top: 10px; left: 10px; font-size: 10px; padding: 3px 8px; border-radius: 9999px; letter-spacing: 0.5px;">
                                    TOP #{{ $index + 1 }} 🔥
                                </span>

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400 text-xs">Sin fotografía</div>
                                @endif
                            </div>

                            <div class="p-3.5 flex flex-col justify-between" style="min-height: 110px;">
                                <div>
                                    <h3 class="font-bold text-stone-900 text-sm leading-snug line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-[11px] text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $product->description ?? 'El auténtico sabor chifa al wok con ingredientes frescos.' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-2 border-t border-stone-100">
                                    <span class="text-base font-black text-stone-900">S/ {{ number_format($product->price, 2) }}</span>
                                    <span class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition active:scale-95">
                                        Pedir →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 2. Especialidades de la casa --}}
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <section class="max-w-4xl mx-auto px-4 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xl">⭐</span>
                    <h2 class="text-lg md:text-xl font-black text-stone-900 tracking-tight">Especialidades de la Casa</h2>
                </div>

                <div id="featured-carousel"
                     class="featured-track no-scrollbar"
                     style="display: flex; gap: 14px; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding: 4px 10px 14px 10px;">
                    
                    @foreach($featuredProducts->take(3) as $index => $product)
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="featured-card block bg-white rounded-2xl overflow-hidden border border-stone-200 shadow-sm hover:shadow-md transition duration-200 hover:-translate-y-0.5"
                           style="scroll-snap-align: center; flex-shrink: 0; text-decoration: none; color: inherit;">
                            
                            <div style="height: 155px; width: 100%; position: relative; overflow: hidden; background-color: #f5f5f4;">
                                <span class="absolute z-10 bg-amber-500 text-white font-black uppercase shadow-md flex items-center gap-1"
                                      style="top: 10px; left: 10px; font-size: 10px; padding: 3px 8px; border-radius: 9999px; letter-spacing: 0.5px;">
                                    ESTRELLA ⭐
                                </span>

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400 text-xs">Sin fotografía</div>
                                @endif
                            </div>

                            <div class="p-3.5 flex flex-col justify-between" style="min-height: 110px;">
                                <div>
                                    <h3 class="font-bold text-stone-900 text-sm leading-snug line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-[11px] text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $product->description ?? 'El auténtico sabor tradicional preparado con la receta especial de la casa.' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-2 border-t border-stone-100">
                                    <span class="text-base font-black text-stone-900">S/ {{ number_format($product->price, 2) }}</span>
                                    <span class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition active:scale-95">
                                        Pedir →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Título de la carta --}}
        <div class="text-center mt-6 mb-4 px-4">
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
    </div>


    {{-- ============================================================
         3. FILTROS POR CATEGORÍA (barra fija)
         ============================================================ --}}
    <div class="sticky top-0 z-20 bg-[#FAF5EA]/95 backdrop-blur py-3 px-4 border-b border-stone-200 mb-6 shadow-sm">
        <div class="flex gap-2 overflow-x-auto no-scrollbar max-w-4xl mx-auto">
            <button onclick="filterCategory('all', this)"
                    class="cat-pill bg-red-700 text-white border border-red-700 text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap shadow-sm transition">
                Todos
            </button>

            @foreach($categories as $category)
                <button onclick="filterCategory('cat-{{ $category->id }}', this)"
                        class="cat-pill bg-white text-stone-700 border border-stone-300 text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap hover:border-red-500 transition">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>


    {{-- ============================================================
         4. LISTADO DE PLATOS POR CATEGORÍA
         ============================================================ --}}
    <main class="max-w-4xl mx-auto px-4 space-y-8">
        @forelse($categories as $category)
            @if($category->products->count() > 0)
                <div id="cat-{{ $category->id }}" class="category-group scroll-mt-20">
                    <h3 class="font-['Playfair_Display',serif] text-xl font-black text-red-900 mb-4 pb-2 border-b border-stone-300 flex items-center justify-between">
                        <span>🥢 {{ $category->name }}</span>
                        <span class="text-xs text-stone-400 font-sans font-normal">
                            {{ $category->products->count() }} {{ $category->products->count() == 1 ? 'plato' : 'platos' }}
                        </span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->products as $product)
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="group bg-[#FFFDF8] rounded-2xl p-3 ring-1 ring-stone-900/5 shadow-md shadow-stone-300/30 hover:shadow-lg hover:-translate-y-0.5 transition flex gap-3 items-center">

                                <!-- Foto del plato -->
                                <div class="shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-stone-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-3xl opacity-40">🍲</div>
                                    @endif
                                </div>

                                <!-- Información -->
                                <div class="flex-1 min-w-0 space-y-1">
                                    <h4 class="font-bold text-stone-900 text-sm group-hover:text-red-700 transition">{{ $product->name }}</h4>
                                    <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                    <p class="text-red-700 font-black text-base pt-1">S/ {{ number_format($product->price, 2) }}</p>
                                </div>

                                <!-- Botón -->
                                <span class="shrink-0 bg-red-50 group-hover:bg-red-100 text-red-700 border border-red-200 font-black w-10 h-10 rounded-xl flex items-center justify-center text-xl transition">
                                    +
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-16 bg-[#FFFDF8] rounded-3xl ring-1 ring-stone-900/5">
                <p class="text-stone-500 text-sm">No hay platos disponibles en este momento.</p>
            </div>
        @endforelse
    </main>


    {{-- ============================================================
         PIE DE PÁGINA
         ============================================================ --}}
    <footer class="mt-12 text-center px-4">
        <div class="flex items-center justify-center gap-2 mb-2">
            <span class="h-px w-10 bg-stone-300"></span>
            <span class="text-lg">🥡</span>
            <span class="h-px w-10 bg-stone-300"></span>
        </div>
        <p class="font-['Playfair_Display',serif] text-sm font-bold text-red-800">Chifa Mi Hogar</p>
        <p class="text-[11px] text-stone-500 mt-0.5">Atendemos Sábados y Domingos</p>
    </footer>


    {{-- ============================================================
         BARRA FLOTANTE DEL CARRITO
         ============================================================ --}}
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

            <a href="{{ route('order.checkout') }}"
               class="bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white font-extrabold px-5 py-3 rounded-xl text-sm shadow-lg shadow-red-900/20 flex items-center gap-2 transition active:scale-95">
                Ver Carrito ➔
            </a>
        </div>
    </div>


    {{-- ============================================================
         SCRIPTS (Carruseles automáticos + Carrito + Filtros)
         ============================================================ --}}
    <script>
        // --- Carruseles independientes de 5 segundos para celular ---
        document.addEventListener('DOMContentLoaded', () => {
            function setupCarousel(carouselId, cardClass) {
                const carousel = document.getElementById(carouselId);
                if (!carousel) return;

                let currentIndex = 0;
                const items = carousel.querySelectorAll(cardClass);
                const totalItems = items.length;
                let autoTimer = null;

                function scrollToItem(index) {
                    if (window.innerWidth >= 768 || totalItems <= 1) return;
                    const target = items[index];
                    if (target) {
                        const scrollLeft = target.offsetLeft - (carousel.clientWidth - target.clientWidth) / 2;
                        carousel.scrollTo({
                            left: scrollLeft,
                            behavior: 'smooth'
                        });
                    }
                }

                function startTimer() {
                    stopTimer();
                    autoTimer = setInterval(() => {
                        currentIndex = (currentIndex + 1) % totalItems;
                        scrollToItem(currentIndex);
                    }, 5000);
                }

                function stopTimer() {
                    if (autoTimer) clearInterval(autoTimer);
                }

                startTimer();

                carousel.addEventListener('touchstart', stopTimer, { passive: true });
                carousel.addEventListener('touchend', startTimer, { passive: true });
                carousel.addEventListener('mouseenter', stopTimer);
                carousel.addEventListener('mouseleave', startTimer);
            }

            setupCarousel('bestsellers-carousel', '.bestseller-card');
            setupCarousel('featured-carousel', '.featured-card');
        });

        // --- Carrito: mostrar barra flotante con totales ---
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

        // --- Filtro por categorías ---
        function filterCategory(catId, btn) {
            const groups = document.querySelectorAll('.category-group');

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