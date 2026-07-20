@extends('layouts.admin.app')

@section('title', 'Detail PPID Pembantu')

@section('content')
    @php
        $kategoriName =
            data_get($ppidPembantu, 'kategoriPpid.kategori') ??
            (data_get($ppidPembantu, 'kategori_ppid.kategori') ?? '-');

        $initial = !empty($ppidPembantu->nama) ? mb_strtoupper(mb_substr($ppidPembantu->nama, 0, 1)) : 'P';

        $websiteUrl = null;

        if (!empty($ppidPembantu->linkweb)) {
            $websiteUrl = \Illuminate\Support\Str::startsWith($ppidPembantu->linkweb, ['http://', 'https://'])
                ? $ppidPembantu->linkweb
                : 'https://' . ltrim($ppidPembantu->linkweb, '/');
        }

        $telephoneUrl = !empty($ppidPembantu->telp)
            ? preg_replace('/[^0-9+]/', '', (string) $ppidPembantu->telp)
            : null;

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

        $createdAt = $formatDateTime(data_get($ppidPembantu, 'created_at'));

        $updatedAt = $formatDateTime(data_get($ppidPembantu, 'updated_at'));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail PPID Pembantu"
            description="Lihat profil, kategori, keterangan, website, kontak, dan alamat unit PPID Pembantu."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'PPID Pembantu',
                    'url' => route('admin.ppid-pembantu.index'),
                ],
                [
                    'label' => 'Detail PPID',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.ppid-pembantu.index') }}"
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

                <a href="{{ route('admin.ppid-pembantu.edit', $ppidPembantu->id) }}"
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

                    <span>Edit PPID</span>
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
                    -top-24
                    h-64
                    w-64
                    rounded-full
                    bg-blue-500/[0.08]
                    blur-3xl
                    dark:bg-blue-500/[0.1]
                "
                aria-hidden="true"></div>

            <div
                class="
                    relative
                    flex
                    flex-col
                    gap-5
                    lg:flex-row
                    lg:items-start
                    lg:justify-between
                ">
                <div class="flex min-w-0 items-start gap-4">
                    <div
                        class="
                            flex
                            h-16
                            w-16
                            shrink-0
                            items-center
                            justify-center
                            rounded-2xl
                            bg-gradient-to-br
                            from-blue-50
                            to-cyan-50
                            text-2xl
                            font-bold
                            text-blue-600
                            ring-1
                            ring-blue-100
                            dark:from-blue-500/15
                            dark:to-cyan-500/15
                            dark:text-blue-400
                            dark:ring-blue-500/20
                        ">
                        @if (!empty($ppidPembantu->icon))
                            <i class="{{ $ppidPembantu->icon }}" aria-hidden="true"></i>
                        @else
                            {{ $initial }}
                        @endif
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
                                    inline-flex
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
                                {{ $kategoriName }}
                            </span>

                            @if (!empty($ppidPembantu->slug))
                                <span
                                    class="
                                        inline-flex
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
                                    {{ $ppidPembantu->slug }}
                                </span>
                            @endif
                        </div>

                        <h2
                            class="
                                mt-3
                                max-w-4xl
                                text-2xl
                                font-bold
                                leading-tight
                                text-gray-900
                                dark:text-white
                                sm:text-3xl
                            ">
                            {{ $ppidPembantu->nama ?? '-' }}
                        </h2>

                        <p
                            class="
                                mt-2
                                text-sm
                                text-gray-500
                                dark:text-gray-400
                            ">
                            ID PPID Pembantu:
                            {{ $ppidPembantu->id }}
                        </p>
                    </div>
                </div>

                @if ($websiteUrl)
                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer"
                        class="
                            inline-flex
                            h-11
                            shrink-0
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-blue-600
                            px-4
                            text-sm
                            font-semibold
                            text-white
                            shadow-theme-xs
                            transition
                            hover:bg-blue-700
                            focus:outline-none
                            focus:ring-3
                            focus:ring-blue-500/20
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-4.553M19.553 5.447H15m4.553 0V10M13 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-7" />
                        </svg>

                        <span>Kunjungi Website</span>
                    </a>
                @endif
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
                            flex
                            items-center
                            gap-3
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                            sm:px-6
                        ">
                        <div
                            class="
                                flex
                                h-10
                                w-10
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
                                    d="M4 6h16M4 10h16M4 14h10M4 18h7" />
                            </svg>
                        </div>

                        <div>
                            <h3
                                class="
                                    text-base
                                    font-semibold
                                    text-gray-800
                                    dark:text-white/90
                                ">
                                Keterangan PPID
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Penjelasan mengenai unit PPID Pembantu.
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-6 sm:px-6">
                        @if (!empty($ppidPembantu->keterangan))
                            <div
                                class="
                                    whitespace-pre-line
                                    text-sm
                                    leading-8
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $ppidPembantu->keterangan }}</div>
                        @else
                            <div
                                class="
                                    rounded-2xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    bg-gray-50
                                    px-6
                                    py-10
                                    text-center
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                ">
                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Keterangan PPID belum tersedia.
                                </p>
                            </div>
                        @endif
                    </div>
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
                            sm:px-6
                        ">
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Kontak dan Lokasi
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Informasi untuk menghubungi atau mengunjungi unit PPID.
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
                                        bg-green-50
                                        text-green-600
                                        dark:bg-green-500/15
                                        dark:text-green-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Nomor Telepon
                                    </p>

                                    @if ($telephoneUrl)
                                        <a href="tel:{{ $telephoneUrl }}"
                                            class="
                                                mt-1.5
                                                block
                                                break-words
                                                text-sm
                                                font-semibold
                                                text-gray-800
                                                hover:text-blue-600
                                                dark:text-gray-200
                                                dark:hover:text-blue-400
                                            ">
                                            {{ $ppidPembantu->telp }}
                                        </a>
                                    @else
                                        <p
                                            class="
                                                mt-1.5
                                                text-sm
                                                text-gray-500
                                                dark:text-gray-400
                                            ">
                                            Belum tersedia
                                        </p>
                                    @endif
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
                                        bg-purple-50
                                        text-purple-600
                                        dark:bg-purple-500/15
                                        dark:text-purple-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
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
                                            text-sm
                                            font-semibold
                                            leading-6
                                            text-gray-800
                                            dark:text-gray-200
                                        ">
                                        {{ $ppidPembantu->alamat ?: 'Belum tersedia' }}
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
                                        bg-blue-50
                                        text-blue-600
                                        dark:bg-blue-500/15
                                        dark:text-blue-400
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 015.656 5.656l-3 3a4 4 0 01-5.656 0M10.172 13.828a4 4 0 01-5.656-5.656l3-3a4 4 0 015.656 0" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <p
                                        class="
                                            text-xs
                                            font-medium
                                            uppercase
                                            tracking-wide
                                            text-gray-400
                                        ">
                                        Website
                                    </p>

                                    @if ($websiteUrl)
                                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer"
                                            class="
                                                mt-1.5
                                                block
                                                break-all
                                                text-sm
                                                font-semibold
                                                text-blue-600
                                                hover:underline
                                                dark:text-blue-400
                                            ">
                                            {{ $ppidPembantu->linkweb }}
                                        </a>
                                    @else
                                        <p
                                            class="
                                                mt-1.5
                                                text-sm
                                                text-gray-500
                                                dark:text-gray-400
                                            ">
                                            Belum tersedia
                                        </p>
                                    @endif
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
                                ID PPID
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $ppidPembantu->id }}
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
                                Kategori
                            </dt>

                            <dd class="mt-2">
                                <span
                                    class="
                                        inline-flex
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
                                    {{ $kategoriName }}
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
                                Slug
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    break-all
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $ppidPembantu->slug ?? '-' }}
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
                                Icon
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    break-all
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $ppidPembantu->icon ?? '-' }}
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
                        Hapus PPID Pembantu
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        Penghapusan dapat memengaruhi data yang terhubung dengan unit PPID ini.
                    </p>

                    <form action="{{ route('admin.ppid-pembantu.destroy', $ppidPembantu->id) }}" method="POST"
                        class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PPID Pembantu ini?')">
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

                            <span>Hapus PPID</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
