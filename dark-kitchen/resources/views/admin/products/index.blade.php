<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Carta de Platos') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow text-sm">
                + Nuevo Plato
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b text-gray-600 uppercase text-xs">
                                <th class="py-3 px-4">Foto</th>
                                <th class="py-3 px-4">Nombre</th>
                                <th class="py-3 px-4">Precio</th>
                                <th class="py-3 px-4">Adicionales</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-md">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-400">Sin foto</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-gray-900">{{ $product->name }}</td>
                                    <td class="py-3 px-4 font-bold text-gray-700">S/ {{ number_format($product->price, 2) }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($product->addons as $addon)
                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded">
                                                    {{ $addon->name }} (+S/ {{ number_format($addon->price, 2) }})
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">Sin adicionales</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($product->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Activo</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Pausado</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este plato?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-400">Aún no has registrado ningún plato. Haz clic en "+ Nuevo Plato".</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>