@extends('layouts.admin.app')

@section('title', 'Tambah FAQ')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Tambah FAQ"
            description="Tambahkan pertanyaan dan jawaban baru yang akan membantu masyarakat memperoleh informasi secara cepat."
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
                    'label' => 'Tambah FAQ',
                ],
            ]" />

        <x-ui.flash-messages />

        <x-forms.faq-form :action="route('admin.faq.store')" method="POST" title="Informasi FAQ"
            description="Field bertanda bintang wajib diisi. Aktifkan status publikasi agar FAQ langsung muncul pada halaman publik."
            submit-label="Simpan FAQ" :cancel-url="route('admin.faq.index')" />
    </div>
@endsection
