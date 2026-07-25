@extends('layouts.admin.app')

@section('title', 'Daftar Akun Admin')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Akun Admin"
            description="Kelola akun Admin Utama dan Admin PPID Pembantu beserta unit yang dikelolanya." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Master Data',
                ],
                [
                    'label' => 'Akun Admin',
                ],
                [
                    'label' => 'Daftar Akun Admin',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL AKUN ADMIN
        ============================================================= --}}

        <x-tables.akun-admin-table :akun-admin="$akunAdmin" :ppid-pembantu-list="$ppidPembantu ?? collect()" />
    </div>
@endsection
