@extends('layouts.admin.app')

@section('title', 'Tambah Halaman')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header
            title="Tambah Halaman"
            description="Tambahkan halaman baru yang akan ditampilkan pada website. Isi judul, status publikasi, dan konten halaman sesuai kebutuhan."
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
                    'label' => 'Halaman',
                    'url' => route('admin.pages.create'),
                ],
                [
                    'label' => 'Tambah Halaman',
                ],
            ]"
        />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            FORM HALAMAN
        ============================================================= --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">
                    Form Input Halaman
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi informasi halaman yang akan dipublikasikan.
                </p>
            </div>

            <form action="{{ route('admin.pages.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Judul --}}
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-slate-700">
                        Judul Halaman <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="{{ old('judul') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Masukkan judul halaman">

                    @error('judul')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Module --}}
<div>
    <label for="module_id" class="block mb-2 text-sm font-medium text-slate-700">
        Jenis Halaman <span class="text-red-500">*</span>
    </label>

    <select
        id="module_id"
        name="module_id"
        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">

        @foreach ($modules as $module)
            <option
                value="{{ $module->id }}"
                {{ old('module_id') == $module->id ? 'selected' : '' }}>

                {{ $module->nama }}

            </option>
        @endforeach

    </select>

    <p class="mt-2 text-sm text-slate-500">
        Pilih jenis halaman yang akan dibuat.
    </p>

    @error('module_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-slate-700">
                        Status Publikasi
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">

                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                            Published
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konten --}}
                <div>
                    <label for="content" class="block mb-2 text-sm font-medium text-slate-700">
                        Isi Halaman
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="12"
                        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Tulis isi halaman di sini...">{{ old('content') }}</textarea>

                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
                    <a href="{{ route('admin.pages.index') }}"
                        class="inline-flex items-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">
                        Simpan Halaman
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection