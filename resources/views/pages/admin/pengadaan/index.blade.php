@extends('layouts.admin.app')

@section('title', 'Data Pengadaan')

@section('content')
    @php
        $currentItems = $pengadaanList->getCollection();

        $firstNumber = $pengadaanList->firstItem() ?? 1;
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Data Pengadaan"
            description="Kelola paket pengadaan sesuai unit PPID Pembantu yang bertanggung jawab." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pengadaan',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.pengadaan.create') }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-4
                        text-sm
                        font-semibold
                        text-white
                        shadow-theme-xs
                        transition
                        hover:bg-brand-600
                    ">
                    <i class="ri-add-line text-lg"></i>

                    Tambah Pengadaan
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        <section
            class="
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                shadow-theme-xs
                dark:border-gray-800
                dark:bg-white/[0.03]
            ">
            <div
                class="
                    flex
                    flex-col
                    gap-4
                    border-b
                    border-gray-100
                    px-5
                    py-4
                    dark:border-gray-800
                    lg:flex-row
                    lg:items-center
                    lg:justify-between
                    sm:px-6
                ">
                <div>
                    <h2
                        class="
                            text-base
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Daftar Paket Pengadaan
                    </h2>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-500
                            dark:text-gray-400
                        ">
                        Total
                        {{ number_format($pengadaanList->total()) }}
                        paket pengadaan.
                    </p>
                </div>

                <form action="{{ route('admin.pengadaan.index') }}" method="GET"
                    class="
                        flex
                        w-full
                        gap-2
                        lg:max-w-md
                    ">
                    <div class="relative flex-1">
                        <span
                            class="
                                pointer-events-none
                                absolute
                                inset-y-0
                                left-0
                                flex
                                items-center
                                pl-3.5
                                text-gray-400
                            ">
                            <i class="ri-search-line text-lg"></i>
                        </span>

                        <input type="search" name="q" value="{{ request('q') }}"
                            placeholder="Cari paket, metode, atau PPID..."
                            class="
                                h-10
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                pl-10
                                pr-4
                                text-sm
                                text-gray-800
                                outline-none
                                transition
                                focus:border-brand-300
                                focus:ring-3
                                focus:ring-brand-500/10
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                            ">
                    </div>

                    <button type="submit"
                        class="
                            inline-flex
                            h-10
                            items-center
                            justify-center
                            rounded-lg
                            bg-gray-800
                            px-4
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-gray-900
                            dark:bg-gray-700
                            dark:hover:bg-gray-600
                        ">
                        Cari
                    </button>

                    @if (request()->filled('q'))
                        <a href="{{ route('admin.pengadaan.index') }}"
                            class="
                                inline-flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                text-gray-600
                                transition
                                hover:bg-gray-50
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-400
                            "
                            title="Hapus pencarian">
                            <i class="ri-close-line text-lg"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1350px]">
                    <thead
                        class="
                            border-b
                            border-gray-100
                            bg-gray-50
                            dark:border-gray-800
                            dark:bg-gray-900/50
                        ">
                        <tr>
                            <th
                                class="w-20 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                No
                            </th>

                            <th
                                class="min-w-[300px] px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Nama Paket
                            </th>

                            <th
                                class="min-w-[190px] px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Pagu
                            </th>

                            <th
                                class="min-w-[180px] px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Sumber Dana
                            </th>

                            <th
                                class="min-w-[200px] px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Metode
                            </th>

                            <th
                                class="min-w-[240px] px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                PPID Pembantu
                            </th>

                            <th
                                class="w-[160px] min-w-[160px] px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="
                            divide-y
                            divide-gray-100
                            dark:divide-gray-800
                        ">
                        @forelse ($currentItems as $index => $item)
                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50
                                    dark:hover:bg-white/[0.03]
                                ">
                                <td class="px-6 py-4">
                                    <span
                                        class="
                                            inline-flex
                                            h-8
                                            min-w-8
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-gray-100
                                            px-2
                                            text-xs
                                            font-semibold
                                            text-gray-600
                                            dark:bg-gray-800
                                            dark:text-gray-300
                                        ">
                                        {{ $firstNumber + $index }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pengadaan.show', $item->id) }}"
                                        class="
                                            text-sm
                                            font-semibold
                                            leading-6
                                            text-gray-800
                                            transition
                                            hover:text-brand-600
                                            dark:text-white/90
                                            dark:hover:text-brand-400
                                        ">
                                        {{ $item->nama_paket }}
                                    </a>

                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        ">
                                        ID: {{ $item->id }}
                                    </p>
                                </td>

                                <td
                                    class="
                                        whitespace-nowrap
                                        px-6
                                        py-4
                                        text-sm
                                        font-semibold
                                        text-gray-800
                                        dark:text-gray-200
                                    ">
                                    {{ $item->pagu_rupiah }}
                                </td>

                                <td
                                    class="
                                        px-6
                                        py-4
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $item->sumber_dana }}
                                </td>

                                <td
                                    class="
                                        px-6
                                        py-4
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $item->metode }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            bg-purple-50
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            text-purple-700
                                            dark:bg-purple-500/15
                                            dark:text-purple-400
                                        ">
                                        <i class="ri-government-line"></i>

                                        {{ data_get($item, 'ppidPembantu.nama', '-') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                        ">
                                        <a href="{{ route('admin.pengadaan.show', $item->id) }}"
                                            title="Lihat detail"
                                            class="
                                                inline-flex
                                                h-9
                                                w-9
                                                items-center
                                                justify-center
                                                rounded-lg
                                                border
                                                border-blue-200
                                                bg-blue-50
                                                text-blue-600
                                                transition
                                                hover:bg-blue-100
                                                dark:border-blue-500/20
                                                dark:bg-blue-500/10
                                                dark:text-blue-400
                                            ">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>

                                        <a href="{{ route('admin.pengadaan.edit', $item->id) }}"
                                            title="Edit pengadaan"
                                            class="
                                                inline-flex
                                                h-9
                                                w-9
                                                items-center
                                                justify-center
                                                rounded-lg
                                                border
                                                border-yellow-200
                                                bg-yellow-50
                                                text-yellow-700
                                                transition
                                                hover:bg-yellow-100
                                                dark:border-yellow-500/20
                                                dark:bg-yellow-500/10
                                                dark:text-yellow-400
                                            ">
                                            <i class="ri-edit-line text-lg"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.pengadaan.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data pengadaan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" title="Hapus pengadaan"
                                                class="
                                                    inline-flex
                                                    h-9
                                                    w-9
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    border
                                                    border-red-200
                                                    bg-red-50
                                                    text-red-600
                                                    transition
                                                    hover:bg-red-100
                                                    dark:border-red-500/20
                                                    dark:bg-red-500/10
                                                    dark:text-red-400
                                                ">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    ">
                                    <div
                                        class="
                                            mx-auto
                                            flex
                                            h-16
                                            w-16
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-blue-50
                                            text-blue-500
                                            dark:bg-blue-500/15
                                            dark:text-blue-400
                                        ">
                                        <i class="ri-shopping-bag-3-line text-3xl"></i>
                                    </div>

                                    <h3
                                        class="
                                            mt-4
                                            text-base
                                            font-semibold
                                            text-gray-800
                                            dark:text-white/90
                                        ">
                                        Data pengadaan belum tersedia
                                    </h3>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            text-gray-500
                                            dark:text-gray-400
                                        ">
                                        Tambahkan paket pengadaan baru melalui tombol tambah.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pengadaanList->hasPages())
                <div
                    class="
                        border-t
                        border-gray-100
                        px-6
                        py-4
                        dark:border-gray-800
                    ">
                    {{ $pengadaanList->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
