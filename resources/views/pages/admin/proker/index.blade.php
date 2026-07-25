@extends('layouts.admin.app')

@section('title', 'Program Kerja')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Program Kerja"
            description="Kelola program kerja, anggaran, sumber dana, target, jadwal, penanggung jawab, dan dokumen pendukung."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Program Kerja',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-tables.proker-table :proker="$proker" :admin="$admin" :ppid-pembantu-list="$ppidPembantuList" />
    </div>
@endsection
