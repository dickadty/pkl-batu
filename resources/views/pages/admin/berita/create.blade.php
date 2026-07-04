@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
<div class="breadcrumb-custom">
    Dashboard / Berita / Tambah Berita
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <span>Tambah Berita</span>
    </div>

    <div class="section-title">
        FORM INPUT BERITA
    </div>

    <form action="{{ route('admin.berita.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="form-material">
        @csrf

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

        <div class="mb-4">
            <label>Judul Berita</label>
            <input type="text"
                   name="judul"
                   value="{{ old('judul') }}"
                   class="form-control"
                   placeholder="Masukkan judul berita"
                   required>
        </div>

        <div class="mb-4">
            <label>Isi atau Caption Berita</label>
            <textarea name="caption"
                      rows="6"
                      class="form-control"
                      placeholder="Masukkan isi berita">{{ old('caption') }}</textarea>
        </div>

        <div class="mb-4">
            <label>Gambar Berita</label>
            <input type="file"
                   name="gambar"
                   class="form-control"
                   accept="image/*">

            <small class="text-muted">
                Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
            </small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-red">
                <i class="bi bi-save"></i>
                Simpan Berita
            </button>

            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection