@extends('layouts.public.app')

@section('title', $ppidPembantu->nama . ' | PPID Kota Batu')

@push('styles')
    <style>
        main {
            padding-top: 5rem !important;
        }
    </style>
@endpush

@section('content')
    @php
        $websiteUrl = null;

        if (!empty($ppidPembantu->linkweb)) {
            $websiteUrl = Str::startsWith($ppidPembantu->linkweb, ['http://', 'https://'])
                ? $ppidPembantu->linkweb
                : 'https://' . ltrim($ppidPembantu->linkweb, '/');
        }

        $phone = !empty($ppidPembantu->telp)
            ? preg_replace('/[^0-9+]/', '', (string) $ppidPembantu->telp)
            : null;
    @endphp

    <section class="relative overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">
        <div class="absolute inset-0">
            <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-6xl px-6 pb-20 pt-14 text-center sm:px-8 lg:px-10">
            <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
                {{ $ppidPembantu->kategoriPpid?->kategori ?? 'PPID' }}
            </span>

            <h1 class="mx-auto mt-4 max-w-3xl text-xl font-bold leading-tight text-white sm:text-2xl md:text-3xl">
                {{ $ppidPembantu->nama }}
            </h1>

            <p class="mx-auto mt-3 max-w-2xl text-[11px] leading-5 text-green-100 sm:text-xs md:text-sm">
                Profil unit PPID pelaksana Kota Batu yang menjadi mitra utama dalam penyediaan informasi publik.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
            <svg class="relative block h-20 w-full" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z" class="fill-white"></path>
            </svg>
        </div>
    </section>

    <section class="relative z-30 -mt-18 pb-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-4 md:px-6">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700">Data PPID Pelaksana</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-800">Informasi lengkap unit</h2>
                </div>

                <dl>
slate-700">{{ $ppidPembantu->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</dt>
                        <dd class="mt-1 text-slate-700">{{ $ppidPembantu->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori PPID</dt>
                        <dd class="mt-1 text-slate-700">{{ $ppidPembantu->kategori0">{{ $ppidPembantu-l text-slate-700">{{ $ppidPembantu->slug ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Website</dt>
                        <dd class="mt-1 break-all text-slate-700">
                            @if ($websiteUrl)
                                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline">{{ $ppidPembantu->linkweb }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Telepon</dt>
                        <dd class="mt-1 text-slate-700">{{ $ppidPembantu->telp ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</dt>
                        <dd class="mt-1 text-slate-700">{{ $ppidPembantu->alamat ?? '-' }}</dd>
                    </div>
                    <Logo
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ikon</dt>
                        <dd class="mt-1 text-slate-700">{{ $ppidPembantu->icon ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keterangan</dt>
                        <dd class="mt-1 leading-6 text-slate-700">{{ $ppidPembantu->keterangan ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-1 border-b border-slate-200 px-4 py-4 md:px-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700">Informasi Publik</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-800">Dokumentasi {{ $ppidPembantu->nama }}</h2>
                    </div>
                    <span class="text-xs text-slate-500">{{ $ppidPembantu->dokumentasi->count() }} dokumen</span>
                </div>

                @if ($ppidPembantu->dokumentasi->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Judul Informasi</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Kategori</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Sifat</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Tahun</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal Upload</th>
                                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($ppidPembantu->dokumentasi as $dokumen)
                                    @php
                                        $tanggalUpload = $dokumen->tanggal
                                            ? (is_numeric($dokumen->tanggal)
                                                ? \Carbon\Carbon::createFromTimestamp((int) $dokumen->tanggal)->translatedFormat('d F Y')
                                                : \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y'))
                                            : '-';
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-4 align-top">
                                            <div class="font-semibold text-slate-800">{{ $dokumen->nama }}</div>
                                            <div class="mt-1 max-w-xl text-xs leading-5 text-slate-500">{{ $dokumen->ringkasan ?: 'Belum terdapat ringkasan dokumen.' }}</div>
                                        </td>
                                        <td class="px-4 py-4 align-top text-sm text-slate-600">{{ $dokumen->kategori?->nama ?? 'Tanpa Kategori' }}</td>
                                        <td class="px-4 py-4 align-top">
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-800">
                                                {{ str_replace('_', ' ', ucfirst(str_replace('-', ' ', $dokumen->kategori?->sifat ?? 'berkala'))) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 align-top text-sm text-slate-600">{{ $dokumen->tahun ?? '-' }}</td>
                                        <td class="px-4 py-4 align-top text-sm text-slate-600">{{ $tanggalUpload }}</td>
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('public.informasi.show', $dokumen->slug) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-green-700 hover:text-green-700" title="Lihat detail" aria-label="Lihat detail {{ $dokumen->nama }}">
                                                    <i class="ri-eye-line" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('public.informasi.download', $dokumen->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-800 text-white transition hover:bg-green-950" title="Download dokumen" aria-label="Download {{ $dokumen->nama }}">
                                                    <i class="ri-download-2-line" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="px-4 py-10 text-center text-sm text-slate-500 md:px-6">Belum ada informasi publik terverifikasi yang diunggah oleh unit ini.</p>
                @endif
            </div>
        </div>
    </section>
@endsection
