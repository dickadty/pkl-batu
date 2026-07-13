@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / FAQ
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header d-flex justify-content-between align-items-center">
            <span>Data FAQ</span>

            <a href="{{ route('admin.faq.create') }}" class="btn btn-red btn-sm">
                Tambah FAQ
            </a>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                            <th width="130">Tanggal</th>
                            <th width="120">Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($faq as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $item->pertanyaan }}</strong>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->jawaban), 120) }}
                                </td>

                                <td>
                                    {{ $item->tanggal ? date('d-m-Y', (int) $item->tanggal) : '-' }}
                                </td>

                                <td>
                                    <span class="badge {{ $item->status_badge_class }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.faq.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.faq.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus FAQ ini?')">
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
                                <td colspan="6" class="text-center text-muted p-4">
                                    Belum ada data FAQ.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
