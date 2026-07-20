@extends('layouts.admin.app')

@section('title', 'Detail Permohonan Informasi')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Data dasar
        |--------------------------------------------------------------------------
        */

        $adminRole = (int) data_get($admin, 'role', 0);

        $status = trim((string) data_get($permohonan, 'status', 'Diajukan'));

        if ($status === '') {
            $status = 'Diajukan';
        }

        /*
        |--------------------------------------------------------------------------
        | Format tanggal
        |--------------------------------------------------------------------------
        */

        $formatDateTime = static function ($value): string {
            if ($value === null || $value === '') {
                return '-';
            }

            try {
                if (is_numeric($value) && (int) $value > 100000000) {
                    return \Illuminate\Support\Carbon::createFromTimestamp((int) $value)->translatedFormat(
                        'd F Y, H:i',
                    );
                }

                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $tanggalPermohonan = $formatDateTime(data_get($permohonan, 'tanggal'));

        $tanggalJawabPembantu = $formatDateTime(data_get($permohonan, 'tanggal_jawab_pembantu'));

        $tanggalJawab = $formatDateTime(data_get($permohonan, 'tanggal_jawab'));

        $createdAt = $formatDateTime(data_get($permohonan, 'created_at'));

        $updatedAt = $formatDateTime(data_get($permohonan, 'updated_at'));

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $statusClasses = [
            'Diajukan' => [
                'badge' =>
                    'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/15 dark:text-blue-400 dark:ring-blue-500/20',
                'dot' => 'bg-blue-500',
                'icon' => 'text-blue-600 dark:text-blue-400',
                'iconBackground' => 'bg-blue-50 dark:bg-blue-500/15',
            ],

            'Diproses' => [
                'badge' =>
                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/15 dark:text-yellow-400 dark:ring-yellow-500/20',
                'dot' => 'bg-yellow-500',
                'icon' => 'text-yellow-600 dark:text-yellow-400',
                'iconBackground' => 'bg-yellow-50 dark:bg-yellow-500/15',
            ],

            'Diteruskan ke PPID Pembantu' => [
                'badge' =>
                    'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-500/15 dark:text-purple-400 dark:ring-purple-500/20',
                'dot' => 'bg-purple-500',
                'icon' => 'text-purple-600 dark:text-purple-400',
                'iconBackground' => 'bg-purple-50 dark:bg-purple-500/15',
            ],

            'Menunggu Validasi Admin Utama' => [
                'badge' =>
                    'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-500/15 dark:text-orange-400 dark:ring-orange-500/20',
                'dot' => 'bg-orange-500',
                'icon' => 'text-orange-600 dark:text-orange-400',
                'iconBackground' => 'bg-orange-50 dark:bg-orange-500/15',
            ],

            'Revisi PPID Pembantu' => [
                'badge' =>
                    'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-400 dark:ring-red-500/20',
                'dot' => 'bg-red-500',
                'icon' => 'text-red-600 dark:text-red-400',
                'iconBackground' => 'bg-red-50 dark:bg-red-500/15',
            ],

            'Selesai' => [
                'badge' =>
                    'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20',
                'dot' => 'bg-green-500',
                'icon' => 'text-green-600 dark:text-green-400',
                'iconBackground' => 'bg-green-50 dark:bg-green-500/15',
            ],
        ];

        $defaultStatusClass = [
            'badge' =>
                'bg-gray-100 text-gray-700 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600/30',
            'dot' => 'bg-gray-400',
            'icon' => 'text-gray-600 dark:text-gray-400',
            'iconBackground' => 'bg-gray-100 dark:bg-gray-800',
        ];

        $currentStatusClass = $statusClasses[$status] ?? $defaultStatusClass;

        /*
        |--------------------------------------------------------------------------
        | Pemohon
        |--------------------------------------------------------------------------
        */

        $pemohonNama = data_get($permohonan, 'userPublic.nama', '-');

        $pemohonEmail = data_get($permohonan, 'userPublic.email', '-');

        $pemohonTelepon = data_get($permohonan, 'userPublic.telp');

        $pemohonNik = data_get($permohonan, 'userPublic.nik');

        $pemohonInitial = $pemohonNama !== '-' ? mb_strtoupper(mb_substr($pemohonNama, 0, 1)) : 'P';

        /*
        |--------------------------------------------------------------------------
        | PPID Pembantu
        |--------------------------------------------------------------------------
        */

        $ppidPembantuNama = data_get($permohonan, 'ppidPembantu.nama', '-');

        /*
        |--------------------------------------------------------------------------
        | File
        |--------------------------------------------------------------------------
        */

        $filePembantu = data_get($permohonan, 'file_pembantu');

        $fileJawaban = data_get($permohonan, 'file_jawaban');

        $filePembantuUrl = null;
        $fileJawabanUrl = null;

        if ($filePembantu) {
            $filePembantuUrl = \Illuminate\Support\Str::startsWith($filePembantu, ['http://', 'https://'])
                ? $filePembantu
                : asset('storage/' . ltrim($filePembantu, '/'));
        }

        if ($fileJawaban) {
            $fileJawabanUrl = \Illuminate\Support\Str::startsWith($fileJawaban, ['http://', 'https://'])
                ? $fileJawaban
                : asset('storage/' . ltrim($fileJawaban, '/'));
        }

        /*
        |--------------------------------------------------------------------------
        | Hak akses tindakan
        |--------------------------------------------------------------------------
        */

        $canForward = $adminRole === 1 && in_array($status, ['Diajukan', 'Diproses'], true);

        $canAnswerAsPpid =
            $adminRole === 2 && in_array($status, ['Diteruskan ke PPID Pembantu', 'Revisi PPID Pembantu'], true);

        $canValidate = $adminRole === 1 && $status === 'Menunggu Validasi Admin Utama';
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Detail Permohonan Informasi"
            description="Lihat identitas pemohon, rincian permohonan, alur disposisi, laporan PPID Pembantu, dan jawaban final."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Permohonan Informasi',
                    'url' => route('admin.permohonan.index'),
                ],
                [
                    'label' => 'Detail Permohonan',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.permohonan.index') }}"
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
            </x-slot:actions>
        </x-admin.page-header>

        {{-- ============================================================
            PESAN SISTEM
        ============================================================= --}}

        <x-ui.flash-messages />

        @if ($errors->any())
            <div class="
                    rounded-2xl
                    border
                    border-red-200
                    bg-red-50
                    p-5
                    dark:border-red-500/20
                    dark:bg-red-500/10
                "
                role="alert">
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
                            bg-red-100
                            text-red-600
                            dark:bg-red-500/15
                            dark:text-red-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 19h14.76a2 2 0 001.73-3l-7.4-12.14a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            ">
                            Data belum valid
                        </h3>

                        <ul
                            class="
                                mt-2
                                list-disc
                                space-y-1
                                pl-5
                                text-sm
                                leading-6
                                text-red-700
                                dark:text-red-400
                            ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================================================
            RINGKASAN PERMOHONAN
        ============================================================= --}}

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
                    bg-blue-500/[0.07]
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
                            h-14
                            w-14
                            shrink-0
                            items-center
                            justify-center
                            rounded-2xl
                            {{ $currentStatusClass['iconBackground'] }}
                            {{ $currentStatusClass['icon'] }}
                        ">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6M9 8h6m2 13H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                        </svg>
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
                                    items-center
                                    gap-2
                                    rounded-full
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    ring-1
                                    ring-inset
                                    {{ $currentStatusClass['badge'] }}
                                ">
                                <span
                                    class="
                                        h-2
                                        w-2
                                        rounded-full
                                        {{ $currentStatusClass['dot'] }}
                                    "></span>

                                {{ $status }}
                            </span>

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
                                ID: {{ $permohonan->id }}
                            </span>
                        </div>

                        <h2
                            class="
                                mt-3
                                break-words
                                text-2xl
                                font-bold
                                tracking-tight
                                text-gray-900
                                dark:text-white
                                sm:text-3xl
                            ">
                            {{ $permohonan->no_pemohon ?? '-' }}
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

                                {{ $tanggalPermohonan }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A9.004 9.004 0 0112 15c2.21 0 4.23.796 5.879 2.117M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                {{ $pemohonNama }}
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    class="
                        flex
                        shrink-0
                        items-center
                        gap-3
                        rounded-xl
                        border
                        border-gray-200
                        bg-gray-50
                        px-4
                        py-3
                        dark:border-gray-700
                        dark:bg-gray-900/60
                    ">
                    <div
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-full
                            bg-white
                            text-blue-600
                            shadow-theme-xs
                            dark:bg-gray-800
                            dark:text-blue-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
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
                            Tanggal Pengajuan
                        </p>

                        <p
                            class="
                                mt-1
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-gray-200
                            ">
                            {{ $tanggalPermohonan }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================
            INFORMASI UTAMA
        ============================================================= --}}

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                {{-- Rincian permohonan --}}
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
                                Rincian Permohonan
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Informasi yang diminta dan tujuan penggunaannya.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5 sm:p-6">
                        <div>
                            <p
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Rincian Informasi
                            </p>

                            <div
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-2xl
                                    border
                                    border-gray-200
                                    bg-gray-50/70
                                    px-5
                                    py-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                    dark:text-gray-300
                                ">
                                {{ $permohonan->rincian ?? '-' }}</div>
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
                                Tujuan Penggunaan Informasi
                            </p>

                            <div
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-2xl
                                    border
                                    border-gray-200
                                    bg-gray-50/70
                                    px-5
                                    py-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                    dark:text-gray-300
                                ">
                                {{ $permohonan->tujuan ?? '-' }}</div>
                        </div>
                    </div>
                </section>

                {{-- Disposisi --}}
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
                                bg-purple-50
                                text-purple-600
                                dark:bg-purple-500/15
                                dark:text-purple-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
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
                                Disposisi Permohonan
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Unit PPID tujuan dan catatan dari Admin Utama.
                            </p>
                        </div>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-5
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                                sm:px-6
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-medium
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                PPID Pembantu Tujuan
                            </dt>

                            <dd
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $ppidPembantuNama }}
                            </dd>
                        </div>

                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-5
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                                sm:px-6
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-medium
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Catatan Admin Utama
                            </dt>

                            <dd
                                class="
                                    whitespace-pre-line
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $permohonan->catatan_utama ?? '-' }}</dd>
                        </div>
                    </dl>
                </section>

                {{-- Laporan PPID Pembantu --}}
                @if ($permohonan->jawaban_pembantu || $filePembantu || $permohonan->tanggal_jawab_pembantu)
                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-purple-200
                            bg-white
                            shadow-theme-xs
                            dark:border-purple-500/20
                            dark:bg-white/[0.03]
                        ">
                        <div
                            class="
                                flex
                                items-center
                                gap-3
                                border-b
                                border-purple-100
                                bg-purple-50/60
                                px-5
                                py-4
                                dark:border-purple-500/20
                                dark:bg-purple-500/[0.06]
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
                                    bg-purple-100
                                    text-purple-600
                                    dark:bg-purple-500/15
                                    dark:text-purple-400
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
                                        text-base
                                        font-semibold
                                        text-purple-900
                                        dark:text-purple-300
                                    ">
                                    Laporan dari PPID Pembantu
                                </h3>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-purple-700
                                        dark:text-purple-400
                                    ">
                                    Laporan yang telah dikirim untuk diperiksa Admin Utama.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                    text-sm
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>

                                <span>
                                    Dikirim pada
                                    <strong
                                        class="
                                            font-semibold
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        {{ $tanggalJawabPembantu }}
                                    </strong>
                                </span>
                            </div>

                            <div
                                class="
                                    whitespace-pre-line
                                    rounded-2xl
                                    border
                                    border-purple-100
                                    bg-purple-50/40
                                    px-5
                                    py-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-purple-500/20
                                    dark:bg-purple-500/[0.05]
                                    dark:text-gray-300
                                ">
                                {{ $permohonan->jawaban_pembantu ?? '-' }}</div>

                            @if ($filePembantuUrl)
                                <a href="{{ $filePembantuUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        h-11
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        border
                                        border-purple-200
                                        bg-purple-50
                                        px-4
                                        text-sm
                                        font-semibold
                                        text-purple-700
                                        transition
                                        hover:bg-purple-100
                                        focus:outline-none
                                        focus:ring-3
                                        focus:ring-purple-500/20
                                        dark:border-purple-500/20
                                        dark:bg-purple-500/10
                                        dark:text-purple-400
                                        dark:hover:bg-purple-500/20
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-4.553M19.553 5.447H15m4.553 0V10M13 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-7" />
                                    </svg>

                                    <span>Lihat File Laporan</span>
                                </a>
                            @endif
                        </div>
                    </section>
                @endif

                {{-- Catatan revisi --}}
                @if ($permohonan->catatan_revisi)
                    <section
                        class="
                            rounded-2xl
                            border
                            border-yellow-200
                            bg-yellow-50
                            p-5
                            dark:border-yellow-500/20
                            dark:bg-yellow-500/10
                            sm:p-6
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
                                    bg-yellow-100
                                    text-yellow-700
                                    dark:bg-yellow-500/15
                                    dark:text-yellow-400
                                ">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 19h14.76a2 2 0 001.73-3l-7.4-12.14a2 2 0 00-3.42 0z" />
                                </svg>
                            </div>

                            <div>
                                <h3
                                    class="
                                        text-sm
                                        font-semibold
                                        text-yellow-900
                                        dark:text-yellow-300
                                    ">
                                    Catatan Revisi
                                </h3>

                                <p
                                    class="
                                        mt-2
                                        whitespace-pre-line
                                        text-sm
                                        leading-7
                                        text-yellow-800
                                        dark:text-yellow-400
                                    ">
                                    {{ $permohonan->catatan_revisi }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Jawaban final --}}
                @if ($permohonan->jawaban || $fileJawaban || $permohonan->tanggal_jawab)
                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-green-200
                            bg-white
                            shadow-theme-xs
                            dark:border-green-500/20
                            dark:bg-white/[0.03]
                        ">
                        <div
                            class="
                                flex
                                items-center
                                gap-3
                                border-b
                                border-green-100
                                bg-green-50/60
                                px-5
                                py-4
                                dark:border-green-500/20
                                dark:bg-green-500/[0.06]
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
                                    bg-green-100
                                    text-green-600
                                    dark:bg-green-500/15
                                    dark:text-green-400
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
                                        text-base
                                        font-semibold
                                        text-green-900
                                        dark:text-green-300
                                    ">
                                    Jawaban Final untuk Warga
                                </h3>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-green-700
                                        dark:text-green-400
                                    ">
                                    Jawaban yang telah disahkan dan dikirim kepada pemohon.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                    text-sm
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>

                                <span>
                                    Dikirim pada
                                    <strong
                                        class="
                                            font-semibold
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        {{ $tanggalJawab }}
                                    </strong>
                                </span>
                            </div>

                            <div
                                class="
                                    whitespace-pre-line
                                    rounded-2xl
                                    border
                                    border-green-100
                                    bg-green-50/40
                                    px-5
                                    py-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-green-500/20
                                    dark:bg-green-500/[0.05]
                                    dark:text-gray-300
                                ">
                                {{ $permohonan->jawaban ?? '-' }}</div>

                            @if ($fileJawabanUrl)
                                <a href="{{ $fileJawabanUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        h-11
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
                                        focus:outline-none
                                        focus:ring-3
                                        focus:ring-green-500/20
                                        dark:border-green-500/20
                                        dark:bg-green-500/10
                                        dark:text-green-400
                                        dark:hover:bg-green-500/20
                                    ">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-4.553M19.553 5.447H15m4.553 0V10M13 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-7" />
                                    </svg>

                                    <span>Lihat File Jawaban</span>
                                </a>
                            @endif
                        </div>
                    </section>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Pemohon --}}
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
                            Identitas Pemohon
                        </h3>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="
                                    flex
                                    h-12
                                    w-12
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-gradient-to-br
                                    from-blue-50
                                    to-purple-50
                                    text-lg
                                    font-bold
                                    text-blue-600
                                    ring-1
                                    ring-blue-100
                                    dark:from-blue-500/15
                                    dark:to-purple-500/15
                                    dark:text-blue-400
                                    dark:ring-blue-500/20
                                ">
                                {{ $pemohonInitial }}
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="
                                        truncate
                                        text-sm
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                    {{ $pemohonNama }}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        truncate
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    {{ $pemohonEmail }}
                                </p>
                            </div>
                        </div>
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
                                Email
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    break-all
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $pemohonEmail }}
                            </dd>
                        </div>

                        @if ($pemohonTelepon)
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
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $pemohonTelepon) }}"
                                        class="
                                            text-sm
                                            font-semibold
                                            text-blue-600
                                            hover:underline
                                            dark:text-blue-400
                                        ">
                                        {{ $pemohonTelepon }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        @if ($pemohonNik)
                            <div class="px-5 py-4">
                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-gray-400
                                    ">
                                    NIK
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        text-sm
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    {{ $pemohonNik }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </section>

                {{-- Detail data --}}
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
                                Nomor Permohonan
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    break-words
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $permohonan->no_pemohon ?? '-' }}
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
                                        gap-2
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        ring-1
                                        ring-inset
                                        {{ $currentStatusClass['badge'] }}
                                    ">
                                    <span
                                        class="
                                            h-2
                                            w-2
                                            rounded-full
                                            {{ $currentStatusClass['dot'] }}
                                        "></span>

                                    {{ $status }}
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
                                PPID Tujuan
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
                                {{ $ppidPembantuNama }}
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
            </aside>
        </div>

        {{-- ============================================================
            TINDAKAN ADMIN UTAMA: TERUSKAN
        ============================================================= --}}

        @if ($canForward)
            <section
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-blue-200
                    bg-white
                    shadow-theme-xs
                    dark:border-blue-500/20
                    dark:bg-white/[0.03]
                ">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-blue-100
                        bg-blue-50/60
                        px-5
                        py-4
                        dark:border-blue-500/20
                        dark:bg-blue-500/[0.06]
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
                            bg-blue-100
                            text-blue-600
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>

                    <div>
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-blue-900
                                dark:text-blue-300
                            ">
                            Teruskan ke PPID Pembantu
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-blue-700
                                dark:text-blue-400
                            ">
                            Pilih unit PPID yang bertanggung jawab dan berikan catatan disposisi.
                        </p>
                    </div>
                </div>

                <form
                    action="{{ route('admin.permohonan.teruskan', $permohonan->id) }}"
                    method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                    @csrf

                    <div
                        class="
                            grid
                            grid-cols-1
                            gap-5
                            lg:grid-cols-2
                        ">
                        <div>
                            <label for="ppid_pembantuid"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                PPID Pembantu

                                <span class="text-red-500">*</span>
                            </label>

                            <select id="ppid_pembantuid" name="ppid_pembantuid" required
                                class="
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    px-4
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
                                    @error('ppid_pembantuid')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">
                                <option value="">
                                    Pilih PPID Pembantu
                                </option>

                                @foreach ($ppidPembantu as $ppid)
                                    <option value="{{ $ppid->id }}" @selected((string) old('ppid_pembantuid', data_get($permohonan, 'ppid_pembantuid')) === (string) $ppid->id)>
                                        {{ $ppid->nama }}
                                    </option>
                                @endforeach
                            </select>

                            @error('ppid_pembantuid')
                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-red-500
                                    ">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan_utama"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Catatan untuk PPID Pembantu
                            </label>

                            <textarea id="catatan_utama" name="catatan_utama" rows="5"
                                placeholder="Contoh: Mohon siapkan laporan sesuai rincian permohonan warga."
                                class="
                                    w-full
                                    resize-y
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    px-4
                                    py-3
                                    text-sm
                                    leading-6
                                    text-gray-800
                                    outline-none
                                    transition
                                    placeholder:text-gray-400
                                    focus:border-brand-300
                                    focus:ring-3
                                    focus:ring-brand-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    @error('catatan_utama')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">{{ old('catatan_utama', $permohonan->catatan_utama) }}</textarea>

                            @error('catatan_utama')
                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-red-500
                                    ">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="
                            flex
                            justify-end
                            border-t
                            border-gray-100
                            pt-5
                            dark:border-gray-800
                        ">
                        <button type="submit" :disabled="submitting"
                            class="
                                inline-flex
                                h-11
                                min-w-[210px]
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                bg-blue-600
                                px-5
                                text-sm
                                font-semibold
                                text-white
                                shadow-theme-xs
                                transition
                                hover:bg-blue-700
                                focus:outline-none
                                focus:ring-3
                                focus:ring-blue-500/20
                                disabled:cursor-not-allowed
                                disabled:opacity-60
                            ">
                            <svg x-show="!submitting" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>

                            <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z">
                                </path>
                            </svg>

                            <span
                                x-text="submitting
                                    ? 'Memproses...'
                                    : 'Teruskan Permohonan'"></span>
                        </button>
                    </div>
                </form>
            </section>
        @endif

        {{-- ============================================================
            TINDAKAN PPID PEMBANTU: KIRIM LAPORAN
        ============================================================= --}}

        @if ($canAnswerAsPpid)
            <section
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-purple-200
                    bg-white
                    shadow-theme-xs
                    dark:border-purple-500/20
                    dark:bg-white/[0.03]
                ">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-purple-100
                        bg-purple-50/60
                        px-5
                        py-4
                        dark:border-purple-500/20
                        dark:bg-purple-500/[0.06]
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
                            bg-purple-100
                            text-purple-600
                            dark:bg-purple-500/15
                            dark:text-purple-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>

                    <div>
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-purple-900
                                dark:text-purple-300
                            ">
                            Kirim Laporan ke Admin Utama
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-purple-700
                                dark:text-purple-400
                            ">
                            Masukkan jawaban PPID Pembantu dan unggah file pendukung bila tersedia.
                        </p>
                    </div>
                </div>

                <form
                    action="{{ route('admin.permohonan.jawab-pembantu', $permohonan->id) }}"
                    method="POST" enctype="multipart/form-data" x-data="{
                        submitting: false,
                        fileName: '',
                        fileError: '',
                    
                        handleFile(event) {
                            const file = event.target.files[0];
                    
                            this.fileName = '';
                            this.fileError = '';
                    
                            if (!file) {
                                return;
                            }
                    
                            const allowedExtensions = [
                                'pdf',
                                'doc',
                                'docx',
                                'xls',
                                'xlsx',
                                'jpg',
                                'jpeg',
                                'png'
                            ];
                    
                            const extension = file.name
                                .split('.')
                                .pop()
                                .toLowerCase();
                    
                            if (!allowedExtensions.includes(extension)) {
                                this.fileError =
                                    'Format file tidak didukung.';
                    
                                event.target.value = '';
                                return;
                            }
                    
                            if (file.size > 5 * 1024 * 1024) {
                                this.fileError =
                                    'Ukuran file maksimal 5 MB.';
                    
                                event.target.value = '';
                                return;
                            }
                    
                            this.fileName = file.name;
                        }
                    }" @submit="submitting = true"
                    class="space-y-5 p-5 sm:p-6">
                    @csrf

                    <div>
                        <label for="jawaban_pembantu"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            ">
                            Jawaban atau Laporan PPID Pembantu

                            <span class="text-red-500">*</span>
                        </label>

                        <textarea id="jawaban_pembantu" name="jawaban_pembantu" rows="7" required
                            placeholder="Masukkan jawaban atau laporan hasil penelusuran informasi."
                            class="
                                w-full
                                resize-y
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                px-4
                                py-3
                                text-sm
                                leading-7
                                text-gray-800
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-purple-300
                                focus:ring-3
                                focus:ring-purple-500/10
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                @error('jawaban_pembantu')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                @enderror
                            ">{{ old('jawaban_pembantu', $permohonan->jawaban_pembantu) }}</textarea>

                        @error('jawaban_pembantu')
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-red-500
                                ">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_pembantu"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            ">
                            File Laporan
                        </label>

                        <input id="file_pembantu" type="file" name="file_pembantu"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" @change="handleFile($event)"
                            class="
                                h-11
                                w-full
                                overflow-hidden
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                text-sm
                                text-gray-500
                                file:mr-4
                                file:border-0
                                file:border-r
                                file:border-gray-200
                                file:bg-gray-50
                                file:px-4
                                file:py-3
                                file:text-sm
                                file:font-medium
                                file:text-gray-700
                                hover:file:bg-gray-100
                                focus:outline-none
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-400
                                dark:file:border-gray-700
                                dark:file:bg-gray-800
                                dark:file:text-gray-300
                                @error('file_pembantu')
                                    border-red-500
                                @enderror
                            ">

                        <p
                            class="
                                mt-1.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Format PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, atau PNG. Maksimal 5 MB.
                        </p>

                        <div x-cloak x-show="fileName !== ''"
                            class="
                                mt-3
                                flex
                                items-center
                                gap-3
                                rounded-lg
                                border
                                border-purple-100
                                bg-purple-50
                                px-4
                                py-3
                                dark:border-purple-500/20
                                dark:bg-purple-500/10
                            ">
                            <svg class="
                                    h-5
                                    w-5
                                    shrink-0
                                    text-purple-600
                                    dark:text-purple-400
                                "
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v15a2 2 0 002 2z" />
                            </svg>

                            <span x-text="fileName"
                                class="
                                    min-w-0
                                    truncate
                                    text-sm
                                    font-medium
                                    text-purple-800
                                    dark:text-purple-300
                                "></span>
                        </div>

                        <p x-cloak x-show="fileError !== ''" x-text="fileError" class="mt-2 text-xs text-red-500"></p>

                        @error('file_pembantu')
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-red-500
                                ">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        class="
                            flex
                            justify-end
                            border-t
                            border-gray-100
                            pt-5
                            dark:border-gray-800
                        ">
                        <button type="submit" :disabled="submitting || fileError !== ''"
                            class="
                                inline-flex
                                h-11
                                min-w-[210px]
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                bg-purple-600
                                px-5
                                text-sm
                                font-semibold
                                text-white
                                shadow-theme-xs
                                transition
                                hover:bg-purple-700
                                focus:outline-none
                                focus:ring-3
                                focus:ring-purple-500/20
                                disabled:cursor-not-allowed
                                disabled:opacity-60
                            ">
                            <svg x-show="!submitting" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>

                            <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z">
                                </path>
                            </svg>

                            <span
                                x-text="submitting
                                    ? 'Mengirim...'
                                    : 'Kirim ke Admin Utama'"></span>
                        </button>
                    </div>
                </form>
            </section>
        @endif

        {{-- ============================================================
            TINDAKAN ADMIN UTAMA: VALIDASI DAN REVISI
        ============================================================= --}}

        @if ($canValidate)
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                {{-- Validasi --}}
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-green-200
                        bg-white
                        shadow-theme-xs
                        dark:border-green-500/20
                        dark:bg-white/[0.03]
                    ">
                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            border-b
                            border-green-100
                            bg-green-50/60
                            px-5
                            py-4
                            dark:border-green-500/20
                            dark:bg-green-500/[0.06]
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
                                bg-green-100
                                text-green-600
                                dark:bg-green-500/15
                                dark:text-green-400
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
                                    text-base
                                    font-semibold
                                    text-green-900
                                    dark:text-green-300
                                ">
                                Validasi dan Kirim ke Warga
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-green-700
                                    dark:text-green-400
                                ">
                                Periksa dan sempurnakan jawaban sebelum dikirim.
                            </p>
                        </div>
                    </div>

                    <form
                        action="{{ route('admin.permohonan.validasi', $permohonan->id) }}"
                        method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                        @csrf

                        <div>
                            <label for="jawaban_final"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Jawaban Final untuk Warga

                                <span class="text-red-500">*</span>
                            </label>

                            <textarea id="jawaban_final" name="jawaban_final" rows="9" required
                                placeholder="Masukkan jawaban final yang akan diterima warga."
                                class="
                                    w-full
                                    resize-y
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    px-4
                                    py-3
                                    text-sm
                                    leading-7
                                    text-gray-800
                                    outline-none
                                    transition
                                    placeholder:text-gray-400
                                    focus:border-green-300
                                    focus:ring-3
                                    focus:ring-green-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    @error('jawaban_final')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">{{ old('jawaban_final', $permohonan->jawaban_pembantu) }}</textarea>

                            @error('jawaban_final')
                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-red-500
                                    ">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit" :disabled="submitting"
                            class="
                                inline-flex
                                h-11
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                bg-green-600
                                px-5
                                text-sm
                                font-semibold
                                text-white
                                shadow-theme-xs
                                transition
                                hover:bg-green-700
                                focus:outline-none
                                focus:ring-3
                                focus:ring-green-500/20
                                disabled:cursor-not-allowed
                                disabled:opacity-60
                            ">
                            <svg x-show="!submitting" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z">
                                </path>
                            </svg>

                            <span
                                x-text="submitting
                                    ? 'Mengirim...'
                                    : 'Validasi dan Kirim ke Warga'"></span>
                        </button>
                    </form>
                </section>

                {{-- Revisi --}}
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-yellow-200
                        bg-white
                        shadow-theme-xs
                        dark:border-yellow-500/20
                        dark:bg-white/[0.03]
                    ">
                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            border-b
                            border-yellow-100
                            bg-yellow-50/60
                            px-5
                            py-4
                            dark:border-yellow-500/20
                            dark:bg-yellow-500/[0.06]
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
                                bg-yellow-100
                                text-yellow-700
                                dark:bg-yellow-500/15
                                dark:text-yellow-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </div>

                        <div>
                            <h3
                                class="
                                    text-base
                                    font-semibold
                                    text-yellow-900
                                    dark:text-yellow-300
                                ">
                                Minta Revisi ke PPID Pembantu
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-yellow-700
                                    dark:text-yellow-400
                                ">
                                Jelaskan bagian laporan yang harus dilengkapi atau diperbaiki.
                            </p>
                        </div>
                    </div>

                    <form
                        action="{{ route('admin.permohonan.revisi', $permohonan->id) }}"
                        method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                        @csrf

                        <div>
                            <label for="catatan_revisi"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Catatan Revisi

                                <span class="text-red-500">*</span>
                            </label>

                            <textarea id="catatan_revisi" name="catatan_revisi" rows="9" required
                                placeholder="Jelaskan bagian laporan yang perlu diperbaiki."
                                class="
                                    w-full
                                    resize-y
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    px-4
                                    py-3
                                    text-sm
                                    leading-7
                                    text-gray-800
                                    outline-none
                                    transition
                                    placeholder:text-gray-400
                                    focus:border-yellow-300
                                    focus:ring-3
                                    focus:ring-yellow-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    @error('catatan_revisi')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">{{ old('catatan_revisi') }}</textarea>

                            @error('catatan_revisi')
                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-red-500
                                    ">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit" :disabled="submitting"
                            class="
                                inline-flex
                                h-11
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                bg-yellow-500
                                px-5
                                text-sm
                                font-semibold
                                text-white
                                shadow-theme-xs
                                transition
                                hover:bg-yellow-600
                                focus:outline-none
                                focus:ring-3
                                focus:ring-yellow-500/20
                                disabled:cursor-not-allowed
                                disabled:opacity-60
                            ">
                            <svg x-show="!submitting" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>

                            <svg x-cloak x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z">
                                </path>
                            </svg>

                            <span
                                x-text="submitting
                                    ? 'Mengirim...'
                                    : 'Kirim Permintaan Revisi'"></span>
                        </button>
                    </form>
                </section>
            </div>
        @endif
    </div>
@endsection
