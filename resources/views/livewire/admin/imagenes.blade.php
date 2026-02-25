<div>
    <div class="flex flex-col gap-4 w-full h-screen overflow-hidden rounded-xl">
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

            <div class="p-6">
                <div class="flex mb-3">
                    <h1 class="text-4xl font-bold mb-3 ml-1">Imagenes subidas</h1>
                    <button wire:click="crearImagen"
                        class="ml-auto bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <flux:icon name="plus" variant="outline" class="inline-block w-5 h-5 mr-1" />
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">

                    <div class="p-2">
                        {{ $imagenes->links() }}
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-green-50 dark:bg-green-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Fecha de subida
                                </th>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Archivo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($imagenes->isEmpty())
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="3">
                                    Lastima, no hay imagenes disponibles ahora
                                </td>
                            </tr>
                            @else
                            @foreach ($imagenes as $imagen)
                            <tr>
                                <td class="px-6 py-4">
                                    {{ $imagen->id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $imagen->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $imagen->descripcion }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $imagen->fecha_subida }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{asset('storage/'.$imagen->ruta_archivo)}}" target="_blank">Ver imagen</a>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" wire:click="editarImagen({{ $imagen->id }})"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 inline-flex items-center">
                                        <flux:icon name="image-plus" variant="outline" class="w-5 h-5 mr-1" />
                                        Editar
                                    </button>
                                    <button wire:click="confirmDelete({{ $imagen->id }})"
                                        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 shadow-sm transition-colors">
                                        <flux:icon name="trash" variant="outline" class="inline-block w-5 h-5 mr-1" />
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($mostrarFormulario)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="w-full max-w-4xl max-h-[92vh] overflow-y-auto rounded-xl bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 shadow-2xl">
            <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
                <h2 class="text-lg font-semibold">{{ $modoFormulario === 'editar' ? 'Editar imagen' : 'Crear imagen' }}</h2>
                <button type="button" wire:click="cerrarFormularioImagen"
                    class="px-3 py-1.5 rounded-lg bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600">
                    Cerrar
                </button>
            </div>
            <div class="p-4">
                @livewire('cliente.crear-o-editar-imagen', ['id' => $imagenSeleccionadaId, 'modo' => $modoFormulario], key('crear-o-editar-imagen-'.$modoFormulario.'-'.$imagenSeleccionadaId))
            </div>
        </div>
    </div>
    @endif
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('show-delete-confirmation', () => {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede revertir",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, elimínalo!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deletePost');
                }
            });
        });

        Livewire.on('imagenEliminada', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Imagen eliminada',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });

        Livewire.on('imagenEliminadaError', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'No se pudo eliminar la imagen',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
