@extends('layouts.admin.app')

@section('title', 'Daftar Informasi Publik')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Informasi Publik"
            description="Kelola dokumen, klasifikasi, tahun, kepemilikan PPID Pembantu, serta status verifikasi informasi publik."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Informasi & Dokumentasi',
                ],
                [
                    'label' => 'Daftar Informasi Publik',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL INFORMASI PUBLIK
        ============================================================= --}}

        <x-tables.informasi-publik-table :dokumentasi="$dokumentasi" :admin="$admin" :ppid-pembantu-list="$ppidPembantuList ?? collect()" />
        
    </div>
@endsection
