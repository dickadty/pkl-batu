@extends('layouts.public.app')

@section('title', 'Informasi Publik | PPID Kota Batu')

@section('content')
{{-- =========================================================
    HERO
========================================================= --}}
<section class="relative mt-15 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">

    {{-- Background Ornament --}}
    <div class="absolute inset-0">

        <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

        <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>

    </div>

    <div class="relative mx-auto max-w-6xl px-6 pb-28 pt-16 text-center sm:px-8 lg:px-10">

        <span
            class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">

            Transparansi Informasi

        </span>

        <h1
            class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">

            Informasi Publik<br>
            <span class="text-yellow-500">Dikecualikan</span>

        </h1>

        <p
            class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">

            Temukan informasi yang wajib diumumkan Serta Merta oleh<br class="hidden sm:inline">
            PPID Kota Batu secara terbuka, mudah, dan terarah.

        </p>

    </div>

    {{-- Wave --}}
        <div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
            <svg
                class="relative block h-20 w-full"
                viewBox="0 0 1200 120"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z"
                    class="fill-white"
                ></path>
            </svg>
        </div>

</section>


{{-- =========================================================
    FLOATING CARD
========================================================= --}}
<section class="relative z-30 -mt-18 pb-4">

    <div
        class="mx-auto grid max-w-4xl grid-cols-1 gap-3 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">

        {{-- Jumlah Kategori --}}
        <div
            class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md transition duration-300 hover:-translate-y-1">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-xs font-medium text-slate-500">

                        Jumlah Kategori

                    </p>

                    <h2
                        class="counter mt-1 text-2xl font-bold text-green-800"
                        data-target="{{ $jumlahKategori }}">

                        0

                    </h2>

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

        {{-- Jumlah Dokumen --}}
        <div
            class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md transition duration-300 hover:-translate-y-1">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-xs font-medium text-slate-500">

                        Jumlah Dokumen

                    </p>

                    <h2
                        class="counter mt-1 text-2xl font-bold text-green-800"
                        data-target="{{ $jumlahDokumen }}">

                        0

                    </h2>

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

    </div>

</section>


{{-- =========================================================
    SEARCH
========================================================= --}}
@php
    $tahunTersedia = $dokumentasi->flatten()->pluck('tahun')->filter()->unique()->sortDesc();
@endphp

<section class="py-6">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="rounded-xl bg-white p-3 shadow-sm">

            <div class="flex flex-col gap-3 md:flex-row md:items-end">

                <div class="min-w-0 flex-1">

                    <label
                        class="mb-2 block text-sm font-semibold text-slate-700">

                        Cari Informasi

                    </label>

                    <div class="relative">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0a7 7 0 0114 0z"/>

                </svg>

                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari berdasarkan judul dokumen..."
                            class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition hover:border-green-700">

                    </div>

                </div>

                <div class="relative flex items-center md:shrink-0">

                    <button
                        type="button"
                        id="filterToggle"
                        aria-expanded="false"
                        aria-controls="filterPanel"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-green-700 hover:text-green-700">

                        Filter

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 9l6 6 6-6" />
                        </svg>

                    </button>

                    <div
                        id="filterPanel"
                        class="absolute right-0 top-full z-40 mt-2 hidden w-64 rounded-lg border border-slate-200 bg-white p-3 shadow-lg">

                        <label for="sortSelect" class="mb-1 block text-xs font-medium text-slate-500">
                            Urutan
                        </label>

                        <select
                            id="sortSelect"
                            aria-label="Urutan dokumen"
                            class="mb-3 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                            <option value="year-desc">Tahun terbaru</option>
                            <option value="year-asc">Tahun terlama</option>
                            <option value="ppid-asc">PPID Pembantu A-Z</option>
                            <option value="ppid-desc">PPID Pembantu Z-A</option>
                        </select>

                        <label for="yearSelect" class="mb-1 block text-xs font-medium text-slate-500">
                            Tahun
                        </label>

                        <select
                            id="yearSelect"
                            aria-label="Filter tahun dokumen"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                            <option value="">Semua tahun</option>
                            @foreach($tahunTersedia as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
            </div>

        {{-- =========================================================
            ACCORDION
        ========================================================= --}}
        <div class="mt-4 space-y-3">

    @forelse($dokumentasi as $namaKategori => $items)
    <div class="accordion overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- HEADER ACCORDION --}}
    <button
        type="button"
        class="accordion-header flex w-full items-center justify-between bg-linear-to-r from-emerald-700 to-green-800 px-4 py-4 text-left transition-all duration-300 hover:from-emerald-800 hover:to-green-900 sm:px-5">

        <div class="flex items-center gap-3">

            {{-- Icon --}}
            <div
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 text-white">

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

            <div>

                <h2
                    class="text-base font-semibold text-white">

                    {{ $namaKategori }}

                </h2>

                <p
                    class="mt-0.5 text-xs text-green-50">

                    {{ $items->count() }} Dokumen

                </p>

            </div>

        </div>

        <svg
            class="accordion-icon h-5 w-5 text-white transition-transform duration-300"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"/>

        </svg>

    </button>

    {{-- CONTENT --}}
    <div
        class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">

        <div class="divide-y divide-slate-100 bg-slate-50">

            @foreach($items as $item)

                <div
                    class="document-item flex flex-col gap-4 px-4 py-4 transition hover:bg-white lg:flex-row lg:items-center lg:justify-between sm:px-5"
                    data-year="{{ $item->tahun ?? '' }}"
                    data-ppid="{{ $item->ppidPembantu->nama ?? 'PPID Utama' }}">

                    {{-- Informasi --}}
                    <div class="flex flex-1">

                        <div class="flex-1">

                            <h3
                                class="document-title text-base font-semibold text-slate-800">

                                {{ $item->nama }}

                            </h3>

                            <p
                                class="mt-2 text-xs leading-5 text-slate-500">

                                {{ $item->ringkasan ?: 'Belum terdapat ringkasan dokumen.' }}

                            </p>

                            <div
                                class="mt-3 flex flex-wrap gap-2">

                                <span
                                    class="rounded-full bg-slate-200 px-3 py-1 text-xs text-slate-600">

                                    {{ is_numeric($item->tanggal) ? \Carbon\Carbon::createFromTimestamp((int) $item->tanggal)->translatedFormat('d F Y') : (\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') ?? '-') }}

                                </span>

                                <span
                                    class="rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-800">

                                    {{ $item->ppidPembantu->nama ?? 'PPID Utama' }}

                                </span>

                            </div>

                        </div>

                    </div>

                    {{-- Tombol --}}
                    <div
                        class="flex shrink-0 gap-2">

                        <a
                            href="{{ route('public.informasi.show', $item->slug) }}"
                            title="Lihat detail"
                            aria-label="Lihat detail {{ $item->nama }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-green-700 hover:text-green-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6z" />
                                <circle cx="12" cy="12" r="2.5" />

                            </svg>

                        </a>

                        <a
                            href="{{ route('public.informasi.download', $item->id) }}"
                            title="Download dokumen"
                            aria-label="Download {{ $item->nama }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-800 text-white transition hover:bg-green-950">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 19.5h14" />

                            </svg>

                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>
@empty

<div class="rounded-xl bg-white p-8 text-center shadow">
    Belum ada dokumen.
</div>

@endforelse
        </div>
    </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const accordions = document.querySelectorAll('.accordion');

    const filterToggle = document.getElementById('filterToggle');
    const filterPanel = document.getElementById('filterPanel');

    filterToggle.addEventListener('click', () => {

        const isHidden = filterPanel.classList.toggle('hidden');
        filterToggle.setAttribute('aria-expanded', String(!isHidden));

    });

    document.addEventListener('click', event => {

        if(!filterToggle.contains(event.target) && !filterPanel.contains(event.target)){

            filterPanel.classList.add('hidden');
            filterToggle.setAttribute('aria-expanded', 'false');

        }

    });

    /* ===========================================
       ACCORDION
    =========================================== */

    accordions.forEach((accordion, index) => {

        const header = accordion.querySelector('.accordion-header');
        const content = accordion.querySelector('.accordion-content');
        const icon = accordion.querySelector('.accordion-icon');

        // buka kategori pertama
        if(index === 0){

            content.style.maxHeight = content.scrollHeight + "px";
            icon.classList.add("rotate-180");
            header.classList.add("bg-green-50");

        }

        header.addEventListener('click', () => {

            const opened = content.style.maxHeight &&
                           content.style.maxHeight !== "0px";

            // tutup semua
            accordions.forEach(item=>{

                item.querySelector('.accordion-content').style.maxHeight = null;

                item.querySelector('.accordion-icon')
                    .classList.remove("rotate-180");

                item.querySelector('.accordion-header')
                    .classList.remove("bg-green-50");

            });

            // buka yang dipilih
            if(!opened){

                content.style.maxHeight = content.scrollHeight + "px";

                icon.classList.add("rotate-180");

                header.classList.add("bg-green-50");

            }

        });

    });


    /* ===========================================
       SEARCH
    =========================================== */

    const input = document.getElementById('searchInput');
    const yearSelect = document.getElementById('yearSelect');

    const applyFilters = () => {

        const keyword = input.value.toLowerCase();
        const selectedYear = yearSelect.value;

        accordions.forEach(acc=>{

            let found = false;

            const docs = acc.querySelectorAll('.document-item');

            docs.forEach(doc=>{

                const title = doc.querySelector('.document-title')
                                .innerText.toLowerCase();
                const matchesTitle = title.includes(keyword);
                const matchesYear = !selectedYear || doc.dataset.year === selectedYear;

                if(matchesTitle && matchesYear){

                    doc.style.display = "flex";
                    found = true;

                }else{

                    doc.style.display = "none";

                }

            });

            const content = acc.querySelector('.accordion-content');
            const icon = acc.querySelector('.accordion-icon');
            const header = acc.querySelector('.accordion-header');

            if(keyword === "" && selectedYear === ""){

                docs.forEach(doc=>{

                    doc.style.display = "flex";

                });

            }

            if(found){

                content.style.maxHeight = content.scrollHeight + "px";

                icon.classList.add("rotate-180");

                header.classList.add("bg-green-50");

            }else{

                if(keyword !== "" || selectedYear !== ""){

                    content.style.maxHeight = null;

                    icon.classList.remove("rotate-180");

                    header.classList.remove("bg-green-50");

                }

            }

        });

    };

    input.addEventListener('input', applyFilters);
    yearSelect.addEventListener('change', applyFilters);


    /* ===========================================
       SORTING
    =========================================== */

    const sortSelect = document.getElementById('sortSelect');

    const sortDocuments = (sortBy) => {

        accordions.forEach(accordion => {

            const list = accordion.querySelector('.divide-y');
            const documents = [...list.querySelectorAll('.document-item')];

            documents.sort((first, second) => {

                if(sortBy.startsWith('year')){

                    const firstYear = Number(first.dataset.year) || 0;
                    const secondYear = Number(second.dataset.year) || 0;

                    return sortBy === 'year-asc'
                        ? firstYear - secondYear
                        : secondYear - firstYear;

                }

                const comparison = first.dataset.ppid.localeCompare(
                    second.dataset.ppid,
                    'id',
                    { sensitivity: 'base' }
                );

                return sortBy === 'ppid-asc' ? comparison : -comparison;

            });

            documents.forEach(document => list.appendChild(document));

        });

        document.querySelectorAll('.accordion-content').forEach(content => {

            if(content.style.maxHeight){

                content.style.maxHeight = content.scrollHeight + "px";

            }

        });

    };

    sortSelect.addEventListener('change', () => sortDocuments(sortSelect.value));
    sortDocuments(sortSelect.value);


    /* ===========================================
       COUNTER
    =========================================== */

    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter=>{

        const target = Number(counter.dataset.target);

        const speed = 25;

        let current = 0;

        const update = ()=>{

            const increment = Math.ceil(target / speed);

            current += increment;

            if(current >= target){

                current = target;

            }

            counter.innerText = current;

            if(current < target){

                requestAnimationFrame(update);

            }

        };

        update();

    });


    /* ===========================================
       RESPONSIVE
    =========================================== */

    window.addEventListener('resize',()=>{

        document.querySelectorAll('.accordion-content').forEach(content=>{

            if(content.style.maxHeight){

                content.style.maxHeight = content.scrollHeight + "px";

            }

        });

    });

});
</script>
@endpush