@extends('layouts.admin.app')

@section('title', 'Notifikasi')

@section('content')
    @php
        $currentStatus = $status ?? request('status', 'semua');
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Notifikasi" description="Kelola seluruh pemberitahuan aktivitas permohonan informasi."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Notifikasi',
                ],
            ]">
            <x-slot:actions>
                <div
                    class="
                        flex
                        flex-wrap
                        items-center
                        gap-2
                    ">
                    @if (Route::has('admin.notifikasi.test'))
                        <form action="{{ route('admin.notifikasi.test') }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="
                                    inline-flex
                                    h-11
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    bg-purple-600
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-theme-xs
                                    transition
                                    hover:bg-purple-700
                                ">
                                <i class="ri-flask-line text-lg"></i>

                                Kirim Notifikasi Uji
                            </button>
                        </form>
                    @endif

                    @if ($totalBelumDibaca > 0)
                        <form action="{{ route('admin.notifikasi.baca-semua') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="
                                    inline-flex
                                    h-11
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-white
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    shadow-theme-xs
                                    transition
                                    hover:border-brand-300
                                    hover:bg-brand-50
                                    hover:text-brand-600
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-gray-300
                                    dark:hover:border-brand-500
                                    dark:hover:bg-brand-500/10
                                    dark:hover:text-brand-400
                                ">
                                <i class="ri-check-double-line text-lg"></i>

                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif

                    @if ($totalSudahDibaca > 0)
                        <form action="{{ route('admin.notifikasi.hapus-semua-dibaca') }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus semua notifikasi yang sudah dibaca?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="
                                    inline-flex
                                    h-11
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    border
                                    border-red-200
                                    bg-red-50
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-red-600
                                    shadow-theme-xs
                                    transition
                                    hover:bg-red-100
                                    dark:border-red-500/20
                                    dark:bg-red-500/10
                                    dark:text-red-400
                                    dark:hover:bg-red-500/20
                                ">
                                <i class="ri-delete-bin-6-line text-lg"></i>

                                Hapus yang Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        <div
            class="
                grid
                grid-cols-1
                gap-4
                md:grid-cols-3
            ">
            <x-summary-card title="Semua Notifikasi" :value="$totalSemua" icon="ri-notification-3-line"
                :url="route('admin.notifikasi.index', ['status' => 'semua'])" :active="$currentStatus === 'semua'" tone="brand" />

            <x-summary-card title="Belum Dibaca" :value="$totalBelumDibaca" icon="ri-mail-unread-line"
                :url="route('admin.notifikasi.index', ['status' => 'belum_dibaca'])" :active="$currentStatus === 'belum_dibaca'" tone="red" />

            <x-summary-card title="Sudah Dibaca" :value="$totalSudahDibaca" icon="ri-mail-check-line"
                :url="route('admin.notifikasi.index', ['status' => 'sudah_dibaca'])" :active="$currentStatus === 'sudah_dibaca'" tone="green" />
        </div>

        <x-tables.notifikasi-table :notifications="$notifications" />
    </div>
@endsection
