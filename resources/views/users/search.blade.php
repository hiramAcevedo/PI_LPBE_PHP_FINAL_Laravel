@php
    use Illuminate\Support\Facades\DB;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buscar Usuarios') }}
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
                    <form action="{{ route('users.search.results') }}" method="GET" class="flex">
                        <input type="text" name="query" placeholder="Buscar por nombre o email" value="{{ $query ?? '' }}" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-deeper-gray-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-purple-500 dark:focus:ring-purple-400 shadow-sm">
                        <button type="submit" class="bg-purple-600 dark:bg-purple-700 text-white px-4 py-2 rounded-r-md hover:bg-purple-700 dark:hover:bg-purple-600">
                            Buscar
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <h3 class="text-lg font-medium mb-4">{{ isset($query) ? 'Resultados de la búsqueda' : 'Usuarios' }}</h3>
                    
                    @if ($users->isEmpty())
                        <p class="text-center py-4">No se encontraron usuarios.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                // Obtener la lista de usuarios que el usuario autenticado sigue
                                $followedUserIds = DB::table('user_follows')
                                    ->where('follower_id', Auth::id())
                                    ->pluck('followed_id')
                                    ->toArray();
                            @endphp
                            @foreach ($users as $user)
                                <div class="border dark:border-gray-700 rounded-lg overflow-hidden shadow-md dark:shadow-gray-900/30 flex flex-col h-full">
                                    <div class="p-4 flex-grow">
                                        <h4 class="text-lg font-semibold">
                                            <a href="{{ route('users.profile', $user->id) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                                                {{ $user->name }}
                                            </a>
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $user->email }}
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">
                                            {{ $user->galleries_count }} {{ Str::plural('galería', $user->galleries_count) }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-deeper-gray-700 px-4 py-3 border-t dark:border-gray-700 flex justify-between">
                                        <a href="{{ route('users.profile', $user->id) }}" class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                                            Ver perfil
                                        </a>
                                        <div>
                                            @if (in_array($user->id, $followedUserIds))
                                                <form action="{{ route('users.unfollow', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                        Dejar de seguir
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('users.follow', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                                        Seguir
                                                    </button>
                                                </form>
                                            @endif
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