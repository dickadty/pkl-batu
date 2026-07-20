@extends('layouts.admin.app')

@section('title', 'Edit FAQ')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Edit FAQ"
            description="Perbarui pertanyaan, jawaban, dan status publikasi FAQ yang ditampilkan kepada masyarakat."
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
                    'url' => route('admin.faq.index'),
                ],
                [
                    'label' => 'Detail FAQ',
                    'url' => route('admin.faq.show', $faq->id),
                ],
                [
                    'label' => 'Edit FAQ',
                ],
            ]" />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM EDIT FAQ
        ============================================================= --}}

        <x-forms.faq-form :action="route('admin.faq.update', $faq->id)" method="PUT" :faq="$faq" title="Edit Informasi FAQ"
            description="Perbarui pertanyaan dan jawaban secara jelas. Aktifkan status publikasi agar FAQ tetap ditampilkan pada halaman publik."
            submit-label="Simpan Perubahan" :cancel-url="route('admin.faq.show', $faq->id)" />
    </div>
@endsection
