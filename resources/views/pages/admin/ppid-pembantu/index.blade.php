@extends('layouts.admin.app')

@section('title', 'Daftar PPID Pelaksana')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar PPID Pelaksana"
            description="Kelola data profil, kategori, kontak, website, dan alamat PPID Pelaksana." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'PPID Pelaksana',
                ],
                [
                    'label' => 'Daftar PPID Pelaksana',
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
