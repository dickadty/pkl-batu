@extends('layouts.admin.app')

@section('title', 'Edit PPID Pembantu')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header
            title="Edit PPID Pembantu"
            description="Perbarui profil unit PPID Pembantu beserta kategori, keterangan, website, kontak, alamat, dan pengaturan tampilannya."
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
                    'label' => 'PPID Pembantu',
                    'url' => route('admin.ppid-pembantu.index'),
                ],
                [
                    'label' => 'Detail PPID Pembantu',
                    'url' => route(
                        'admin.ppid-pembantu.show',
                        $ppidPembantu->id
                    ),
                ],
                [
                    'label' => 'Edit PPID Pembantu',
                ],
            ]"
        />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT PPID PEMBANTU
        ============================================================= --}}

        <x-forms.ppid-pembantu-form
            :action="route(
                'admin.ppid-pembantu.update',
                $ppidPembantu->id
            )"
            method="PUT"
            :ppid-pembantu="$ppidPembantu"
            :kategori="$kategori"
            title="Edit Profil PPID Pembantu"
            description="Periksa kembali profil, kategori, website, kontak, alamat, dan ikon unit sebelum menyimpan perubahan."
            submit-label="Simpan Perubahan"
            :cancel-url="route(
                'admin.ppid-pembantu.show',
                $ppidPembantu->id
            )"
        />
    </div>
@endsection