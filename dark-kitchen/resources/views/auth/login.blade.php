<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <!-- Tarjeta con borde y fondo definido -->
        <div class="bg-[#18181b] border border-stone-800 rounded-3xl p-8 shadow-2xl space-y-6">
            
            <!-- Logo y Encabezado -->
            <div class="text-center space-y-3">
                <div class="inline-flex p-3 rounded-2xl bg-[#09090b] border border-stone-800 shadow-inner">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo Chifa Mi Hogar" 
                         class="h-14 w-14 object-contain">
                </div>
                
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-white">
                        Chifa <span class="text-red-500">Mi Hogar</span>
                    </h1>
                    <p class="text-xs text-stone-400 font-medium mt-1">
                        Acceso al Panel Administrativo
                    </p>
                </div>
            </div>

            <!-- Alertas de estado -->
            @if (session('status'))
                <div class="p-3 rounded-xl bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-xs text-center font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Campo Correo -->
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-300 mb-1.5 uppercase tracking-wider">
                        Correo Electrónico
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="admin@chifamihogar.com"
                           class="w-full px-4 py-3 bg-[#09090b] border border-stone-700 focus:border-red-500 rounded-xl text-sm text-white placeholder-stone-600 focus:ring-1 focus:ring-red-500 outline-none transition">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-stone-300 uppercase tracking-wider">
                            Contraseña
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-stone-400 hover:text-red-400 transition" href="{{ route('password.request') }}">
                                ¿Olvidaste tu clave?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full px-4 py-3 bg-[#09090b] border border-stone-700 focus:border-red-500 rounded-xl text-sm text-white placeholder-stone-600 focus:ring-1 focus:ring-red-500 outline-none transition">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recordar sesión -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember" 
                               class="rounded bg-[#09090b] border-stone-700 text-red-600 focus:ring-red-500 focus:ring-offset-stone-900 w-4 h-4 cursor-pointer">
                        <span class="ms-2 text-xs text-stone-400">Recordar sesión</span>
                    </label>
                </div>

                <!-- Botón Ingresar -->
                <button type="submit" 
                        class="w-full mt-2 py-3.5 px-4 bg-red-600 hover:bg-red-500 active:scale-[0.98] text-white font-extrabold text-sm rounded-xl shadow-lg shadow-red-900/40 transition duration-150 flex items-center justify-center gap-2 cursor-pointer">
                    <span>Ingresar al Sistema</span>
                    <span class="text-base font-bold">→</span>
                </button>
            </form>

            <!-- Pie de tarjeta -->
            <div class="pt-4 border-t border-stone-800 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs text-stone-400 hover:text-white transition">
                    <span>←</span> Volver a la Carta del Menú
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] text-stone-600 mt-6 font-medium">
            &copy; {{ date('Y') }} Chifa Mi Hogar &middot; San Juan de Lurigancho
        </p>
    </div>
</x-guest-layout>