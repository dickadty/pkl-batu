@extends('layouts.admin.app')

@section('title', 'Detail Pejabat')

@section('content')
    @php
        $photoUrl = null;

        if (!empty($pejabat->foto)) {
            $photoUrl = \Illuminate\Support\Str::startsWith($pejabat->foto, ['http://', 'https://'])
                ? $pejabat->foto
                : asset('storage/' . ltrim($pejabat->foto, '/'));
        }

        $initial = !empty($pejabat->nama) ? mb_strtoupper(mb_substr($pejabat->nama, 0, 1)) : 'P';

        $telephoneUrl = !empty($pejabat->no_telp) ? preg_replace('/[^0-9+]/', '', (string) $pejabat->no_telp) : null;

        $formatDateTime = static function ($value): string {
            if (empty($value)) {
                return '-';
            }

            try {
                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $createdAt = $formatDateTime(data_get($pejabat, 'created_at'));

        $updatedAt = $formatDateTime(data_get($pejabat, 'updated_at'));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail Pejabat"
            description="Lihat profil, jabatan, masa jabatan, tempat dan tanggal lahir, alamat, serta kontak pejabat."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pejabat',
                    'url' => route('admin.pejabat.index'),
                ],
                [
                    'label' => 'Detail Pejabat',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.pejabat.index') }}"
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
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        focus:outline-none
                        focus:ring-3
                        focus:ring-gray-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                    ">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    <span>Kembali</span>
                </a>

                <a href="{{ route('admin.pejabat.edit', $pejabat->id) }}"
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
                        focus:outline-none
                        focus:ring-3
                        focus:ring-brand-500/20
                    ">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>

                    <span>Edit Pejabat</span>
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
                p-5
                shadow-theme-xs
                dark:border-gray-800
                dark:bg-white/[0.03]
                sm:p-6
            ">
            <div class="
                    pointer-events-none
                    absolute
                    -right-20
                    -top-20
                    h-64
                    w-64
                    rounded-full
                    bg-purple-500/[0.07]
                    blur-3xl
                    dark:bg-purple-500/[0.1]
                "
                aria-hidden="true"></div>

            <div
                class="
                    relative
                    flex
                    flex-col
                    gap-6
                    lg:flex-row
                    lg:items-center
                ">
                @if ($photoUrl)
                    <div
                        class="
                            h-40
                            w-40
                            shrink-0
                            overflow-hidden
                            rounded-3xl
                            border
                            border-gray-200
                            bg-gray-100
                            shadow-theme-xs
                            dark:border-gray-700
                            dark:bg-gray-800
                        ">
                        <img src="{{ $photoUrl }}" alt="{{ $pejabat->nama ?? 'Foto pejabat' }}"
                            class="
                                h-full
                                w-full
                                object-cover
                                object-center
                            ">
                    </div>
                @else
                    <div
                        class="
                            flex
                            h-40
                            w-40
                            shrink-0
                            items-center
                            justify-center
                            rounded-3xl
                            bg-gradient-to-br
                            from-purple-50
                            to-blue-50
                            text-5xl
                            font-bold
                            text-purple-600
                            ring-1
                            ring-purple-100
                            dark:from-purple-500/15
                            dark:to-blue-500/15
                            dark:text-purple-400
                            dark:ring-purple-500/20
                        ">
                        {{ $initial }}
                    </div>
                @endif

                <div class="min-w-0 flex-1">
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
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.18 0-6.21-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>

                        {{ $pejabat->jabatan ?? 'Jabatan belum tersedia' }}
                    </span>

                    <h2
                        class="
                            mt-3
                            text-3xl
                            font-bold
                            tracking-tight
                            text-gray-900
                            dark:text-white
                            sm:text-4xl
                        ">
                        {{ $pejabat->nama ?? '-' }}
                    </h2>

                    <div
                        class="
                            mt-4
                            flex
                            flex-wrap
                            items-center
                            gap-x-5
                            gap-y-3
                            text-sm
                            text-gray-500
                            dark:text-gray-400
                        ">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>

                            Masa Jabatan:
                            {{ $pejabat->masa ?? '-' }}
                        </span>

                        <span>
                            ID Pejabat:
                            {{ $pejabat->id }}
                        </span>
                    </div>

                    @if ($telephoneUrl)
                        <a href="tel:{{ $telephoneUrl }}"
                            class="
                                mt-5
                                inline-flex
                                h-10
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-blue-200
                                bg-blue-50
                                px-4
                                text-sm
                                font-semibold
                                text-blue-700
                                transition
                                hover:bg-blue-100
                                dark:border-blue-500/20
                                dark:bg-blue-500/10
                                dark:text-blue-400
                                dark:hover:bg-blue-500/20
                            ">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>

                            {{ $pejabat->no_telp }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
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
                            sm:px-6
                        ">
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Informasi Pribadi
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Data profil dan identitas pejabat.
                        </p>
                    </div>

                    <div
                        class="
                            grid
                            grid-cols-1
                            gap-4
                            p-5
                            sm:grid-cols-2
                            sm:p-6
                        ">
                        <div
                            class="
                                rounded-2xl
                                border
                                border-gray-200
                                bg-gray-50/70
                                p-4
                                dark:border-gray-700
                                dark:bg-gray-900/50
                            ">
                            <div class="flex items-start gap-3">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-purple-50
                                        text-purple-600
                                        dark:bg-purple-500/15
                                        dark:text-purple-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A9.004 9.004 0 0112 15c2.21 0 4.23.796 5.879 2.117M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>

                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Nama Lengkap
                                    </p>

                                    <p
                                        class="
                                            mt-1.5
                                            text-sm
                                            font-semibold
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $pejabat->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="
                                rounded-2xl
                                border
                                border-gray-200
                                bg-gray-50/70
                                p-4
                                dark:border-gray-700
                                dark:bg-gray-900/50
                            ">
                            <div class="flex items-start gap-3">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-blue-50
                                        text-blue-600
                                        dark:bg-blue-500/15
                                        dark:text-blue-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.18 0-6.21-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Jabatan
                                    </p>

                                    <p
                                        class="
                                            mt-1.5
                                            text-sm
                                            font-semibold
                                            leading-6
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $pejabat->jabatan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="
                                rounded-2xl
                                border
                                border-gray-200
                                bg-gray-50/70
                                p-4
                                dark:border-gray-700
                                dark:bg-gray-900/50
                            ">
                            <div class="flex items-start gap-3">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-green-50
                                        text-green-600
                                        dark:bg-green-500/15
                                        dark:text-green-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                </div>

                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Masa Jabatan
                                    </p>

                                    <p
                                        class="
                                            mt-1.5
                                            text-sm
                                            font-semibold
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $pejabat->masa ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="
                                rounded-2xl
                                border
                                border-gray-200
                                bg-gray-50/70
                                p-4
                                dark:border-gray-700
                                dark:bg-gray-900/50
                            ">
                            <div class="flex items-start gap-3">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-orange-50
                                        text-orange-600
                                        dark:bg-orange-500/15
                                        dark:text-orange-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                    </svg>
                                </div>

                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Tempat/Tanggal Lahir
                                    </p>

                                    <p
                                        class="
                                            mt-1.5
                                            text-sm
                                            font-semibold
                                            leading-6
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $pejabat->tmp_tgl_lahir ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="
                                rounded-2xl
                                border
                                border-gray-200
                                bg-gray-50/70
                                p-4
                                dark:border-gray-700
                                dark:bg-gray-900/50
                                sm:col-span-2
                            ">
                            <div class="flex items-start gap-3">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-red-50
                                        text-red-600
                                        dark:bg-red-500/15
                                        dark:text-red-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>

                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Alamat
                                    </p>

                                    <p
                                        class="
                                            mt-1.5
                                            whitespace-pre-line
                                            text-sm
                                            font-semibold
                                            leading-7
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $pejabat->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
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
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Detail Data
                        </h3>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                ID Pejabat
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $pejabat->id }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Nomor Telepon
                            </dt>

                            <dd class="mt-1.5">
                                @if ($telephoneUrl)
                                    <a href="tel:{{ $telephoneUrl }}"
                                        class="
                                            text-sm
                                            font-semibold
                                            text-blue-600
                                            hover:underline
                                            dark:text-blue-400
                                        ">
                                        {{ $pejabat->no_telp }}
                                    </a>
                                @else
                                    <span
                                        class="
                                            text-sm
                                            text-gray-500
                                            dark:text-gray-400
                                        ">
                                        -
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Foto
                            </dt>

                            <dd class="mt-2">
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        {{ $photoUrl
                                            ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}
                                    ">
                                    {{ $photoUrl ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Dibuat
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $createdAt }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Diperbarui
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $updatedAt }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <section
                    class="
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50/70
                        p-5
                        dark:border-red-500/20
                        dark:bg-red-500/10
                    ">
                    <h3
                        class="
                            text-sm
                            font-semibold
                            text-red-800
                            dark:text-red-300
                        ">
                        Hapus Data Pejabat
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        Data dan foto pejabat yang dihapus tidak dapat dikembalikan.
                    </p>

                    <form action="{{ route('admin.pejabat.destroy', $pejabat->id) }}" method="POST" class="mt-4"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pejabat ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="
                                inline-flex
                                h-10
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-red-300
                                bg-white
                                px-4
                                text-sm
                                font-semibold
                                text-red-600
                                transition
                                hover:bg-red-600
                                hover:text-white
                                focus:outline-none
                                focus:ring-3
                                focus:ring-red-500/20
                                dark:border-red-500/30
                                dark:bg-gray-900
                                dark:text-red-400
                                dark:hover:bg-red-600
                                dark:hover:text-white
                            ">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            <span>Hapus Pejabat</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
