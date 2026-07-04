@extends('layouts.public')

@section('title', 'Berita | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-slate-900">
                Berita PPID Kota Batu
            </h1>

            <p class="mt-3 text-slate-600 max-w-2xl">
                Informasi terbaru terkait layanan, kegiatan, dan publikasi PPID Kota Batu.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid md:grid-cols-3 gap-6">
            @forelse ($berita as $item)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    @if ($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-slate-200 flex items-center justify-center text-slate-500">
                            Tidak ada gambar
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="text-sm text-slate-500 mb-2">
                            {{ $item->tanggal ? date('d M Y', $item->tanggal) : '-' }}
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 mb-3">
                            {{ $item->judul }}
                        </h2>

                        <p class="text-sm text-slate-600 mb-5">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->caption), 120) }}
                        </p>

                        <a href="{{ route('public.berita.show', $item->id) }}"
                            class="inline-flex px-4 py-2 rounded-lg bg-blue-700 text-white text-sm font-medium hover:bg-blue-800">
                            Baca Berita
                        </a>
                    </div>
                </article>
            @empty
                <div class="md:col-span-3 bg-white border border-slate-200 rounded-xl p-8 text-center">
                    <h2 class="text-lg font-semibold text-slate-900">
                        Belum ada berita.
                    </h2>

                    <p class="text-slate-600 mt-2">
                        Berita akan tampil setelah admin menambahkan data berita.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
