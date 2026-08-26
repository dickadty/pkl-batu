@extends('layouts.public.app')

@section('title', 'Daftar Keberatan | PPID Kota Batu')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Status Aktif
        |--------------------------------------------------------------------------
        */

        $activeStatus = trim((string) ($currentStatus ?? (request('status') ?? '')));

        /*
        |--------------------------------------------------------------------------
        | Membentuk URL Filter Card
        |--------------------------------------------------------------------------
        */

        $buildFilterUrl = static function (?string $status = null): string {
            $query = [
                'q' => request('q'),
                'status' => $status,
                'per_page' => request('per_page', 15),
            ];

            $query = array_filter($query, static fn($value): bool => $value !== null && $value !== '');

            return route('public.keberatan.index', $query);
        };

        /*
        |--------------------------------------------------------------------------
        | Daftar Card
        |--------------------------------------------------------------------------
        */

        $summaryCards = [
            [
                'title' => 'Semua Keberatan',
                'value' => $summary['semua'] ?? 0,
                'icon' => 'ri-file-list-3-line',
                'status' => null,
                'tone' => 'brand',
            ],
            [
                'title' => 'Diajukan',
                'value' => $summary['diajukan'] ?? 0,
                'icon' => 'ri-file-add-line',
                'status' => 'Diajukan',
                'tone' => 'brand',
            ],
            [
                'title' => 'Diproses',
                'value' => $summary['diproses'] ?? 0,
                'icon' => 'ri-loader-4-line',
                'status' => 'Diproses',
                'tone' => 'brand',
            ],
            [
                'title' => 'Selesai',
                'value' => $summary['selesai'] ?? 0,
                'icon' => 'ri-checkbox-circle-line',
                'status' => 'Selesai',
                'tone' => 'green',
            ],
            [
                'title' => 'Ditolak',
                'value' => $summary['ditolak'] ?? 0,
                'icon' => 'ri-close-circle-line',
                'status' => 'Ditolak',
                'tone' => 'red',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Jumlah Filter Aktif
        |--------------------------------------------------------------------------
        */

        $activeFilterCount = collect([request('q'), request('status')])
            ->filter(fn($value): bool => $value !== null && $value !== '')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Label Status Aktif
        |--------------------------------------------------------------------------
        */

        $activeStatusLabel = $activeStatus !== '' ? $activeStatus : 'Semua Keberatan';
    @endphp

    <x-public.sections.page-hero
        eyebrow="Layanan Keberatan"
        title="Daftar Keberatan"
        highlight="PPID Kota Batu"
        description="Pantau status keberatan yang telah Anda ajukan atas hasil pelayanan permohonan informasi publik."
        :action-url="Route::has('public.keberatan.create') ? route('public.keberatan.create') : null"
        action-label="Ajukan Keberatan"
        action-icon="ri-file-warning-line"
    />

    {{-- ================================================================
        KONTEN HALAMAN
    ================================================================= --}}

    <section
        class="
            mx-auto
            max-w-7xl
            space-y-6
            px-4
            py-10
            sm:px-6
            lg:px-8
        ">
        {{-- Flash message --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            JUDUL RINGKASAN
        ============================================================= --}}

        <div
            class="
                flex
                flex-col
                gap-2
                sm:flex-row
                sm:items-end
                sm:justify-between
            ">
            <div>
                <h2
                    class="
                        text-lg
                        font-bold
                        text-slate-900
                    ">
                    Ringkasan Keberatan
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        text-slate-500
                    ">
                    Jumlah pengajuan keberatan berdasarkan
                    status penanganannya.
                </p>
            </div>

            <span
                class="
                    inline-flex
                    w-fit
                    items-center
                    gap-2
                    rounded-full
                    bg-slate-100
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    text-slate-600
                ">
                <i class="ri-database-2-line"></i>

                Total
                {{ number_format($summary['semua'] ?? 0) }}
                keberatan
            </span>
        </div>

        {{-- ============================================================
            CARD RINGKASAN
        ============================================================= --}}

        <div
            class="
                grid
                grid-cols-1
                gap-4
                sm:grid-cols-2
                xl:grid-cols-5
            ">
            @foreach ($summaryCards as $card)
                @php
                    $cardStatus = $card['status'];

                    $cardActive = $cardStatus === null ? $activeStatus === '' : $activeStatus === $cardStatus;
                @endphp

                <x-summary-card :title="$card['title']" :value="$card['value']" :icon="$card['icon']" :url="$buildFilterUrl($cardStatus)" :active="$cardActive"
                    :tone="$card['tone']" />
            @endforeach
        </div>

        {{-- ============================================================
            FILTER PENCARIAN
        ============================================================= --}}

        <section
            class="
                overflow-hidden
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">
            <div
                class="
                    border-b
                    border-slate-200
                    px-5
                    py-5
                    sm:px-6
                ">
                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                    ">
                    <div>
                        <h2
                            class="
                                text-base
                                font-bold
                                text-slate-900
                            ">
                            Data Keberatan
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Status aktif:
                            <span
                                class="
                                    font-semibold
                                    text-slate-700
                                ">
                                {{ $activeStatusLabel }}
                            </span>
                        </p>
                    </div>

                    @if ($activeFilterCount > 0)
                        <span
                            class="
                                inline-flex
                                w-fit
                                rounded-full
                                bg-blue-50
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                text-blue-700
                            ">
                            {{ $activeFilterCount }}
                            Filter Aktif
                        </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('public.keberatan.index') }}"
                method="GET"
                class="
                    grid
                    grid-cols-1
                    gap-4
                    border-b
                    border-slate-200
                    bg-slate-50/70
                    px-5
                    py-5
                    md:grid-cols-2
                    lg:grid-cols-[minmax(0,1fr)_220px_auto]
                    sm:px-6
                ">
                {{-- Pertahankan status dari card --}}

                @if ($activeStatus !== '')
                    <input type="hidden" name="status" value="{{ $activeStatus }}">
                @endif

                {{-- Pencarian --}}

                <div>
                    <label for="keberatan_q"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Pencarian
                    </label>

                    <div class="relative">
                        <span
                            class="
                                pointer-events-none
                                absolute
                                inset-y-0
                                left-0
                                flex
                                items-center
                                pl-3.5
                                text-slate-400
                            ">
                            <i
                                class="
                                    ri-search-line
                                    text-lg
                                "></i>
                        </span>

                        <input id="keberatan_q" type="search" name="q" value="{{ request('q') }}"
                            placeholder="Cari nomor, alasan, atau permohonan" autocomplete="off"
                            class="
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                bg-white
                                py-2.5
                                pl-11
                                pr-4
                                text-sm
                                text-slate-800
                                outline-none
                                transition
                                placeholder:text-slate-400
                                focus:border-blue-500
                                focus:ring-2
                                focus:ring-blue-500/20
                            ">
                    </div>
                </div>

                {{-- Per halaman --}}

                <div>
                    <label for="keberatan_per_page"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Data per Halaman
                    </label>

                    <select id="keberatan_per_page" name="per_page"
                        class="
                            h-11
                            w-full
                            rounded-lg
                            border
                            border-slate-300
                            bg-white
                            px-3
                            text-sm
                            text-slate-800
                            outline-none
                            focus:border-blue-500
                            focus:ring-2
                            focus:ring-blue-500/20
                        ">
                        @foreach ([10, 15, 25, 50, 100] as $limit)
                            <option value="{{ $limit }}" @selected((int) request('per_page', 15) === $limit)>
                                {{ $limit }} data
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol filter --}}

                <div
                    class="
                        flex
                        items-end
                        gap-2
                    ">
                    <button type="submit"
                        class="
                            inline-flex
                            h-11
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-blue-700
                            px-5
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-blue-800
                        ">
                        <i class="ri-filter-3-line"></i>

                        Terapkan
                    </button>

                    <a href="{{ route('public.keberatan.index') }}"
                        class="
                            inline-flex
                            h-11
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-slate-300
                            bg-white
                            px-4
                            text-sm
                            font-semibold
                            text-slate-700
                            transition
                            hover:bg-slate-50
                        ">
                        Reset
                    </a>
                </div>
            </form>

            {{-- ========================================================
                TABEL KEBERATAN
            ========================================================= --}}

            <div class="overflow-x-auto">
                <table class="
                        min-w-[1100px]
                        w-full
                    ">
                    <thead
                        class="
                            border-b
                            border-slate-200
                            bg-slate-50
                        ">
                        <tr>
                            <th
                                class="
                                    w-20
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                No
                            </th>

                            <th
                                class="
                                    min-w-[220px]
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Nomor Keberatan
                            </th>

                            <th
                                class="
                                    min-w-[220px]
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Nomor Permohonan
                            </th>

                            <th
                                class="
                                    min-w-[340px]
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Alasan
                            </th>

                            <th
                                class="
                                    min-w-[170px]
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Tanggal
                            </th>

                            <th
                                class="
                                    min-w-[150px]
                                    px-5
                                    py-4
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Status
                            </th>

                            <th
                                class="
                                    w-[130px]
                                    min-w-[130px]
                                    px-5
                                    py-4
                                    text-center
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-slate-500
                                ">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="
                            divide-y
                            divide-slate-100
                        ">
                        @forelse ($keberatan as $index => $item)
                            @php
                                $rowNumber = ($keberatan->firstItem() ?? 1) + $index;

                                $statusClass = match ($item->status) {
                                    'Diajukan' => 'bg-blue-50 text-blue-700',

                                    'Diproses' => 'bg-amber-50 text-amber-700',

                                    'Selesai' => 'bg-green-50 text-green-700',

                                    'Ditolak' => 'bg-red-50 text-red-700',

                                    default => 'bg-slate-100 text-slate-700',
                                };

                                $tanggalPengajuan =
                                    $item->tanggal_pengajuan?->locale('id')->translatedFormat('d F Y') ?? '-';

                                $detailUrl = route('public.keberatan.show', [
                                    'id' => $item->id,
                                ]);
                            @endphp

                            <tr
                                class="
                                    transition
                                    hover:bg-slate-50
                                ">
                                <td
                                    class="
                                        whitespace-nowrap
                                        px-5
                                        py-4
                                        text-sm
                                        font-medium
                                        text-slate-500
                                    ">
                                    {{ $rowNumber }}
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ $detailUrl }}"
                                        class="
                                            group
                                            block
                                            rounded-xl
                                            border
                                            border-blue-100
                                            bg-blue-50/70
                                            px-4
                                            py-3
                                            transition
                                            hover:border-blue-200
                                            hover:bg-blue-100/70
                                        ">
                                        <div
                                            class="
                                                flex
                                                items-start
                                                justify-between
                                                gap-2
                                            ">
                                            <p
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-blue-700
                                                ">
                                                {{ $item->no_keberatan ?? '-' }}
                                            </p>

                                            <i
                                                class="
                                                    ri-arrow-right-up-line
                                                    shrink-0
                                                    text-blue-400
                                                    transition
                                                    group-hover:translate-x-0.5
                                                    group-hover:-translate-y-0.5
                                                "></i>
                                        </div>

                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-blue-500
                                            ">
                                            ID:
                                            {{ $item->id }}
                                        </p>
                                    </a>
                                </td>

                                <td
                                    class="
                                        px-5
                                        py-4
                                        text-sm
                                        text-slate-600
                                    ">
                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-lg
                                            bg-purple-50
                                            px-3
                                            py-2
                                            font-semibold
                                            text-purple-700
                                        ">
                                        <i
                                            class="
                                                ri-file-list-3-line
                                            "></i>

                                        {{ data_get($item, 'permohonan.no_pemohon') ?? '-' }}
                                    </span>
                                </td>

                                <td
                                    class="
                                        px-5
                                        py-4
                                        text-sm
                                        leading-7
                                        text-slate-600
                                    ">
                                    <div
                                        class="
                                            rounded-xl
                                            border
                                            border-slate-100
                                            bg-slate-50/70
                                            px-4
                                            py-3
                                        ">
                                        {{ \Illuminate\Support\Str::limit(trim((string) $item->alasan), 180) ?: '-' }}
                                    </div>
                                </td>

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-5
                                        py-4
                                        text-sm
                                        text-slate-600
                                    ">
                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                        ">
                                        <span
                                            class="
                                                flex
                                                h-8
                                                w-8
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-slate-100
                                                text-slate-500
                                            ">
                                            <i
                                                class="
                                                    ri-calendar-line
                                                "></i>
                                        </span>

                                        {{ $tanggalPengajuan }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-full
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            {{ $statusClass }}
                                        ">
                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-current
                                            "></span>

                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td
                                    class="
                                        px-5
                                        py-4
                                        text-center
                                    ">
                                    <a href="{{ $detailUrl }}" title="Lihat detail keberatan"
                                        class="
                                            inline-flex
                                            h-10
                                            w-10
                                            items-center
                                            justify-center
                                            rounded-lg
                                            border
                                            border-slate-300
                                            bg-white
                                            text-slate-600
                                            transition
                                            hover:border-blue-300
                                            hover:bg-blue-50
                                            hover:text-blue-700
                                        ">
                                        <i
                                            class="
                                                ri-eye-line
                                                text-lg
                                            "></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    ">
                                    <div
                                        class="
                                            mx-auto
                                            flex
                                            h-16
                                            w-16
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-red-50
                                            text-red-500
                                        ">
                                        <i
                                            class="
                                                ri-file-warning-line
                                                text-3xl
                                            "></i>
                                    </div>

                                    <h3
                                        class="
                                            mt-4
                                            text-base
                                            font-semibold
                                            text-slate-900
                                        ">
                                        Belum ada data keberatan
                                    </h3>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            text-slate-500
                                        ">
                                        Belum ada keberatan yang
                                        sesuai dengan status atau
                                        pencarian yang dipilih.
                                    </p>

                                    @if (\Illuminate\Support\Facades\Route::has('public.keberatan.create'))
                                        <a href="{{ route('public.keberatan.create') }}"
                                            class="
                                                mt-5
                                                inline-flex
                                                h-10
                                                items-center
                                                justify-center
                                                rounded-lg
                                                bg-red-700
                                                px-4
                                                text-sm
                                                font-semibold
                                                text-white
                                                hover:bg-red-800
                                            ">
                                            Ajukan Keberatan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}

            @if ($keberatan->hasPages())
                <div
                    class="
                        border-t
                        border-slate-200
                        px-5
                        py-4
                        sm:px-6
                    ">
                    {{ $keberatan->links() }}
                </div>
            @endif
        </section>
    </section>
@endsection
