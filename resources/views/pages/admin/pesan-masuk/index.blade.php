@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Pesan Masuk
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Pesan Masuk</span>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jumlah Balasan</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanMasuk as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->subjek ?? '-' }}</td>

                                <td>
                                    @if ($item->tanggal)
                                        {{ date('d-m-Y H:i', (int) $item->tanggal) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $item->status_badge_class }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>

                                <td>{{ $item->balasan_count }}</td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.pesan-masuk.show', $item->id) }}"
                                            class="btn btn-sm btn-primary">
                                            Detail
                                        </a>

                                        <form action="{{ route('admin.pesan-masuk.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
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
                                    Belum ada pesan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
