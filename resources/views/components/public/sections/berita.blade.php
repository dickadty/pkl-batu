<section class="bg-white py-5 lg:py-16">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Main Container --}}
        <div
            class="rounded-2xl border0 bg-white px-5 py-6 shadow-sm sm:px-7 lg:px-9 lg:py-8">

            {{-- Header --}}
            <div class="mb-6 flex items-end justify-between border-b pb-5">

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 lg:text-3xl">
                        Berita Kota Batu
                    </h2>

                    <span class="mt-3 block h-1 w-14 rounded-full bg-emerald-600"></span>
                </div>

                {{-- Lihat Semua --}}
                <a href="{{ route('public.berita.index') }}"
                    class="hidden shrink-0 items-center gap-2 rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-600 hover:text-emerald-700 md:flex">

                    Lihat Semua

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>

                </a>

            </div>

            {{-- Berita --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">

                @foreach($berita as $item)

                    @include('components.public.berita.card', [
                        'item' => $item
                    ])

                @endforeach

            </div>

            {{-- Mobile Button --}}
            <div class="mt-6 text-center md:hidden">

                <a href="{{ route('public.berita.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-600 hover:text-emerald-700">

                    Lihat Semua

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>

                </a>

            </div>

        </div>

    </div>

</section>