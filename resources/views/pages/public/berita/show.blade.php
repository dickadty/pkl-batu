@extends('layouts.public')

@section('title', $berita->judul . ' | PPID Kota Batu')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @if ($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}"
                    class="w-full h-80 object-cover">
            @endif

            <div class="p-6">
                <div class="text-sm text-slate-500 mb-3">
                    {{ $berita->tanggal ? date('d M Y', $berita->tanggal) : '-' }}
                </div>

                <h1 class="text-3xl font-bold text-slate-900 mb-6">
                    {{ $berita->judul }}
                </h1>

                <div class="text-slate-700 leading-7">
                    {!! nl2br(e($berita->caption)) !!}
                </div>

                <div class="mt-8">
                    <a href="{{ route('public.berita.index') }}"
                        class="inline-flex px-5 py-2 rounded-lg bg-slate-100 text-slate-700 font-medium hover:bg-slate-200">
                        Kembali ke Berita
                    </a>
                </div>
            </div>
        </article>
    </section>
@endsection
