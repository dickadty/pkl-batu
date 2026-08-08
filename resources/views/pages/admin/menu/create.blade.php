@extends('layouts.admin.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <x-admin.page-header
        title="Tambah Menu"
        description="Tambahkan menu navigasi baru untuk website."
        :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Menu',
                'url' => route('admin.menu.index'),
            ],
            [
                'label' => 'Tambah',
            ],
        ]">

        <a href="{{ route('admin.menu.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">

            <i class="ri-arrow-left-line"></i>
            Kembali

        </a>

    </x-admin.page-header>

    <x-ui.flash-messages />

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <form action="{{ route('admin.menu.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">

                {{-- Nama Menu --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Nama Menu
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                </div>

                {{-- Jenis --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Jenis Menu
                    </label>

                    <select
                        id="tipe"
                        name="tipe"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                        <option value="">Pilih Jenis</option>

                        <option value="page" {{ old('tipe')=='page'?'selected':'' }}>
                            Halaman
                        </option>

                        <option value="module" {{ old('tipe')=='module'?'selected':'' }}>
                            Module
                        </option>

                        <option value="url" {{ old('tipe')=='url'?'selected':'' }}>
                            URL
                        </option>

                    </select>

                </div>

                {{-- Halaman --}}
                <div id="pageField" style="display:none;">

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Halaman
                    </label>

                    <select
                        id="page_id"
                        name="page_id"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        <option value="">Pilih Halaman</option>

                        @foreach($pages as $page)

                            <option
                                value="{{ $page->id }}"
                                {{ old('page_id')==$page->id?'selected':'' }}>

                                {{ $page->judul }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Module --}}
                <div id="moduleField" style="display:none;">

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Module
                    </label>

                    <select
                        id="module_id"
                        name="module_id"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        <option value="">Pilih Module</option>

                        @foreach($modules as $module)

                            <option
                                value="{{ $module->id }}"
                                {{ old('module_id')==$module->id?'selected':'' }}>

                                {{ $module->nama }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- URL --}}
                <div id="urlField" style="display:none;">

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        URL
                    </label>

                    <input
                        type="text"
                        id="url"
                        name="url"
                        value="{{ old('url') }}"
                        placeholder="https://example.com"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                </div>

                {{-- Parent --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Parent Menu
                    </label>

                    <select
                        name="parent_id"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        <option value="">Tidak Ada</option>

                        @foreach($parents as $parent)

                            <option
                                value="{{ $parent->id }}"
                                {{ old('parent_id')==$parent->id?'selected':'' }}>

                                {{ $parent->nama }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Urutan --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Urutan
                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order',0) }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                </div>

                {{-- Status --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="is_active"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        <option value="1" {{ old('is_active',1)==1?'selected':'' }}>
                            Aktif
                        </option>

                        <option value="0" {{ old('is_active')==='0'?'selected':'' }}>
                            Tidak Aktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                <a
                    href="{{ route('admin.menu.index') }}"
                    class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">

                    Batal

                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">

                    <i class="ri-save-line mr-1"></i>
                    Simpan Menu

                </button>

            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
const tipe = document.getElementById('tipe');
const pageField = document.getElementById('pageField');
const moduleField = document.getElementById('moduleField');
const urlField = document.getElementById('urlField');

const nama = document.getElementById('nama');
const pageSelect = document.getElementById('page_id');
const moduleSelect = document.getElementById('module_id');

function tampilkanField() {

    pageField.style.display = 'none';
    moduleField.style.display = 'none';
    urlField.style.display = 'none';

    if (tipe.value === 'page') {
        pageField.style.display = 'block';
    }

    if (tipe.value === 'module') {
        moduleField.style.display = 'block';
    }

    if (tipe.value === 'url') {
        urlField.style.display = 'block';
    }
}

pageSelect.addEventListener('change', function () {
    if (this.value) {
        nama.value = this.options[this.selectedIndex].text;
    }
});

moduleSelect.addEventListener('change', function () {
    if (this.value) {
        nama.value = this.options[this.selectedIndex].text;
    }
});

document.addEventListener('DOMContentLoaded', tampilkanField);
tipe.addEventListener('change', tampilkanField);
</script>
@endpush