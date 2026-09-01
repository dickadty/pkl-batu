@extends('layouts.public.app')

@section('title', 'PPID Pelaksana | PPID Kota Batu')

@push('styles')
    <style>
        main {
            padding-top: 5rem !important;
        }
    </style>
@endpush

@section('content')
   
    <section class="relative mt-0 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">
        <div class="absolute inset-0">
            <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-6xl px-6 pb-20 pt-12 text-center sm:px-8 lg:px-10">
            <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
                PPID Kota Batu
            </span>

            <h1 class="mx-auto mt-3 max-w-3xl text-xl font-bold leading-tight text-white sm:text-2xl md:text-3xl">
                PPID<br>
                <span class="text-yellow-500">Pelaksana</span>
            </h1>

            <p class="mx-auto mt-3 max-w-2xl text-[11px] leading-5 text-green-100 sm:text-xs md:text-sm">
                Daftar unit PPID pelaksana dan profil lengkap yang bertanggung jawab atas penyelenggaraan informasi publik di Kota Batu.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
            <svg class="relative block h-20 w-full" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z" class="fill-white"></path>
            </svg>
        </div>
    </section>

    <section class="relative z-30 -mt-14 pb-6">
        <div class="mx-auto grid max-w-4xl grid-cols-1 gap-3 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-slate-200 bg-white px-4 pb-4 pt-3 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-slate-500">Jumlah Kategori</p>
                        <h2 class="counter mt-1 text-xl font-bold text-green-800" data-target="{{ $totalCategories ?? 0 }}">0</h2>
                    </div>
                    <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-linear-to-br from-green-950 via-green-900 to-emerald-700 text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 7a2 2 0 012-2h4l2 2h10v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>

                    </svg>

                </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-4 pb-4 pt-3 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-medium uppercase tracking-[0.12em] text-slate-500">Jumlah Unit</p>
                        <h2 class="counter mt-1 text-xl font-bold text-green-800" data-target="{{ $totalUnits ?? 0 }}">0</h2>
                    </div>
                    <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-linear-to-br from-green-950 via-green-900 to-emerald-700 text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6M8 4h7l5 5v11a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"/>

                    </svg>
                </div>
            </div>
        </div>
    </section>

   <section class="bg-white py-6">

    <div class="mx-auto max-w-6xl rounded-lg border border-slate-200 bg-white px-4 shadow-xs sm:px-6 lg:px-8">

        <div class="grid gap-5 py-3 lg:grid-cols-[180px_minmax(0,1fr)]">

            {{-- SIDEBAR --}}
            <aside class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs lg:sticky lg:top-24 lg:self-start">

                <a href="{{ route('public.ppid-pelaksana.index') }}"
                    class="flex items-center gap-2.5 border-b border-slate-100 px-3.5 py-2.5 text-[13px] font-semibold transition
                    {{ empty($selectedCategoryId)
                        ? 'bg-emerald-50 text-emerald-900'
                        : 'text-slate-600 hover:bg-slate-50' }}">

                    <span class="tracking-wide">SEMUA</span>
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('public.ppid-pelaksana.index', ['kategori' => $category->id]) }}"
                        class="flex items-center gap-2.5 border-b border-slate-100 px-3.5 py-2.5 text-[13px] font-semibold transition

                        {{ (string) $selectedCategoryId === (string) $category->id
                            ? 'bg-emerald-50 text-emerald-900'
                            : 'text-slate-600 hover:bg-slate-50' }}">

                        <span class="truncate tracking-wide">
                            {{ $category->kategori }}
                        </span>

                    </a>

                @endforeach

            </aside>

            {{-- CONTENT --}}
            <div>

                @forelse ($visibleCategories as $category)

                    @foreach ($category->ppidPembantu as $ppid)

                        <div
                            x-data="{ open:false }"
                            class="mb-1.5 overflow-hidden rounded-lg border border-slate-100 bg-white shadow-xs transition duration-200 hover:shadow-sm">

                            <button
                                @click="open = !open"
                                class="flex w-full items-center justify-between px-4 py-3 text-left transition hover:bg-slate-50 sm:px-4.5 sm:py-3">

                                <h3 class="text-[12px] font-semibold text-slate-800 sm:text-base">
                                    {{ $ppid->nama }}
                                </h3>

                                <span class="ml-3 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-base leading-none text-slate-400">

                                    <span x-show="!open" class="text-sm">+</span>

                                    <span x-show="open" class="text-sm">−</span>

                                </span>

                            </button>

                            <div
                                x-show="open"
                                x-transition
                                x-cloak
                                class="border-t border-slate-100 bg-slate-50">

                                <div class="px-4 py-3.5 sm:px-4.5 sm:py-4">

                                    <div class="grid gap-3 md:grid-cols-2">

                                        <div class="rounded-lg bg-white p-3 border border-slate-100">

                                            <h4 class="mb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700">
                                                Alamat
                                            </h4>

                                            <p class="leading-5 text-xs text-slate-600">

                                                {{ $ppid->alamat ?: '-' }}

                                            </p>

                                        </div>

                                        <div class="rounded-lg bg-white p-3 border border-slate-100">

                                            <h4 class="mb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700">
                                                Kontak
                                            </h4>

                                            <p class="leading-5 text-xs text-slate-600">

                                                {{ $ppid->telp ?: '-' }}

                                            </p>

                                            @if($ppid->email)
                                                <p class="mt-1.5 text-xs text-slate-600">
                                                    {{ $ppid->email }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                    @if($ppid->keterangan)

                                        <div class="mt-3 rounded-lg bg-white p-3 border border-slate-100">

                                            <h4 class="mb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-700">
                                                Keterangan
                                            </h4>

                                            <p class="leading-5 text-xs text-slate-600">

                                                {{ $ppid->keterangan }}

                                            </p>

                                        </div>

                                    @endif

                                    <div class="mt-3">


                                        <a
                                            href="{{ route('public.ppid-pelaksana.show', $ppid->slug) }}"
                                            class="inline-flex items-center rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700 hover:shadow-sm">

                                            Lihat Detail PPID

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                @empty

                    <div class="bg-white p-10 text-center shadow-sm">

                        <p class="text-slate-500">
                            Belum ada data PPID Pelaksana.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.counter');

        counters.forEach((counter) => {
            const target = Number(counter.dataset.target || 0);
            let current = 0;
            const step = Math.max(1, Math.ceil(target / 20));

            const update = () => {
                current += step;

                if (current >= target) {
                    counter.textContent = target;
                    return;
                }

                counter.textContent = current;
                requestAnimationFrame(update);
            };

            if (target > 0) {
                update();
            } else {
                counter.textContent = 0;
            }
        });
    });
</script>
@endpush

@endsection