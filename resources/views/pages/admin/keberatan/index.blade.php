@extends('layouts.admin.app')

@section('title', 'Keberatan')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Parameter filter aktif
        |--------------------------------------------------------------------------
        */

        $activeStatus = trim((string) request('status', ''));
        $activeHasil = trim((string) request('hasil', ''));

        /*
        |--------------------------------------------------------------------------
        | Normalisasi data dari controller
        |--------------------------------------------------------------------------
        |
        | Mendukung nama variabel:
        | - $keberatan
        | - $keberatans
        |
        | Hal ini mencegah error ketika controller menggunakan bentuk jamak.
        |
        */

        $keberatanData = $keberatan ?? $keberatans ?? collect();

        $ppidPembantuData = $ppidPembantuList ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Status yang valid
        |--------------------------------------------------------------------------
        |
        | Ditolak bukan status proses. Ditolak merupakan hasil keputusan.
        |
        */

        $validStatuses = [
            'Diajukan',
            'Diproses',
            'Selesai',
        ];

        $normalizedStatusOptions = collect(
            $statusOptions ?? $validStatuses,
        )
            ->filter(
                static fn($status): bool => in_array(
                    $status,
                    $validStatuses,
                    true,
                ),
            )
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | URL card ringkasan
        |--------------------------------------------------------------------------
        |
        | Filter pencarian, unit PPID, hasil, dan jumlah data dipertahankan.
        |
        */

        $buildCardUrl = static function (
            ?string $status = null,
            ?string $hasil = null,
        ): string {
            $query = [
                'q' => request('q'),
                'status' => $status,
                'hasil' => $hasil,
                'ppid_pembantuid' => request('ppid_pembantuid'),
                'per_page' => request('per_page', 15),
            ];

            $query = array_filter(
                $query,
                static fn($value): bool => $value !== null
                    && $value !== '',
            );

            return route(
                'admin.keberatan.index',
                $query,
            );
        };

        /*
        |--------------------------------------------------------------------------
        | Daftar card ringkasan
        |--------------------------------------------------------------------------
        */

        $summaryCards = [
            [
                'title' => 'Semua Keberatan',
                'value' => $summary['semua'] ?? 0,
                'icon' => 'ri-file-list-3-line',
                'status' => null,
                'hasil' => null,
                'tone' => 'brand',
            ],
            [
                'title' => 'Diajukan',
                'value' => $summary['diajukan'] ?? 0,
                'icon' => 'ri-file-add-line',
                'status' => 'Diajukan',
                'hasil' => null,
                'tone' => 'brand',
            ],
            [
                'title' => 'Diproses',
                'value' => $summary['diproses'] ?? 0,
                'icon' => 'ri-loader-4-line',
                'status' => 'Diproses',
                'hasil' => null,
                'tone' => 'brand',
            ],
            [
                'title' => 'Selesai',
                'value' => $summary['selesai'] ?? 0,
                'icon' => 'ri-checkbox-circle-line',
                'status' => 'Selesai',
                'hasil' => null,
                'tone' => 'green',
            ],
            [
                'title' => 'Hasil Ditolak',
                'value' => $summary['hasil_ditolak']
                    ?? $summary['ditolak']
                    ?? 0,
                'icon' => 'ri-close-circle-line',
                'status' => 'Selesai',
                'hasil' => 'Ditolak',
                'tone' => 'red',
            ],
        ];
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            HEADER
        ============================================================= --}}

        <x-admin.page-header
            title="Daftar Keberatan"
            description="Pantau keberatan masyarakat, permohonan terkait, alasan pengajuan, unit PPID, status penanganan, hasil keputusan, dan proses penyelesaiannya."
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
            ]"
        />

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
            "
        >
            <div>
                <h2
                    class="
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    "
                >
                    Ringkasan Keberatan
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    "
                >
                    Jumlah keberatan berdasarkan tahapan penanganan
                    dan hasil keputusan final.
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
                "
            >
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
            "
        >
            @foreach ($summaryCards as $card)
                @php
                    $cardStatus = $card['status'];
                    $cardHasil = $card['hasil'];

                    /*
                     * Card Semua aktif ketika tidak ada filter status
                     * dan tidak ada filter hasil.
                     */
                    if (
                        $cardStatus === null
                        && $cardHasil === null
                    ) {
                        $cardActive = $activeStatus === ''
                            && $activeHasil === '';
                    } elseif ($cardHasil !== null) {
                        /*
                         * Card hasil keputusan aktif berdasarkan hasil.
                         */
                        $cardActive = $activeHasil === $cardHasil;
                    } else {
                        /*
                         * Card status aktif hanya ketika tidak ada
                         * filter hasil keputusan.
                         */
                        $cardActive = $activeStatus === $cardStatus
                            && $activeHasil === '';
                    }
                @endphp

                <x-summary-card
                    :title="$card['title']"
                    :value="$card['value']"
                    :icon="$card['icon']"
                    :url="$buildCardUrl(
                        $cardStatus,
                        $cardHasil,
                    )"
                    :active="$cardActive"
                    :tone="$card['tone']"
                />
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
            "
        >
            <div
                class="
                    flex
                    flex-col
                    gap-5
                    xl:flex-row
                    xl:items-center
                    xl:justify-between
                "
            >
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
                        "
                    >
                        <i class="ri-file-warning-line text-xl"></i>
                    </span>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            Alur Penanganan Keberatan
                        </h3>

                        <p
                            class="
                                mt-1
                                max-w-2xl
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Keberatan diajukan oleh masyarakat,
                            diperiksa oleh Admin Utama, kemudian
                            diberikan tanggapan final dan hasil
                            keputusan. Status permohonan informasi
                            awal tidak direset.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- Status proses --}}
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
                        "
                    >
                        <span
                            class="
                                rounded-full
                                bg-blue-50
                                px-3
                                py-1.5
                                text-blue-700
                                dark:bg-blue-500/15
                                dark:text-blue-400
                            "
                        >
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
                            "
                        >
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
                            "
                        >
                            Selesai
                        </span>
                    </div>

                    {{-- Hasil keputusan --}}
                    <div
                        class="
                            flex
                            flex-wrap
                            items-center
                            gap-2
                            text-xs
                            font-medium
                        "
                    >
                        <span
                            class="
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Hasil:
                        </span>

                        <span
                            class="
                                rounded-full
                                bg-green-50
                                px-3
                                py-1.5
                                text-green-700
                                dark:bg-green-500/15
                                dark:text-green-400
                            "
                        >
                            Diterima
                        </span>

                        <span
                            class="
                                rounded-full
                                bg-yellow-50
                                px-3
                                py-1.5
                                text-yellow-700
                                dark:bg-yellow-500/15
                                dark:text-yellow-400
                            "
                        >
                            Diterima Sebagian
                        </span>

                        <span
                            class="
                                rounded-full
                                bg-red-50
                                px-3
                                py-1.5
                                text-red-700
                                dark:bg-red-500/15
                                dark:text-red-400
                            "
                        >
                            Ditolak
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            FILTER AKTIF HASIL
        ============================================================= --}}

        @if ($activeHasil !== '')
            <div
                class="
                    flex
                    flex-col
                    gap-3
                    rounded-xl
                    border
                    border-red-200
                    bg-red-50
                    px-4
                    py-3
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    dark:border-red-500/20
                    dark:bg-red-500/10
                "
            >
                <div class="flex items-center gap-2">
                    <i
                        class="
                            ri-filter-3-line
                            text-red-600
                            dark:text-red-400
                        "
                    ></i>

                    <p
                        class="
                            text-sm
                            font-medium
                            text-red-700
                            dark:text-red-300
                        "
                    >
                        Menampilkan keberatan dengan hasil:
                        <span class="font-semibold">
                            {{ $activeHasil }}
                        </span>
                    </p>
                </div>

                <a
                    href="{{ $buildCardUrl() }}"
                    class="
                        inline-flex
                        items-center
                        gap-1.5
                        text-sm
                        font-semibold
                        text-red-700
                        hover:text-red-800
                        dark:text-red-300
                        dark:hover:text-red-200
                    "
                >
                    <i class="ri-close-line"></i>
                    Hapus filter
                </a>
            </div>
        @endif

        {{-- ============================================================
            TABEL KEBERATAN
        ============================================================= --}}

        <div
            id="daftar-keberatan"
            class="scroll-mt-24"
        >
            <x-tables.keberatan-table
                :keberatan="$keberatanData"
                :ppid-pembantu-list="$ppidPembantuData"
                :status-options="$normalizedStatusOptions"
            />
        </div>
    </div>
@endsection