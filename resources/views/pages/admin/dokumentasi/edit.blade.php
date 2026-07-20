@extends('layouts.admin.app')

@section('title', 'Edit Informasi Publik')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Edit Informasi Publik"
            description="Perbarui dokumen informasi publik beserta klasifikasi, ringkasan, tahun, unit PPID penanggung jawab, dan berkasnya."
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
                    'label' => 'Detail Informasi',
                    'url' => route('admin.informasi-publik.show', $dokumentasi->id),
                ],
                [
                    'label' => 'Edit Informasi',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT INFORMASI PUBLIK
        ============================================================= --}}

        <x-forms.informasi-publik-form :action="route('admin.informasi-publik.update', $dokumentasi->id)" method="PUT" :informasi="$dokumentasi" :admin="$admin" :ppid-pembantu="$ppidPembantu"
            title="Edit Informasi Publik"
            description="Periksa kembali seluruh informasi sebelum menyimpan perubahan. File lama tetap digunakan apabila Anda tidak mengunggah file pengganti."
            submit-label="Simpan Perubahan" :cancel-url="route('admin.informasi-publik.show', $dokumentasi->id)" />
    </div>
@endsection
