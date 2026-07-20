@extends('layouts.admin.app')

@section('title', 'Tambah Pengadaan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Tambah Pengadaan"
            description="Tambahkan paket pengadaan beserta anggaran, sumber dana, metode, rencana kegiatan, dan unit PPID."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pengadaan',
                    'url' => route('admin.pengadaan.index'),
                ],
                [
                    'label' => 'Tambah Pengadaan',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-forms.pengadaan-form :action="route('admin.pengadaan.store')" method="POST" :ppid-pembantu="$ppidPembantu" :locked-ppid="$lockedPpid" title="Form Pengadaan"
            description="Admin Utama dapat memilih unit PPID. Admin PPID Pembantu otomatis menggunakan unit yang terhubung dengan akunnya."
            submit-label="Simpan Pengadaan" :cancel-url="route('admin.pengadaan.index')" />
    </div>
@endsection
