@extends('layouts.admin.app')

@section('title', 'Edit Menu')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">
        Edit Menu
    </div>

    <div class="panel-card-body">

        <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST">

            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label class="form-label">Nama Menu</label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $menu->nama) }}"
                    required>
            </div>

            {{-- Jenis --}}
            <div class="mb-4">
                <label class="form-label">Jenis Menu</label>

                <select
                    id="tipe"
                    name="tipe"
                    class="form-control">

                    <option value="">-- Pilih Jenis Menu --</option>

                    <option value="page"
                        {{ old('tipe', $menu->tipe) == 'page' ? 'selected' : '' }}>
                        Halaman
                    </option>

                    <option value="module"
                        {{ old('tipe', $menu->tipe) == 'module' ? 'selected' : '' }}>
                        Module
                    </option>

                    <option value="url"
                        {{ old('tipe', $menu->tipe) == 'url' ? 'selected' : '' }}>
                        URL
                    </option>

                </select>
            </div>

            {{-- Halaman --}}
            <div
                id="pageField"
                class="mb-4"
                style="display:none;">

                <label class="form-label">Halaman</label>

                <select
                    id="page_id"
                    name="page_id"
                    class="form-control">

                    <option value="">-- Pilih Halaman --</option>

                    @foreach($pages as $page)

                        <option
                            value="{{ $page->id }}"
                            {{ old('page_id', $menu->page_id) == $page->id ? 'selected' : '' }}>

                            {{ $page->judul }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Module --}}
            <div
                id="moduleField"
                class="mb-4"
                style="display:none;">

                <label class="form-label">Module</label>

                <select
                    id="module_id"
                    name="module_id"
                    class="form-control">

                    <option value="">-- Pilih Module --</option>

                    @foreach($modules as $module)

                        <option
                            value="{{ $module->id }}"
                            {{ old('module_id', $menu->module_id) == $module->id ? 'selected' : '' }}>

                            {{ $module->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- URL --}}
            <div
                id="urlField"
                class="mb-4"
                style="display:none;">

                <label class="form-label">URL</label>

                <input
                    type="text"
                    id="url"
                    name="url"
                    class="form-control"
                    value="{{ old('url', $menu->url) }}"
                    placeholder="https://example.com">

            </div>

            {{-- Parent --}}
            <div class="mb-4">

                <label class="form-label">Parent Menu</label>

                <select
                    name="parent_id"
                    class="form-control">

                    <option value="">
                        Tidak Ada
                    </option>

                    @foreach($parents as $parent)

                        <option
                            value="{{ $parent->id }}"
                            {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>

                            {{ $parent->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Urutan --}}
            <div class="mb-4">

                <label class="form-label">Urutan</label>

                <input
                    type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order', $menu->sort_order) }}">

            </div>

            {{-- Status --}}
            <div class="mb-4">

                <label class="form-label">Status</label>

                <select
                    name="is_active"
                    class="form-control">

                    <option
                        value="1"
                        {{ old('is_active', $menu->is_active) == 1 ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option
                        value="0"
                        {{ old('is_active', $menu->is_active) == 0 ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Update Menu

            </button>

            <a
                href="{{ route('admin.menu.index') }}"
                class="btn btn-secondary">

                Kembali

            </a>

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

function tampilkanField()
{
    pageField.style.display = 'none';
    moduleField.style.display = 'none';
    urlField.style.display = 'none';

    if (tipe.value === 'page')
    {
        pageField.style.display = 'block';
    }

    if (tipe.value === 'module')
    {
        moduleField.style.display = 'block';
    }

    if (tipe.value === 'url')
    {
        urlField.style.display = 'block';
    }
}

tipe.addEventListener('change', tampilkanField);

document.addEventListener('DOMContentLoaded', tampilkanField);

</script>

@endpush