<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Adicionales y Extras Globales') }}
            </h2>
            <button type="button" 
                    onclick="document.getElementById('modal-new-addon').classList.remove('hidden')" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow text-sm transition active:scale-95">
                + Nuevo Adicional
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 border-l-4 border-red-500 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($addons as $addon)
                        <div class="border border-stone-200 rounded-2xl p-4 flex flex-col justify-between hover:shadow-md transition bg-stone-50">
                            <div>
                                <div class="w-full h-32 bg-stone-200 rounded-xl overflow-hidden mb-3 relative">
                                    @if($addon->image)
                                        <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-stone-400">Sin foto</div>
                                    @endif
                                    <span class="absolute top-2 right-2 px-2 py-0.5 text-[10px] rounded-full font-bold {{ $addon->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                        {{ $addon->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-stone-900 text-sm truncate">{{ $addon->name }}</h4>
                                <p class="text-red-700 font-black text-base mt-1">+ S/ {{ number_format($addon->price, 2) }}</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-stone-200 flex items-center justify-end gap-2">
                                <!-- Botón Editar -->
                                <button type="button" 
                                        onclick="openEditAddonModal({
                                            id: {{ $addon->id }},
                                            name: '{{ addslashes($addon->name) }}',
                                            price: '{{ $addon->price }}',
                                            is_active: {{ $addon->is_active ? 'true' : 'false' }},
                                            image_url: '{{ $addon->image ? asset('storage/' . $addon->image) : '' }}',
                                            update_url: '{{ route('admin.addons.update', $addon) }}'
                                        })"
                                        class="px-2.5 py-1 text-xs text-stone-700 hover:text-stone-900 font-semibold bg-white border border-stone-300 rounded-lg hover:bg-stone-100 transition shadow-sm">
                                    ✏️ Editar
                                </button>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este adicional? Se desvinculará de los platos.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 text-xs text-red-600 hover:text-red-800 font-semibold bg-white border border-red-200 rounded-lg hover:bg-red-50 transition shadow-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-stone-400 text-sm">
                            No tienes adicionales globales registrados aún. Haz clic en "+ Nuevo Adicional".
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: Registrar Nuevo Adicional -->
    <div id="modal-new-addon" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="font-bold text-lg text-stone-900 mb-4">Registrar Nuevo Adicional Global</h3>
            <form action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nombre (ej: Pepsi, Wantán Frito)</label>
                    <input type="text" name="name" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Precio Extra (S/)</label>
                    <input type="number" step="0.10" min="0" name="price" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="1.00">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Foto del Adicional</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-stone-500">
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modal-new-addon').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-xs font-bold text-stone-600 hover:bg-stone-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold shadow">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Editar Adicional Existente -->
    <div id="modal-edit-addon" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="font-bold text-lg text-stone-900 mb-4">Editar Adicional Global</h3>
            
            <form id="form-edit-addon" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Nombre del Adicional</label>
                    <input type="text" id="edit-addon-name" name="name" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Precio Extra (S/)</label>
                    <input type="number" step="0.10" min="0" id="edit-addon-price" name="price" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Foto del Adicional (dejar vacío para mantener la actual)</label>
                    <div id="edit-addon-preview-container" class="mb-2 hidden">
                        <img id="edit-addon-preview" src="" alt="Foto actual" class="w-16 h-16 object-cover rounded-lg border border-stone-200">
                    </div>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-stone-500">
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edit-addon-active" name="is_active" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="text-xs font-semibold text-stone-700">Adicional activo (disponible en la carta)</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modal-edit-addon').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-xs font-bold text-stone-600 hover:bg-stone-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold shadow">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para abrir y poblar el modal de edición -->
    <script>
        function openEditAddonModal(data) {
            const form = document.getElementById('form-edit-addon');
            form.action = data.update_url;

            document.getElementById('edit-addon-name').value = data.name;
            document.getElementById('edit-addon-price').value = parseFloat(data.price).toFixed(2);
            document.getElementById('edit-addon-active').checked = Boolean(data.is_active);

            const previewContainer = document.getElementById('edit-addon-preview-container');
            const previewImg = document.getElementById('edit-addon-preview');

            if (data.image_url) {
                previewImg.src = data.image_url;
                previewContainer.classList.remove('hidden');
            } else {
                previewImg.src = '';
                previewContainer.classList.add('hidden');
            }

            document.getElementById('modal-edit-addon').classList.remove('hidden');
        }
    </script>
</x-app-layout>