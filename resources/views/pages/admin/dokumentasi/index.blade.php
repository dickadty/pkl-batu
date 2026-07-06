@extends('layouts.admin')

@section('title', 'Daftar Informasi Publik')

@section('content')

    <div class="breadcrumb-custom">
        Informasi & Dokumentasi &nbsp; &gt; &nbsp; Daftar Informasi Publik
    </div>

    <div class="panel-card">

        <div class="panel-card-header">
            <span>Daftar Informasi Publik</span>

            <a href="{{ route('admin.informasi-publik.create') }}" class="btn btn-red btn-sm">
                <i class="bi bi-plus-circle"></i>
                Tambah Informasi
            </a>
        </div>

        <div class="section-title">
            Data Informasi Publik
        </div>

        <div class="p-4">

            @if(session('success'))
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
            </div

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Informasi</th>
                            <th>PPID Pembantu</th>
                            <th width="90">Tahun</th>
                            <th width="130">Sifat</th>
                            <th width="140">Status</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($dokumentasi as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $item->nama }}</strong>
                                </td>

                                <td>
                                    {{ $item->ppidPembantu->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->tahun ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->sifat ?? '-' }}
                                </td>

                                <td>

                                    @if($item->is_verifikasi)

                                        <span class="badge bg-success">
                                            Terverifikasi
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Menunggu
                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    @if((int)$admin->role === 1 && $item->is_verifikasi == 0)

                                        <form
                                            action="{{ route('admin.informasi-publik.verifikasi',$item->id) }}"
                                            method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i>
                                            </button>

                                        </form>

                                    @endif

                                    <form
                                        action="{{ route('admin.informasi-publik.destroy',$item->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus informasi ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada informasi publik.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection