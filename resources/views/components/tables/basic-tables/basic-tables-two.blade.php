@props([
    'title' => 'Data',
    'description' => null,

    'seeAllUrl' => null,
    'seeAllText' => 'Lihat Semua',

    'rowIds' => [],
    'selectable' => false,

    'showActions' => false,
    'actionsLabel' => 'Aksi',

    'minWidth' => 'min-w-[900px]',

    'paginator' => null,
    'showPagination' => true,
    'showPaginationSummary' => true,
    'paginationWindow' => 1,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Normalisasi ID baris
    |--------------------------------------------------------------------------
    */

    $normalizedRowIds = collect($rowIds)
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi pagination
    |--------------------------------------------------------------------------
    */

    $isPaginator = $paginator instanceof \Illuminate\Pagination\AbstractPaginator;

    $isLengthAwarePaginator = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $currentPage = 1;
    $lastPage = 1;
    $paginationItems = [];

    if ($isPaginator) {
        /*
         * Mempertahankan query filter ketika pindah halaman.
         */
        $paginator->appends(request()->except('page'));

        $currentPage = $paginator->currentPage();

        if ($isLengthAwarePaginator) {
            $lastPage = max(1, $paginator->lastPage());

            $window = max(1, (int) $paginationWindow);

            $pages = [1, $lastPage];

            /*
             * Halaman di sekitar halaman aktif.
             */
            for ($page = $currentPage - $window; $page <= $currentPage + $window; $page++) {
                $pages[] = $page;
            }

            /*
             * Tampilkan beberapa halaman awal.
             */
            if ($currentPage <= 4) {
                for ($page = 1; $page <= min(5, $lastPage); $page++) {
                    $pages[] = $page;
                }
            }

            /*
             * Tampilkan beberapa halaman akhir.
             */
            if ($currentPage >= $lastPage - 3) {
                for ($page = max(1, $lastPage - 4); $page <= $lastPage; $page++) {
                    $pages[] = $page;
                }
            }

            $pages = array_values(array_unique(array_filter($pages, fn($page) => $page >= 1 && $page <= $lastPage)));

            sort($pages);

            $previousPage = null;

            foreach ($pages as $page) {
                if ($previousPage !== null && $page - $previousPage > 1) {
                    $paginationItems[] = '...';
                }

                $paginationItems[] = $page;
                $previousPage = $page;
            }
        }
    }
@endphp

<section x-data="{
    /*
     * Seluruh ID pada halaman yang sedang ditampilkan.
     */
    rowIds: @js($normalizedRowIds),

    /*
     * ID data yang sedang dipilih.
     */
    selectedRows: [],

    /*
     * Status panel filter.
     */
    filterOpen: false,

    /*
     * Inisialisasi Alpine.
     */
    init() {
        const ids = Array.isArray(this.rowIds) ?
            this.rowIds :
            [];

        this.rowIds = [
            ...new Set(
                ids.map(id => String(id))
            )
        ];

        this.selectedRows = [];
    },

    /*
     * Normalisasi ID menjadi string.
     */
    normalizeId(id) {
        return String(id);
    },

    /*
     * Mendaftarkan ID yang dirender pada tabel.
     */
    registerRow(id) {
        const normalizedId = this.normalizeId(id);

        if (this.rowIds.includes(normalizedId)) {
            return;
        }

        this.rowIds = [
            ...this.rowIds,
            normalizedId
        ];
    },

    /*
     * Memeriksa satu baris.
     */
    isRowSelected(id) {
        return this.selectedRows.includes(
            this.normalizeId(id)
        );
    },

    /*
     * Memeriksa apakah seluruh baris dipilih.
     */
    get allSelected() {
        if (this.rowIds.length === 0) {
            return false;
        }

        return this.rowIds.every(
            id => this.selectedRows.includes(
                this.normalizeId(id)
            )
        );
    },

    /*
     * Memeriksa apakah sebagian baris dipilih.
     */
    get partiallySelected() {
        if (this.rowIds.length === 0) {
            return false;
        }

        const selectedCount = this.rowIds.filter(
            id => this.selectedRows.includes(
                this.normalizeId(id)
            )
        ).length;

        return (
            selectedCount > 0 &&
            selectedCount < this.rowIds.length
        );
    },

    /*
     * Memilih atau membatalkan satu baris.
     */
    handleRowSelect(id) {
        const normalizedId = this.normalizeId(id);

        this.registerRow(normalizedId);

        if (
            this.selectedRows.includes(
                normalizedId
            )
        ) {
            this.selectedRows =
                this.selectedRows.filter(
                    selectedId =>
                    selectedId !== normalizedId
                );

            return;
        }

        this.selectedRows = [
            ...this.selectedRows,
            normalizedId
        ];
    },

    /*
     * Memilih atau membatalkan semua baris.
     */
    handleSelectAll() {
        const visibleRowIds = [
            ...new Set(
                this.rowIds.map(
                    id => this.normalizeId(id)
                )
            )
        ];

        if (visibleRowIds.length === 0) {
            return;
        }

        if (this.allSelected) {
            this.selectedRows =
                this.selectedRows.filter(
                    selectedId =>
                    !visibleRowIds.includes(
                        selectedId
                    )
                );

            return;
        }

        this.selectedRows = [
            ...new Set([
                ...this.selectedRows,
                ...visibleRowIds
            ])
        ];
    },

    /*
     * Membatalkan seluruh pilihan.
     */
    clearSelection() {
        this.selectedRows = [];
    },

    /*
     * Membuka atau menutup filter.
     */
    toggleFilter() {
        this.filterOpen = !this.filterOpen;
    },

    closeFilter() {
        this.filterOpen = false;
    }
}"
    {{ $attributes->class([
        'relative',
        'overflow-visible',
        'rounded-2xl',
        'border',
        'border-gray-200',
        'bg-white',
        'pt-4',
        'shadow-sm',
        'dark:border-white/[0.05]',
        'dark:bg-white/[0.03]',
        'dark:shadow-none',
    ]) }}>
    {{-- ============================================================
        HEADER TABEL
    ============================================================= --}}

    <div
        class="
            mb-4
            flex
            flex-col
            gap-4
            px-5
            sm:flex-row
            sm:items-center
            sm:justify-between
            sm:px-6
        ">
        <div class="min-w-0">
            <h3
                class="
                    text-lg
                    font-semibold
                    text-gray-800
                    dark:text-white/90
                ">
                {{ $title }}
            </h3>

            @if ($description)
                <p
                    class="
                        mt-1
                        text-sm
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    {{ $description }}
                </p>
            @endif

            @if ($selectable)
                <div x-cloak x-show="selectedRows.length > 0" x-transition
                    class="
                        mt-2
                        flex
                        flex-wrap
                        items-center
                        gap-2
                        text-sm
                        font-medium
                        text-blue-600
                        dark:text-blue-400
                    ">
                    <span>
                        <span x-text="selectedRows.length"></span>

                        dari

                        <span x-text="rowIds.length"></span>

                        data dipilih
                    </span>

                    <button type="button" @click.prevent.stop="clearSelection()"
                        class="
                            text-gray-500
                            underline
                            transition
                            hover:text-gray-700
                            dark:text-gray-400
                            dark:hover:text-gray-200
                        ">
                        Batalkan
                    </button>
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @isset($filter)
                <div class="relative">
                    <button type="button" @click.prevent.stop="toggleFilter()" :aria-expanded="filterOpen"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-lg
                            border
                            border-gray-300
                            bg-white
                            px-4
                            py-3
                            text-sm
                            font-medium
                            text-gray-700
                            shadow-sm
                            transition
                            hover:bg-gray-50
                            hover:text-gray-800
                            focus:outline-none
                            focus:ring-4
                            focus:ring-blue-100
                            dark:border-gray-700
                            dark:bg-gray-800
                            dark:text-gray-400
                            dark:hover:bg-white/[0.03]
                            dark:hover:text-gray-200
                            dark:focus:ring-blue-900/30
                        ">
                        <i class="ri-filter-3-line text-lg"></i>

                        <span>Filter</span>

                        <i class="
                                ri-arrow-down-s-line
                                text-base
                                transition-transform
                            "
                            :class="filterOpen
                                ?
                                'rotate-180' :
                                ''"></i>
                    </button>

                    <div x-cloak x-show="filterOpen" x-transition.origin.top.right @click.outside="closeFilter()"
                        @keydown.escape.window="closeFilter()"
                        class="
                            absolute
                            right-0
                            z-50
                            mt-2
                            w-72
                            rounded-xl
                            border
                            border-gray-200
                            bg-white
                            p-4
                            shadow-lg
                            dark:border-gray-700
                            dark:bg-gray-800
                            sm:w-80
                        ">
                        {{ $filter }}
                    </div>
                </div>
            @endisset

            @isset($headerActions)
                {{ $headerActions }}
            @endisset

            @if ($seeAllUrl)
                <a href="{{ $seeAllUrl }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-4
                        py-3
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-sm
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        dark:border-gray-700
                        dark:bg-gray-800
                        dark:text-gray-400
                        dark:hover:bg-white/[0.03]
                        dark:hover:text-gray-200
                    ">
                    <span>{{ $seeAllText }}</span>

                    <i class="ri-arrow-right-line"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- ============================================================
        TABEL
    ============================================================= --}}

    <div class="max-w-full overflow-x-auto">
        <table class="w-full {{ $minWidth }}">
            <thead
                class="
                    border-y
                    border-gray-100
                    bg-gray-50
                    dark:border-white/[0.05]
                    dark:bg-gray-900
                ">
                <tr>
                    @if ($selectable)
                        <th scope="col"
                            class="
                                w-16
                                px-4
                                py-3.5
                                text-left
                                sm:px-6
                            ">
                            <button type="button" role="checkbox" @click.prevent.stop="handleSelectAll()"
                                :aria-checked="allSelected
                                    ?
                                    'true' :
                                    partiallySelected ?
                                    'mixed' :
                                    'false'"
                                class="
                                    flex
                                    h-5
                                    w-5
                                    cursor-pointer
                                    items-center
                                    justify-center
                                    rounded-md
                                    border-[1.25px]
                                    transition
                                    focus:outline-none
                                    focus:ring-4
                                    focus:ring-blue-100
                                    dark:focus:ring-blue-900/30
                                "
                                :class="allSelected || partiallySelected ?
                                    'border-blue-500 bg-blue-500 dark:border-blue-500 dark:bg-blue-500' :
                                    'border-gray-300 bg-white dark:border-gray-700 dark:bg-transparent'"
                                aria-label="Pilih semua data pada halaman ini">
                                <svg x-cloak x-show="allSelected" width="14" height="14" viewBox="0 0 14 14"
                                    fill="none" aria-hidden="true">
                                    <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white"
                                        stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <svg x-cloak
                                    x-show="
                                        partiallySelected &&
                                        !allSelected
                                    "
                                    width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                    <path d="M2.5 6H9.5" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </button>
                        </th>
                    @endif

                    @isset($head)
                        {{ $head }}
                    @endisset

                    @if ($showActions)
                        <th scope="col"
                            class="
                                min-w-[120px]
                                px-4
                                py-3.5
                                text-center
                                text-xs
                                font-medium
                                text-gray-500
                                dark:text-gray-400
                                sm:px-6
                            ">
                            {{ $actionsLabel }}
                        </th>
                    @endif
                </tr>
            </thead>

            <tbody
                class="
                    divide-y
                    divide-gray-100
                    bg-white
                    dark:divide-white/[0.05]
                    dark:bg-transparent
                ">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    {{-- ============================================================
        PAGINATION DI DALAM CARD TABEL
    ============================================================= --}}

    @if ($showPagination && $isPaginator)
        <div
            class="
                border-t
                border-gray-200
                px-4
                py-4
                dark:border-white/[0.05]
                sm:px-6
            ">
            @if ($showPaginationSummary)
                <div
                    class="
                        mb-4
                        flex
                        flex-col
                        gap-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    ">
                    <p>
                        @if ($isLengthAwarePaginator && $paginator->total() > 0)
                            Menampilkan

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $paginator->firstItem() }}
                            </span>

                            sampai

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $paginator->lastItem() }}
                            </span>

                            dari

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ number_format($paginator->total(), 0, ',', '.') }}
                            </span>

                            data
                        @elseif ($paginator->count() > 0)
                            Menampilkan

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $paginator->count() }}
                            </span>

                            data pada halaman ini
                        @else
                            Tidak ada data untuk ditampilkan
                        @endif
                    </p>

                    @if ($isLengthAwarePaginator)
                        <p>
                            Halaman

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $currentPage }}
                            </span>

                            dari

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $lastPage }}
                            </span>
                        </p>
                    @else
                        <p>
                            Halaman

                            <span
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $currentPage }}
                            </span>
                        </p>
                    @endif
                </div>
            @endif

            @if ($paginator->hasPages())
                <nav class="
                        flex
                        items-center
                        justify-between
                        gap-3
                    "
                    aria-label="Navigasi pagination">
                    {{-- Tombol sebelumnya --}}
                    @if ($paginator->onFirstPage())
                        <span
                            class="
                                inline-flex
                                cursor-not-allowed
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                font-medium
                                text-gray-400
                                opacity-50
                                shadow-sm
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-500
                            "
                            aria-disabled="true">
                            <i class="ri-arrow-left-line text-lg"></i>

                            <span class="hidden sm:inline">
                                Sebelumnya
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                font-medium
                                text-gray-700
                                shadow-sm
                                transition
                                hover:bg-gray-50
                                hover:text-gray-900
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-300
                                dark:hover:bg-gray-700
                                dark:hover:text-white
                            ">
                            <i class="ri-arrow-left-line text-lg"></i>

                            <span class="hidden sm:inline">
                                Sebelumnya
                            </span>
                        </a>
                    @endif

                    {{-- Informasi mobile --}}
                    <span
                        class="
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-300
                            sm:hidden
                        ">
                        @if ($isLengthAwarePaginator)
                            {{ $currentPage }} / {{ $lastPage }}
                        @else
                            Halaman {{ $currentPage }}
                        @endif
                    </span>

                    {{-- Nomor halaman desktop --}}
                    @if ($isLengthAwarePaginator)
                        <ul
                            class="
                                hidden
                                items-center
                                gap-1
                                sm:flex
                            ">
                            @foreach ($paginationItems as $page)
                                <li>
                                    @if ($page === '...')
                                        <span
                                            class="
                                                flex
                                                h-10
                                                min-w-10
                                                items-center
                                                justify-center
                                                px-2
                                                text-sm
                                                font-medium
                                                text-gray-500
                                                dark:text-gray-400
                                            ">
                                            ...
                                        </span>
                                    @elseif ((int) $page === (int) $currentPage)
                                        <span
                                            class="
                                                flex
                                                h-10
                                                min-w-10
                                                items-center
                                                justify-center
                                                rounded-lg
                                                bg-blue-600
                                                px-3
                                                text-sm
                                                font-semibold
                                                text-white
                                                shadow-sm
                                                dark:bg-blue-500
                                            "
                                            aria-current="page">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $paginator->url((int) $page) }}"
                                            class="
                                                flex
                                                h-10
                                                min-w-10
                                                items-center
                                                justify-center
                                                rounded-lg
                                                px-3
                                                text-sm
                                                font-medium
                                                text-gray-700
                                                transition
                                                hover:bg-blue-50
                                                hover:text-blue-600
                                                dark:text-gray-400
                                                dark:hover:bg-blue-500/15
                                                dark:hover:text-blue-400
                                            "
                                            aria-label="Buka halaman {{ $page }}">
                                            {{ $page }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Tombol selanjutnya --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                font-medium
                                text-gray-700
                                shadow-sm
                                transition
                                hover:bg-gray-50
                                hover:text-gray-900
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-300
                                dark:hover:bg-gray-700
                                dark:hover:text-white
                            ">
                            <span class="hidden sm:inline">
                                Selanjutnya
                            </span>

                            <i class="ri-arrow-right-line text-lg"></i>
                        </a>
                    @else
                        <span
                            class="
                                inline-flex
                                cursor-not-allowed
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                font-medium
                                text-gray-400
                                opacity-50
                                shadow-sm
                                dark:border-gray-700
                                dark:bg-gray-800
                                dark:text-gray-500
                            "
                            aria-disabled="true">
                            <span class="hidden sm:inline">
                                Selanjutnya
                            </span>

                            <i class="ri-arrow-right-line text-lg"></i>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    @endif
</section>
