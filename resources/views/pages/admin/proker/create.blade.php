@extends('layouts.admin.app')

@section('title', 'Tambah Program Kerja')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header
            title="Tambah Program Kerja"
            description="Tambahkan program kerja baru beserta anggaran, target, jadwal, dan dokumen pendukung."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Program Kerja',
                    'url' => route('admin.proker.index'),
                ],
                [
                    'label' => 'Tambah Program Kerja',
                ],
            ]"
        />

        <x-ui.flash-messages />

        <x-forms.proker-form
            :action="route('admin.proker.store')"
            method="POST"
            :admin="$admin"
            :ppid-pembantu-list="$ppidPembantuList"
            submit-label="Simpan Program Kerja"
            :cancel-url="route('admin.proker.index')"
        />
    </div>
@endsection