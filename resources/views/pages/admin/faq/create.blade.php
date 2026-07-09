@extends('layouts.admin')

@section('title', 'Tambah FAQ')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / FAQ / Tambah
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
            <span>Tambah FAQ</span>
        </div>

        <form action="{{ route('admin.faq.store') }}" method="POST" class="form-material">
            @csrf

            <div class="mb-4">
                <label>Pertanyaan</label>
                <input type="text" name="pertanyaan" value="{{ old('pertanyaan') }}" class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Jawaban</label>
                <textarea name="jawaban" rows="7" class="form-control" required>{{ old('jawaban') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" name="status" value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                    Tampilkan FAQ di halaman publik
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan
                </button>

                <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
