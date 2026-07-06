@extends('layouts.admin')

@section('title', 'Tambah Informasi Publik')

@section('content')
<div class="breadcrumb-custom">
     Informasi Publik & Dokumentasi &nbsp; &gt; &nbsp; Tambah Info
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <span>Tambah Informasi Publik</span>
    </div>

    <div class="section-title">
        FORM INPUT INFORMASI PUBLIK
    </div>

    <form method="POST"
          action="{{ route('admin.informasi-publik.store') }}"
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
            <label>Nama Informasi</label>
            <input type="text"
                   name="nama"
                   value="{{ old('nama') }}"
                   class="form-control"
                   placeholder="Masukkan nama informasi"
                   required>
        </div>

        <div class="mb-4">
            <label>Tahun</label>
            <input type="number"
                   name="tahun"
                   value="{{ old('tahun', date('Y')) }}"
                   class="form-control">
        </div>

        <div class="mb-4">
            <label>Sifat Informasi</label>
            <select name="sifat" class="form-control">
                <option value="">Pilih Sifat Informasi</option>
                <option value="Berkala">Berkala</option>
                <option value="Serta Merta">Serta Merta</option>
                <option value="Setiap Saat">Setiap Saat</option>
                <option value="Dikecualikan">Dikecualikan</option>
            </select>
        </div>

        @if ((int) $admin->role === 1)
            <div class="mb-4">
                <label>PPID Pembantu</label>
                <select name="ppid_pembantuid" class="form-control">
                    <option value="">Pilih PPID Pembantu</option>
                    @foreach ($ppidPembantu as $ppid)
                        <option value="{{ $ppid->id }}">
                            {{ $ppid->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mb-4">
            <label>Ringkasan</label>
            <textarea name="ringkasan"
                      rows="5"
                      class="form-control"
                      placeholder="Masukkan ringkasan informasi">{{ old('ringkasan') }}</textarea>
        </div>

        <div class="mb-4">
            <label>File Informasi</label>
            <input type="file"
                   name="file"
                   class="form-control"
                   required>

            <small class="text-muted">
                Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 5 MB.
            </small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-red">
                <i class="bi bi-save"></i>
                Simpan Informasi
            </button>

            <a href="{{ route('admin.informasi-publik.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection