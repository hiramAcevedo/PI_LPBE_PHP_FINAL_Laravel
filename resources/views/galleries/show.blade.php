<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $gallery->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('galleries.edit', $gallery->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Editar Galería
                </a>
                <a href="{{ route('images.create', ['gallery_id' => $gallery->id]) }}" class="bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                    Añadir Imagen
                </a>
                <a href="{{ route('galleries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    @if($gallery->description)
                        <h3 class="text-lg font-medium mb-2">Descripción:</h3>
                        <p class="mb-4">{{ $gallery->description }}</p>
                    @endif
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Creada: {{ $gallery->created_at->format('d/m/Y') }}
                        @if(Auth::user()->role?->name === 'admin' && Auth::user()->id !== $gallery->user_id)
                            | Propietario: {{ $gallery->user->name }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <h3 class="text-lg font-medium mb-4">Imágenes en esta galería:</h3>
                    
                    @if ($gallery->images->isEmpty())
                        <p class="text-center py-4">No hay imágenes en esta galería. ¡Añade tu primera imagen!</p>
                    @else
                        <div id="gallery-images" class="grid grid-cols-1 md:grid-cols-3 gap-6" data-gallery-id="{{ $gallery->id }}" data-csrf-token="{{ csrf_token() }}">
                            @foreach ($gallery->images->sortBy('position') as $image)
                                <div class="image-item border dark:border-gray-700 rounded-lg overflow-hidden shadow-md dark:shadow-gray-900/30 flex flex-col h-full" data-id="{{ $image->id }}">
                                    <div class="aspect-video w-full overflow-hidden">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4 flex-grow">
                                        <h4 class="font-semibold dark:text-gray-200">{{ $image->title }}</h4>
                                        @if($image->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($image->description, 50) }}</p>
                                        @endif
                                    </div>
                                    <div class="bg-gray-50 dark:bg-deeper-gray-700 px-4 py-3 border-t dark:border-gray-700 flex justify-between">
                                        <a href="{{ route('images.edit', $image->id) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300">
                                            Editar
                                        </a>
                                        <form action="{{ route('images.destroy', $image->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de querer eliminar esta imagen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (!$gallery->images->isEmpty())
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gallery = document.getElementById('gallery-images');
            
            if (gallery) {
                const galleryId = gallery.dataset.galleryId;
                const csrfToken = gallery.dataset.csrfToken;
                
                const sortable = Sortable.create(gallery, {
                    animation: 150,
                    onEnd: function() {
                        const items = document.querySelectorAll('.image-item');
                        const positions = {};
                        
                        items.forEach((item, index) => {
                            const id = item.getAttribute('data-id');
                            positions[id] = index;
                        });
                        
                        fetch("{{ route('images.update-positions') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                positions: positions,
                                gallery_id: galleryId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Posiciones actualizadas con éxito');
                            } else {
                                console.error('Error al actualizar posiciones', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            }
        });
    </script>
    @endif
</x-app-layout> 