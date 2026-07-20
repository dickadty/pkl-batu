@extends('layouts.admin.app')

@section('title', 'Daftar PPID Pembantu')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar PPID Pembantu"
            description="Kelola data profil, kategori, kontak, website, dan alamat PPID Pembantu." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'PPID Pembantu',
                ],
                [
                    'label' => 'Daftar PPID Pembantu',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL PPID PEMBANTU
        ============================================================= --}}

        <x-tables.ppid-pembantu-table :ppid-pembantu="$ppidPembantu" :kategori-ppid-list="$kategoriPpidList ?? collect()" />
    </div>
@endsection
