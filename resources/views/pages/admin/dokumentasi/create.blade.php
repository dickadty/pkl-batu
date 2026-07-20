@extends('layouts.admin.app')

@section('title', 'Tambah Informasi Publik')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Tambah Informasi Publik"
            description="Tambahkan dokumen informasi publik beserta klasifikasi, ringkasan, tahun, dan unit PPID penanggung jawab."
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
                    'label' => 'Informasi Publik',
                    'url' => route('admin.informasi-publik.index'),
                ],
                [
                    'label' => 'Tambah Informasi',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-forms.informasi-publik-form :action="route('admin.informasi-publik.store')" method="POST" :admin="$admin" :ppid-pembantu="$ppidPembantu"
            title="Form Informasi Publik"
            description="Field bertanda bintang wajib diisi. Unggah dokumen dengan format yang didukung dan ukuran maksimal 5 MB."
            submit-label="Simpan Informasi" :cancel-url="route('admin.informasi-publik.index')" />
    </div>
@endsection
