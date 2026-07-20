@extends('layouts.admin.app')

@section('title', 'Tambah Pejabat')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header
            title="Tambah Pejabat"
            description="Tambahkan profil pejabat beserta jabatan, masa jabatan, data kelahiran, alamat, kontak, dan foto resmi."
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
                    'url' => route('admin.pejabat.index'),
                ],
                [
                    'label' => 'Tambah Pejabat',
                ],
            ]"
        />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM PEJABAT
        ============================================================= --}}

        <x-forms.pejabat-form
            :action="route('admin.pejabat.store')"
            method="POST"
            title="Informasi Pejabat"
            description="Lengkapi data pejabat secara akurat. Field bertanda bintang wajib diisi sebelum data dapat disimpan."
            submit-label="Simpan Pejabat"
            :cancel-url="route('admin.pejabat.index')"
        />
    </div>
@endsection