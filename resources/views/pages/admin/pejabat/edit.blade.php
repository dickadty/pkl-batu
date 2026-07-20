@extends('layouts.admin.app')

@section('title', 'Edit Pejabat')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Edit Pejabat"
            description="Perbarui profil pejabat beserta jabatan, masa jabatan, data kelahiran, alamat, kontak, dan foto resmi."
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
                    'label' => 'Detail Pejabat',
                    'url' => route('admin.pejabat.show', $pejabat->id),
                ],
                [
                    'label' => 'Edit Pejabat',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT PEJABAT
        ============================================================= --}}

        <x-forms.pejabat-form :action="route('admin.pejabat.update', $pejabat->id)" method="PUT" :pejabat="$pejabat" title="Edit Informasi Pejabat"
            description="Periksa kembali data pejabat sebelum menyimpan perubahan. Foto lama tetap digunakan apabila Anda tidak mengunggah foto pengganti."
            submit-label="Simpan Perubahan" :cancel-url="route('admin.pejabat.show', $pejabat->id)" />
    </div>
@endsection
