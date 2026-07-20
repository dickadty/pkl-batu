@extends('layouts.public')

@section('title', 'Informasi Publik | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-slate-900">
                Daftar Informasi Publik
            </h1>

            <p class="mt-3 text-slate-600 max-w-2xl">
                Masyarakat dapat melihat dan mengunduh informasi publik yang telah diverifikasi oleh PPID.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl p-15" style="background: linear-gradient(135deg, #033927 10%, #04853c 100%)">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">

                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center border-b border-gray-100 bg-white px-4 py-4 sm:px-6 lg:px-8">
                    <input type="text" placeholder="Cari informasi..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-800 sm:max-w-sm">
                </div>

                {{-- Table --}}
                <div class="px-3 py-3 sm:px-5 lg:px-6">
                    <div class="max-w-full overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-100">

                            <thead class="border-y border-gray-100 bg-gray-100">

                                <tr>

                                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800 sm:px-6">
                                        Nama Informasi
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-800 sm:px-6">
                                        PPID
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-800 sm:px-6">
                                        Tahun
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-800 sm:px-6">
                                        Sifat
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-800 sm:px-6">
                                        Status
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-800 sm:px-6">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($dokumentasi as $item)

                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                                        {{-- Nama Informasi --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <div>

                                                <p class="font-medium text-gray-800">
                                                    {{ $item->nama }}
                                                </p>

                                                <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                                    {{ $item->ringkasan ?? 'Tidak ada ringkasan.' }}
                                                </p>

                                            </div>

                                        </td>

                                        {{-- PPID --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <p class="text-sm text-gray-700">
                                                {{ $item->ppidPembantu->nama ?? '-' }}
                                            </p>

                                        </td>

                                        {{-- Tahun --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <p class="text-sm text-gray-700">
                                                {{ $item->tahun ?? '-' }}
                                            </p>

                                        </td>

                                        {{-- Sifat --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <span
                                                class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">

                                                {{ $item->sifat ?? 'Informasi Publik' }}

                                            </span>

                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <span
                                                class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">

                                                Publik

                                            </span>

                                        </td>

                                        {{-- Action --}}
                                        <td class="px-4 py-4 sm:px-6">

                                            <div class="flex items-center gap-4">

                                                {{-- Detail --}}
                                                <a href="{{ route('public.informasi.show', $item->slug) }}"
                                                    class="text-gray-500 hover:text-blue-600 transition" title="Lihat Detail">

                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0" />

                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                                           c4.478 0 8.268 2.943 9.542 7
                                                                           -1.274 4.057-5.064 7-9.542 7
                                                                           -4.477 0-8.268-2.943-9.542-7z" />

                                                    </svg>

                                                </a>

                                                {{-- Download --}}
                                                <a href="{{ route('public.informasi.download', $item->id) }}"
                                                    class="text-gray-500 hover:text-green-600 transition" title="Download">

                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4v10m0 0l-4-4m4 4l4-4" />

                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 20h16" />

                                                    </svg>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="px-4 py-12 text-center sm:px-6">

                                            <h3 class="font-medium text-gray-700">
                                                Belum ada informasi publik
                                            </h3>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Informasi akan muncul setelah diverifikasi oleh admin.
                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
         </div>
        </div>
@endsection