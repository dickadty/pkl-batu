<article
    class="group flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300"
>

    {{-- Gambar --}}
    @if ($item->gambar)
        <div class="overflow-hidden rounded-lg border border-slate-100 bg-slate-50">
            <img
                src="{{ asset('storage/' . $item->gambar) }}"
                alt="{{ $item->judul }}"
                class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105"
            >
        </div>
    @endif

    {{-- Content --}}
    <div class="flex flex-1 flex-col px-1.5 pb-3 pt-3">

        {{-- Tanggal --}}
        <div class="mb-2">
            <span
                class="inline-flex rounded-full border border-emerald-700/20 bg-emerald-900 px-2.5 py-0.5 text-[10px] font-semibold text-white"
            >
                {{ date('d M Y', $item->tanggal) }}
            </span>
        </div>

        {{-- Judul --}}
        <h3
            class="line-clamp-3 text-xs font-bold leading-5 text-slate-800"
        >
            {{ $item->judul }}
        </h3>

        {{-- Baca --}}
        <a
            href="{{ route('public.berita.show', $item->id) }}"
            class="mt-auto inline-flex items-center pt-3 text-[11px] font-semibold text-green-800 transition-colors duration-300 hover:text-emerald-600"
        >
            Baca Lebih Lanjut

            <span>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="ml-1 h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        </a>

    </div>

</article>