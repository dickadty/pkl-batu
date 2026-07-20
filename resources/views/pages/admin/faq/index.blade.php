@extends('layouts.admin.app')

@section('title', 'FAQ')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Daftar FAQ"
            description="Kelola pertanyaan, jawaban, tanggal publikasi, dan status FAQ yang ditampilkan kepada masyarakat."
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
                    'label' => 'FAQ',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            TABEL FAQ
        ============================================================= --}}

        <x-tables.faq-table :faq="$faq" />
    </div>
@endsection
