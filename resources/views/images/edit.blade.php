<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Imagen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-deeper-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <form method="POST" action="{{ route('images.update', $image->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">Imagen Actual:</h3>
                            <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->title }}" class="w-64 h-auto rounded shadow">
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="$image->title" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-deeper-gray-700 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-400 focus:ring-purple-500 dark:focus:ring-purple-400 rounded-md shadow-sm" rows="4">{{ $image->description }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Reemplazar Imagen (opcional)')" />
                            
                            <div id="drop-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md border-gray-300 dark:border-gray-600 transition duration-300 ease-in-out hover:border-purple-400 dark:hover:border-purple-500 cursor-pointer">
                                <div class="space-y-1 text-center">
                                    <svg id="default-icon" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8m36-12h-4m4 0H20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    
                                    <!-- Contenedor para la vista previa de la imagen -->
                                    <div id="preview-container" class="hidden flex flex-col items-center">
                                        <img id="preview-image" src="#" alt="Vista previa" class="max-h-64 max-w-full object-contain rounded-lg shadow-md my-2" />
                                        <button type="button" id="remove-image" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 mt-2">
                                            Eliminar imagen
                                        </button>
                                    </div>
                                    
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 focus-within:outline-none">
                                            <span>Subir una imagen</span>
                                            <input id="image" name="image" type="file" accept="image/*" class="sr-only" />
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Solo si deseas reemplazar la imagen actual. Formato: JPG, PNG, GIF, etc. Tamaño máximo 2MB.
                                    </p>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('galleries.show', $image->gallery_id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Cancelar</a>
                            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropArea = document.getElementById('drop-area');
            const fileInput = document.getElementById('image');
            const preview = document.getElementById('preview-image');
            const previewContainer = document.getElementById('preview-container');
            const defaultIcon = document.getElementById('default-icon');
            const removeButton = document.getElementById('remove-image');
            
            // Prevenir comportamiento por defecto de arrastrar y soltar
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            // Resaltar zona de arrastre al entrar
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            // Quitar resaltado al salir
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropArea.classList.add('border-purple-500');
                dropArea.classList.add('bg-purple-50');
                dropArea.classList.add('dark:bg-purple-900/10');
            }
            
            function unhighlight() {
                dropArea.classList.remove('border-purple-500');
                dropArea.classList.remove('bg-purple-50');
                dropArea.classList.remove('dark:bg-purple-900/10');
            }
            
            // Manejar archivos soltados
            dropArea.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length) {
                    fileInput.files = files;
                    updatePreview(files[0]);
                }
            }
            
            // Mostrar vista previa al seleccionar archivo con el input
            fileInput.addEventListener('change', function() {
                if (this.files.length) {
                    updatePreview(this.files[0]);
                }
            });
            
            // Actualizar vista previa de la imagen
            function updatePreview(file) {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        defaultIcon.classList.add('hidden');
                        preview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
            
            // Eliminar imagen
            removeButton.addEventListener('click', function() {
                fileInput.value = '';
                previewContainer.classList.add('hidden');
                defaultIcon.classList.remove('hidden');
            });
            
            // Click en el área para activar el input de archivo
            dropArea.addEventListener('click', function() {
                fileInput.click();
            });
        });
    </script>
</x-app-layout> 