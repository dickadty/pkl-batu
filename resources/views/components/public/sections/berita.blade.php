<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 pb-10">

    {{-- Heading --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-10">

        <div class="max-w-2xl">
            <h2 class="mt-2 text-4xl font-bold text-green-700">
                Berita Kota Batu
            </h2>

            <p class="mt-3 text-slate-600 leading-relaxed">
                Temukan informasi terbaru, pengumuman, kegiatan, dan berbagai
                berita yang telah dipublikasikan oleh PPID Kota Batu.
            </p>
        </div>

        <a href="{{ route('public.berita.index') }}"
            class="mt-5 md:mt-0 text-slate-800 font-semibold hover:text-green-700">
            Lihat Semua →
        </a>

    </div>

    {{-- Card --}}
    <div class="grid md:grid-cols-3 gap-6">

        @foreach ($berita as $item)

            @include('components.public.berita.card', [
                'item' => $item
            ])

        @endforeach

    </div>
    </div>

</section>