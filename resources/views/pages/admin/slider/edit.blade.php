@extends('layouts.admin')

@section('title', 'Edit Slider')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Slider / Edit
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
            <span>Edit Slider</span>
        </div>

        <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data"
            class="form-material">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="form-control"
                    required>
            </div>

            <div class="mb-4">
                <label>Banner Saat Ini</label>

                <div class="mb-2">
                    @if ($slider->banner)
                        <img src="{{ asset('storage/' . $slider->banner) }}" alt="{{ $slider->title }}"
                            style="width:240px;height:120px;object-fit:cover;border-radius:6px;">
                    @else
                        <div class="text-muted">
                            Belum ada banner.
                        </div>
                    @endif
                </div>

                <input type="file" name="banner" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti banner.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
