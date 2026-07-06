@extends('layouts.admin')

@section('title', 'Permohonan Informasi')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Permohonan Informasi
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Daftar Permohonan Informasi</span>
        </div>

        <div class="p-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No Permohonan</th>
                            <th>Pemohon</th>
                            <th>PPID Pembantu</th>
                            <th>Tanggal</th>
                            <th>Rincian</th>
                            <th>Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permohonan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_pemohon }}</td>
                                <td>
                                    {{ $item->userPublic->nama ?? '-' }}
                                    <div class="text-muted small">
                                        {{ $item->userPublic->email ?? '-' }}
                                    </div>
                                </td>
                                <td>{{ $item->ppidPembantu->nama ?? '-' }}</td>
                                <td>{{ $item->tanggal ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->rincian, 80) }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $item->status ?? 'Diajukan' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.permohonan.show', $item->id) }}" class="btn btn-sm btn-red">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada permohonan informasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
