@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Daftar Informasi Publik</h1>

            <a href="{{ route('admin.informasi-publik.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                Tambah Informasi
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Nama Informasi</th>
                        <th class="p-3 border">PPID</th>
                        <th class="p-3 border">Tahun</th>
                        <th class="p-3 border">Sifat</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumentasi as $item)
                        <tr>
                            <td class="p-3 border">{{ $loop->iteration }}</td>
                            <td class="p-3 border">{{ $item->nama }}</td>
                            <td class="p-3 border">{{ $item->ppidPembantu->nama ?? '-' }}</td>
                            <td class="p-3 border">{{ $item->tahun ?? '-' }}</td>
                            <td class="p-3 border">{{ $item->sifat ?? '-' }}</td>
                            <td class="p-3 border">
                                @if ($item->is_verifikasi == 1)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Terverifikasi</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Menunggu</span>
                                @endif
                            </td>
                            <td class="p-3 border">
                                <div class="flex gap-2">
                                    @if ((int) $admin->role === 1 && $item->is_verifikasi == 0)
                                        <form method="POST"
                                            action="{{ route('admin.informasi-publik.verifikasi', $item->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="px-3 py-1 bg-green-600 text-white rounded">
                                                Verifikasi
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.informasi-publik.destroy', $item->id) }}"
                                        onsubmit="return confirm('Hapus informasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Belum ada informasi publik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
