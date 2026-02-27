<div class="p-6 min-h-screen">
    <div class="flex flex-col gap-4 w-full">
        <div class="relative rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Imagenes subidas</h1>
                    <button type="button" wire:click="crearImagen"
                        class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors flex items-center">
                        <flux:icon name="plus" variant="outline" class="w-5 h-5 mr-2" />
                        Nueva Imagen
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descripcion</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Archivo</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($imagenes as $imagen)
                            <tr wire:key="fila-{{ $imagen->id }}" class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $imagen->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $imagen->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $imagen->descripcion}}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $imagen->estado->nombre_estado}}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $imagen->fecha_subida}}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ asset('storage/' . $imagen->ruta_archivo) }}" target="_blank"
                                        class="text-blue-600 hover:underline inline-flex items-center">
                                        <flux:icon name="eye" variant="outline" class="w-4 h-4 mr-1" /> Ver
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button" wire:click="editarImagen({{ $imagen->id }})"
                                        class="p-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400">
                                        <flux:icon name="pencil-square" variant="outline" class="w-5 h-5" />
                                    </button>
                                    <button type="button" wire:click="confirmDelete({{ $imagen->id }})"
                                        class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400">
                                        <flux:icon name="trash" variant="outline" class="w-5 h-5" />
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-gray-500" colspan="6">No hay imagenes.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $imagenes->links() }}</div>
            </div>
        </div>
    </div>

    <div class="{{ $mostrarFormulario ? 'fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4' : 'hidden' }}">
        <div class="w-full max-w-4xl max-h-[92vh] overflow-y-auto rounded-xl bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 shadow-2xl">
            <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
                <h2 class="text-lg font-semibold">{{ $modoFormulario === 'editar' ? 'Editar imagen' : 'Crear imagen' }}</h2>
                <button type="button" wire:click="cerrarFormularioImagen"
                    class="px-3 py-1.5 rounded-lg bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600">
                    Cerrar
                </button>
            </div>
            <div class="p-4">
                @livewire('cliente.crear-o-editar-imagen', [], key('crear-o-editar-imagen'))
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-delete-confirmation', () => {
            Swal.fire({
                title: "¿Estas seguro?",
                text: "Se eliminara el archivo permanentemente.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Si, eliminar"
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
                timer: 2500
            });
        });

        Livewire.on('imagenEliminadaError', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'No se pudo eliminar la imagen',
                showConfirmButton: false,
                timer: 2500
            });
        });
    });
</script>
@endpush
@endonce