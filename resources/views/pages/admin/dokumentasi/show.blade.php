@extends('layouts.admin.app')

@section('title', 'Detail Informasi Publik')

@section('content')
    @php
        $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

        $ppidName =
            data_get($dokumentasi, 'ppidPembantu.nama') ?? (data_get($dokumentasi, 'ppid_pembantu.nama') ?? '-');

        $rawStatus = data_get($dokumentasi, 'status_label', data_get($dokumentasi, 'status', 'Belum Diverifikasi'));

        $normalizedStatus = strtolower(trim((string) $rawStatus));

        $isVerified = in_array($normalizedStatus, ['1', 'verified', 'terverifikasi', 'disetujui', 'aktif'], true);

        $statusLabel = $isVerified
            ? (in_array($normalizedStatus, ['1', 'verified'], true)
                ? 'Terverifikasi'
                : (string) $rawStatus)
            : (in_array($normalizedStatus, ['', '0'], true)
                ? 'Belum Diverifikasi'
                : (string) $rawStatus);

        $statusClass = $isVerified
            ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20'
            : 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/15 dark:text-yellow-400 dark:ring-yellow-500/20';

        $sifatKey = strtolower(trim((string) ($dokumentasi->sifat ?? '')));

        $sifatClass = match ($sifatKey) {
            'berkala' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

            'serta merta' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

            'setiap saat' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

            'dikecualikan' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

            default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        };

        $filePath = trim((string) ($dokumentasi->file ?? ''));

        $fileName = $filePath !== '' ? basename($filePath) : null;

        $fileExtension = $fileName ? strtoupper(pathinfo($fileName, PATHINFO_EXTENSION)) : null;

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

        $createdAt = $formatDateTime(data_get($dokumentasi, 'created_at'));

        $updatedAt = $formatDateTime(data_get($dokumentasi, 'updated_at'));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail Informasi Publik"
            description="Lihat isi, klasifikasi, unit pengelola, status verifikasi, dan file informasi publik."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Informasi & Dokumentasi',
                ],
                [
                    'label' => 'Daftar Informasi',
                    'url' => route('admin.informasi-publik.index'),
                ],
                [
                    'label' => 'Detail Informasi',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.informasi-publik.index') }}"
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

                <a href="{{ route('admin.informasi-publik.edit', $dokumentasi->id) }}"
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

                    <span>Edit Informasi</span>
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
                    -right-16
                    -top-20
                    h-52
                    w-52
                    rounded-full
                    bg-cyan-500/[0.06]
                    blur-3xl
                    dark:bg-cyan-500/[0.08]
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
                            h-14
                            w-14
                            shrink-0
                            items-center
                            justify-center
                            rounded-2xl
                            bg-cyan-50
                            text-cyan-600
                            ring-1
                            ring-cyan-100
                            dark:bg-cyan-500/15
                            dark:text-cyan-400
                            dark:ring-cyan-500/20
                        ">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    {{ $sifatClass }}
                                ">
                                {{ $dokumentasi->sifat ? \Illuminate\Support\Str::title($dokumentasi->sifat) : 'Sifat belum ditentukan' }}
                            </span>

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    ring-1
                                    ring-inset
                                    {{ $statusClass }}
                                ">
                                <span
                                    class="
                                        h-1.5
                                        w-1.5
                                        rounded-full
                                        {{ $isVerified ? 'bg-green-500' : 'bg-yellow-500' }}
                                    "></span>

                                {{ $statusLabel }}
                            </span>
                        </div>

                        <h2
                            class="
                                mt-3
                                max-w-4xl
                                text-xl
                                font-bold
                                leading-8
                                text-gray-900
                                dark:text-white
                                sm:text-2xl
                            ">
                            {{ $dokumentasi->nama ?? '-' }}
                        </h2>

                        <div
                            class="
                                mt-3
                                flex
                                flex-wrap
                                items-center
                                gap-x-5
                                gap-y-2
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

                                Tahun {{ $dokumentasi->tahun ?? '-' }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>

                                {{ $ppidName }}
                            </span>

                            <span>
                                ID Informasi: {{ $dokumentasi->id }}
                            </span>
                        </div>
                    </div>
                </div>

                @if ($fileName)
                    <a href="{{ route('public.informasi.download', $dokumentasi->id) }}"
                        class="
                            inline-flex
                            h-11
                            shrink-0
                            items-center
                            justify-center
                            gap-2
                            rounded-lg
                            bg-green-600
                            px-4
                            text-sm
                            font-semibold
                            text-white
                            shadow-theme-xs
                            transition
                            hover:bg-green-700
                            focus:outline-none
                            focus:ring-3
                            focus:ring-green-500/20
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14a2 2 0 002-2v-3M3 16v3a2 2 0 002 2" />
                        </svg>

                        <span>Unduh File</span>
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
                                Ringkasan Informasi
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Uraian singkat mengenai informasi yang tersedia.
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-5 sm:px-6 sm:py-6">
                        @if (!empty($dokumentasi->ringkasan))
                            <div
                                class="
                                    whitespace-pre-line
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $dokumentasi->ringkasan }}
                            </div>
                        @else
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    bg-gray-50
                                    px-5
                                    py-8
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
                                    Ringkasan informasi belum tersedia.
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
                                bg-green-50
                                text-green-600
                                dark:bg-green-500/15
                                dark:text-green-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
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
                                File Informasi
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Dokumen yang tersimpan pada informasi publik ini.
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-5 sm:px-6">
                        @if ($fileName)
                            <div
                                class="
                                    flex
                                    flex-col
                                    gap-4
                                    rounded-2xl
                                    border
                                    border-gray-200
                                    bg-gray-50/70
                                    p-4
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                ">
                                <div class="flex min-w-0 items-center gap-4">
                                    <div
                                        class="
                                            flex
                                            h-12
                                            w-12
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-white
                                            text-green-600
                                            shadow-theme-xs
                                            dark:bg-gray-800
                                            dark:text-green-400
                                        ">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="
                                                truncate
                                                text-sm
                                                font-semibold
                                                text-gray-800
                                                dark:text-white/90
                                            "
                                            title="{{ $fileName }}">
                                            {{ $fileName }}
                                        </p>

                                        <div
                                            class="
                                                mt-1
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-2
                                                text-xs
                                                text-gray-500
                                                dark:text-gray-400
                                            ">
                                            @if ($fileExtension)
                                                <span
                                                    class="
                                                        rounded-md
                                                        bg-gray-200
                                                        px-2
                                                        py-1
                                                        font-semibold
                                                        text-gray-600
                                                        dark:bg-gray-700
                                                        dark:text-gray-300
                                                    ">
                                                    {{ $fileExtension }}
                                                </span>
                                            @endif

                                            <span>
                                                File informasi publik
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('public.informasi.download', $dokumentasi->id) }}"
                                    class="
                                        inline-flex
                                        h-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        border
                                        border-green-200
                                        bg-green-50
                                        px-4
                                        text-sm
                                        font-semibold
                                        text-green-700
                                        transition
                                        hover:bg-green-100
                                        dark:border-green-500/20
                                        dark:bg-green-500/10
                                        dark:text-green-400
                                        dark:hover:bg-green-500/20
                                    ">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14a2 2 0 002-2v-3M3 16v3a2 2 0 002 2" />
                                    </svg>

                                    <span>Unduh</span>
                                </a>
                            </div>
                        @else
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    bg-gray-50
                                    px-5
                                    py-8
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
                                    File informasi belum tersedia.
                                </p>
                            </div>
                        @endif
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
                            Detail Informasi
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
                                ID Informasi
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $dokumentasi->id }}
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
                                Tahun
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $dokumentasi->tahun ?? '-' }}
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
                                Sifat Informasi
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
                                        {{ $sifatClass }}
                                    ">
                                    {{ $dokumentasi->sifat ? \Illuminate\Support\Str::title($dokumentasi->sifat) : '-' }}
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
                                PPID Pembantu
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    leading-6
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $ppidName }}
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
                                Status
                            </dt>

                            <dd class="mt-2">
                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        ring-1
                                        ring-inset
                                        {{ $statusClass }}
                                    ">
                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            {{ $isVerified ? 'bg-green-500' : 'bg-yellow-500' }}
                                        "></span>

                                    {{ $statusLabel }}
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
                                Terakhir Diperbarui
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

                        @if (!empty($dokumentasi->slug))
                            <div class="px-5 py-4">
                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-gray-400
                                    ">
                                    Slug Publik
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        break-all
                                        text-sm
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    {{ $dokumentasi->slug }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </section>

                @if ($isAdminUtama && !$isVerified)
                    <section
                        class="
                            rounded-2xl
                            border
                            border-emerald-200
                            bg-emerald-50/70
                            p-5
                            dark:border-emerald-500/20
                            dark:bg-emerald-500/10
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
                                    bg-white
                                    text-emerald-600
                                    shadow-theme-xs
                                    dark:bg-gray-900
                                    dark:text-emerald-400
                                ">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div>
                                <h3
                                    class="
                                        text-sm
                                        font-semibold
                                        text-emerald-800
                                        dark:text-emerald-300
                                    ">
                                    Verifikasi Informasi
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        leading-6
                                        text-emerald-700
                                        dark:text-emerald-400
                                    ">
                                    Dokumen ini belum diverifikasi oleh Admin Utama.
                                </p>
                            </div>
                        </div>

                        <form
                            action="{{ route('admin.informasi-publik.verifikasi', $dokumentasi->id) }}"
                            method="POST" class="mt-4"
                            onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi informasi ini?')">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="
                                    inline-flex
                                    h-10
                                    w-full
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    bg-emerald-600
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-emerald-700
                                    focus:outline-none
                                    focus:ring-3
                                    focus:ring-emerald-500/20
                                ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                                <span>Verifikasi Sekarang</span>
                            </button>
                        </form>
                    </section>
                @endif

                <section
                    class="
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50/60
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
                        Hapus Informasi
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        Data dan file yang dihapus tidak dapat dikembalikan.
                    </p>

                    <form
                        action="{{ route('admin.informasi-publik.destroy', $dokumentasi->id) }}"
                        method="POST" class="mt-4"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi publik ini?')">
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

                            <span>Hapus Informasi</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
