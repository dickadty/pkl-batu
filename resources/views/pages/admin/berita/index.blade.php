@extends('layouts.admin.app')

@section('title', 'Berita')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Berita"
            description="Kelola gambar, judul, isi singkat, tanggal publikasi, dan data berita yang ditampilkan melalui sistem."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Berita',
                ],
                [
                    'label' => 'Daftar Berita',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL BERITA
        ============================================================= --}}

        <x-tables.berita-table :berita="$berita" />
    </div>
@endsection
