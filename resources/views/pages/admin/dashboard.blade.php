@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard
    </div>

    <div class="panel-card mb-4">
        <div class="panel-card-header">
            <span>Dashboard Admin</span>

            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">
                    Logout
                </button>
            </form>
        </div>

        <div class="p-4">
            <h5 class="mb-1">
                Selamat datang, {{ $admin->username }}
            </h5>

            <p class="text-muted mb-0">
                Login sebagai:
                @if ($admin->isAdminUtama())
                    <strong>Admin PPID Utama</strong>
                @else
                    <strong>Admin PPID Pembantu</strong>
                    @if ($admin->ppidPembantu)
                        - {{ $admin->ppidPembantu->nama }}
                    @endif
                @endif
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @if ($admin->isAdminUtama())
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">PPID Pembantu</small>
                        <h3>{{ $stats['total_ppid_pembantu'] }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Total Informasi</small>
                    <h3>{{ $stats['total_informasi'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Menunggu Verifikasi</small>
                    <h3>{{ $stats['informasi_menunggu'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Terverifikasi</small>
                    <h3>{{ $stats['informasi_terverifikasi'] }}</h3>
                </div>
            </div>
        </div>

        @if ($admin->isAdminUtama())
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Permohonan</small>
                        <h3>{{ $stats['total_permohonan'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Keberatan</small>
                        <h3>{{ $stats['total_keberatan'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Pesan Masuk</small>
                        <h3>{{ $stats['total_pesan_masuk'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Download</small>
                        <h3>{{ $stats['total_download'] }}</h3>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Informasi Publik Terbaru</span>

            <a href="{{ route('admin.informasi-publik.index') }}" class="btn btn-red btn-sm">
                Lihat Semua
            </a>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Informasi</th>
                            <th>PPID Pembantu</th>
                            <th>Sifat</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($latestDokumentasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->ppidPembantu->nama ?? '-' }}</td>
                                <td>{{ $item->sifat ?? '-' }}</td>
                                <td>
                                    @if ($item->is_verifikasi == 1)
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
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

