<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Plato') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Plato *</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Arroz Chaufa Especial" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Precio (S/) *</label>
                            <input type="number" step="0.50" name="price" value="{{ old('price') }}" placeholder="Ej: 18.00" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción / Ingredientes</label>
                        <textarea name="description" rows="3" placeholder="Arroz al wok, trozos de pollo, sillao, cebollita china, huevo..." class="w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Foto del Plato</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 hover:file:bg-gray-200">
                    </div>

                    <!-- Sección de Adicionales -->
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-800 text-sm">Adicionales / Extras (Opcional)</h3>
                            <button type="button" id="add-addon-btn" class="text-xs bg-gray-800 hover:bg-black text-white px-3 py-1 rounded">
                                + Agregar Extra
                            </button>
                        </div>

                        <div id="addons-container" class="space-y-2">
                            <!-- Fila inicial por defecto -->
                            <div class="flex gap-2 items-center">
                                <input type="text" name="addons[0][name]" placeholder="Nombre extra (Ej: +Porción Wantán)" class="w-2/3 border-gray-300 rounded-md shadow-sm text-sm">
                                <input type="number" step="0.50" name="addons[0][price]" placeholder="Precio S/ (Ej: 4.00)" class="w-1/3 border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md text-sm shadow">Guardar Plato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let addonIndex = 1;
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