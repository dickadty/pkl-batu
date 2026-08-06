@extends('layouts.admin.app')

@section('title', 'Tambah Menu')

@section('content')

<div class="panel-card">

    <div class="panel-card-header">
        Tambah Menu
    </div>

    <div class="panel-card-body">

        <form action="{{ route('admin.menu.store') }}" method="POST">

            @csrf

            {{-- Nama Menu --}}
            <div class="mb-4">
                <label>Nama Menu</label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control"
                    value="{{ old('nama') }}"
                    required>
            </div>

            {{-- Jenis Menu --}}
            <div class="mb-4">

                <label>Jenis Menu</label>

                <select
                    name="tipe"
                    id="tipe"
                    class="form-control">

                    <option value="">-- Pilih --</option>

                    <option value="page">Halaman</option>

                    <option value="module">Module</option>

                    <option value="url">URL</option>

                </select>

            </div>

            {{-- Page --}}
            <div
                id="pageField"
                class="mb-4"
                style="display:none;">

                <label>Halaman</label>

                <select
                    id="page_id"
                    name="page_id"
                    class="form-control">

                    <option value="">-- Pilih Halaman --</option>

                    @foreach($pages as $page)

                        <option value="{{ $page->id }}">

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

                <label>Module</label>

                <select
                    id="module_id"
                    name="module_id"
                    class="form-control">

                    <option value="">-- Pilih Module --</option>

                    @foreach($modules as $module)

                        <option value="{{ $module->id }}">

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

                <label>URL</label>

                <input
                    type="text"
                    id="url"
                    name="url"
                    class="form-control"
                    placeholder="https://example.com">

            </div>

            {{-- Parent --}}
            <div class="mb-4">

                <label>Parent Menu</label>

                <select
                    name="parent_id"
                    class="form-control">

                    <option value="">
                        Tidak Ada
                    </option>

                    @foreach($parents as $parent)

                        <option value="{{ $parent->id }}">

                            {{ $parent->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Urutan --}}
            <div class="mb-4">

                <label>Urutan</label>

                <input
                    type="number"
                    name="sort_order"
                    class="form-control"
                    value="0">

            </div>

            {{-- Status --}}
            <div class="mb-4">

                <label>Status</label>

                <select
                    name="is_active"
                    class="form-control">

                    <option value="1">
                        Aktif
                    </option>

                    <option value="0">
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Simpan

            </button>

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

function resetField()
{
    pageField.style.display = 'none';
    moduleField.style.display = 'none';
    urlField.style.display = 'none';
}

tipe.addEventListener('change', function () {

    resetField();

    if(this.value === 'page')
    {
        pageField.style.display = 'block';

        pageSelect.onchange = function(){

            nama.value = this.options[this.selectedIndex].text;

        };

    }

    if(this.value === 'module')
    {
        moduleField.style.display = 'block';

        moduleSelect.onchange = function(){

            nama.value = this.options[this.selectedIndex].text;

        };

    }

    if(this.value === 'url')
    {
        urlField.style.display = 'block';
    }

});

</script>

@endpush