@extends('layouts.admin.app')

@section('title', 'Edit Pengadaan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header
            title="Edit Pengadaan"
            description="Perbarui informasi paket pengadaan dan unit PPID Pembantu yang bertanggung jawab."
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
                    'label' => 'Detail Pengadaan',
                    'url' => route(
                        'admin.pengadaan.show',
                        $pengadaan->id
                    ),
                ],
                [
                    'label' => 'Edit Pengadaan',
                ],
            ]"
        />

        <x-ui.flash-messages />

        <x-forms.pengadaan-form
            :action="route(
                'admin.pengadaan.update',
                $pengadaan->id
            )"
            method="PUT"
            :pengadaan="$pengadaan"
            :ppid-pembantu="$ppidPembantu"
            :locked-ppid="$lockedPpid"
            title="Edit Informasi Pengadaan"
            description="Admin PPID Pembantu hanya dapat memperbarui data pengadaan milik unitnya sendiri."
            submit-label="Simpan Perubahan"
            :cancel-url="route(
                'admin.pengadaan.show',
                $pengadaan->id
            )"
        />
    </div>
@endsection