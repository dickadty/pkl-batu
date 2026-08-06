@extends('layouts.admin.app')

@section('title', 'Manajemen Module')

@section('content')
<div class="space-y-6">

    {{-- ============================================================
    JUDUL HALAMAN
    ============================================================= --}}
    <x-admin.page-header
        title="Manajemen Module"
        description="Kelola seluruh module website PPID Kota Batu."
        :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Module',
            ],
        ]">

        <a href="{{ route('admin.module.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition">
            <i class="ri-add-line"></i>
            Tambah Module
        </a>

    </x-admin.page-header>

    {{-- ============================================================
    FLASH MESSAGE
    ============================================================= --}}
    <x-ui.flash-messages />

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">

        {{-- Header Card --}}
        <div class="flex flex-col gap-4 p-5 border-b border-slate-200 md:flex-row md:items-center md:justify-between">

            <div>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola seluruh module website PPID Kota Batu.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                <form method="GET" class="relative">

                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari module..."
                        class="w-64 rounded-lg border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                </form>

                <a href="{{ route('admin.module.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">

                    <i class="ri-add-line"></i>
                    Tambah Module

                </a>

            </div>

        </div>

        {{-- ============================================================
        TABEL MODULE
        ============================================================= --}}
        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            No
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Nama
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Slug
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Route
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            View
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Status
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($modules as $module)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $module->nama }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $module->slug }}
                            </td>

                            <td class="px-6 py-4">
                                <code class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">
                                    {{ $module->route_name }}
                                </code>
                            </td>

                            <td class="px-6 py-4">
                                <code class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">
                                    {{ $module->view_name }}
                                </code>
                            </td>

                            <td class="px-6 py-4 text-center">

                                @if($module->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Nonaktif
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('admin.module.edit', $module->id) }}"
                                        class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">

                                        <i class="ri-edit-line"></i>

                                    </a>

                                    <form
                                        action="{{ route('admin.module.destroy', $module->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus module ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-lg border border-red-300 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                Belum ada module yang tersedia.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection