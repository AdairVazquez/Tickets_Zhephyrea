<div>
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6">
                <div class="flex mb-3">
                    <h1 class="text-2xl font-bold mb-3 ml-1">DETALLE DEL TICKET #{{ $ticket->id }}</h1>
                </div>

                <div
                    class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <div
                        class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

                        <div class="p-6">
                            <label for="" class="block text-xl font-medium text-gray-700 dark:text-gray-300">
                                <strong>Titulo</strong></label>
                            <input type="text" id="name" disabled value="{{ $ticket->titulo }}"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">

                            <label for=""
                                class="block text-xl font-medium text-gray-700 dark:text-gray-300 mt-2">
                                <strong>Descripción</strong></label>
                            <textarea disabled
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100"
                                name="" id="">{{ $ticket->descripcion }}</textarea>

                            <label for=""
                                class="block text-xl font-medium text-gray-700 dark:text-gray-300 mt-2">
                                <strong>Archivos adjuntos</strong></label>
                            <!-- Vista previa del archivo -->
                            <div class="mt-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Vista previa:</span>
                                @if ($ticket->archivo)
                                    <!-- Verificamos que exista el archivo relacionado -->
                                    @php
                                        $ruta = $ticket->archivo->ruta; // 'adjuntos/hnrerMjxdPu3cN0KZ1G682ucn6VT8RAGKymg9EGL.png'
                                        $pathFisico = storage_path('app/public/' . $ruta);
                                        $isImage = false;

                                        if (\Illuminate\Support\Facades\File::exists($pathFisico)) {
                                            $mime = \Illuminate\Support\Facades\File::mimeType($pathFisico);
                                            $isImage = str_starts_with($mime, 'image/');
                                        }
                                    @endphp

                                    @if ($isImage)
                                        <img src="{{ Storage::url($archivoC) }}" alt="Vista previa"
                                            class="rounded-lg border border-gray-300 dark:border-gray-700 max-w-300 h-auto object-contain shadow-sm transition-opacity duration-300" />
                                    @else
                                        <div
                                            class="flex flex-col items-center justify-center w-64 h-40 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm transition-opacity duration-300">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 8h10M7 12h10m-9 4h9m2 0a2 2 0 002-2V8a2 2 0 00-2-2h-5.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 008.586 3H6a2 2 0 00-2 2v14a2 2 0 002 2h12z" />
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center px-2">
                                                Vista previa no disponible para este tipo de archivo.
                                            </p>
                                            <a href="{{ Storage::url($ruta) }}" download
                                                class="mt-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Descargar archivo
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500">No hay archivo adjunto.</p>
                                @endif
                            </div>

                            <div class="mt-5">
                                @if ($ticket->id_estado == 3)
                                    <button disabled wire:click="confirmCerrar({{ $ticket->id }})"
                                        class="mt-1 px-4 py-2 bg-red-900 text-white rounded hover:bg-red-900">Cerrar
                                        Ticket</button>
                                    <button disabled
                                        class="mt-1 ml-3 px-4 py-2 bg-yellow-900 text-white rounded hover:bg-yellow-900">Ir
                                        al chat</button>
                                    <button wire:click="regresar"
                                        class="mt-1 ml-3 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Regresar</button>
                                @else
                                    @if ($ticket->id_estado != 1)
                                        <button wire:click="confirmCerrar({{ $ticket->id }})"
                                            class="mt-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cerrar
                                            Ticket</button>
                                        <button class="px-4 ml-3 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"
                                                wire:click="irChat({{ $ticket->id }})">Ir al chat
                                            </button>
                                    @endif

                                    
                                    <button wire:click="regresar"
                                        class="mt-1 ml-3 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Regresar</button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('show-cerrar-confirmation', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta acción no se puede revertir",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Cerrar Ticket"
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('hola');
                        Livewire.dispatch('cerrarTicket');
                    }
                });
            });

            Livewire.on('ticketCerrado', () => {
                Swal.fire({
                    toast: true, // Activar estilo toast
                    position: 'top-end', // Arriba derecha
                    icon: 'error', // Icono de éxito
                    title: 'Ticket Cerrado', // Texto
                    showConfirmButton: false, // Sin botón de OK
                    timer: 3000, // Duración 3 segundos
                    timerProgressBar: true // Barra de progreso
                });
            });
        </script>
    </div>
