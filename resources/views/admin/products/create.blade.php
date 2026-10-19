<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($product) ? __('Editar Plato') : __('Registrar Nuevo Plato') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 text-xs rounded-lg">
                        <strong class="font-bold block mb-1">Revisa los siguientes campos:</strong>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Plato *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $product->name ?? '') }}"
                                   placeholder="Ej: Arroz Chaufa Especial"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                   required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Precio (S/) *</label>
                            <input type="number"
                                   step="0.50"
                                   name="price"
                                   value="{{ old('price', $product->price ?? '') }}"
                                   placeholder="Ej: 18.00"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                                   required>
                            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción / Ingredientes</label>
                        <textarea name="description"
                                  rows="3"
                                  placeholder="Arroz al wok, trozos de pollo, sillao, cebollita china, huevo..."
                                  class="w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                            <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Sin categoría asignada --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto del Plato</label>
                            @if(isset($product) && $product->image)
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Foto actual" class="w-12 h-12 object-cover rounded-lg border">
                                    <span class="text-xs text-gray-500">Imagen actual</span>
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 hover:file:bg-gray-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <input type="checkbox"
                                   name="is_featured"
                                   id="is_featured"
                                   value="1"
                                   {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                                   class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <label for="is_featured" class="text-xs font-bold text-amber-950 block">⭐ Producto Estrella de la Casa</label>
                                <span class="text-[10px] text-amber-800">Se mostrará en la sección destacada superior del menú.</span>
                            </div>
                        </div>

                        @if(isset($product))
                            <div class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <input type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                                       class="rounded border-green-300 text-green-600 focus:ring-green-500">
                                <div>
                                    <label for="is_active" class="text-xs font-bold text-green-950 block">✅ Activo en la Carta</label>
                                    <span class="text-[10px] text-green-800">Si lo desmarcas, no se mostrará a los clientes.</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sección de Adicionales Globales -->
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm">Adicionales / Extras permitidos para este plato</h3>
                                <p class="text-xs text-gray-400">Marca los extras disponibles que el cliente podrá añadir a este plato.</p>
                            </div>
                            <a href="{{ route('admin.addons.index') }}" target="_blank" class="text-xs text-red-600 font-bold hover:underline">
                                + Crear nuevo adicional global ↗
                            </a>
                        </div>

                        @if(isset($addons) && $addons->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($addons as $addon)
                                    @php
                                        $isChecked = isset($product) ? $product->addons->contains($addon->id) : false;
                                    @endphp
                                    <label class="flex items-center gap-3 p-2.5 border rounded-xl cursor-pointer hover:bg-stone-50 transition {{ $isChecked ? 'border-red-500 bg-red-50/30' : 'border-stone-200' }}">
                                        <input type="checkbox" name="addons[]" value="{{ $addon->id }}" {{ $isChecked ? 'checked' : '' }}
                                            class="rounded border-stone-300 text-red-600 focus:ring-red-500">
                                        
                                        @if($addon->image)
                                            <img src="{{ asset('storage/' . $addon->image) }}" class="w-10 h-10 object-cover rounded-lg border">
                                        @else
                                            <div class="w-10 h-10 bg-stone-200 rounded-lg flex items-center justify-center text-[10px] text-stone-400">Extra</div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <span class="block text-xs font-bold text-stone-800 truncate">{{ $addon->name }}</span>
                                            <span class="block text-[11px] text-red-700 font-black">+ S/ {{ number_format($addon->price, 2) }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-stone-50 rounded-xl border border-dashed border-stone-300 text-center">
                                <p class="text-xs text-stone-500">No hay adicionales globales creados.</p>
                                <a href="{{ route('admin.addons.index') }}" class="text-xs font-bold text-red-600 underline mt-1 inline-block">Crear adicionales aquí</a>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md text-sm shadow">
                            {{ isset($product) ? 'Actualizar Plato' : 'Guardar Plato' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let addonIndex = {{ (isset($product) && $product->addons && $product->addons->count() > 0) ? $product->addons->count() : 1 }};
        document.getElementById('add-addon-btn').addEventListener('click', function() {
            const container = document.getElementById('addons-container');
            const row = document.createElement('div');
            row.className = 'flex gap-2 items-center';
            row.innerHTML = `
                <input type="text" name="addons[${addonIndex}][name]" placeholder="Nombre extra (Ej: +Pollo)" class="w-2/3 border-gray-300 rounded-md shadow-sm text-sm">
                <input type="number" step="0.50" name="addons[${addonIndex}][price]" placeholder="Precio S/" class="w-1/3 border-gray-300 rounded-md shadow-sm text-sm">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-bold px-2 text-sm hover:text-red-700">&times;</button>
            `;
            container.appendChild(row);
            addonIndex++;
        });
    </script>
</x-app-layout>