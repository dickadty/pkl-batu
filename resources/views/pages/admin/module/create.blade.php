@extends('layouts.admin.app')

@section('title', 'Tambah Module')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <x-admin.page-header
        title="Tambah Module"
        description="Tambahkan module baru ke dalam sistem."
        :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Module',
                'url' => route('admin.module.index'),
            ],
            [
                'label' => 'Tambah',
            ],
        ]">

        <a href="{{ route('admin.module.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">

            <i class="ri-arrow-left-line"></i>
            Kembali

        </a>

    </x-admin.page-header>

    <x-ui.flash-messages />

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <form action="{{ route('admin.module.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">

                {{-- Nama Module --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Nama Module
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh : Berita"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                </div>

                {{-- Slug --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Slug
                    </label>

                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug') }}"
                        placeholder="berita"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                    <p class="mt-1 text-xs text-slate-500">
                        Slug akan digunakan sebagai identitas module.
                    </p>

                </div>

                {{-- Route --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Route Name
                    </label>

                    <input
                        type="text"
                        id="route_name"
                        name="route_name"
                        value="{{ old('route_name') }}"
                        placeholder="public.berita.index"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                </div>

                {{-- View --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        View Name
                    </label>

                    <input
                        type="text"
                        id="view_name"
                        name="view_name"
                        value="{{ old('view_name') }}"
                        placeholder="pages.public.berita.index"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                </div>

                {{-- Deskripsi --}}
                <div class="lg:col-span-2">

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Deskripsi singkat module"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">{{ old('description') }}</textarea>

                </div>

                {{-- Status --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="is_active"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                        <option value="1" {{ old('is_active',1)==1 ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ old('is_active')==='0' ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <a
                    href="{{ route('admin.module.index') }}"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">

                    Batal

                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">

                    <i class="ri-save-line mr-1"></i>
                    Simpan Module

                </button>

            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
const namaInput = document.getElementById('nama');
const slugInput = document.getElementById('slug');
const routeInput = document.getElementById('route_name');
const viewInput = document.getElementById('view_name');

let manualSlug = false;

function generateSlug(text)
{
    return text
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function updateRoute()
{
    if (slugInput.value === '') {
        routeInput.value = '';
        viewInput.value = '';
        return;
    }

    routeInput.value = `public.${slugInput.value}.index`;
    viewInput.value = `pages.public.${slugInput.value}.index`;
}

namaInput.addEventListener('input', function () {

    if (!manualSlug) {
        slugInput.value = generateSlug(this.value);
        updateRoute();
    }

});

slugInput.addEventListener('input', function () {

    manualSlug = true;
    this.value = generateSlug(this.value);

    updateRoute();

});

window.addEventListener('DOMContentLoaded', function () {

    if (slugInput.value === '') {
        slugInput.value = generateSlug(namaInput.value);
    }

    updateRoute();

});
</script>
@endpush