<div>
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex mb-3">
                    <h1 class="text-3xl font-bold mb-3 ml-1">SUBIR NUEVA IMAGEN</h1>
                </div>

                <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">

                    <div>
                        <label for="nombre"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" id="nombre" wire:model="nombre"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('nombre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Descripción -->
                    <div>
                        <label for="descripcion"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea id="descripcion" wire:model="descripcion" cols="30" rows="2" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100"></textarea>
                        @error('descripcion')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Archivo adjunto -->
                    <div x-data="{ cargando: false }">
                        <label for="ruta_archivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Archivo adjunto
                        </label>
                        <input type="file" id="ruta_archivo" wire:model="ruta_archivo" @change="cargando = true"
                            class="mt-1 block w-full text-gray-700 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800">
                        @error('ruta_archivo')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                        <!-- Vista previa del archivo -->
                        <div class="mt-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Vista previa:</span>
                            @if ($ruta_archivo)
                                <!-- Mensaje mientras se carga -->
                                <div wire:loading wire:target="ruta_archivo" x-show="cargando"
                                    class="mt-2 flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm animate-pulse">
                                    <svg class="w-5 h-5 animate-spin text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                    Cargando vista previa...
                                </div>
                                @php
                                    $mime = $ruta_archivo->getMimeType();
                                    $isImage = str_starts_with($mime, 'image/');
                                @endphp
                                <div wire:loading.remove wire:target="ruta_archivo" class="mt-2">
                                    @if ($isImage)
                                        <!-- Si es una imagen -->
                                        <img src="{{ $ruta_archivo->temporaryUrl() }}" alt="Vista previa"
                                            @load="cargando = false"
                                            class="rounded-lg border border-gray-300 dark:border-gray-700 max-w-full h-auto object-contain shadow-sm transition-opacity duration-300" />
                                    @else
                                        <!-- Si no es una imagen -->
                                        <div class="flex flex-col items-center justify-center w-64 h-40 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm transition-opacity duration-300"
                                            x-init="cargando = false">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h10m-9 4h9m2 0a2 2 0 002-2V8a2 2 0 00-2-2h-5.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 008.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2h12z" />
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center px-2">
                                                Vista previa no disponible para este tipo de archivo.
                                            </p>
                                            <span class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                {{ $ruta_archivo->getClientOriginalName() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
