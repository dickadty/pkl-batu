@extends('layouts.admin.app')

@section('title', 'Tambah Slider')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Tambah Slider"
            description="Tambahkan banner slider baru yang akan ditampilkan pada halaman utama website." :breadcrumbs="[
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
                    'url' => route('admin.slider.index'),
                ],
                [
                    'label' => 'Tambah Slider',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-forms.slider-form :action="route('admin.slider.store')" method="POST" title="Informasi Slider"
            description="Masukkan judul dan unggah banner dengan format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal banner adalah 3 MB."
            submit-label="Simpan Slider" :cancel-url="route('admin.slider.index')" />
    </div>
@endsection
