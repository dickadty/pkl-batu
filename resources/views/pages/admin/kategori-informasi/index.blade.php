@extends('layouts.admin.app')

@section('title', 'Kategori Informasi')

@section('content')
<div class="space-y-6">

    <x-admin.page-header
        title="Kategori Informasi"
        description="Kelola kategori dan sifat informasi publik."
        :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Kategori Informasi',
            ],
        ]"
    />

    <x-ui.flash-messages />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="flex items-center justify-between border-b border-gray-200 p-5 dark:border-gray-800">

            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Data Kategori Informasi
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Daftar kategori informasi publik yang tersedia.
                </p>
            </div>

            <a
                href="{{ route('admin.kategori-informasi.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600"
            >
                <i class="ri-add-line"></i>
                Tambah Kategori
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">

                        <th class="px-6 py-4 text-left text-xs font-medium uppercase text-gray-500">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-medium uppercase text-gray-500">
                            Nama Kategori
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-medium uppercase text-gray-500">
                            Sifat
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-medium uppercase text-gray-500">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse ($kategori as $index => $item)

                        @php
                            $sifatClass = match ($item->sifat) {
                                'berkala' =>
                                    'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                                'setiap saat' =>
                                    'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                                'serta merta' =>
                                    'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

                                'dikecualikan' =>
                                    'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                                default =>
                                    'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                            };
                        @endphp

                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            <td class="px-6 py-4">
                                {{ $kategori->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                {{ $item->nama }}
                            </td>

                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $sifatClass }}">
                                    {{ Str::title($item->sifat) }}
                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('admin.kategori-informasi.edit', $item->id) }}"
                                        class="inline-flex h-9 items-center rounded-lg border border-blue-200 px-3 text-sm text-blue-600 hover:bg-blue-50"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.kategori-informasi.destroy', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus kategori ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex h-9 items-center rounded-lg border border-red-200 px-3 text-sm text-red-600 hover:bg-red-50"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                Belum ada data kategori informasi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($kategori instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="border-t border-gray-200 p-5 dark:border-gray-800">
                {{ $kategori->links() }}
            </div>
        @endif

    </div>

</div>
@endsection