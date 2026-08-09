@php
    $heroSlides = [
        [
            'image' => asset('assets/img/banner/alunbatu.jpg'),
            'alt'   => 'PPID Kota Batu',
        ],
        [
            'image' => asset('assets/img/banner/alunbatu.jpg'),
            'alt'   => 'Kota Batu',
        ],
        [
            'image' => asset('assets/img/banner/balai-kota-batu.jpg'),
            'alt'   => 'Balai Kota Batu',
        ],
    ];
@endphp

<section class="relative overflow-hidden pb-24 pt-28 lg:pb-28">

    {{-- Background Slider --}}
    <div class="absolute inset-0">
        @foreach ($heroSlides as $index => $slide)
            <div
                class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                data-slide="{{ $index }}"
            >
                <img
                    src="{{ $slide['image'] }}"
                    alt="{{ $slide['alt'] }}"
                    class="h-full w-full object-cover"
                >
            </div>
        @endforeach

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-950/100 via-black/90 to-white/10"></div>
    </div>

    {{-- Hero Content --}}
    <div class="relative z-20 mx-auto flex max-w-6xl items-center px-6 lg:min-h-[80vh] lg:px-8">
        <div class="max-w-3xl">

            <h1 class="mt-6 text-4xl font-bold leading-tight text-white">
                Keterbukaan Informasi
                <br>
                Publik Yang Transparan
            </h1>

            <p class="mt-6 max-w-2xl text-base leading-relaxed text-slate-200 ">
                Layanan informasi publik yang cepat, mudah diakses, <br>
                dan transparan bagi seluruh masyarakat Kota Batu.
            </p>

            <div class="mt-10 flex items-center gap-5">
                
                <a href="{{ route('public.permohonan.create') }}"
                    class="rounded-full px-8 py-4 font-semibold text-white bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 transition hover:via-emerald-600 hover:to-emerald-900 text-[0.875rem]">
                    Ajukan Permohonan
                </a>
            </div>

        </div>
    </div>

    {{-- Slider Controls --}}
    <div class="absolute right-6 top-1/2 z-30 hidden -translate-y-1/2 flex-col gap-3 lg:flex">
        <button
            type="button"
            id="hero-next"
            class="rounded-full border border-white/20 bg-white/10 p-4 text-white backdrop-blur-md transition hover:bg-white/20"
            aria-label="Slide berikutnya">
        </button>
    </div>

    {{-- Slide Indicators --}}
    <div class="absolute bottom-32 left-1/2 z-30 flex -translate-x-1/2 gap-2 lg:bottom-40 lg:left-16 lg:translate-x-0">
        @foreach ($heroSlides as $index => $slide)
            <span
                class="hero-dot h-1.5 rounded-full bg-white/40 transition-all duration-500 {{ $index === 0 ? 'w-8 bg-white' : 'w-4' }}"
                data-dot="{{ $index }}"
            ></span>
        @endforeach
    </div>

</section>