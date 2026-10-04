<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-stone-800 leading-tight">
                {{ __('Categorías del Menú') }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-lg transition">
                ← Volver a Platos
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-100 text-emerald-800 border-l-4 border-emerald-500 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Formulario Crear Categoría -->
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm h-fit">
                    <h3 class="font-bold text-sm text-stone-900 mb-4 pb-2 border-b border-stone-100">Nueva Categoría</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Nombre</label>
                            <input type="text" name="name" required placeholder="Ej: Chaufas, Aeropuertos"
                                   class="w-full text-xs bg-stone-50 border border-stone-300 rounded-xl px-3 py-2 focus:bg-white focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Orden de visualización</label>
                            <input type="number" name="sort_order" value="1" min="0" required
                                   class="w-full text-xs bg-stone-50 border border-stone-300 rounded-xl px-3 py-2 focus:bg-white focus:ring-red-500 focus:border-red-500">
                            <span class="text-[10px] text-stone-400">Los números más bajos se muestran primero (1, 2, 3...).</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked 
                                   class="rounded border-stone-300 text-red-600 focus:ring-red-500">
                            <label for="is_active" class="text-xs text-stone-700 font-medium">Visible en la carta</label>
                        </div>

                        <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold text-xs py-2.5 rounded-xl shadow transition">
                            Guardar Categoría
                        </button>
                    </form>
                </div>

                <!-- Tabla / Listado de Categorías -->
                <div class="md:col-span-2 bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                    <h3 class="font-bold text-sm text-stone-900 mb-4 pb-2 border-b border-stone-100">Categorías Existentes</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-stone-400 border-b border-stone-100">
                                    <th class="pb-2">Orden</th>
                                    <th class="pb-2">Categoría</th>
                                    <th class="pb-2 text-center">Platos vinculados</th>
                                    <th class="pb-2 text-center">Estado</th>
                                    <th class="pb-2 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-stone-700">
                                @forelse($categories as $cat)
                                    <tr>
                                        <td class="py-3 font-black text-red-700 w-12">{{ $cat->sort_order }}</td>
                                        <td class="py-3 font-bold text-stone-900">{{ $cat->name }}</td>
                                        <td class="py-3 text-center">
                                            <span class="bg-stone-100 text-stone-700 px-2 py-0.5 rounded-full text-[10px] font-bold">
                                                {{ $cat->products_count }} platos
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            @if($cat->is_active)
                                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full text-[10px] font-bold">Activo</span>
                                            @else
                                                <span class="bg-stone-100 text-stone-500 px-2 py-0.5 rounded-full text-[10px]">Oculto</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-right">
                                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('¿Eliminar esta categoría? Los platos vinculados quedarán sin categoría.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-[11px]">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-stone-400">No hay categorías creadas aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>