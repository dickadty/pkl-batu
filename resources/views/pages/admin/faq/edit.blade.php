@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / FAQ / Edit
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
            <span>Edit FAQ</span>
        </div>

        <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST" class="form-material">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Pertanyaan</label>
                <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $faq->pertanyaan) }}"
                    class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Jawaban</label>
                <textarea name="jawaban" rows="7" class="form-control" required>{{ old('jawaban', $faq->jawaban) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" name="status" value="1"
                        {{ old('status', $faq->status) == 1 ? 'checked' : '' }}>
                    Tampilkan FAQ di halaman publik
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
