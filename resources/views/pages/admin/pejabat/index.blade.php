@extends('layouts.admin')

@section('title', 'Data Pejabat')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Pejabat
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header d-flex justify-content-between align-items-center">
            <span>Data Pejabat</span>

            <a href="{{ route('admin.pejabat.create') }}" class="btn btn-red btn-sm">
                Tambah Pejabat
            </a>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th width="100">Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Masa</th>
                            <th>Tempat/Tanggal Lahir</th>
                            <th>No. Telepon</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pejabat as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                                            style="width:70px;height:70px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>

                                <td>{{ $item->nama ?? '-' }}</td>
                                <td>{{ $item->jabatan ?? '-' }}</td>
                                <td>{{ $item->masa ?? '-' }}</td>
                                <td>{{ $item->tmp_tgl_lahir ?? '-' }}</td>
                                <td>{{ $item->no_telp ?? '-' }}</td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.pejabat.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.pejabat.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data pejabat ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">
                                    Belum ada data pejabat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
