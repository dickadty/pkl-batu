@extends('layouts.public.app')

@section('title', 'Riwayat Permohonan Informasi | PPID Kota Batu')

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900">Riwayat Permohonan Informasi</h1>
            <p class="mt-3 max-w-2xl text-slate-600">Pantau status dan jawaban final permohonan Anda.</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Daftar Permohonan</h2>
                    <p class="mt-1 text-sm text-slate-500">Klik detail untuk membuka tiket permohonan.</p>
                </div>

                <a href="{{ route('public.permohonan.create') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white hover:bg-blue-800">
                    Ajukan Permohonan Baru
                </a>
            </div>

            @if (session('success'))
                <div class="m-6 mb-0 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto p-6">
                <table class="w-full min-w-[900px] border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-left text-slate-700">
                            <th class="border border-slate-200 p-3">No.</th>
                            <th class="border border-slate-200 p-3">Nomor Tiket</th>
                            <th class="border border-slate-200 p-3">Tanggal</th>
                            <th class="border border-slate-200 p-3">Rincian</th>
                            <th class="border border-slate-200 p-3">Status</th>
                            <th class="border border-slate-200 p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permohonan as $item)
                            <tr class="align-top hover:bg-slate-50">
                                <td class="border border-slate-200 p-3">{{ $loop->iteration }}</td>
                                <td class="border border-slate-200 p-3 font-semibold text-slate-800">
                                    {{ $item->no_pemohon ?? '-' }}
                                </td>
                                <td class="border border-slate-200 p-3">
                                    {{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}
                                </td>
                                <td class="border border-slate-200 p-3">{{ $item->rincian ?? '-' }}</td>
                                <td class="border border-slate-200 p-3">
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $item->status ?? 'Diajukan' }}
                                    </span>
                                </td>
                                <td class="border border-slate-200 p-3">
                                    <a href="{{ route('public.permohonan.show', ['token' => $item->token]) }}"
                                        class="inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border border-slate-200 p-8 text-center text-slate-500">
                                    Belum ada permohonan informasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
