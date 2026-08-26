@extends('layouts.admin.app')

@section('title', 'Keberatan')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Filter Aktif
        |--------------------------------------------------------------------------
        */

        $currentStatus = trim((string) request('status', 'semua'));

        $currentHasil = trim((string) request('hasil', ''));

        /*
        |--------------------------------------------------------------------------
        | Normalisasi Data
        |--------------------------------------------------------------------------
        */

        $keberatanData = $keberatans ?? ($keberatan ?? collect());

        $ppidPembantuData = $ppidPembantuList ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validStatuses = ['Diajukan', 'Diproses', 'Selesai'];

        $normalizedStatusOptions = collect($statusOptions ?? $validStatuses)
            ->filter(static fn($status): bool => in_array($status, $validStatuses, true))
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | URL Filter
        |--------------------------------------------------------------------------
        */

        $filterUrl = static function (string $status = 'semua', ?string $hasil = null): string {
            $query = [
                'status' => $status !== 'semua' ? $status : null,

                'hasil' => $hasil,

                'search' => request('search'),

                'ppid_pembantuid' => request('ppid_pembantuid'),

                'per_page' => request('per_page'),
            ];

            $query = array_filter($query, static fn($value): bool => $value !== null && $value !== '');

            return route('admin.keberatan.index', $query);
        };

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        */

        $summaryCards = [
            [
                'status' => 'semua',
                'hasil' => null,
                'title' => 'Semua Keberatan',
                'value' => $summary['semua'] ?? 0,
                'icon' => 'ri-file-list-3-line',
                'tone' => 'brand',
            ],

            [
                'status' => 'Diajukan',
                'hasil' => null,
                'title' => 'Diajukan',
                'value' => $summary['diajukan'] ?? 0,
                'icon' => 'ri-file-add-line',
                'tone' => 'brand',
            ],

            [
                'status' => 'Diproses',
                'hasil' => null,
                'title' => 'Diproses',
                'value' => $summary['diproses'] ?? 0,
                'icon' => 'ri-loader-4-line',
                'tone' => 'brand',
            ],

            [
                'status' => 'Selesai',
                'hasil' => null,
                'title' => 'Selesai',
                'value' => $summary['selesai'] ?? 0,
                'icon' => 'ri-checkbox-circle-line',
                'tone' => 'green',
            ],

            [
                'status' => 'Selesai',
                'hasil' => 'Ditolak',
                'title' => 'Hasil Ditolak',
                'value' => $summary['hasil_ditolak'] ?? ($summary['ditolak'] ?? 0),
                'icon' => 'ri-close-circle-line',
                'tone' => 'red',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Filter Card Aktif
        |--------------------------------------------------------------------------
        */

        $activeCard = collect($summaryCards)->first(function (array $card) use ($currentStatus, $currentHasil): bool {
            $cardStatus = $card['status'];
            $cardHasil = $card['hasil'];

            if ($cardStatus === 'semua' && $cardHasil === null) {
                return ($currentStatus === 'semua' || $currentStatus === '') && $currentHasil === '';
            }

            if ($cardHasil !== null) {
                return $currentHasil === $cardHasil;
            }

            return $currentStatus === $cardStatus && $currentHasil === '';
        });

        $hasActiveCardFilter = !in_array($currentStatus, ['', 'semua'], true) || $currentHasil !== '';
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            HEADER
        ============================================================= --}}

        <x-admin.page-header title="Daftar Keberatan"
            description="Kelola pengajuan keberatan masyarakat, permohonan terkait, unit PPID, status penanganan, hasil keputusan, dan proses penyelesaiannya."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pelayanan Informasi',
                ],
                [
                    'label' => 'Keberatan',
                ],
            ]" />

        <x-ui.flash-messages />

        {{-- ============================================================
            SUMMARY CARDS
        ============================================================= --}}

        <div
            class="
                grid
                grid-cols-1
                gap-4
                sm:grid-cols-2
                lg:grid-cols-3
                xl:grid-cols-5
            ">
            @foreach ($summaryCards as $card)
                @php
                    if ($card['status'] === 'semua' && $card['hasil'] === null) {
                        $isActive = in_array($currentStatus, ['', 'semua'], true) && $currentHasil === '';
                    } elseif ($card['hasil'] !== null) {
                        $isActive = $currentHasil === $card['hasil'];
                    } else {
                        $isActive = $currentStatus === $card['status'] && $currentHasil === '';
                    }
                @endphp

                <x-summary-card :title="$card['title']" :value="$card['value']" :icon="$card['icon']" :url="$filterUrl($card['status'], $card['hasil'])" :active="$isActive"
                    :tone="$card['tone']" />
            @endforeach
        </div>

        {{-- ============================================================
            FILTER CARD AKTIF
        ============================================================= --}}

        @if ($hasActiveCardFilter)
            <div
                class="
                    flex
                    flex-col
                    gap-3
                    rounded-xl
                    border
                    border-brand-200
                    bg-brand-50
                    px-4
                    py-3
                    dark:border-brand-500/20
                    dark:bg-brand-500/10
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                    ">
                    <span
                        class="
                            flex
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-lg
                            bg-brand-100
                            text-brand-600
                            dark:bg-brand-500/20
                            dark:text-brand-400
                        ">
                        <i
                            class="
                                {{ $activeCard['icon'] ?? 'ri-filter-3-line' }}
                                text-lg
                            "></i>
                    </span>

                    <div>
                        <p
                            class="
                                text-sm
                                font-semibold
                                text-brand-700
                                dark:text-brand-300
                            ">
                            Filter aktif
                        </p>

                        <p
                            class="
                                mt-0.5
                                text-sm
                                text-brand-600
                                dark:text-brand-400
                            ">
                            {{ $activeCard['title'] ?? ($currentHasil !== '' ? 'Hasil ' . $currentHasil : $currentStatus) }}
                        </p>
                    </div>
                </div>

                <a href="{{ $filterUrl('semua') }}"
                    class="
                        inline-flex
                        h-9
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-brand-300
                        bg-white
                        px-3
                        text-xs
                        font-semibold
                        text-brand-600
                        transition
                        hover:bg-brand-100
                        dark:border-brand-500/30
                        dark:bg-gray-900
                        dark:text-brand-400
                        dark:hover:bg-brand-500/10
                    ">
                    <i class="ri-close-line text-base"></i>

                    Tampilkan Semua
                </a>
            </div>
        @endif

        {{-- ============================================================
            TABEL KEBERATAN
        ============================================================= --}}

        <x-tables.keberatan-table :keberatan="$keberatanData" :ppid-pembantu-list="$ppidPembantuData" :status-options="$normalizedStatusOptions" />
    </div>
@endsection
