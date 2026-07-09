@extends('layouts.admin')

@section('title', 'Data Slider')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Slider
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header d-flex justify-content-between align-items-center">
            <span>Data Slider</span>

            <a href="{{ route('admin.slider.create') }}" class="btn btn-red btn-sm">
                Tambah Slider
            </a>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th width="180">Banner</th>
                            <th>Title</th>
                            <th width="180">Tanggal</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($slider as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    @if ($item->banner)
                                        <img src="{{ asset('storage/' . $item->banner) }}" alt="{{ $item->title }}"
                                            style="width:160px;height:80px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>

                                <td>{{ $item->title ?? '-' }}</td>

                                <td>
                                    @if ($item->tanggal)
                                        {{ is_numeric($item->tanggal) ? date('d-m-Y', $item->tanggal) : $item->tanggal }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.slider.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.slider.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus slider ini?')">
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
                                <td colspan="5" class="text-center text-muted p-4">
                                    Belum ada data slider.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
