@extends('layouts.admin')

@section('title', 'Edit Pejabat')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Pejabat / Edit
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
            <span>Edit Pejabat</span>
        </div>

        <form action="{{ route('admin.pejabat.update', $pejabat->id) }}" method="POST" enctype="multipart/form-data"
            class="form-material">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Nama Pejabat</label>
                <input type="text" name="nama" value="{{ old('nama', $pejabat->nama) }}" class="form-control"
                    required>
            </div>

            <div class="mb-4">
                <label>Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $pejabat->jabatan) }}" class="form-control"
                    required>
            </div>

            <div class="mb-4">
                <label>Masa Jabatan</label>
                <input type="text" name="masa" value="{{ old('masa', $pejabat->masa) }}" class="form-control">
            </div>

            <div class="mb-4">
                <label>Tempat/Tanggal Lahir</label>
                <input type="text" name="tmp_tgl_lahir" value="{{ old('tmp_tgl_lahir', $pejabat->tmp_tgl_lahir) }}"
                    class="form-control">
            </div>

            <div class="mb-4">
                <label>Alamat</label>
                <textarea name="alamat" rows="4" class="form-control">{{ old('alamat', $pejabat->alamat) }}</textarea>
            </div>

            <div class="mb-4">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $pejabat->no_telp) }}" class="form-control">
            </div>

            <div class="mb-4">
                <label>Foto Saat Ini</label>

                <div class="mb-2">
                    @if ($pejabat->foto)
                        <img src="{{ asset('storage/' . $pejabat->foto) }}" alt="{{ $pejabat->nama }}"
                            style="width:120px;height:120px;object-fit:cover;border-radius:6px;">
                    @else
                        <div class="text-muted">
                            Belum ada foto.
                        </div>
                    @endif
                </div>

                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti foto.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.pejabat.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
