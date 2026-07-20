@extends('layouts.admin.app')

@section('title', 'Data Pejabat')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Pejabat"
            description="Kelola profil pejabat, jabatan, masa jabatan, data kelahiran, foto, dan informasi kontak."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Master Data',
                ],
                [
                    'label' => 'Pejabat',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL PEJABAT
        ============================================================= --}}

        <x-tables.pejabat-table :pejabat="$pejabat" />
    </div>
@endsection
