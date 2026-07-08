@extends('layouts.admin')

@section('title', 'Berita')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Berita
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Daftar Berita</span>

            <a href="{{ route('admin.berita.create') }}" class="btn btn-red btn-sm">
                <i class="bi bi-plus-circle"></i>
                Tambah Berita
            </a>
        </div>

        <div class="p-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4 ms-auto">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari Berita">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($berita as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td width="140">
                                    @if ($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                                            style="width:110px;height:70px;object-fit:cover;border-radius:4px;">
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $item->judul }}</strong>
                                    <div class="text-muted small mt-1">
                                        {{ Str::limit(strip_tags($item->caption), 100) }}
                                    </div>
                                </td>

                                <td>
                                    {{ $item->tanggal ? date('d-m-Y', $item->tanggal) : '-' }}
                                </td>

                                <td>
                                    <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada berita.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
