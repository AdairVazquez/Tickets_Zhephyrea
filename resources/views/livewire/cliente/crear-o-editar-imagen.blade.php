<div>
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex mb-3">
                    <h1 class="text-3xl font-bold mb-3 ml-1">
                        {{ $imagen_id ? 'EDITAR IMAGEN' : 'CREAR IMAGEN' }}
                    </h1>
                </div>

                <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" id="nombre" wire:model="nombre"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('nombre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripcion</label>
                        <textarea id="descripcion" wire:model="descripcion" cols="30" rows="2"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100"></textarea>
                        @error('descripcion')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ cargando: false }">
                        <label for="ruta_archivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Archivo adjunto
                        </label>
                        <input type="file" id="ruta_archivo" wire:model="ruta_archivo" @change="cargando = true"
                            class="mt-1 block w-full text-gray-700 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800">
                        @error('ruta_archivo')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror

                        <div class="mt-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Vista previa:</span>

                            @if ($ruta_archivo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                <div wire:loading wire:target="ruta_archivo" x-show="cargando"
                                    class="mt-2 flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm animate-pulse">
                                    <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
                                    </svg>
                                    Cargando vista previa...
                                </div>

                                <div wire:loading.remove wire:target="ruta_archivo" class="mt-2">
                                    @php
                                        $mime = $ruta_archivo->getMimeType();
                                        $isImage = str_starts_with($mime, 'image/');
                                    @endphp

                                    @if ($isImage)
                                        <img src="{{ $ruta_archivo->temporaryUrl() }}"
                                             @load="cargando = false"
                                             class="rounded-lg border border-gray-300 dark:border-gray-700 max-w-full h-auto object-contain shadow-sm">
                                    @else
                                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg border-2 border-dashed border-gray-300">
                                            <p class="text-sm text-gray-500">Archivo listo: {{ $ruta_archivo->getClientOriginalName() }}</p>
                                        </div>
                                    @endif
                                </div>
                            @elseif ($imagen_antigua)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $imagen_antigua) }}"
                                         class="rounded-lg border border-gray-300 dark:border-gray-700 max-w-full h-48 object-cover shadow-sm">
                                    <p class="text-xs text-gray-500 mt-1 italic text-center">Imagen actual</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="crearImagen"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                            Cancelar / Limpiar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                            {{ $imagen_id ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
