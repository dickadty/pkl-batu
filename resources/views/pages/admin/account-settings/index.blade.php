@extends('layouts.admin.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Pengaturan Akun" description="Kelola informasi akun dan keamanan password admin."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pengaturan Akun',
                ],
            ]" />

        <x-ui.flash-messages />

        {{-- <x-admin.account-settings.profile-card :admin="$admin" /> --}}

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-2
            ">
            <x-admin.account-settings.account-information-card :admin="$admin" />

            <x-admin.account-settings.password-card />
        </div>
    </div>
@endsection
