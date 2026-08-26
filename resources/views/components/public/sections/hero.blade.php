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
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-950/100 via-black/50 to-white/10"></div>
    </div>
{{-- Hero Content --}}
<div class="relative z-20 mx-auto flex min-h-[80vh] max-w-6xl items-center justify-center px-6 text-center lg:px-8">
    <div class="max-w-2xl">

        <h1 class="mt-6 text-3xl font-bold leading-tight text-white md:text-4xl lg:text-5xl">
            Keterbukaan Informasi
            <br>
            Publik Yang Transparan
        </h1>

        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-slate-200 md:text-base">
            Layanan informasi publik yang cepat, mudah diakses,
            dan transparan bagi seluruh masyarakat Kota Batu.
        </p>

        {{-- Buttons --}}
        <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">

            {{-- Ajukan Permohonan --}}
            <a href="{{ route('public.permohonan.create') }}"
                class="rounded-full bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 px-6 py-3 text-xs font-semibold text-white shadow-lg transition hover:via-emerald-600 hover:to-emerald-900">
                Ajukan Permohonan
            </a>

            {{-- Ajukan Keberatan --}}
            <a href="{{ route('public.pesan.create') }}"
                class="rounded-full border border-white/40 bg-white/10 px-6 py-3 text-xs font-semibold text-white backdrop-blur-sm transition hover:bg-white hover:text-green-950">
                Layanan Pesan
            </a>

        </div>

    </div>
</div>

{{-- Wave Bottom --}}
<div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-[0]">
    <svg
        class="relative block h-[80px] w-full"
        viewBox="0 0 1200 120"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z"
            class="fill-white"
        ></path>
    </svg>
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