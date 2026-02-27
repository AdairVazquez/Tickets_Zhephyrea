<div>
    <div class="relative flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
        <div class="p-6">
            <div class="flex mb-4">
                <h1 class="text-2xl font-bold dark:text-white uppercase tracking-tight">
                    {{ $imagen_id ? 'Editar Imagen' : 'Crear Nueva Imagen' }}
                </h1>
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300">Nombre del archivo</label>
                    <input type="text" id="nombre" wire:model="nombre"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-800 dark:text-white transition-all">
                    @error('nombre')
                    <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300">Descripción (Opcional)</label>
                    <textarea id="descripcion" wire:model="descripcion" rows="2"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-800 dark:text-white transition-all"></textarea>
                    @error('descripcion')
                    <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div>

                            
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300">Seleccione un estado</label>
                    <select wire:model="estado_id"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Seleccione un estado</option>
                        @foreach($estados as $estado)
                        <option value="{{ $estado->id }}">
                            {{ $estado->nombre_estado }}
                        </option>
                        @endforeach
                    </select>
                    @error('descripcion')
                    <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div x-data="{ isUploading: false }"
                    x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="isUploading = false">

                    <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300">Archivo de Imagen</label>
                    <input type="file" id="ruta_archivo" wire:model="ruta_archivo"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-zinc-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 dark:file:bg-zinc-800 dark:file:text-zinc-300">

                    @error('ruta_archivo')
                    <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                    @enderror

                    <div class="mt-4 border-2 border-dashed border-gray-200 dark:border-zinc-800 rounded-lg p-2 min-h-[150px] flex items-center justify-center relative">
                        <div x-show="isUploading" class="absolute inset-0 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm flex items-center justify-center z-10 rounded-lg">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs font-bold mt-2 text-blue-600">Subiendo...</span>
                            </div>
                        </div>

                        @if ($ruta_archivo)
                        <div class="text-center">
                            <img src="{{ $ruta_archivo->temporaryUrl() }}" class="max-h-48 rounded-lg shadow-md mx-auto">
                            <p class="text-[10px] text-green-600 mt-2 font-bold uppercase">Nueva imagen lista</p>
                        </div>
                        @elseif ($imagen_antigua)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $imagen_antigua) }}" class="max-h-48 rounded-lg shadow-md mx-auto">
                            <p class="text-[10px] text-gray-500 mt-2 font-bold uppercase italic">Imagen almacenada</p>
                        </div>
                        @else
                        <div class="text-gray-400 text-sm flex flex-col items-center">
                            <flux:icon name="photo" variant="outline" class="w-10 h-10 mb-2 opacity-20" />
                            <span>Sin archivo seleccionado</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t dark:border-zinc-800">
                    <button type="button"
                        wire:click="limpiarCampos"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-zinc-400 hover:text-red-500 transition-colors">
                        Limpiar Formulario
                    </button>

                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save, ruta_archivo"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all disabled:opacity-50 flex items-center">
                        <span wire:loading.remove wire:target="save">
                            {{ $imagen_id ? 'Actualizar Datos' : 'Guardar Imagen' }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>