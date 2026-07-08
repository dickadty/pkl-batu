@extends('layouts.admin')

@section('title', 'Daftar PPID Pembantu')

@section('content')
    @php
        $isPaginated = $ppidPembantu instanceof \Illuminate\Pagination\AbstractPaginator;
    @endphp

    <div class="breadcrumb-custom">
        PPID Pembantu &nbsp; &gt; &nbsp; Daftar PPID
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Daftar PPID Pembantu</span>

            <a href="{{ route('admin.ppid-pembantu.create') }}" class="btn btn-red btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah PPID
            </a>
        </div>

        <div class="section-title">
            Data Profil PPID Pembantu
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
                    placeholder="Cari PPID Pembantu">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama PPID Pembantu</th>
                            <th>Kategori</th>
                            <th>URL Website</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th width="160" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ppidPembantu as $index => $item)
                            <tr>
                                <td>
                                    {{ $isPaginated ? $ppidPembantu->firstItem() + $index : $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $item->nama }}</strong>

                                    @if (!empty($item->keterangan))
                                        <br>
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($item->keterangan, 80) }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->kategoriPpid->kategori ?? '-' }}
                                </td>

                                <td>
                                    @if (!empty($item->linkweb))
                                        <a href="{{ $item->linkweb }}" target="_blank">
                                            {{ \Illuminate\Support\Str::limit($item->linkweb, 30) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $item->telp ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->alamat ?? '-' }}
                                </td>

                                <td class="text-center">
                                    @if (\Illuminate\Support\Facades\Route::has('admin.ppid-pembantu.edit'))
                                        <a href="{{ route('admin.ppid-pembantu.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-outline-warning btn-sm">

<i class="bi bi-pencil"></i>

</button>
                                    @endif

                                    @if (\Illuminate\Support\Facades\Route::has('admin.ppid-pembantu.destroy'))
                                        <form action="{{ route('admin.ppid-pembantu.destroy', $item->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus data PPID Pembantu ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-danger btn-sm" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data PPID Pembantu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($isPaginated)
                <div class="mt-3">
                    {{ $ppidPembantu->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
