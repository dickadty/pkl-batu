@extends('layouts.admin.app')

@section('title', 'Detail Informasi Publik')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

        /*
        |--------------------------------------------------------------------------
        | PPID PEMBANTU
        |--------------------------------------------------------------------------
        */

        $ppidName =
            data_get($dokumentasi, 'ppidPembantu.nama')
            ?? data_get($dokumentasi, 'ppid_pembantu.nama')
            ?? '-';

        /*
        |--------------------------------------------------------------------------
        | STATUS VERIFIKASI
        |--------------------------------------------------------------------------
        |
        | Database menggunakan kolom:
        | is_verifikasi = 0 -> Belum Diverifikasi
        | is_verifikasi = 1 -> Terverifikasi
        |
        */

        $isVerified = (int) data_get(
            $dokumentasi,
            'is_verifikasi',
            0
        ) === 1;

        $statusLabel = $isVerified
            ? 'Terverifikasi'
            : 'Belum Diverifikasi';

        $statusClass = $isVerified
            ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20'
            : 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/15 dark:text-yellow-400 dark:ring-yellow-500/20';

        /*
        |--------------------------------------------------------------------------
        | SIFAT INFORMASI
        |--------------------------------------------------------------------------
        */

        $sifatKey = strtolower(
            trim((string) ($dokumentasi->sifat ?? ''))
        );

        $sifatClass = match ($sifatKey) {
            'berkala'
                => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

            'serta merta'
                => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

            'setiap saat'
                => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

            'dikecualikan'
                => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

            default
                => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        };

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        $filePath = trim(
            (string) ($dokumentasi->file ?? '')
        );

        $filePathNormalized = str_replace(
            '\\',
            '/',
            $filePath
        );

        $fileName = $filePath !== ''
            ? basename($filePathNormalized)
            : null;

        $fileExtension = $fileName
            ? strtolower(
                pathinfo(
                    $fileName,
                    PATHINFO_EXTENSION
                )
            )
            : null;

        $fileExtensionLabel = $fileExtension
            ? strtoupper($fileExtension)
            : null;

        /*
        |--------------------------------------------------------------------------
        | JENIS PREVIEW
        |--------------------------------------------------------------------------
        */

        $isPdf = $fileExtension === 'pdf';

        $isImage = in_array(
            $fileExtension,
            [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'bmp',
            ],
            true
        );

        $isOffice = in_array(
            $fileExtension,
            [
                'doc',
                'docx',
                'xls',
                'xlsx',
            ],
            true
        );

        /*
        |--------------------------------------------------------------------------
        | FORMAT TANGGAL
        |--------------------------------------------------------------------------
        */

        $formatDateTime = static function ($value): string {
            if (empty($value)) {
                return '-';
            }

            try {
                return \Illuminate\Support\Carbon::parse(
                    $value
                )->translatedFormat(
                    'd F Y'
                );
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        /*
        |--------------------------------------------------------------------------
        | TANGGAL INFORMASI
        |--------------------------------------------------------------------------
        |
        | Karena model Dokumentasi menggunakan:
        | public $timestamps = false;
        |
        | Maka tanggal mengambil kolom "tanggal" dari database.
        |
        */

        $tanggalInformasi = $formatDateTime(
            data_get(
                $dokumentasi,
                'tanggal'
            )
        );
    @endphp


    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <x-admin.page-header
            title="Detail Informasi Publik"
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
            ]"
        >

            <x-slot:actions>

                {{-- KEMBALI --}}

                <a
                    href="{{ route('admin.informasi-publik.index') }}"
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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    <span>
                        Kembali
                    </span>

                </a>


                {{-- EDIT --}}

                <a
                    href="{{ route('admin.informasi-publik.edit', $dokumentasi->id) }}"
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
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                        />
                    </svg>

                    <span>
                        Edit Informasi
                    </span>

                </a>

            </x-slot:actions>

        </x-admin.page-header>


        <x-ui.flash-messages />


        {{-- ========================================================= --}}
        {{-- INFORMASI UTAMA --}}
        {{-- ========================================================= --}}

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
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-5
                    lg:flex-row
                    lg:items-start
                    lg:justify-between
                "
            >

                <div
                    class="
                        flex
                        min-w-0
                        items-start
                        gap-4
                    "
                >

                    {{-- ICON --}}

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
                        "
                    >

                        <svg
                            class="h-7 w-7"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>


                    <div class="min-w-0">

                        {{-- BADGE --}}

                        <div
                            class="
                                flex
                                flex-wrap
                                items-center
                                gap-2
                            "
                        >

                            {{-- SIFAT --}}

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    {{ $sifatClass }}
                                "
                            >
                                {{
                                    $dokumentasi->sifat
                                        ? \Illuminate\Support\Str::title(
                                            $dokumentasi->sifat
                                        )
                                        : 'Sifat belum ditentukan'
                                }}
                            </span>


                            {{-- STATUS --}}

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
                                "
                            >

                                <span
                                    class="
                                        h-1.5
                                        w-1.5
                                        rounded-full
                                        {{
                                            $isVerified
                                                ? 'bg-green-500'
                                                : 'bg-yellow-500'
                                        }}
                                    "
                                ></span>

                                {{ $statusLabel }}

                            </span>

                        </div>


                        {{-- NAMA INFORMASI --}}

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
                            "
                        >
                            {{ $dokumentasi->nama ?? '-' }}
                        </h2>


                        {{-- META --}}

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
                            "
                        >

                            <span>
                                Tahun
                                {{ $dokumentasi->tahun ?? '-' }}
                            </span>

                            <span>
                                {{ $ppidName }}
                            </span>

                            <span>
                                ID Informasi:
                                {{ $dokumentasi->id }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TOMBOL LIHAT FILE --}}
                {{-- ================================================= --}}

                @if ($fileName)

                    <a
                        href="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                        target="_blank"
                        rel="noopener noreferrer"
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
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />

                        </svg>

                        <span>
                            Lihat File
                        </span>

                    </a>

                @endif

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- GRID CONTENT --}}
        {{-- ========================================================= --}}

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-3
            "
        >

            {{-- ===================================================== --}}
            {{-- BAGIAN KIRI --}}
            {{-- ===================================================== --}}

            <div
                class="
                    space-y-6
                    xl:col-span-2
                "
            >

                {{-- ================================================= --}}
                {{-- RINGKASAN --}}
                {{-- ================================================= --}}

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
                    "
                >

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                            sm:px-6
                        "
                    >

                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            Ringkasan Informasi
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Uraian singkat mengenai informasi yang tersedia.
                        </p>

                    </div>


                    <div
                        class="
                            px-5
                            py-5
                            sm:px-6
                        "
                    >

                        @if (!empty($dokumentasi->ringkasan))

                            <div
                                class="
                                    whitespace-pre-line
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:text-gray-300
                                "
                            >
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
                                "
                            >

                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        dark:text-gray-400
                                    "
                                >
                                    Ringkasan informasi belum tersedia.
                                </p>

                            </div>

                        @endif

                    </div>

                </section>


                {{-- ================================================= --}}
                {{-- FILE INFORMASI --}}
                {{-- ================================================= --}}

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
                    "
                >

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                            sm:px-6
                        "
                    >

                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            File Informasi
                        </h3>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Dokumen yang tersimpan pada informasi publik ini.
                        </p>

                    </div>


                    <div
                        class="
                            px-5
                            py-5
                            sm:px-6
                            sm:py-6
                        "
                    >

                        @if ($fileName)

                            {{-- INFORMASI FILE --}}

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
                                "
                            >

                                <div
                                    class="
                                        flex
                                        min-w-0
                                        items-center
                                        gap-4
                                    "
                                >

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
                                            text-blue-600
                                            shadow-theme-xs
                                            dark:bg-gray-800
                                            dark:text-blue-400
                                        "
                                    >

                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"
                                            />
                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        <p
                                            class="
                                                truncate
                                                text-sm
                                                font-semibold
                                                text-gray-800
                                                dark:text-white/90
                                            "
                                            title="{{ $fileName }}"
                                        >
                                            {{ $fileName }}
                                        </p>


                                        <div
                                            class="
                                                mt-1
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-2
                                            "
                                        >

                                            @if ($fileExtensionLabel)

                                                <span
                                                    class="
                                                        rounded-md
                                                        bg-gray-200
                                                        px-2
                                                        py-1
                                                        text-xs
                                                        font-semibold
                                                        text-gray-600
                                                        dark:bg-gray-700
                                                        dark:text-gray-300
                                                    "
                                                >
                                                    {{ $fileExtensionLabel }}
                                                </span>

                                            @endif


                                            <span
                                                class="
                                                    text-xs
                                                    text-gray-500
                                                    dark:text-gray-400
                                                "
                                            >
                                                File informasi publik
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <a
                                    href="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        h-10
                                        shrink-0
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
                                    "
                                >

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />

                                    </svg>

                                    <span>
                                        Lihat File
                                    </span>

                                </a>

                            </div>


                            {{-- PREVIEW --}}

                            <div class="mt-6">

                                <div
                                    class="
                                        mb-4
                                        flex
                                        flex-col
                                        gap-3
                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                    "
                                >

                                    <div>

                                        <h4
                                            class="
                                                text-sm
                                                font-semibold
                                                text-gray-800
                                                dark:text-white/90
                                            "
                                        >
                                            Preview File
                                        </h4>

                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-gray-500
                                                dark:text-gray-400
                                            "
                                        >
                                            Pratinjau dokumen informasi publik.
                                        </p>

                                    </div>


                                    <a
                                        href="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            text-sm
                                            font-semibold
                                            text-blue-600
                                            transition
                                            hover:text-blue-700
                                            dark:text-blue-400
                                        "
                                    >
                                        Buka layar penuh

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 3h7m0 0v7m0-7L10 14M5 5h5M5 5v14h14v-5"
                                            />
                                        </svg>

                                    </a>

                                </div>


                                {{-- PDF --}}

                                @if ($isPdf)

                                    <div
                                        class="
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-gray-200
                                            bg-gray-100
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                        "
                                    >

                                        <iframe
                                            src="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                                            class="
                                                h-[750px]
                                                w-full
                                                border-0
                                            "
                                            title="Preview {{ $fileName }}"
                                        ></iframe>

                                    </div>


                                {{-- GAMBAR --}}

                                @elseif ($isImage)

                                    <div
                                        class="
                                            flex
                                            min-h-[300px]
                                            items-center
                                            justify-center
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-gray-200
                                            bg-gray-50
                                            p-4
                                            dark:border-gray-700
                                            dark:bg-gray-900/50
                                        "
                                    >

                                        <img
                                            src="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                                            alt="{{ $fileName }}"
                                            class="
                                                max-h-[750px]
                                                max-w-full
                                                rounded-lg
                                                object-contain
                                            "
                                        >

                                    </div>


                                {{-- OFFICE --}}

                                @elseif ($isOffice)

                                    <div
                                        class="
                                            rounded-xl
                                            border
                                            border-dashed
                                            border-gray-300
                                            bg-gray-50
                                            px-6
                                            py-10
                                            text-center
                                            dark:border-gray-700
                                            dark:bg-gray-900/50
                                        "
                                    >

                                        <div
                                            class="
                                                mx-auto
                                                flex
                                                h-14
                                                w-14
                                                items-center
                                                justify-center
                                                rounded-xl
                                                bg-white
                                                text-blue-600
                                                shadow-theme-xs
                                                dark:bg-gray-800
                                                dark:text-blue-400
                                            "
                                        >

                                            <svg
                                                class="h-7 w-7"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"
                                                />
                                            </svg>

                                        </div>


                                        <p
                                            class="
                                                mt-4
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                                dark:text-gray-300
                                            "
                                        >
                                            Preview
                                            {{ $fileExtensionLabel }}
                                            tidak didukung langsung oleh browser.
                                        </p>

                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                leading-5
                                                text-gray-500
                                                dark:text-gray-400
                                            "
                                        >
                                            Preview langsung saat ini tersedia
                                            untuk PDF dan gambar.
                                        </p>


                                        <a
                                            href="{{ route('admin.informasi-publik.file.show', $dokumentasi->id) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="
                                                mt-5
                                                inline-flex
                                                h-10
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-lg
                                                bg-blue-600
                                                px-4
                                                text-sm
                                                font-semibold
                                                text-white
                                                transition
                                                hover:bg-blue-700
                                            "
                                        >
                                            Buka File
                                        </a>

                                    </div>


                                {{-- FORMAT LAIN --}}

                                @else

                                    <div
                                        class="
                                            rounded-xl
                                            border
                                            border-dashed
                                            border-gray-300
                                            bg-gray-50
                                            px-6
                                            py-10
                                            text-center
                                            dark:border-gray-700
                                            dark:bg-gray-900/50
                                        "
                                    >

                                        <p
                                            class="
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                                dark:text-gray-300
                                            "
                                        >
                                            Preview belum tersedia untuk format
                                            {{ $fileExtensionLabel ?? '-' }}.
                                        </p>

                                    </div>

                                @endif

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
                                "
                            >

                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        dark:text-gray-400
                                    "
                                >
                                    File informasi belum tersedia.
                                </p>

                            </div>

                        @endif

                    </div>

                </section>

            </div>


            {{-- ===================================================== --}}
            {{-- SIDEBAR --}}
            {{-- ===================================================== --}}

            <aside class="space-y-6">

                {{-- ================================================= --}}
                {{-- DETAIL INFORMASI --}}
                {{-- ================================================= --}}

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
                    "
                >

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                        "
                    >

                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            Detail Informasi
                        </h3>

                    </div>


                    <dl
                        class="
                            divide-y
                            divide-gray-100
                            dark:divide-gray-800
                        "
                    >

                        {{-- ID --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
                                ID Informasi
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                "
                            >
                                {{ $dokumentasi->id }}
                            </dd>

                        </div>


                        {{-- TAHUN --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
                                Tahun
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                "
                            >
                                {{ $dokumentasi->tahun ?? '-' }}
                            </dd>

                        </div>


                        {{-- SIFAT --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
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
                                    "
                                >
                                    {{
                                        $dokumentasi->sifat
                                            ? \Illuminate\Support\Str::title(
                                                $dokumentasi->sifat
                                            )
                                            : '-'
                                    }}
                                </span>

                            </dd>

                        </div>


                        {{-- PPID --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
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
                                "
                            >
                                {{ $ppidName }}
                            </dd>

                        </div>


                        {{-- STATUS --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
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
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            {{
                                                $isVerified
                                                    ? 'bg-green-500'
                                                    : 'bg-yellow-500'
                                            }}
                                        "
                                    ></span>

                                    {{ $statusLabel }}

                                </span>

                            </dd>

                        </div>


                        {{-- TANGGAL --}}

                        <div class="px-5 py-4">

                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                "
                            >
                                Tanggal Informasi
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                "
                            >
                                {{ $tanggalInformasi }}
                            </dd>

                        </div>

                    </dl>

                </section>


                {{-- ================================================= --}}
                {{-- VERIFIKASI --}}
                {{-- ================================================= --}}

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
                        "
                    >

                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-emerald-800
                                dark:text-emerald-300
                            "
                        >
                            Verifikasi Informasi
                        </h3>


                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-emerald-700
                                dark:text-emerald-400
                            "
                        >
                            Dokumen ini belum diverifikasi oleh Admin Utama.
                        </p>


                        <form
                            action="{{ route('admin.informasi-publik.verifikasi', $dokumentasi->id) }}"
                            method="POST"
                            class="mt-4"
                            onsubmit="
                                return confirm(
                                    'Apakah Anda yakin ingin memverifikasi informasi ini?'
                                )
                            "
                        >

                            @csrf
                            @method('PATCH')


                            <button
                                type="submit"
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
                                "
                            >
                                Verifikasi Sekarang
                            </button>

                        </form>

                    </section>

                @endif


                {{-- ================================================= --}}
                {{-- SUDAH DIVERIFIKASI --}}
                {{-- ================================================= --}}

                @if ($isAdminUtama && $isVerified)

                    <section
                        class="
                            rounded-2xl
                            border
                            border-green-200
                            bg-green-50/70
                            p-5
                            dark:border-green-500/20
                            dark:bg-green-500/10
                        "
                    >

                        <div
                            class="
                                flex
                                items-start
                                gap-3
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-10
                                    w-10
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-green-100
                                    text-green-600
                                    dark:bg-green-500/20
                                    dark:text-green-400
                                "
                            >
                                <i class="ri-checkbox-circle-line text-xl"></i>
                            </div>


                            <div>

                                <h3
                                    class="
                                        text-sm
                                        font-semibold
                                        text-green-800
                                        dark:text-green-300
                                    "
                                >
                                    Informasi Terverifikasi
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        leading-6
                                        text-green-700
                                        dark:text-green-400
                                    "
                                >
                                    Dokumen informasi ini sudah diverifikasi oleh Admin Utama.
                                </p>

                            </div>

                        </div>

                    </section>

                @endif


                {{-- ================================================= --}}
                {{-- HAPUS --}}
                {{-- ================================================= --}}

                <section
                    class="
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50/60
                        p-5
                        dark:border-red-500/20
                        dark:bg-red-500/10
                    "
                >

                    <h3
                        class="
                            text-sm
                            font-semibold
                            text-red-800
                            dark:text-red-300
                        "
                    >
                        Hapus Informasi
                    </h3>


                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        "
                    >
                        Data dan file yang dihapus tidak dapat dikembalikan.
                    </p>


                    <form
                        action="{{ route('admin.informasi-publik.destroy', $dokumentasi->id) }}"
                        method="POST"
                        class="mt-4"
                        onsubmit="
                            return confirm(
                                'Apakah Anda yakin ingin menghapus informasi publik ini?'
                            )
                        "
                    >

                        @csrf
                        @method('DELETE')


                        <button
                            type="submit"
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
                                dark:border-red-500/30
                                dark:bg-gray-900
                                dark:text-red-400
                            "
                        >
                            Hapus Informasi
                        </button>

                    </form>

                </section>

            </aside>

        </div>

    </div>

@endsection