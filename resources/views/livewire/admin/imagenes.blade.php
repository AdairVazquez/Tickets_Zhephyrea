<div class="flex flex-col gap-4 w-full h-screen overflow-hidden rounded-xl">
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="p-6">

            <div class="flex mb-3">
                <h1 class="text-2xl font-bold mb-3 ml-1">LISTA DE IMAGENES</h1>
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
                                <a href="{{asset('storage/'.$imagen->ruta_archivo)}}"target="_blank">Ver imagen</a>
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
@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>