<article class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-md">

                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                        class="w-full aspect-[16/9] object-cover">
                @endif

                <div class="p-5">

                    <div class="text-sm text-slate-500 mb-2">
                        {{ date('d M Y', $item->tanggal) }}
                    </div>

                    <h3 class="mb-3 line-clamp-2 text-lg font-bold text-slate-700 transition duration-200 group-hover:text-green-600">
                        {{ $item->judul }}
                    </h3>

                    <p class="text-slate-600 text-sm line-clamp-3 mb-5">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->caption), 120) }}
                    </p>

                    <a href="{{ route('public.berita.show', $item->id) }}"
                        class="font-semibold text-slate-700 transition duration-200 group-hover:text-green-600">
                        Baca Selengkapnya →
                    </a>

                </div>

            </article>