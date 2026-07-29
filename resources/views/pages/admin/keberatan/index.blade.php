@extends('layouts.admin.app')

@section('title', 'Keberatan')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Status aktif
        |--------------------------------------------------------------------------
        */

        $activeStatus = trim((string) request('status', ''));

        /*
        |--------------------------------------------------------------------------
        | URL card
        |--------------------------------------------------------------------------
        |
        | Filter pencarian, PPID Pembantu, dan jumlah data tetap dipertahankan
        | ketika card status dipilih.
        |
        */

        $buildCardUrl = static function (?string $status = null): string {
            $query = [
                'q' => request('q'),
                'status' => $status,
                'ppid_pembantuid' => request('ppid_pembantuid'),
                'per_page' => request('per_page', 15),
            ];

            $query = array_filter($query, static fn($value): bool => $value !== null && $value !== '');

            return route('admin.keberatan.index', $query);
        };

        /*
        |--------------------------------------------------------------------------
        | Daftar card
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
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            HEADER
        ============================================================= --}}

        <x-admin.page-header title="Daftar Keberatan"
            description="Pantau keberatan warga, permohonan terkait, alasan pengajuan, unit PPID, status, dan proses penyelesaiannya."
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
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Ringkasan Keberatan
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Jumlah keberatan berdasarkan status penanganan.
                </p>
            </div>

            <span
                class="
                    inline-flex
                    w-fit
                    items-center
                    gap-1.5
                    rounded-full
                    bg-gray-100
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    text-gray-600
                    dark:bg-white/[0.06]
                    dark:text-gray-400
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

                <x-summary-card :title="$card['title']" :value="$card['value']" :icon="$card['icon']" :url="$buildCardUrl($cardStatus)" :active="$cardActive"
                    :tone="$card['tone']" />
            @endforeach
        </div>

        {{-- ============================================================
            INFORMASI ALUR
        ============================================================= --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                px-5
                py-4
                shadow-theme-sm
                dark:border-gray-800
                dark:bg-gray-900
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
                            bg-brand-50
                            text-brand-600
                            dark:bg-brand-500/15
                            dark:text-brand-400
                        ">
                        <i
                            class="
                                ri-file-warning-line
                                text-xl
                            "></i>
                    </span>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Alur Penanganan Keberatan
                        </h3>

                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Keberatan diajukan oleh warga, diproses oleh
                            PPID, kemudian diselesaikan atau ditolak
                            dengan tanggapan resmi.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        flex
                        flex-wrap
                        items-center
                        gap-2
                        text-xs
                        font-medium
                        text-gray-500
                        dark:text-gray-400
                    ">
                    <span
                        class="
                            rounded-full
                            bg-blue-50
                            px-3
                            py-1.5
                            text-blue-700
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        Diajukan
                    </span>

                    <i class="ri-arrow-right-line"></i>

                    <span
                        class="
                            rounded-full
                            bg-orange-50
                            px-3
                            py-1.5
                            text-orange-700
                            dark:bg-orange-500/15
                            dark:text-orange-400
                        ">
                        Diproses
                    </span>

                    <i class="ri-arrow-right-line"></i>

                    <span
                        class="
                            rounded-full
                            bg-green-50
                            px-3
                            py-1.5
                            text-green-700
                            dark:bg-green-500/15
                            dark:text-green-400
                        ">
                        Selesai
                    </span>
                </div>
            </div>
        </div>

        {{-- ============================================================
            TABEL KEBERATAN
        ============================================================= --}}

        <div id="daftar-keberatan" class="scroll-mt-24">
            <x-tables.keberatan-table :keberatan="$keberatan" :ppid-pembantu-list="$ppidPembantuList ?? collect()" :status-options="$statusOptions ?? []" />
        </div>
    </div>
@endsection
