@extends('layouts.admin.app')

@section('title', 'Edit Berita')

@section('content')
    <div class="space-y-6">

        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Edit Berita"
            description="Perbarui judul, isi publikasi, atau gambar utama berita yang akan ditampilkan kepada masyarakat."
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
                    'label' => 'Edit Berita',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT BERITA
        ============================================================= --}}

        <x-forms.berita-form :action="route('admin.berita.update', $berita->id)" method="PUT" title="Form Edit Berita"
            description="Perbarui informasi berita sesuai kebutuhan. Judul wajib diisi. Gambar dapat diganti atau dibiarkan menggunakan gambar yang sudah ada."
            submit-label="Simpan Perubahan" :cancel-url="route('admin.berita.index')" :berita="$berita" />

    </div>
@endsection
