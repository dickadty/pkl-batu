@extends('layouts.admin.app')

@section('title', 'Keberatan')

@section('content')

    @php

        $currentStatus = strtolower((string) ($currentStatus ?? request('status', 'semua')));

        $currentHasil = strtolower((string) request('hasil', ''));

        /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

        $keberatanData = $keberatans ?? ($keberatan ?? collect());

        $ppidPembantuData = $ppidPembantuList ?? collect();

        /*
    |--------------------------------------------------------------------------
    | URL Filter
    |--------------------------------------------------------------------------
    */

        $statusUrl = static function (string $status = 'semua', ?string $hasil = null): string {
            $query = [
                'status' => $status,

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
    | Summary Card
    |--------------------------------------------------------------------------
    */

        $summaryCards = [
            [
                'status' => 'semua',

                'title' => 'Semua Keberatan',

                'value' => $summary['semua'] ?? 0,

                'icon' => 'ri-file-list-3-line',

                'tone' => 'brand',
            ],

            [
                'status' => 'diajukan',

                'title' => 'Diajukan',

                'value' => $summary['diajukan'] ?? 0,

                'icon' => 'ri-file-add-line',

                'tone' => 'brand',
            ],

            [
                'status' => 'diproses',

                'title' => 'Diproses',

                'value' => $summary['diproses'] ?? 0,

                'icon' => 'ri-loader-4-line',

                'tone' => 'brand',
            ],

            [
                'status' => 'selesai',

                'title' => 'Selesai',

                'value' => $summary['selesai'] ?? 0,

                'icon' => 'ri-checkbox-circle-line',

                'tone' => 'green',
            ],

            [
                'status' => 'ditolak',

                'title' => 'Ditolak',

                'value' => $summary['ditolak'] ?? 0,

                'icon' => 'ri-close-circle-line',

                'tone' => 'red',
            ],
        ];
    @endphp



    <div class="space-y-6">


        {{-- HEADER --}}

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





        {{-- SUMMARY CARD --}}


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





        {{-- FILTER AKTIF --}}


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
                        items-center
                        justify-center
                        rounded-lg
                        bg-brand-100
                        text-brand-600
                    ">

                        <i
                            class="
                            {{ $activeCard['icon'] ?? 'ri-filter-line' }}
                            text-lg
                        "></i>


                    </span>



                    <div>


                        <p
                            class="
                            text-sm
                            font-semibold
                            text-brand-700
                        ">

                            Filter status aktif

                        </p>



                        <p
                            class="
                            text-sm
                            text-brand-600
                        ">

                            {{ $activeCard['title'] ?? 'Status Keberatan' }}

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
                ">

                    <i class="ri-close-line"></i>

                    Tampilkan Semua


                </a>


            </div>
        @endif






        {{-- TABLE --}}


        <x-tables.keberatan-table :keberatan="$keberatanData" :ppid-pembantu-list="$ppidPembantuData" :status-options="[]" />


    </div>


@endsection
