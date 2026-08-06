@extends('layouts.admin.app')

@section('title', 'Edit Halaman')

@section('content')

    <div class="panel-card">
        <div class="panel-card-header">
            Edit Halaman
        </div>

        <div class="panel-card-body">

            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $page->judul) }}" class="form-control" required>
                </div>

                <select name="module_id" class="form-control">

                    @foreach($modules as $module)

                        <option value="{{ $module->id }}" {{ $page->module_id == $module->id ? 'selected' : '' }}>

                            {{ $module->nama }}

                        </option>

                    @endforeach

                </select>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ $page->status == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="published" {{ $page->status == 'published' ? 'selected' : '' }}>
                            Published
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konten</label>
                    <textarea name="content" rows="10" class="form-control">{{ old('content', $page->content) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Halaman
                </button>

                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

@endsection