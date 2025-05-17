<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil de') }}: {{ $user->name }}
        </h2>
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
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            
                            <div class="mt-2 flex space-x-4">
                                <a href="{{ route('users.followers', $user->id) }}" class="text-purple-600 dark:text-purple-400 hover:underline">
                                    <span class="font-bold">{{ $followersCount }}</span> {{ Str::plural('seguidor', $followersCount) }}
                                </a>
                                <a href="{{ route('users.following', $user->id) }}" class="text-purple-600 dark:text-purple-400 hover:underline">
                                    <span class="font-bold">{{ $followingCount }}</span> seguidos
                                </a>
                            </div>
                        </div>
                        
                        @if (Auth::id() !== $user->id)
                            <div class="mt-4 md:mt-0">
                                @if ($isFollowing)
                                    <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Dejar de seguir
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.follow', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                                            Seguir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <h3 class="text-lg font-medium mb-4">Imágenes de {{ $user->name }}</h3>
                    
                    @if ($images->isEmpty())
                        <p class="text-center py-4">{{ $user->name }} no ha subido imágenes todavía.</p>
                    @else
                        <div class="image-grid mx-auto">
                            @foreach ($images as $image)
                            <div class="image-item mb-4">
                                <div class="image-card rounded-lg shadow-md hover:shadow-xl dark:shadow-gray-900/20 dark:hover:shadow-gray-900/40 overflow-hidden cursor-pointer transition-all duration-300" 
                                    data-id="{{ $image->id }}"
                                    onclick="openImageModal('{{ $image->id }}')">
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" 
                                            alt="{{ $image->title }}" 
                                            class="w-full h-auto object-cover">
                                        
                                        <!-- Overlay al hacer hover -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                                            <h4 class="text-white text-sm font-medium truncate">{{ $image->title }}</h4>
                                            <p class="text-gray-200 text-xs truncate">{{ $image->gallery->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de imagen reutilizado del dashboard -->
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="relative bg-white dark:bg-deeper-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-2/3 bg-gray-100 dark:bg-black flex items-center justify-center">
                    <img id="modal-image" src="" alt="" class="max-h-[60vh] max-w-full object-contain">
                </div>
                
                <div class="w-full md:w-1/3 p-6 overflow-y-auto max-h-[60vh]">
                    <h3 id="modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2"></h3>
                    <p id="modal-description" class="text-sm text-gray-600 dark:text-gray-400 mb-4"></p>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span class="font-semibold">Galería:</span>
                            <a id="modal-gallery-link" href="#" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300"></a>
                        </p>
                        <p id="modal-user" class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span class="font-semibold">Propietario:</span>
                        </p>
                        <p id="modal-date" class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold">Fecha:</span>
                        </p>
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <a id="modal-view-gallery" href="#" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 dark:hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                            Ver Galería
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para el grid de imágenes */
        .image-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1rem;
            width: 100%;
        }
        
        @media (min-width: 640px) {
            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 768px) {
            .image-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .image-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        .image-item {
            width: 100%;
        }
        
        .image-card {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .image-card:hover {
            transform: translateY(-3px);
        }
    </style>

    <script>
        // Replicar la funcionalidad del modal del dashboard
        function openImageModal(imageId) {
            fetch(`/images/${imageId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('No se pudo cargar la información de la imagen');
                    }
                    return response.json();
                })
                .then(data => {
                    // Llenar el modal con los datos
                    document.getElementById('modal-image').src = data.file_path;
                    document.getElementById('modal-image').alt = data.title;
                    document.getElementById('modal-title').textContent = data.title;
                    document.getElementById('modal-description').textContent = data.description || 'Sin descripción';
                    document.getElementById('modal-gallery-link').textContent = data.gallery.name;
                    document.getElementById('modal-gallery-link').href = `/galleries/${data.gallery.id}`;
                    document.getElementById('modal-user').innerHTML = `<span class="font-semibold">Propietario:</span> ${data.gallery.user_name}`;
                    document.getElementById('modal-date').innerHTML = `<span class="font-semibold">Fecha:</span> ${data.created_at}`;
                    document.getElementById('modal-view-gallery').href = `/galleries/${data.gallery.id}`;
                    
                    // Mostrar modal
                    document.getElementById('image-modal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Evitar scroll
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudo cargar la información de la imagen');
                });
        }
        
        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Permitir scroll
        }
        
        // Cerrar modal al presionar Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
        
        // Cerrar modal al hacer clic fuera de él
        document.getElementById('image-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageModal();
            }
        });
    </script>
</x-app-layout> 