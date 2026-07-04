@extends('layouts.public')

@section('title', 'Informasi Publik | PPID Kota Batu')

@section('content')
<section class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-slate-900">
            Daftar Informasi Publik
        </h1>

        <p class="mt-3 text-slate-600 max-w-2xl">
            Masyarakat dapat melihat dan mengunduh informasi publik yang telah diverifikasi oleh PPID.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid md:grid-cols-3 gap-6">
        @forelse($dokumentasi as $item)
            <article class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="mb-4">
                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                        {{ $item->sifat ?? 'Informasi Publik' }}
                    </span>
                </div>

                <h2 class="text-lg font-bold text-slate-900 mb-3 line-clamp-2">
                    {{ $item->nama }}
                </h2>

                <div class="space-y-1 text-sm text-slate-600 mb-4">
                    <p>
                        <strong>PPID:</strong> {{ $item->ppidPembantu->nama ?? '-' }}
                    </p>
                    <p>
                        <strong>Tahun:</strong> {{ $item->tahun ?? '-' }}
                    </p>
                </div>

                <p class="text-sm text-slate-600 mb-5 line-clamp-3">
                    {{ $item->ringkasan ?? 'Tidak ada ringkasan.' }}
                </p>

                <div class="flex gap-2">
                    <a href="{{ route('public.informasi.show', $item->slug) }}"
                       class="px-4 py-2 rounded-lg bg-blue-700 text-white text-sm font-medium hover:bg-blue-800">
                        Lihat Detail
                    </a>

                    <a href="{{ route('public.informasi.download', $item->id) }}"
                       class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200">
                        Download
                    </a>
                </div>
            </article>
        @empty
            <div class="md:col-span-3 bg-white border border-slate-200 rounded-xl p-8 text-center">
                <h2 class="text-lg font-semibold text-slate-900">
                    Belum ada informasi publik.
                </h2>
                <p class="text-slate-600 mt-2">
                    Informasi akan tampil setelah diverifikasi oleh admin.
                </p>
            </div>
        @endforelse
    </div>
</section>
@endsection