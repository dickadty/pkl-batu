@extends('layouts.admin.app')

@section('title', 'Data Pengadaan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Data Pengadaan"
            description="Kelola paket pengadaan sesuai unit PPID Pembantu yang bertanggung jawab." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pengadaan',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-tables.pengadaan-table :pengadaan-list="$pengadaanList" />
    </div>
@endsection
