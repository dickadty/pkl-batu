@extends('layouts.public.app')

@section('title', 'Informasi Publik | PPID Kota Batu')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-r from-blue-700 to-sky-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <nav class="text-sm text-blue-100 mb-3">
            Beranda
            <span class="mx-2">/</span>
            Informasi Publik
        </nav>

        <h1 class="text-4xl font-bold">
            Daftar Informasi Publik
        </h1>

        <p class="mt-4 max-w-3xl text-blue-100">
            Seluruh informasi publik yang telah diverifikasi oleh PPID Kota Batu
            dapat diakses dan diunduh oleh masyarakat sebagai bentuk keterbukaan informasi.
        </p>

        <div class="mt-8 flex flex-wrap gap-6">

            <div class="bg-white/10 backdrop-blur rounded-xl px-6 py-4">
                <p class="text-3xl font-bold">{{ $dokumentasi->count() }}</p>
                <p class="text-sm text-blue-100">Dokumen</p>
            </div>

        </div>

    </div>
</section>

{{-- FILTER --}}
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="grid md:grid-cols-3 gap-4">

            <input
                type="text"
                placeholder="Cari informasi..."
                class="rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">

            <select
                class="rounded-lg border border-slate-300 px-4 py-3">
                <option>Semua Tahun</option>
            </select>

            <select
                class="rounded-lg border border-slate-300 px-4 py-3">
                <option>Semua Kategori</option>
            </select>

        </div>

    </div>
</section>

{{-- LIST --}}
<section class="bg-slate-50 py-12">

<div class="max-w-7xl mx-auto px-4">

    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">

        @forelse($dokumentasi as $item)

        <article
            class="bg-white rounded-2xl border border-slate-200 hover:border-blue-500 hover:shadow-xl transition duration-300 flex flex-col">

            <div class="p-6 flex-1">

                {{-- Badge --}}
                <div class="flex justify-between items-start">

                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                        {{ $item->sifat ?? 'Informasi Publik' }}
                    </span>

                    <span class="text-sm text-slate-500">
                        {{ $item->tahun }}
                    </span>

                </div>

                {{-- Judul --}}
                <h2 class="mt-5 text-xl font-bold text-slate-800 line-clamp-2">
                    {{ $item->nama }}
                </h2>

                {{-- Ringkasan --}}
                <p class="mt-4 text-slate-600 text-sm line-clamp-3">
                    {{ $item->ringkasan ?? 'Belum terdapat ringkasan informasi.' }}
                </p>

                {{-- Info --}}
                <div class="mt-6 space-y-3 text-sm text-slate-600">

                    <div class="flex items-center gap-2">
                        📁
                        <span>{{ $item->ppidPembantu->nama ?? '-' }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        📅
                        <span>{{ $item->tahun }}</span>
                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t p-5 flex gap-3">

                <a
                    href="{{ route('public.informasi.show',$item->slug) }}"
                    class="flex-1 text-center rounded-lg bg-blue-700 hover:bg-blue-800 text-white py-2.5 font-medium transition">

                    Detail

                </a>

                <a
                    href="{{ route('public.informasi.download',$item->id) }}"
                    class="flex-1 text-center rounded-lg border border-slate-300 hover:bg-slate-100 py-2.5 font-medium">

                    Download

                </a>

            </div>

        </article>

        @empty

        <div class="col-span-full">

            <div class="bg-white rounded-2xl p-12 border text-center">

                <svg class="mx-auto w-20 h-20 text-slate-300"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>

                </svg>

                <h3 class="mt-6 text-xl font-semibold">
                    Belum Ada Informasi Publik
                </h3>

                <p class="text-slate-500 mt-2">
                    Informasi yang telah diverifikasi akan ditampilkan di halaman ini.
                </p>

            </div>

        </div>

        @endforelse

    </div>

</div>

</section>

@endsection