@extends('layouts.admin.app')

@section('title', 'Tambah Module')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">
        Tambah Module
    </div>

    <div class="panel-card-body">

        <form action="{{ route('admin.module.store') }}" method="POST">

            @csrf

            {{-- Nama Module --}}
            <div class="mb-4">

                <label class="form-label">
                    Nama Module
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama') }}"
                    placeholder="Contoh : Berita"
                    required>

            </div>

            {{-- Slug --}}
            <div class="mb-4">

                <label class="form-label">
                    Slug
                </label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug') }}"
                    placeholder="berita"
                    required>

                <small class="text-muted">
                    Slug akan digunakan sebagai identitas module.
                </small>

            </div>

            {{-- Route --}}
            <div class="mb-4">

                <label class="form-label">
                    Route Name
                </label>

                <input
                    type="text"
                    id="route_name"
                    name="route_name"
                    class="form-control"
                    value="{{ old('route_name') }}"
                    placeholder="public.berita.index"
                    required>

            </div>

            {{-- View --}}
            <div class="mb-4">

                <label class="form-label">
                    View Name
                </label>

                <input
                    type="text"
                    id="view_name"
                    name="view_name"
                    class="form-control"
                    value="{{ old('view_name') }}"
                    placeholder="pages.public.berita.index"
                    required>

            </div>

           
            {{-- Deskripsi --}}
            <div class="mb-4">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="form-control"
                    placeholder="Deskripsi singkat module">{{ old('description') }}</textarea>

            </div>

            {{-- Status --}}
            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="is_active"
                    class="form-control">

                    <option value="1" selected>
                        Aktif
                    </option>

                    <option value="0">
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.module.index') }}"
                    class="btn btn-secondary">

                    Kembali

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Simpan Module

                </button>

            </div>

        </form>

    </div>

</div>

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
    if(slugInput.value === '')
    {
        routeInput.value = '';
        viewInput.value = '';
        return;
    }

    routeInput.value = `public.${slugInput.value}.index`;
    viewInput.value = `pages.public.${slugInput.value}.index`;
}

namaInput.addEventListener('input', function(){

    if(!manualSlug)
    {
        slugInput.value = generateSlug(this.value);
        updateRoute();
    }

});

slugInput.addEventListener('input', function(){

    manualSlug = true;

    this.value = generateSlug(this.value);

    updateRoute();

});

window.onload = function(){

    if(slugInput.value === '')
    {
        slugInput.value = generateSlug(namaInput.value);
    }

    updateRoute();

}

</script>

@endpush

@endsection