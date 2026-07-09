@extends('layouts.admin')

@section('title', 'Tambah Pejabat')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Pejabat / Tambah
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
            <span>Tambah Pejabat</span>
        </div>

        <form action="{{ route('admin.pejabat.store') }}" method="POST" enctype="multipart/form-data" class="form-material">
            @csrf

            <div class="mb-4">
                <label>Nama Pejabat</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Masa Jabatan</label>
                <input type="text" name="masa" value="{{ old('masa') }}" class="form-control"
                    placeholder="Contoh: 2024 - 2029">
            </div>

            <div class="mb-4">
                <label>Tempat/Tanggal Lahir</label>
                <input type="text" name="tmp_tgl_lahir" value="{{ old('tmp_tgl_lahir') }}" class="form-control"
                    placeholder="Contoh: Batu, 01 Januari 1980">
            </div>

            <div class="mb-4">
                <label>Alamat</label>
                <textarea name="alamat" rows="4" class="form-control">{{ old('alamat') }}</textarea>
            </div>

            <div class="mb-4">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control">
            </div>

            <div class="mb-4">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                    Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan
                </button>

                <a href="{{ route('admin.pejabat.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
