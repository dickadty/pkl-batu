@extends('layouts.admin.app')

@section('title', 'Tambah Kategori Informasi')

@section('content')
<div class="space-y-6">

    <x-admin.page-header
        title="Tambah Kategori Informasi"
        description="Tambahkan kategori informasi publik beserta sifat informasinya."
        :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Kategori Informasi',
                'url' => route('admin.kategori-informasi.index'),
            ],
            [
                'label' => 'Tambah',
            ],
        ]"
    />

    <x-ui.flash-messages />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

        <form
            action="{{ route('admin.kategori-informasi.store') }}"
            method="POST"
            class="space-y-6"
        >
            @csrf

            {{-- Nama Kategori --}}
            <div>
                <label
                    for="nama"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Nama Kategori
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    placeholder="Masukkan nama kategori informasi"
                    class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                >

                @error('nama')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Sifat --}}
            <div>
                <label
                    for="sifat"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Sifat Informasi
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="sifat"
                    name="sifat"
                    required
                    class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                >
                    <option value="">Pilih Sifat Informasi</option>

                    <option value="berkala"
                        @selected(old('sifat') == 'berkala')>
                        Berkala
                    </option>

                    <option value="setiap saat"
                        @selected(old('sifat') == 'setiap saat')>
                        Setiap Saat
                    </option>

                    <option value="serta merta"
                        @selected(old('sifat') == 'serta merta')>
                        Serta Merta
                    </option>

                    <option value="dikecualikan"
                        @selected(old('sifat') == 'dikecualikan')>
                        Dikecualikan
                    </option>
                </select>

                @error('sifat')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-4">
                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white hover:bg-brand-600"
                >
                    Simpan Kategori
                </button>

                <a
                    href="{{ route('admin.kategori-informasi.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 px-5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300"
                >
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>
@endsection