@extends('layouts.admin.app')

@section('title', 'Detail Pengadaan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Detail Pengadaan"
            description="Lihat informasi lengkap paket pengadaan dan PPID Pembantu yang bertanggung jawab."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pengadaan',
                    'url' => route('admin.pengadaan.index'),
                ],
                [
                    'label' => 'Detail Pengadaan',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.pengadaan.index') }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-4
                        text-sm
                        font-semibold
                        text-gray-700
                        transition
                        hover:bg-gray-50
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                    ">
                    <i class="ri-arrow-left-line text-lg"></i>

                    Kembali
                </a>

                <a href="{{ route('admin.pengadaan.edit', $pengadaan->id) }}"
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
                        transition
                        hover:bg-brand-600
                    ">
                    <i class="ri-edit-line text-lg"></i>

                    Edit
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        <section
            class="
                relative
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-6
                shadow-theme-xs
                dark:border-gray-800
                dark:bg-white/[0.03]
            ">
            <div
                class="
                    pointer-events-none
                    absolute
                    -right-20
                    -top-24
                    h-64
                    w-64
                    rounded-full
                    bg-blue-500/[0.08]
                    blur-3xl
                ">
            </div>

            <div
                class="
                    relative
                    flex
                    items-start
                    gap-4
                ">
                <div
                    class="
                        flex
                        h-14
                        w-14
                        shrink-0
                        items-center
                        justify-center
                        rounded-2xl
                        bg-blue-50
                        text-blue-600
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <i class="ri-shopping-bag-3-line text-2xl"></i>
                </div>

                <div class="min-w-0">
                    <div
                        class="
                            flex
                            flex-wrap
                            items-center
                            gap-2
                        ">
                        <span
                            class="
                                rounded-full
                                bg-gray-100
                                px-3
                                py-1.5
                                text-xs
                                font-medium
                                text-gray-600
                                dark:bg-gray-800
                                dark:text-gray-400
                            ">
                            ID: {{ $pengadaan->id }}
                        </span>

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

                            {{ data_get($pengadaan, 'ppidPembantu.nama', '-') }}
                        </span>
                    </div>

                    <h1
                        class="
                            mt-3
                            break-words
                            text-2xl
                            font-bold
                            leading-9
                            text-gray-900
                            dark:text-white
                        ">
                        {{ $pengadaan->nama_paket }}
                    </h1>

                    <p
                        class="
                            mt-2
                            text-xl
                            font-bold
                            text-blue-600
                            dark:text-blue-400
                        ">
                        {{ $pengadaan->pagu_rupiah }}
                    </p>
                </div>
            </div>
        </section>

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-3
            ">
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
                    xl:col-span-2
                ">
                <div
                    class="
                        border-b
                        border-gray-100
                        px-6
                        py-4
                        dark:border-gray-800
                    ">
                    <h2
                        class="
                            text-base
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Rencana Kegiatan
                    </h2>
                </div>

                <div
                    class="
                        whitespace-pre-line
                        px-6
                        py-5
                        text-sm
                        leading-7
                        text-gray-700
                        dark:text-gray-300
                    ">
                    {{ $pengadaan->rencana_kegiatan }}</div>
            </section>

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
                        border-b
                        border-gray-100
                        px-5
                        py-4
                        dark:border-gray-800
                    ">
                    <h2
                        class="
                            text-base
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Informasi Paket
                    </h2>
                </div>

                <dl
                    class="
                        divide-y
                        divide-gray-100
                        dark:divide-gray-800
                    ">
                    <div class="px-5 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Pagu
                        </dt>

                        <dd class="mt-1.5 text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ $pengadaan->pagu_rupiah }}
                        </dd>
                    </div>

                    <div class="px-5 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Sumber Dana
                        </dt>

                        <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">
                            {{ $pengadaan->sumber_dana }}
                        </dd>
                    </div>

                    <div class="px-5 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Metode
                        </dt>

                        <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">
                            {{ $pengadaan->metode }}
                        </dd>
                    </div>

                    <div class="px-5 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            PPID Pembantu
                        </dt>

                        <dd class="mt-1.5 text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ data_get($pengadaan, 'ppidPembantu.nama', '-') }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        <section
            class="
                flex
                flex-col
                gap-4
                rounded-2xl
                border
                border-red-200
                bg-red-50
                p-5
                dark:border-red-500/20
                dark:bg-red-500/10
                sm:flex-row
                sm:items-center
                sm:justify-between
            ">
            <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">
                    Hapus Data Pengadaan
                </h3>

                <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                    Data yang telah dihapus tidak dapat dikembalikan.
                </p>
            </div>

            <form
                action="{{ route('admin.pengadaan.destroy', $pengadaan->id) }}"
                method="POST" onsubmit="return confirm('Yakin ingin menghapus data pengadaan ini?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-red-600
                        px-4
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-red-700
                    ">
                    <i class="ri-delete-bin-line text-lg"></i>

                    Hapus Pengadaan
                </button>
            </form>
        </section>
    </div>
@endsection
