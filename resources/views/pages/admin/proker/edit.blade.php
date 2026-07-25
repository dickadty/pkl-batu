@extends('layouts.admin.app')

@section('title', 'Edit Program Kerja')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Edit Program Kerja"
            description="Perbarui data program kerja, jadwal, penanggung jawab, dan dokumen pendukung." :breadcrumbs="[
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
                    'label' => 'Edit Program Kerja',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-forms.proker-form :action="route('admin.proker.update', ['id' => $proker->id])" method="PUT" :proker="$proker" :admin="$admin" :ppid-pembantu-list="$ppidPembantuList"
            submit-label="Simpan Perubahan" :cancel-url="route('admin.proker.show', ['id' => $proker->id])" />
    </div>
@endsection
