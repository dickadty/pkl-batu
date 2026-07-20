@php
$heroSlides = [
    [
        'image' => asset('assets/img/banner/1001824500.jpg'),
        'alt' => 'PPID Kota Batu',
        'title' => ' <span class="text-yellow-400">SELAMAT DATANG</span> <br> di PPID Kota Batu',
        'description' => 'Layanan informasi publik yang terbuka, cepat, dan transparan untuk seluruh masyarakat Kota Batu.',
        'primary_link' => '#',
        'primary_text' => 'Ajukan Permohonan',
        'secondary_link' => '#',
        'secondary_text' => 'Lihat Informasi Publik',
    ],
    [
        'image' => asset('assets/img/banner/alunbatu.jpg'),
        'alt' => 'Kota Batu yang indah',
        'title' => 'Informasi Publik <br><span class="text-yellow-400">Lebih Mudah Diakses</span>',
        'description' => 'Temukan berbagai dokumen, kebijakan, dan layanan publik secara terbuka melalui portal PPID Kota Batu.',
        'primary_link' => '#',
        'primary_text' => 'Lihat Dokumen',
        'secondary_link' => '#',
        'secondary_text' => 'Hubungi PPID',
    ],
    [
        'image' => asset('assets/img/banner/balai-kota-batu.jpg'),
        'alt' => 'Transparansi pemerintah',
        'title' => 'Transparansi Pemerintah <br><span class="text-yellow-400">Untuk Semua Warga</span>',
        'description' => 'Dukung keterbukaan informasi dengan mengajukan permohonan, mengakses data publik, dan mengikuti perkembangan layanan.',
        'primary_link' => '#',
        'primary_text' => 'Ajukan Keberatan',
        'secondary_link' => '#',
        'secondary_text' => 'Baca Kebijakan',
    ],
];
@endphp

<section class="relative min-h-[300px] overflow-hidden pb-28 md:pb-36" id="hero-slider" aria-label="Hero slider">
    <div class="absolute inset-0">
        @foreach($heroSlides as $index => $slide)
            <div class="hero-slide absolute inset-0 transition-all duration-7000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                data-hero-slide="{{ $index }}">
                <img src="{{ $slide['image'] }}"
                    alt="{{ $slide['alt'] }}"
                    class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-[#033927]/60 via-[#033927]/30 to-[#033927]/80"></div>
            </div>
        @endforeach
    </div>

    <div class="relative z-20 mx-auto flex min-h-[500px] max-w-4xl flex-col items-center justify-center px-6 py-20 text-center sm:px-8">
        @foreach($heroSlides as $index => $slide)
            <div class="hero-content absolute inset-x-0 flex flex-col items-center px-4 transition-all duration-3000 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $index === 0 ? 'translate-y-0 opacity-100 pointer-events-auto' : 'translate-y-8 opacity-0 pointer-events-none' }}"
                data-hero-content="{{ $index }}">
                <!-- Subtle dark overlay behind text for better contrast -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl rounded-2xl bg-gradient-to-b from-black/40 to-black/50 backdrop-blur-sm pointer-events-none z-0"></div>
                <h1 class="mx-auto max-w-3xl text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
                    <span class="relative z-10">{!! $slide['title'] !!}</span>
                </h1>

                <p class="mx-auto mt-6 max-w-xl text-base leading-relaxed text-slate-100/90 sm:text-lg relative z-50">
                    {{ $slide['description'] }}
                </p>

                <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ $slide['primary_link'] }}"
                        class="inline-flex items-center gap-2 rounded-3xl bg-green-700 px-6 py-3 font-medium text-white shadow-lg  hover:bg-green-600 relative z-10">
                        {{ $slide['primary_text'] }}
                    </a>

                    <a href="{{ $slide['secondary_link'] }}"
                        class="inline-flex items-center gap-2 rounded-3xl bg-green-700 px-6 py-3 font-medium text-white shadow-lg  hover:bg-green-600 relative z-10">
                        {{ $slide['secondary_text'] }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button"
        class="absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full border border-white/30 bg-white/10 p-3 text-white backdrop-blur-sm transition hover:bg-white/20"
        id="hero-next"
        aria-label="Slide berikutnya">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
</section>
