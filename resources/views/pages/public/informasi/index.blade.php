@extends('layouts.public.app')

@section('title', 'Daftar Informasi Publik | PPID Kota Batu')

@push('styles')
    <style>
        main {
            padding-top: 5rem!important;
        }
    </style>
@endpush

@section('content')
<section class="relative mt-0 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">

    <div class="absolute inset-0">
        <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-6 pb-28 pt-16 text-center sm:px-8 lg:px-10">
        <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
            Transparansi Informasi
        </span>

        <h1 class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">
            Daftar Informasi<br>
            <span class="text-yellow-500">Publik</span>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
            Semua dokumentasi dan informasi publik yang tersedia untuk masyarakat,<br class="hidden sm:inline">
            kecuali informasi yang dikecualikan sesuai ketentuan peraturan yang berlaku.
        </p>
    </div>

    <div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
        <svg class="relative block h-20 w-full" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z" class="fill-white"></path>
        </svg>
    </div>
</section>

<section class="relative z-30 -mt-18 pb-4">
    <div class="mx-auto grid max-w-4xl grid-cols-1 gap-3 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md">
            <p class="text-xs font-medium text-slate-500">Total Dokumen</p>
            <h2 class="counter mt-1 text-2xl font-bold text-green-800" data-target="{{ $totalDokumen }}">0</h2>
        </div>

        <div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md">
            <p class="text-xs font-medium text-slate-500">Kategori Tersedia</p>
            <h2 class="counter mt-1 text-2xl font-bold text-green-800" data-target="{{ $kategoriTersedia->count() }}">0</h2>
        </div>
    </div>
</section>

<section class="py-6">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <div class="min-w-0 flex-1">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Cari Informasi</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0a7 7 0 0114 0z" />
                        </svg>
                        <input id="searchInput" type="text" placeholder="Cari judul, kategori, atau unit PPID..." class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition hover:border-green-700 focus:border-green-700">
                    </div>
                </div>

                <div class="relative lg:w-[250px]">
                    <button id="filterToggle" type="button" class="inline-flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-green-700 hover:text-green-700">
                        <span>Filter</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    <div id="filterPanel" class="absolute right-0 top-full z-40 mt-2 hidden w-[320px] rounded-xl border border-slate-200 bg-white p-4 shadow-xl">
                        <div class="grid gap-3">
                            <div>
                                <label for="categoryFilter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</label>
                                <select id="categoryFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                                    <option value="">Semua kategori</option>
                                    @foreach($kategoriTersedia as $kategori)
                                        <option value="{{ strtolower($kategori) }}">{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="sifatFilter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Sifat</label>
                                <select id="sifatFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                                    <option value="">Semua sifat</option>
                                    <option value="berkala">Berkala</option>
                                    <option value="setiap_saat">Setiap Saat</option>
                                    <option value="serta_merta">Serta Merta</option>
                                </select>
                            </div>

                            <div>
                                <label for="yearFilter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tahun</label>
                                <select id="yearFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                                    <option value="">Semua tahun</option>
                                    @foreach($tahunTersedia as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="ppidFilter" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Unit PPID</label>
                                <select id="ppidFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none">
                                    <option value="">Semua unit PPID</option>
                                    @foreach($dokumen->pluck('ppidPembantu.nama')->filter()->unique()->sort() as $unit)
                                        <option value="{{ strtolower($unit) }}">{{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button id="resetFilter" type="button" class="mt-1 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-600 transition hover:border-slate-300 hover:bg-slate-100">
                                Reset filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Judul Informasi</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Kategori</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Sifat</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Tahun</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal Upload</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600">Unit PPID</th>
                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="documentTableBody">
                        @forelse($dokumen as $item)
                            @php
                                $tanggalUpload = $item->tanggal
                                    ? (
                                        is_numeric($item->tanggal)
                                            ? \Carbon\Carbon::createFromTimestamp((int) $item->tanggal)->translatedFormat('d F Y')
                                            : \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y')
                                    )
                                    : '-';
                            @endphp
                            <tr class="document-row" data-title="{{ strtolower($item->nama) }}" data-kategori="{{ strtolower($item->kategori->nama ?? '') }}" data-sifat="{{ strtolower($item->kategori->sifat ?? '') }}" data-year="{{ $item->tahun ?? '' }}" data-ppid="{{ strtolower($item->ppidPembantu->nama ?? 'PPID Utama') }}">
                                <td class="px-4 py-4 align-top">
                                    <div class="font-semibold text-slate-800">{{ $item->nama }}</div>
                                    <div class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">{{ $item->ringkasan ?: 'Belum terdapat ringkasan dokumen.' }}</div>
                                </td>
                                <td class="px-4 py-4 align-top text-sm text-slate-600">
                                    {{ $item->kategori->nama ?? 'Tanpa Kategori' }}
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-800">
                                        {{ str_replace('_', ' ', ucfirst(str_replace('-', ' ', $item->kategori->sifat ?? 'berkala'))) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 align-top text-sm text-slate-600">
                                    {{ $item->tahun ?? '-' }}
                                </td>
                                <td class="px-4 py-4 align-top text-sm text-slate-600">
                                    {{ $tanggalUpload }}
                                </td>
                                <td class="px-4 py-4 align-top text-sm text-slate-600">
                                    {{ $item->ppidPembantu->nama ?? 'PPID Utama' }}
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('public.informasi.show', $item->slug) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-green-700 hover:text-green-700" title="Lihat detail" aria-label="Lihat detail {{ $item->nama }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6-9.75-6-9.75-6z" />
                                                <circle cx="12" cy="12" r="2.5" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('public.informasi.download', $item->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-800 text-white transition hover:bg-green-950" title="Download dokumen" aria-label="Download {{ $item->nama }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 19.5h14" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada dokumen informasi publik yang dapat ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sifatFilter = document.getElementById('sifatFilter');
    const yearFilter = document.getElementById('yearFilter');
    const ppidFilter = document.getElementById('ppidFilter');
    const filterToggle = document.getElementById('filterToggle');
    const filterPanel = document.getElementById('filterPanel');
    const resetFilter = document.getElementById('resetFilter');
    const rows = [...document.querySelectorAll('.document-row')];

    const applyFilter = () => {
        const keyword = (input?.value || '').toLowerCase().trim();
        const category = (categoryFilter?.value || '').toLowerCase();
        const sifat = (sifatFilter?.value || '').toLowerCase();
        const year = (yearFilter?.value || '').toString();
        const ppid = (ppidFilter?.value || '').toLowerCase();

        rows.forEach(row => {
            const title = row.dataset.title || '';
            const categoryName = row.dataset.kategori || '';
            const rowSifat = row.dataset.sifat || '';
            const rowYear = row.dataset.year || '';
            const rowPpid = row.dataset.ppid || '';

            const matchesKeyword = !keyword || title.includes(keyword) || categoryName.includes(keyword) || rowPpid.includes(keyword);
            const matchesCategory = !category || categoryName === category;
            const matchesSifat = !sifat || rowSifat === sifat;
            const matchesYear = !year || rowYear === year;
            const matchesPpid = !ppid || rowPpid === ppid;

            row.style.display = matchesKeyword && matchesCategory && matchesSifat && matchesYear && matchesPpid ? '' : 'none';
        });
    };

    const toggleFilterPanel = () => {
        const isHidden = filterPanel.classList.toggle('hidden');
        filterToggle.setAttribute('aria-expanded', String(!isHidden));
    };

    filterToggle?.addEventListener('click', toggleFilterPanel);

    document.addEventListener('click', (event) => {
        if (!filterToggle.contains(event.target) && !filterPanel.contains(event.target)) {
            filterPanel.classList.add('hidden');
            filterToggle.setAttribute('aria-expanded', 'false');
        }
    });

    input?.addEventListener('input', applyFilter);
    categoryFilter?.addEventListener('change', applyFilter);
    sifatFilter?.addEventListener('change', applyFilter);
    yearFilter?.addEventListener('change', applyFilter);
    ppidFilter?.addEventListener('change', applyFilter);

    resetFilter?.addEventListener('click', () => {
        input.value = '';
        categoryFilter.value = '';
        sifatFilter.value = '';
        yearFilter.value = '';
        ppidFilter.value = '';
        applyFilter();
    });

    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = Number(counter.dataset.target || 0);
        let current = 0;
        const speed = 25;

        const update = () => {
            const increment = Math.ceil(target / speed);
            current += increment;

            if (current >= target) {
                current = target;
                counter.textContent = current;
                return;
            }

            counter.textContent = current;
            requestAnimationFrame(update);
        };

        update();
    });
});
</script>
@endpush
@endsection
