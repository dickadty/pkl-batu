@extends('layouts.public.app')

@section('title', 'Riwayat Permohonan Informasi | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-bold text-slate-900">
                Riwayat Permohonan Informasi
            </h1>

            <p class="mt-3 text-slate-600 max-w-2xl">
                Halaman ini menampilkan daftar permohonan informasi yang sudah Anda ajukan, status proses, dan jawaban final
                dari PPID.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Daftar Permohonan
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Pantau status permohonan informasi Anda di sini.
                    </p>
                </div>

                <a href="{{ route('public.permohonan.create') }}"
                    class="px-4 py-2 rounded-lg bg-blue-700 text-white text-sm font-semibold hover:bg-blue-800">
                    Ajukan Permohonan Baru
                </a>
            </div>

            @if (session('success'))
                <div class="m-6 mb-0 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto p-6">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-left text-slate-700">
                            <th class="p-3 border border-slate-200">No</th>
                            <th class="p-3 border border-slate-200">No Permohonan</th>
                            <th class="p-3 border border-slate-200">Tanggal</th>
                            <th class="p-3 border border-slate-200">Rincian Informasi</th>
                            <th class="p-3 border border-slate-200">Tujuan Penggunaan</th>
                            <th class="p-3 border border-slate-200">Status</th>
                            <th class="p-3 border border-slate-200">Jawaban Final</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permohonan as $item)
                            <tr class="align-top hover:bg-slate-50">
                                <td class="p-3 border border-slate-200">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="p-3 border border-slate-200 font-semibold text-slate-800">
                                    {{ $item->no_pemohon ?? '-' }}
                                </td>

                                <td class="p-3 border border-slate-200">
                                    {{ $item->tanggal ? date('d-m-Y', strtotime($item->tanggal)) : '-' }}
                                </td>

                                <td class="p-3 border border-slate-200">
                                    {{ $item->rincian ?? '-' }}
                                </td>

                                <td class="p-3 border border-slate-200">
                                    {{ $item->tujuan ?? '-' }}
                                </td>

                                <td class="p-3 border border-slate-200">
                                    @if ($item->status === 'Selesai')
                                        <span
                                            class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Selesai
                                        </span>
                                    @elseif ($item->status === 'Ditolak')
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            Ditolak
                                        </span>
                                    @elseif ($item->status === 'Menunggu Validasi Admin Utama')
                                        <span
                                            class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                            Menunggu Validasi
                                        </span>
                                    @elseif ($item->status === 'Diteruskan ke PPID Pembantu')
                                        <span
                                            class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                                            Diteruskan
                                        </span>
                                    @elseif ($item->status === 'Revisi PPID Pembantu')
                                        <span
                                            class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                            Revisi
                                        </span>
                                    @elseif ($item->status === 'Diproses')
                                        <span
                                            class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                            Diproses
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            {{ $item->status ?? 'Diajukan' }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 border border-slate-200">
                                    @if ($item->status === 'Selesai' && $item->jawaban)
                                        <div class="mb-2 text-slate-700 leading-6">
                                            {{ $item->jawaban }}
                                        </div>

                                        @if ($item->file_jawaban)
                                            <a href="{{ asset('storage/' . $item->file_jawaban) }}" target="_blank"
                                                class="inline-flex mt-2 px-3 py-2 rounded-lg bg-blue-700 text-white text-xs font-semibold hover:bg-blue-800">
                                                Lihat File Jawaban
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-slate-500">
                                            Belum ada jawaban final.
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500 border border-slate-200">
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
