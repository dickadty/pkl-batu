@extends('layouts.public')

@section('title', $dokumen->nama . ' | PPID Kota Batu')

@section('content')

{{-- Header --}}
<section class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <p class="text-sm text-slate-500 mb-2">
            Detail Informasi Publik
        </p>

        <h1 class="text-3xl font-bold text-slate-900 mb-6">
            {{ $dokumen->nama }}
        </h1>

        <div class="grid md:grid-cols-2 gap-4 text-sm text-slate-700">

            <div>
                <span class="font-semibold">PPID:</span>
                {{ $dokumen->ppidPembantu->nama ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Tahun:</span>
                {{ $dokumen->tahun ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Sifat Informasi:</span>
                {{ $dokumen->sifat ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Tanggal Upload:</span>
                {{ $dokumen->tanggal
                    ? date('d F Y', $dokumen->tanggal)
                    : '-' }}
            </div>

        </div>

    </div>
</section>

{{-- Konten --}}
<section class="bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Ringkasan --}}
            <div class="p-6 border-b border-slate-200">

                <h2 class="text-xl font-semibold text-slate-900 mb-3">
                    Ringkasan
                </h2>

                <p class="text-slate-700 leading-relaxed">
                    {{ $dokumen->ringkasan ?? 'Tidak ada ringkasan.' }}
                </p>

            </div>

            {{-- Tombol --}}
            <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap gap-3">

                <a href="{{ route('public.informasi.download', $dokumen->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700 transition">

                    <svg class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v10m0 0l-4-4m4 4l4-4M4 20h16" />
                    </svg>

                    Download File

                </a>

            </div>

            {{-- Preview Dokumen --}}
            <div class="p-6">

                @if($dokumen->file)

                    <iframe
                        src="{{ Storage::url($dokumen->file) }}"
                        width="100%"
                        height="800"
                        class="rounded-lg border border-slate-200">
                    </iframe>

                @else

                    <div class="text-center py-12 text-slate-500">
                        Tidak ada file yang tersedia.
                    </div>

                @endif

            </div>

        </div>

    </div>
</section>

@endsection