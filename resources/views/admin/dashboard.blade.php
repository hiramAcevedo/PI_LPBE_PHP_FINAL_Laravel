<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Administración') }}
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Resumen de Usuarios -->
                <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <h3 class="text-lg font-medium mb-4">Usuarios</h3>
                        <p class="mb-2">Total de usuarios: <span class="font-bold">{{ $users->count() }}</span></p>
                        <a href="{{ route('admin.users') }}" class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                            Administrar usuarios →
                        </a>
                    </div>
                </div>

                <!-- Resumen de Galerías -->
                <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <h3 class="text-lg font-medium mb-4">Galerías</h3>
                        <p class="mb-2">Total de galerías: <span class="font-bold">{{ $galleries->count() }}</span></p>
                        <p class="mb-2">Total de imágenes: <span class="font-bold">{{ $galleries->sum('images_count') }}</span></p>
                        <a href="{{ route('admin.galleries') }}" class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                            Ver todas las galerías →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <h3 class="text-lg font-medium mb-4">Usuarios Recientes</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-deeper-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rol</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Galerías</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Creado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-deeper-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users->sortByDesc('created_at')->take(5) as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-200">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role?->name === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' }}">
                                                {{ $user->role?->name ?? 'Sin rol' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->galleries_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 mr-3">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 