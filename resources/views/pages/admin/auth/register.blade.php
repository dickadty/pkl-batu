@extends('layouts.admin.app')

@section('title', 'Tambah Akun Admin')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Tambah Akun Admin"
            description="Tambahkan akun administrator baru, tentukan role, unit PPID yang dikelola, dan informasi keamanannya."
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
                    'label' => 'Akun Admin',
                ],
                [
                    'label' => 'Tambah Akun Admin',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM AKUN ADMIN
        ============================================================= --}}

        <x-forms.akun-admin-form :action="route('admin.akun-admin.store')" method="POST" :ppid-pembantu="$ppidPembantu" title="Informasi Akun Admin"
            description="Field bertanda bintang wajib diisi. PPID Pembantu hanya wajib dipilih ketika role akun adalah Admin Pembantu."
            submit-label="Simpan Akun Admin" :cancel-url="route('admin.dashboard')" />
    </div>
@endsection
