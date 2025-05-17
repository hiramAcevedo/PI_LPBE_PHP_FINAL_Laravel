<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tus Imágenes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Encabezado del mosaico con búsqueda de usuarios -->
            <div class="flex justify-between items-center mb-6 px-4">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                    Tus imágenes subidas
                </h3>
                <div class="flex space-x-4">
                    <a href="{{ route('users.search') }}" class="flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Buscar Usuarios</span>
                    </a>
                    <a href="{{ route('explore') }}" class="flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Explorar todas las imágenes</span>
                    </a>
                </div>
            </div>

            @if($images->isEmpty())
                <div class="text-center py-12 bg-white dark:bg-deeper-gray-800 rounded-lg shadow-md mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium mb-2 text-gray-800 dark:text-gray-200">No has subido imágenes</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Aún no has subido imágenes a tus galerías.
                    </p>
                    <a href="{{ route('galleries.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 dark:hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        Crear tu primera galería
                    </a>
                </div>
            @else
                <!-- Mosaico de imágenes del usuario -->
                <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <div class="image-grid mx-auto" id="image-masonry">
                            @foreach($images as $image)
                            <div class="image-item mb-4">
                                <div class="image-card rounded-lg shadow-md hover:shadow-xl dark:shadow-gray-900/20 dark:hover:shadow-gray-900/40 overflow-hidden cursor-pointer transition-all duration-300" 
                                    data-id="{{ $image->id }}"
                                    onclick="openImageModal('{{ $image->id }}')">
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" 
                                            alt="{{ $image->title }}" 
                                            class="w-full h-auto object-cover">
                                        
                                        <!-- Overlay que aparece en hover -->
                                        <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                                            <h4 class="text-white text-sm font-medium truncate">{{ $image->title }}</h4>
                                            <p class="text-gray-200 text-xs truncate">{{ $image->gallery->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($followedImages->count() > 0)
                <!-- Sección de imágenes de usuarios seguidos -->
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4 px-4">Imágenes de personas que sigues</h3>
                
                @foreach($followedImages as $userId => $userImages)
                    @php
                        $user = $userImages->first()->gallery->user;
                    @endphp
                    <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-semibold">
                                    <a href="{{ route('users.profile', $userId) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                                        {{ $user->name }}
                                    </a>
                                </h4>
                                <a href="{{ route('users.profile', $userId) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                                    Ver todas sus imágenes
                                </a>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($userImages->take(4) as $image)
                                <div class="aspect-square overflow-hidden rounded-lg cursor-pointer"
                                   onclick="openImageModal('{{ $image->id }}')">
                                    <img src="{{ asset('storage/' . $image->file_path) }}" 
                                         alt="{{ $image->title }}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif(!$images->isEmpty())
                <!-- Sugerencia de usuarios para seguir (si no sigue a nadie pero tiene imágenes) -->
                <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <p class="mb-4">Todavía no sigues a ningún usuario. Encuentra personas para seguir:</p>
                        <a href="{{ route('users.search') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 dark:hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                            Buscar Usuarios
                        </a>
                    </div>
                </div>
            @endif

            <!-- Modal de imagen -->
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
                                <a id="modal-edit-image" href="#" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded-md text-xs">
                                    Editar
                                </a>
                            </div>
                        </div>
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
        
        .image-overlay {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
    </style>

    <script>
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
                    
                    // Mostrar u ocultar el botón de editar según si el usuario es propietario
                    const editButton = document.getElementById('modal-edit-image');
                    if (data.is_owner) {
                        editButton.href = `/images/${data.id}/edit`;
                        editButton.classList.remove('hidden');
                    } else {
                        editButton.classList.add('hidden');
                    }
                    
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
