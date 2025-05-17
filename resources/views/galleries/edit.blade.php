<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Galería') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <form method="POST" action="{{ route('galleries.update', $gallery->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nombre')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$gallery->name" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-deeper-gray-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-purple-500 dark:focus:ring-purple-400 rounded-md shadow-sm" rows="4">{{ $gallery->description }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('galleries.show', $gallery->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Cancelar</a>
                            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 