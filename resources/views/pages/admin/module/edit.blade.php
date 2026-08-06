@extends('layouts.admin.app')

@section('title', 'Edit Module')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">
        Edit Module
    </div>

    <div class="panel-card-body">

        <form action="{{ route('admin.module.update', $module->id) }}" method="POST">

            @csrf
            @method('PUT')

            {{-- Nama Module --}}
            <div class="mb-4">
                <label class="form-label">Nama Module</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $module->nama) }}"
                    required>
            </div>

            {{-- Slug --}}
            <div class="mb-4">
                <label class="form-label">Slug</label>
                <input
                    type="text"
                    id="slug"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug', $module->slug) }}"
                    required>
            </div>

            {{-- Route --}}
            <div class="mb-4">
                <label class="form-label">Route Name</label>
                <input
                    type="text"
                    id="route_name"
                    name="route_name"
                    class="form-control"
                    value="{{ old('route_name', $module->route_name) }}"
                    required>
            </div>

            {{-- View --}}
            <div class="mb-4">
                <label class="form-label">View Name</label>
                <input
                    type="text"
                    id="view_name"
                    name="view_name"
                    class="form-control"
                    value="{{ old('view_name', $module->view_name) }}"
                    required>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="form-label">Deskripsi</label>
                <textarea
                    name="description"
                    rows="5"
                    class="form-control">{{ old('description', $module->description) }}</textarea>
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $module->is_active ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="0" {{ !$module->is_active ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Update Module
            </button>

            <a href="{{ route('admin.module.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

@push('scripts')
<script>
const namaInput = document.getElementById('nama');
const slugInput = document.getElementById('slug');
const routeInput = document.getElementById('route_name');
const viewInput = document.getElementById('view_name');

function generateSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function updateField() {
    routeInput.value = `public.${slugInput.value}.index`;
    viewInput.value = `pages.public.${slugInput.value}.index`;
}

namaInput.addEventListener('input', function () {
    slugInput.value = generateSlug(this.value);
    updateField();
});

slugInput.addEventListener('input', function () {
    this.value = generateSlug(this.value);
    updateField();
});
</script>
@endpush

@endsection