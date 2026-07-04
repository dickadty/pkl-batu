@extends('layouts.admin')

@section('title', 'Tambah PPID Pembantu')

@section('content')
    <div class="breadcrumb-custom">
        PPID Pembantu &nbsp; &gt; &nbsp; Tambah PPID
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Tambah PPID Pembantu</span>
            <i class="bi bi-three-dots-vertical"></i>
        </div>

        <div class="section-title">
            Informasi Profil PPID Pembantu
        </div>

        <div class="form-material">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <b>Terjadi kesalahan.</b>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.ppid-pembantu.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label>Nama PPID Pembantu</label>
                    <input 
                        type="text" 
                        name="nama" 
                        class="form-control" 
                        placeholder="Masukkan nama ..."
                        value="{{ old('nama') }}"
                    >
                </div>

                <div class="mb-4">
                    <label>Keterangan</label>
                    <textarea 
                        name="keterangan" 
                        class="form-control" 
                        rows="4" 
                        placeholder="Masukkan keterangan ..."
                    >{{ old('keterangan') }}</textarea>
                </div>

                <div class="mb-4">
                    <label>Kategori PPID</label>
                    <select name="kategori_ppidid" class="form-select">
                        <option value="">-- Pilih kategori PPID --</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" {{ old('kategori_ppidid') == $item->id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label>URL Website</label>
                    <input 
                        type="text" 
                        name="linkweb" 
                        class="form-control" 
                        placeholder="Masukkan URL website ..."
                        value="{{ old('linkweb') }}"
                    >
                </div>

                <div class="mb-4">
                    <label>No. Telepon</label>
                    <input 
                        type="text" 
                        name="telp" 
                        class="form-control" 
                        placeholder="Masukkan No. Telepon ..."
                        value="{{ old('telp') }}"
                    >
                </div>

                <div class="mb-4">
                    <label>Alamat</label>
                    <input 
                        type="text" 
                        name="alamat" 
                        class="form-control" 
                        placeholder="Masukkan alamat ..."
                        value="{{ old('alamat') }}"
                    >
                </div>

                <div class="mb-4">
                    <label>Icon</label>
                    <input 
                        type="text" 
                        name="icon" 
                        class="form-control" 
                        placeholder="Masukkan nama icon ..."
                        value="{{ old('icon') }}"
                    >
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-red px-4">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection