@extends('layouts.admin.app')

@section('title', 'Pesan Masuk')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Status Aktif
        |--------------------------------------------------------------------------
        */

        $currentStatus = $currentStatus ?? request('status', 'semua');

        /*
        |--------------------------------------------------------------------------
        | URL Filter Status
        |--------------------------------------------------------------------------
        */

        $statusUrl = static function (string $status): string {
            $query = [
                'status' => $status,
                'q' => request('q'),
                'per_page' => request('per_page'),
            ];

            $query = array_filter($query, static fn($value): bool => $value !== null && $value !== '');

            return route('admin.pesan-masuk.index', $query);
        };

        /*
        |--------------------------------------------------------------------------
        | Data Card
        |--------------------------------------------------------------------------
        */

        $summaryCards = [
            [
                'status' => 'semua',
                'title' => 'Semua Percakapan',
                'value' => $totalSemua ?? 0,
                'icon' => 'ri-chat-3-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'baru',
                'title' => 'Pesan Baru',
                'value' => $totalBaru ?? 0,
                'icon' => 'ri-mail-unread-line',
                'tone' => 'red',
            ],
            [
                'status' => 'dibaca',
                'title' => 'Sudah Dibaca',
                'value' => $totalDibaca ?? 0,
                'icon' => 'ri-mail-open-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'dibalas',
                'title' => 'Sudah Dibalas',
                'value' => $totalDibalas ?? 0,
                'icon' => 'ri-reply-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'ditutup',
                'title' => 'Ditutup',
                'value' => $totalDitutup ?? 0,
                'icon' => 'ri-checkbox-circle-line',
                'tone' => 'green',
            ],
        ];
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            HEADER
        ============================================================= --}}

        <x-admin.page-header title="Pesan Masuk"
            description="Kelola percakapan, periksa pesan dari masyarakat, berikan balasan, dan pantau status setiap percakapan."
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
                    'label' => 'Pesan Masuk',
                ],
            ]" />

        {{-- ============================================================
            PESAN SISTEM
        ============================================================= --}}

        <x-ui.flash-messages />

        @if ($errors->any())
            <div class="
                    rounded-2xl
                    border
                    border-red-200
                    bg-red-50
                    p-5
                    dark:border-red-500/20
                    dark:bg-red-500/10
                "
                role="alert">
                <div class="flex items-start gap-3">
                    <span
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-red-100
                            text-red-600
                            dark:bg-red-500/15
                            dark:text-red-400
                        ">
                        <i
                            class="
                                ri-error-warning-line
                                text-xl
                            "></i>
                    </span>

                    <div class="min-w-0">
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            ">
                            Data belum dapat diproses
                        </h3>

                        <ul
                            class="
                                mt-2
                                list-disc
                                space-y-1
                                pl-5
                                text-sm
                                leading-6
                                text-red-700
                                dark:text-red-400
                            ">
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

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
                <x-summary-card :title="$card['title']" :value="$card['value']" :icon="$card['icon']" :url="$statusUrl($card['status'])" :active="$currentStatus === $card['status']"
                    :tone="$card['tone']" />
            @endforeach
        </div>

        {{-- ============================================================
            INFORMASI FILTER AKTIF
        ============================================================= --}}

        @if ($currentStatus !== 'semua')
            @php
                $activeCard = collect($summaryCards)->firstWhere('status', $currentStatus);
            @endphp

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
                            class="{{ $activeCard['icon'] ?? 'ri-filter-3-line' }} text-lg"></i>
                    </span>

                    <div>
                        <p
                            class="
                                text-sm
                                font-semibold
                                text-brand-700
                                dark:text-brand-300
                            ">
                            Filter status aktif
                        </p>

                        <p
                            class="
                                mt-0.5
                                text-sm
                                text-brand-600
                                dark:text-brand-400
                            ">
                            {{ $activeCard['title'] ?? 'Status Pesan' }}
                        </p>
                    </div>
                </div>

                <a href="{{ $statusUrl('semua') }}"
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
                    <i
                        class="
                            ri-close-line
                            text-base
                        "></i>

                    Tampilkan Semua
                </a>
            </div>
        @endif

        {{-- ============================================================
            TABEL PESAN MASUK
        ============================================================= --}}

        <x-tables.pesan-masuk-table :pesan-masuk="$pesanMasuk" :status-options="$statusOptions" />
    </div>
@endsection
