@extends('layouts.admin.app')

@section('title', 'Permohonan Informasi')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar Permohonan Informasi"
            description="Kelola permohonan informasi publik, identitas pemohon, unit PPID tujuan, rincian permohonan, dan status penyelesaiannya."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pelayanan Informasi',
                ],
                [
                    'label' => 'Permohonan Informasi',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL PERMOHONAN INFORMASI
        ============================================================= --}}

        <x-tables.permohonan-informasi-table :permohonan="$permohonan" :ppid-pembantu-list="$ppidPembantuList ?? collect()" />
    </div>
@endsection
