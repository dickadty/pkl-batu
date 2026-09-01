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
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-5 p-4 md:p-6 lg:grid-cols-[220px_minmax(0,1fr)]">
                    <div class="flex flex-col items-center rounded-2xl bg-slate-50 p-4 text-center ring-1 ring-slate-200">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-700 shadow-sm">
                            @if (!empty($ppidPembantu->icon))
                                <i class="{{ $ppidPembantu->icon }}" aria-hidden="true"></i>
                            @else
                                {{ strtoupper(mb_substr($ppidPembantu->nama, 0, 1)) }}
                            @endif
                        </div>

                        <div class="mt-3 w-full space-y-2 text-left">
                            <div class="rounded-xl bg-white px-3 py-2 text-[11px] text-slate-600">
                                <span class="font-semibold text-slate-700">Kategori:</span>
                                <br>
                                {{ $ppidPembantu->kategoriPpid?->kategori ?? '-' }}
                            </div>

                            @if (!empty($ppidPembantu->telp))
                                <div class="rounded-xl bg-white px-3 py-2 text-[11px] text-slate-600">
                                    <span class="font-semibold text-slate-700">Telepon:</span>
                                    <br>
                                    {{ $ppidPembantu->telp }}
                                </div>
                            @endif

                            @if (!empty($websiteUrl))
                                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer" class="block rounded-xl bg-emerald-600 px-3 py-2 text-center text-[11px] font-semibold text-white transition hover:bg-emerald-700">
                                    Kunjungi Website
                                </a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="flex flex-col gap-3 border-b border-slate-200 pb-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700">Unit PPID</p>
                                <h2 class="mt-2 text-xl font-bold text-slate-800 sm:text-2xl">{{ $ppidPembantu->nama }}</h2>
                            </div>

                            @if (!empty($websiteUrl))
                                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 transition hover:border-emerald-300 hover:text-emerald-700">
                                    <i class="ri-global-line"></i>
                                    Website Resmi
                                </a>
                            @endif
                        </div>

                        @if (!empty($ppidPembantu->keterangan))
                            <div class="mt-4 rounded-2xl bg-slate-50 p-4">
                                <h3 class="text-sm font-bold text-slate-800">Keterangan</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">
                                    {{ $ppidPembantu->keterangan }}
                                </p>
                            </div>
                        @endif

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @if (!empty($ppidPembantu->alamat))
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <h4 class="text-sm font-bold text-slate-800">Alamat</h4>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        {{ $ppidPembantu->alamat }}
                                    </p>
                                </div>
                            @endif

                            @if (!empty($ppidPembantu->telp))
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <h4 class="text-sm font-bold text-slate-800">Kontak</h4>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        {{ $ppidPembantu->telp }}
                                    </p>
                                    @if ($phone)
                                        <a href="tel:{{ $phone }}" class="mt-3 inline-flex items-center gap-2 text-xs font-medium text-emerald-700 hover:underline">
                                            <i class="ri-phone-line"></i>
                                            Hubungi sekarang
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
