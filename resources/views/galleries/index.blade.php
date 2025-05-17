<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mis Galerías') }}
            </h2>
            <a href="{{ route('galleries.create') }}" class="bg-purple-600 dark:bg-purple-700 hover:bg-purple-700 dark:hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                Crear Galería
            </a>
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

            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    @if ($galleries->isEmpty())
                        <p class="text-center py-4">No tienes galerías creadas. ¡Crea tu primera galería!</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($galleries as $gallery)
                                <div class="border dark:border-gray-700 rounded-lg overflow-hidden shadow-md dark:shadow-gray-900/30 flex flex-col h-full">
                                    @if($gallery->images->count() > 0)
                                    <div class="grid grid-cols-2 gap-1 aspect-video">
                                        @foreach($gallery->images->take(4) as $index => $image)
                                            <div class="{{ $gallery->images->count() === 1 ? 'col-span-2 row-span-2' : ($gallery->images->count() === 2 ? 'col-span-1 row-span-2' : '') }}">
                                                <img src="{{ asset('storage/' . $image->file_path) }}" 
                                                     alt="{{ $image->title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                        
                                        @if($gallery->images->count() < 4)
                                            @for($i = $gallery->images->count(); $i < 4; $i++)
                                                <div class="bg-gray-100 dark:bg-deeper-gray-700"></div>
                                            @endfor
                                        @endif
                                    </div>
                                    @else
                                    <div class="bg-gray-100 dark:bg-deeper-gray-700 aspect-video"></div>
                                    @endif
                                    
                                    <div class="p-4 flex-grow">
                                        <h3 class="text-lg font-semibold">
                                            <a href="{{ route('galleries.show', $gallery->id) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                                                {{ $gallery->name }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                                            @if(Auth::id() !== $gallery->user_id)
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Propietario: {{ $gallery->user->name }}</span><br>
                                            @endif
                                            <span class="text-sm">{{ $gallery->images->count() }} imágenes</span>
                                        </p>
                                        <p class="mt-2 text-gray-700 dark:text-gray-300">
                                            {{ Str::limit($gallery->description, 100) }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-deeper-gray-700 px-4 py-3 border-t dark:border-gray-700 flex justify-between">
                                        <a href="{{ route('galleries.show', $gallery->id) }}" class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                                            Ver galería
                                        </a>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('galleries.edit', $gallery->id) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300">
                                                Editar
                                            </a>
                                            <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de querer eliminar esta galería?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                    Eliminar
                                                </button>
                                            </form>
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
</x-app-layout> 