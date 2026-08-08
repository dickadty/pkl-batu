@extends('layouts.admin.app')

@section('title', 'Manajemen Halaman')

@section('content')

    <div class="space-y-6">

        <x-admin.page-header title="Manajemen Halaman"
            description="Kelola seluruh halaman statis yang ditampilkan pada website PPID Kota Batu." :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Halaman',
            ],
        ]">

            <a href="{{ route('admin.pages.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition">

                <i class="ri-add-line"></i>

                Tambah Halaman

            </a>

        </x-admin.page-header>

        <x-ui.flash-messages />

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">

            <div class="flex flex-col gap-4 p-5 border-b border-slate-200 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Daftar Halaman
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola seluruh halaman website PPID Kota Batu.
                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <form method="GET" class="relative">

                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari halaman..."
                            class="w-72 rounded-lg border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                    </form>

                    <a href="{{ route('admin.pages.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">

                        <i class="ri-add-line"></i>

                        Tambah Halaman

                    </a>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                No
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                Gambar
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                Nama Halaman
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                Deskripsi
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

                        @forelse($pages as $page)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4">

                                    {{ $loop->iteration }}

                                </td>

                                <td class="px-6 py-4">

                                    @if($page->gambar)

                                        <img src="{{ asset('storage/' . $page->gambar) }}" alt="{{ $page->judul }}"
                                            class="w-24 h-16 rounded-lg object-cover border border-slate-200 shadow-sm">

                                    @else

                                        <div
                                            class="w-24 h-16 rounded-lg border border-dashed border-slate-300 bg-slate-100 flex items-center justify-center">

                                            <i class="ri-image-line text-2xl text-slate-400"></i>

                                        </div>

                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    <div class="font-semibold text-slate-800">

                                        {{ $page->judul }}

                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">

                                        {{ $page->slug }}

                                    </div>

                                </td>

                                <td class="px-6 py-4">
                                    <p class="max-w-sm text-sm text-slate-600 line-clamp-2">
                                        {{ Str::limit(strip_tags($page->content), 100) }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if($page->status == 'published')

                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">

                                            Published

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">

                                            Draft

                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('admin.pages.edit', $page->id) }}"
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">

                                            <i class="ri-edit-line"></i>

                                        </a>

                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus halaman ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-red-300 text-red-600 hover:bg-red-50">

                                                <i class="ri-delete-bin-line"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="py-16 text-center">

                                    <i class="ri-file-list-3-line text-5xl text-slate-300"></i>

                                    <h3 class="mt-4 text-lg font-semibold text-slate-700">

                                        Belum Ada Halaman

                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">

                                        Tambahkan halaman pertama untuk mulai mengelola konten website.

                                    </p>

                                    <a href="{{ route('admin.pages.create') }}"
                                        class="mt-5 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">

                                        <i class="ri-add-line"></i>

                                        Tambah Halaman

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('gambar');
        const preview = document.getElementById('preview-gambar');
        const placeholder = document.getElementById('upload-placeholder');

        if (!input) return;

        input.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {

                preview.src = event.target.result;

                preview.classList.remove('hidden');

                placeholder.classList.add('hidden');

            };

            reader.readAsDataURL(file);

        });

    });
</script>
@endstack