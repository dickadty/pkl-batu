@extends('layouts.admin.app')

@section('title', 'Tambah Berita')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Tambah Berita"
            description="Tambahkan berita baru beserta judul, isi publikasi, dan gambar utama yang akan ditampilkan kepada masyarakat."
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
                    'label' => 'Berita',
                    'url' => route('admin.berita.index'),
                ],
                [
                    'label' => 'Tambah Berita',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM BERITA
        ============================================================= --}}

        <x-forms.berita-form :action="route('admin.berita.store')" method="POST" title="Form Input Berita"
            description="Field judul wajib diisi. Isi berita dan gambar dapat dilengkapi sesuai kebutuhan publikasi. Gambar maksimal berukuran 2 MB."
            submit-label="Simpan Berita" :cancel-url="route('admin.berita.index')" />
    </div>
@endsection
