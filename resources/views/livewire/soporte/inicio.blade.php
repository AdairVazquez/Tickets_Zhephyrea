<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video bg-green-500 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center">
                <h1 class="text-white text-2xl font-bold">TICKETS ABIERTOS</h1>
                <span class="text-white text-4xl font-semibold mt-2">{{ $tickets_abiertos }}</span>
            </div>

            <div
                class="relative aspect-video bg-yellow-500 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center">
                <h1 class="text-white text-2xl font-bold">TICKETS EN CURSO</h1>
                <span class="text-white text-4xl font-semibold mt-2">{{ $tickets_proceso }}</span>
            </div>
            <div
                class="relative aspect-video bg-red-500 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center">
                <h1 class="text-white text-2xl font-bold">TICKETS CERRADOS</h1>
                <span class="text-white text-4xl font-semibold mt-2">{{ $tickets_cerrados }}</span>
            </div>
        </div>
        <div class="relative h-100 flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="grid auto-rows-min gap-6 md:grid-cols-2">
            <div
                class="relative aspect-video bg-green-500 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center">
                <h1 class="text-white text-2xl font-bold">TICKETS ABIERTOS</h1>
                <span class="text-white text-4xl font-semibold mt-2">{{ $tickets_abiertos }}</span>
            </div>
        </div>
        </div>
    </div>
</div>
