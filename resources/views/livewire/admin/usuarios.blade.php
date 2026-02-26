<div>
    <div class="flex flex-col gap-4 w-full h-screen overflow-hidden rounded-xl">
        <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

            <div class="p-6">
                <div class="flex mb-3">
                    <h1 class="text-4xl font-bold mb-3 ml-1">Usuarios registrados</h1>
                    <button wire:click="mostrarCrear"
                        class="ml-auto bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Crear usuario
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="mt-4">
                        {{ $usuarios->links() }}
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Rol
                                </th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">

                            @foreach ($usuarios as $usuario)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-800"
                                    wire:key='usuario-{{ $usuario->id }}'>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $usuario->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $usuario->email }} </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $usuario->rol->nombre_rol }} </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button href="#" class="text-blue-600 dark:text-blue-400 hover:underline"
                                            wire:click='edit({{ $usuario->id }})'>Editar</button>
                                        <button href="#" wire:click="confirmDelete({{ $usuario->id }})"
                                            class="text-blue-600 dark:text-blue-400 hover:underline ml-4"
                                            style="color: red">Eliminar</button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL CREAR USUARIO --}}
    @if ($openCrear)
        <!-- Fondo semi-transparente -->
        <div class="fixed inset-0 bg-black bg-opacity-10 flex items-center justify-center z-50">

            <!-- Contenedor del modal -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-xl p-6 relative">

                <!-- Cerrar -->
                <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                    wire:click="cerrarCrear">
                    ✕
                </button>

                <!-- Contenido -->
                <form wire:submit.prevent="save" class="space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" id="name" wire:model="name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" wire:model="email"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rol (select) -->
                    <div>
                        <label for="rol"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                        <select id="rol" wire:model="rol_id"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option value="" disabled>Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                        @error('rol_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Empresa -->
                    <div>
                        <label for="empresa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Empresa
                        </label>
                        <select id="empresa" wire:model.live="empresa_id"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option value="" disabled>Selecciona la empresa</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre_empresa }}</option>
                            @endforeach
                            <option value="otro" class="font-bold text-blue-600 italic">+ Otro (Crear nueva)</option>
                        </select>
                        @error('empresa_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($empresa_id === 'otro')
                        <div class="mt-3 p-4 bg-blue-50 dark:bg-gray-800 border border-blue-200 dark:border-blue-900 rounded-lg">
                            <label for="nueva_empresa" class="block text-sm font-medium text-blue-800 dark:text-blue-400">
                                Nombre de la nueva empresa
                            </label>
                            <input type="text" id="nueva_empresa" wire:model="nueva_empresa"
                                placeholder="Ej. Mi Nueva Empresa S.A."
                                class="mt-1 block w-full px-4 py-2 border border-blue-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            @error('nueva_empresa')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Contraseña -->
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                        <input type="password" wire:model="password"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirma la
                            contraseña</label>
                        <input type="password" wire:model="password_confirmation"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('password')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="cerrarCrear"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Guardar
                        </button>
                    </div>
                </form>

                <!-- Botones -->

            </div>
        </div>
    @elseif ($openEdit)
        <!-- Fondo semi-transparente -->
        <div class="fixed inset-0 bg-black bg-opacity-10 flex items-center justify-center z-50">

            <!-- Contenedor del modal -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-xl p-6 relative">

                <!-- Cerrar -->
                <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                    wire:click="cerrarEdit">
                    ✕
                </button>

                <!-- Contenido -->
                <form wire:submit.prevent="update" class="space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" id="name" wire:model="user_edit.name"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" wire:model="user_edit.email"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rol (select) -->
                    <div>
                        <label for="rol"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                        <select id="rol" wire:model="user_edit.id_rol"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                        @error('rol_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Botones -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="cerrarEdit"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Actualizar
                        </button>
                    </div>
                </form>

                <!-- Botones -->

            </div>
        </div>
    @endif



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.addEventListener('usuarioCreado', () => {
            Swal.fire({
                toast: true, // Activar estilo toast
                position: 'top-end', // Arriba derecha
                icon: 'success', // Icono de éxito
                title: 'Usuario creado', // Texto
                showConfirmButton: false, // Sin botón de OK
                timer: 3000, // Duración 3 segundos
                timerProgressBar: true // Barra de progreso
            });
        });
        window.addEventListener('usuarioActualizado', () => {
            Swal.fire({
                toast: true, // Activar estilo toast
                position: 'top-end', // Arriba derecha
                icon: 'success', // Icono de éxito
                title: 'Usuario Actualizado', // Texto
                showConfirmButton: false, // Sin botón de OK
                timer: 3000, // Duración 3 segundos
                timerProgressBar: true // Barra de progreso
            });
        });
        // Escucha evento emitido desde Livewire
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

        Livewire.on('usuarioEliminado', () => {
            Swal.fire({
                toast: true, // Activar estilo toast
                position: 'top-end', // Arriba derecha
                icon: 'error', // Icono de éxito
                title: 'Usuario eliminado', // Texto
                showConfirmButton: false, // Sin botón de OK
                timer: 3000, // Duración 3 segundos
                timerProgressBar: true // Barra de progreso
            });
        });
    </script>
</div>
