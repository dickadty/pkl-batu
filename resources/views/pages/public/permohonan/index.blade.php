@extends('layouts.public')

@section('title', 'Riwayat Permohonan | PPID Kota Batu')

@section('content')
    <section class="max-w-5xl mx-auto px-4 py-10">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Riwayat Permohonan
                    </h1>
                    <p class="text-slate-600 mt-1">
                        Daftar permohonan informasi yang sudah Anda ajukan.
                    </p>
                </div>

                <a href="{{ route('public.permohonan.create') }}"
                    class="px-4 py-2 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                    Ajukan Baru
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-left">
                            <th class="p-3 border">No</th>
                            <th class="p-3 border">No Permohonan</th>
                            <th class="p-3 border">Tanggal</th>
                            <th class="p-3 border">Rincian</th>
                            <th class="p-3 border">Tujuan</th>
                            <th class="p-3 border">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permohonan as $item)
                            <tr>
                                <td class="p-3 border">{{ $loop->iteration }}</td>
                                <td class="p-3 border">{{ $item->no_pemohon }}</td>
                                <td class="p-3 border">{{ $item->tanggal }}</td>
                                <td class="p-3 border">{{ $item->rincian }}</td>
                                <td class="p-3 border">{{ $item->tujuan }}</td>
                                <td class="p-3 border">
                                    <span
                                        class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-500">
                                    Belum ada permohonan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
