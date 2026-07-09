@extends('layouts.admin')

@section('title', 'Tambah Slider')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Slider / Tambah
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Data belum valid.</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Tambah Slider</span>
        </div>

        <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data" class="form-material">
            @csrf

            <div class="mb-4">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Banner</label>
                <input type="file" name="banner" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>

                <small class="text-muted">
                    Format: JPG, JPEG, PNG, WEBP. Maksimal 3 MB.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan
                </button>

                <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
