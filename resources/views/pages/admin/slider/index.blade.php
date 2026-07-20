@extends('layouts.admin.app')

@section('title', 'Data Slider')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Slider"
            description="Kelola banner, judul, dan tanggal penayangan slider yang ditampilkan pada halaman utama website."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Konten & Informasi',
                ],
                [
                    'label' => 'Slider',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL SLIDER
        ============================================================= --}}

        <x-tables.slider-table :slider="$slider" />
    </div>
@endsection
