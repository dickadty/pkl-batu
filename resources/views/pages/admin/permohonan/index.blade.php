@extends('layouts.admin.app')

@section('title', 'Permohonan Informasi')

@section('content')
    @php
        $currentStatus = $currentStatus ?? request('status', 'semua');

        $statusUrl = static function (string $status): string {
            $query = [
                'status' => $status,
                'q' => request('q'),
                'ppid_pembantuid' => request('ppid_pembantuid'),
            ];

            $query = array_filter($query, static fn($value): bool => $value !== null && $value !== '');

            return route('admin.permohonan.index', $query);
        };

        $summaryCards = [
            [
                'status' => 'semua',
                'title' => 'Semua Permohonan',
                'value' => $totalSemua ?? 0,
                'icon' => 'ri-file-list-3-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'diajukan',
                'title' => 'Diajukan',
                'value' => $totalDiajukan ?? 0,
                'icon' => 'ri-file-add-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'diproses',
                'title' => 'Diproses',
                'value' => $totalDiproses ?? 0,
                'icon' => 'ri-loader-4-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'diteruskan',
                'title' => 'Diteruskan',
                'value' => $totalDiteruskan ?? 0,
                'icon' => 'ri-send-plane-2-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'menunggu_validasi',
                'title' => 'Menunggu Validasi',
                'value' => $totalMenungguValidasi ?? 0,
                'icon' => 'ri-time-line',
                'tone' => 'brand',
            ],
            [
                'status' => 'revisi',
                'title' => 'Perlu Revisi',
                'value' => $totalRevisi ?? 0,
                'icon' => 'ri-edit-2-line',
                'tone' => 'red',
            ],
            [
                'status' => 'selesai',
                'title' => 'Selesai',
                'value' => $totalSelesai ?? 0,
                'icon' => 'ri-checkbox-circle-line',
                'tone' => 'green',
            ],
            [
                'status' => 'ditolak',
                'title' => 'Ditolak',
                'value' => $totalDitolak ?? 0,
                'icon' => 'ri-close-circle-line',
                'tone' => 'red',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Daftar Permohonan Informasi"
            description="Kelola permohonan informasi publik, identitas pemohon, unit PPID tujuan, rincian permohonan, dan status penyelesaiannya."
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
                    'label' => 'Permohonan Informasi',
                ],
            ]" />

        <x-ui.flash-messages />

        <div
            class="
                grid
                grid-cols-1
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            ">
            @foreach ($summaryCards as $card)
                <x-summary-card :title="$card['title']" :value="$card['value']" :icon="$card['icon']" :url="$statusUrl($card['status'])" :active="$currentStatus === $card['status']"
                    :tone="$card['tone']" />
            @endforeach
        </div>

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
                <div class="flex items-center gap-3">
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
                            {{ $activeCard['title'] ?? 'Status Permohonan' }}
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
                    <i class="ri-close-line text-base"></i>

                    Tampilkan Semua
                </a>
            </div>
        @endif

        <x-tables.permohonan-informasi-table :permohonan="$permohonan" :ppid-pembantu-list="$ppidPembantuList ?? collect()" />
    </div>
@endsection
