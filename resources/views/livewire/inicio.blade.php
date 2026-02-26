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
        <div class="card-body">
            {{-- Wire:ignore evita que Livewire reinicie el carrusel al refrescar los contadores de tickets --}}
            <div wire:ignore id="carouselPersonalizado" class="inicio-carousel" data-interval="5000">
                <div class="inicio-carousel__track" data-carousel-track>
                    @forelse($imagenes as $imagen)
                    <div class="inicio-carousel__slide {{ $loop->first ? 'is-active' : '' }}" data-carousel-slide>
                        <img src="{{ asset('storage/'.$imagen->ruta_archivo) }}" alt="Banner {{ $loop->iteration }}">
                    </div> 
                    @empty
                    <div class="inicio-carousel__slide is-active" data-carousel-slide>
                        <img src="{{ asset('img/default-banner.jpg') }}" alt="Banner por defecto">
                    </div>
                    @endforelse
                </div>

                @if($imagenes->count() > 1)
                    <button class="inicio-carousel__control prev" type="button" data-carousel-prev aria-label="Anterior">
                        &#10094;
                    </button>
                    <button class="inicio-carousel__control next" type="button" data-carousel-next aria-label="Siguiente">
                        &#10095;
                    </button>

                    <div class="inicio-carousel__indicators" data-carousel-indicators>
                        @foreach($imagenes as $index => $imagen)
                        <button
                            type="button"
                            class="inicio-carousel__dot {{ $loop->first ? 'is-active' : '' }}"
                            data-carousel-dot
                            data-slide-index="{{ $index }}"
                            aria-label="Ir al slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:navigated', initInicioCarousel);
        document.addEventListener('DOMContentLoaded', initInicioCarousel);

        function initInicioCarousel() {
            const carousel = document.getElementById('carouselPersonalizado');
            if (!carousel || carousel.dataset.initialized === 'true') {
                return;
            }

            const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
            const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));
            const prevBtn = carousel.querySelector('[data-carousel-prev]');
            const nextBtn = carousel.querySelector('[data-carousel-next]');
            const intervalMs = Number(carousel.dataset.interval) || 5000;

            if (slides.length <= 1) {
                carousel.dataset.initialized = 'true';
                return;
            }

            let currentIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
            currentIndex = currentIndex >= 0 ? currentIndex : 0;
            let timer = null;

            const showSlide = (index) => {
                slides[currentIndex]?.classList.remove('is-active');
                dots[currentIndex]?.classList.remove('is-active');

                currentIndex = (index + slides.length) % slides.length;

                slides[currentIndex]?.classList.add('is-active');
                dots[currentIndex]?.classList.add('is-active');
            };

            const startAutoSlide = () => {
                stopAutoSlide();
                timer = setInterval(() => showSlide(currentIndex + 1), intervalMs);
            };

            const stopAutoSlide = () => {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            };

            prevBtn?.addEventListener('click', () => {
                showSlide(currentIndex - 1);
                startAutoSlide();
            });

            nextBtn?.addEventListener('click', () => {
                showSlide(currentIndex + 1);
                startAutoSlide();
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    startAutoSlide();
                });
            });

            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);

            startAutoSlide();
            carousel.dataset.initialized = 'true';
        }
    </script>
    @endpush
</div>
