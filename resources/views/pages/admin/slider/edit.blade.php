@extends('layouts.admin.app')

@section('title', 'Edit Slider')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header
            title="Edit Slider"
            description="Perbarui judul dan banner slider yang ditampilkan pada halaman utama website."
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
                    'url' => route('admin.slider.index'),
                ],
                [
                    'label' => 'Detail Slider',
                    'url' => route(
                        'admin.slider.show',
                        $slider->id
                    ),
                ],
                [
                    'label' => 'Edit Slider',
                ],
            ]"
        />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT SLIDER
        ============================================================= --}}

        <x-forms.slider-form
            :action="route(
                'admin.slider.update',
                $slider->id
            )"
            method="PUT"
            :slider="$slider"
            title="Edit Informasi Slider"
            description="Perbarui judul atau unggah banner pengganti. Banner lama tetap digunakan apabila Anda tidak memilih file baru."
            submit-label="Simpan Perubahan"
            :cancel-url="route(
                'admin.slider.show',
                $slider->id
            )"
        />
    </div>
@endsection