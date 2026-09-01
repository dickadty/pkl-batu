@extends('layouts.admin.app')

@section('title', 'Tambah PPID Pelaksana')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Tambah PPID Pelaksana"
            description="Tambahkan unit PPID Pelaksana beserta profil, kategori, kontak, alamat, dan pengaturan tampilannya."
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
                    'label' => 'PPID Pelaksana',
                    'url' => route('admin.ppid-pembantu.index'),
                ],
                [
                    'label' => 'Tambah PPID Pelaksana',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM PPID PEMBANTU
        ============================================================= --}}

        <x-forms.ppid-pembantu-form :action="route('admin.ppid-pembantu.store')" method="POST" :kategori="$kategori" title="Informasi Profil PPID Pelaksana"
            description="Lengkapi seluruh informasi yang diperlukan. Field bertanda bintang wajib diisi sebelum data dapat disimpan."
            submit-label="Simpan PPID" :cancel-url="route('admin.ppid-pembantu.index')" />
    </div>
@endsection
