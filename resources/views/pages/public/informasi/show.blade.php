@extends('layouts.public.app')

@section('title', ($dokumen->nama ?? 'Detail Informasi') . ' | PPID Kota Batu')

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | DATA UTAMA
        |--------------------------------------------------------------------------
        */

        $namaPpid =
            data_get($dokumen, 'ppidPembantu.nama') ?? (data_get($dokumen, 'ppid_pembantu.nama') ?? 'PPID Utama');

        $namaKategori = data_get($dokumen, 'kategori.nama') ?? 'Tanpa Kategori';

        $tanggalUpload = $dokumen->tanggal
            ? (
                is_numeric($dokumen->tanggal)
                    ? \Carbon\Carbon::createFromTimestamp((int) $dokumen->tanggal)->translatedFormat('d F Y')
                    : \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y')
            )
            : '-';

        $ringkasan = trim((string) ($dokumen->ringkasan ?? ''));

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        $filePath = trim((string) ($dokumen->file ?? ''));

        $filePathNormalized = str_replace('\\', '/', $filePath);

        $fileName = $filePath !== '' ? basename($filePathNormalized) : null;

        $fileExtension = $fileName ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : null;

        $fileExtensionLabel = $fileExtension ? strtoupper($fileExtension) : null;

        /*
        |--------------------------------------------------------------------------
        | JENIS PREVIEW
        |--------------------------------------------------------------------------
        */

        $isPdf = $fileExtension === 'pdf';

        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true);

        $isOffice = in_array($fileExtension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'], true);
    @endphp


    {{-- =========================================================
        HERO
    ========================================================== --}}

    <section
        class="
            relative
            -mt-8
            overflow-hidden
            bg-linear-to-r
            from-green-950
            via-green-900
            to-emerald-800
        ">

        {{-- Background ornament --}}

        <div class="absolute inset-0">

            <div
                class="
                    absolute
                    -left-20
                    top-0
                    h-72
                    w-72
                    rounded-full
                    bg-white/10
                    blur-3xl
                ">
            </div>

            <div
                class="
                    absolute
                    right-0
                    top-10
                    h-64
                    w-64
                    rounded-full
                    bg-emerald-300/10
                    blur-3xl
                ">
            </div>

        </div>


        <div
            class="
                relative
                mx-auto
                max-w-6xl
                px-6
                pb-24
                pt-14
                sm:px-8
                lg:px-10
            ">

            <div class="max-w-4xl">

                <span
                    class="
                        inline-flex
                        items-center
                        rounded-full
                        border
                        border-white/20
                        bg-white/10
                        px-3
                        py-1
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-widest
                        text-white
                        backdrop-blur-sm
                    ">
                    Detail Informasi Publik
                </span>


                <h1
                    class="
                        mt-4
                        text-2xl
                        font-bold
                        leading-tight
                        text-white
                        md:text-3xl
                    ">
                    {{ $dokumen->nama ?? 'Informasi Publik' }}
                </h1>


                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        gap-2
                    ">

                    {{-- Tanggal Upload --}}

                    <span
                        class="
                            rounded-full
                            bg-white/10
                            px-3
                            py-1
                            text-xs
                            text-green-50
                        ">
                        Tanggal Upload {{ $tanggalUpload }}
                    </span>


                    {{-- Kategori --}}

                    <span
                        class="
                            rounded-full
                            bg-white/10
                            px-3
                            py-1
                            text-xs
                            text-green-50
                        ">
                        {{ $namaKategori }}
                    </span>


                    {{-- PPID --}}

                    <span
                        class="
                            rounded-full
                            bg-white/10
                            px-3
                            py-1
                            text-xs
                            text-green-50
                        ">
                        {{ $namaPpid }}
                    </span>


                    {{-- Status --}}

                    <span
                        class="
                            inline-flex
                            items-center
                            gap-1.5
                            rounded-full
                            bg-emerald-400/20
                            px-3
                            py-1
                            text-xs
                            text-emerald-50
                        ">

                        <span
                            class="
                                h-1.5
                                w-1.5
                                rounded-full
                                bg-emerald-300
                            "></span>

                        Terverifikasi

                    </span>

                </div>

            </div>

        </div>


        {{-- Wave --}}

        <div
            class="
                absolute
                bottom-0
                left-0
                z-20
                w-full
                overflow-hidden
                leading-0
            ">

            <svg class="relative block h-16 w-full" viewBox="0 0 1200 120" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg">

                <path d="
                            M0,60
                            C200,120 350,0 600,60
                            C850,120 1000,0 1200,60
                            V120
                            H0
                            Z
                        " class="fill-white"></path>

            </svg>

        </div>

    </section>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}

    <section class="py-10">

        <div
            class="
                mx-auto
                max-w-6xl
                px-4
                sm:px-6
                lg:px-8
            ">

            {{-- =====================================================
                BACK
            ====================================================== --}}

            <div class="mb-6">

                <a href="{{ url()->previous() }}"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-lg
                        border
                        border-slate-200
                        bg-white
                        px-4
                        py-2.5
                        text-sm
                        font-medium
                        text-slate-700
                        transition
                        hover:border-green-700
                        hover:text-green-700
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

                    </svg>

                    Kembali

                </a>

            </div>


            <div
                class="
                    grid
                    grid-cols-1
                    gap-6
                    lg:grid-cols-3
                ">

                {{-- =====================================================
                    KONTEN KIRI
                ====================================================== --}}

                <div class="space-y-6 lg:col-span-2">

                    {{-- =================================================
                        RINGKASAN
                    ================================================== --}}

                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            shadow-sm
                        ">

                        <div
                            class="
                                border-b
                                border-slate-100
                                px-5
                                py-4
                                sm:px-6
                            ">

                            <h2
                                class="
                                    text-base
                                    font-semibold
                                    text-slate-800
                                ">
                                Ringkasan Informasi
                            </h2>


                            <p
                                class="
                                    mt-1
                                    text-xs
                                    text-slate-500
                                ">
                                Uraian singkat mengenai informasi publik.
                            </p>

                        </div>


                        <div
                            class="
                                px-5
                                py-5
                                sm:px-6
                            ">

                            @if ($ringkasan !== '')
                                <div
                                    class="
                                        whitespace-pre-line
                                        text-sm
                                        leading-7
                                        text-slate-600
                                    ">
                                    {{ $ringkasan }}
                                </div>
                            @else
                                <div
                                    class="
                                        rounded-xl
                                        border
                                        border-dashed
                                        border-slate-300
                                        bg-slate-50
                                        px-5
                                        py-8
                                        text-center
                                    ">

                                    <p class="text-sm text-slate-500">
                                        Ringkasan informasi belum tersedia.
                                    </p>

                                </div>
                            @endif

                        </div>

                    </section>


                    {{-- =================================================
                        FILE INFORMASI
                    ================================================== --}}

                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            shadow-sm
                        ">

                        {{-- HEADER --}}

                        <div
                            class="
                                border-b
                                border-slate-100
                                px-5
                                py-4
                                sm:px-6
                            ">

                            <h2
                                class="
                                    text-base
                                    font-semibold
                                    text-slate-800
                                ">
                                File Informasi
                            </h2>

                            <p
                                class="
                                    mt-1
                                    text-xs
                                    text-slate-500
                                ">
                                Dokumen yang tersedia pada informasi publik ini.
                            </p>

                        </div>


                        <div
                            class="
                                px-5
                                py-5
                                sm:px-6
                            ">

                            @if ($fileName)

                                {{-- =====================================
                                    INFORMASI FILE
                                ====================================== --}}

                                <div
                                    class="
                                        flex
                                        flex-col
                                        gap-4
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        p-4
                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                    ">

                                    <div
                                        class="
                                            flex
                                            min-w-0
                                            items-center
                                            gap-4
                                        ">

                                        {{-- Icon --}}

                                        <div
                                            class="
                                                flex
                                                h-12
                                                w-12
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-xl
                                                bg-green-100
                                                text-green-800
                                            ">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                                            M9 12h6
                                                            m-6 4h6
                                                            M8 4h7
                                                            l5 5
                                                            v11
                                                            a2 2 0 01-2 2
                                                            H8
                                                            a2 2 0 01-2-2
                                                            V6
                                                            a2 2 0 012-2z
                                                        " />

                                            </svg>

                                        </div>


                                        <div class="min-w-0">

                                            <p class="
                                                    truncate
                                                    text-sm
                                                    font-semibold
                                                    text-slate-800
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
                                                ">

                                                @if ($fileExtensionLabel)
                                                    <span
                                                        class="
                                                            rounded-md
                                                            bg-slate-200
                                                            px-2
                                                            py-1
                                                            text-[10px]
                                                            font-semibold
                                                            text-slate-600
                                                        ">
                                                        {{ $fileExtensionLabel }}
                                                    </span>
                                                @endif


                                                <span
                                                    class="
                                                        text-xs
                                                        text-slate-500
                                                    ">
                                                    Dokumen informasi publik
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- DOWNLOAD --}}

                                    <a href="{{ route('public.informasi.download', $dokumen->id) }}"
                                        class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                            rounded-lg
                                            bg-green-800
                                            px-4
                                            py-2.5
                                            text-sm
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-green-950
                                        ">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                                                        M12 3v12
                                                        m0 0l-4-4
                                                        m4 4l4-4
                                                        M5 19.5h14
                                                    " />

                                        </svg>

                                        Download

                                    </a>

                                </div>


                                {{-- =====================================
                                    PREVIEW
                                ====================================== --}}

                                <div class="mt-5">

                                    {{-- ===============================
                                        PDF
                                    ================================ --}}

                                    @if ($isPdf)
                                        <div
                                            class="
                                                overflow-hidden
                                                rounded-xl
                                                border
                                                border-slate-200
                                                bg-slate-100
                                            ">

                                            {{-- Toolbar --}}

                                            <div
                                                class="
                                                    flex
                                                    flex-col
                                                    gap-3
                                                    border-b
                                                    border-slate-200
                                                    bg-white
                                                    px-4
                                                    py-3
                                                    sm:flex-row
                                                    sm:items-center
                                                    sm:justify-between
                                                ">

                                                <div class="min-w-0">

                                                    <h3
                                                        class="
                                                            text-sm
                                                            font-semibold
                                                            text-slate-700
                                                        ">
                                                        Preview Dokumen
                                                    </h3>

                                                    <p
                                                        class="
                                                            mt-0.5
                                                            truncate
                                                            text-xs
                                                            text-slate-500
                                                        ">
                                                        {{ $fileName }}
                                                    </p>

                                                </div>


                                                <a href="{{ route('public.informasi.file', $dokumen->id) }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    class="
                                                        inline-flex
                                                        shrink-0
                                                        items-center
                                                        gap-1.5
                                                        text-xs
                                                        font-semibold
                                                        text-green-700
                                                        transition
                                                        hover:text-green-950
                                                    ">

                                                    Buka Layar Penuh

                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="
                                                                    M14 3h7
                                                                    m0 0v7
                                                                    m0-7L10 14
                                                                    M5 5h5
                                                                    M5 5v14
                                                                    h14v-5
                                                                " />

                                                    </svg>

                                                </a>

                                            </div>


                                            {{-- PDF IFRAME --}}

                                            <iframe src="{{ route('public.informasi.file', $dokumen->id) }}"
                                                class="
                                                    h-[650px]
                                                    w-full
                                                    border-0
                                                    sm:h-[750px]
                                                "
                                                title="Preview {{ $fileName }}"></iframe>

                                        </div>


                                        {{-- ===============================
                                        IMAGE
                                    ================================ --}}
                                    @elseif ($isImage)
                                        <div
                                            class="
                                                overflow-hidden
                                                rounded-xl
                                                border
                                                border-slate-200
                                                bg-slate-50
                                            ">

                                            {{-- Toolbar --}}

                                            <div
                                                class="
                                                    flex
                                                    items-center
                                                    justify-between
                                                    border-b
                                                    border-slate-200
                                                    bg-white
                                                    px-4
                                                    py-3
                                                ">

                                                <div class="min-w-0">

                                                    <h3
                                                        class="
                                                            text-sm
                                                            font-semibold
                                                            text-slate-700
                                                        ">
                                                        Preview Gambar
                                                    </h3>

                                                    <p
                                                        class="
                                                            mt-0.5
                                                            truncate
                                                            text-xs
                                                            text-slate-500
                                                        ">
                                                        {{ $fileName }}
                                                    </p>

                                                </div>


                                                <a href="{{ route('public.informasi.file', $dokumen->id) }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    class="
                                                        shrink-0
                                                        text-xs
                                                        font-semibold
                                                        text-green-700
                                                        transition
                                                        hover:text-green-950
                                                    ">
                                                    Buka Layar Penuh
                                                </a>

                                            </div>


                                            <div
                                                class="
                                                    flex
                                                    min-h-[350px]
                                                    items-center
                                                    justify-center
                                                    p-4
                                                ">

                                                <img src="{{ route('public.informasi.file', $dokumen->id) }}"
                                                    alt="{{ $fileName }}"
                                                    class="
                                                        max-h-[750px]
                                                        max-w-full
                                                        rounded-lg
                                                        object-contain
                                                    ">

                                            </div>

                                        </div>


                                        {{-- ===============================
                                        OFFICE
                                    ================================ --}}
                                    @elseif ($isOffice)
                                        <div
                                            class="
                                                rounded-xl
                                                border
                                                border-dashed
                                                border-slate-300
                                                bg-slate-50
                                                px-6
                                                py-10
                                                text-center
                                            ">

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
                                                    text-green-700
                                                    shadow-sm
                                                ">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="
                                                                M9 12h6
                                                                m-6 4h6
                                                                M8 4h7
                                                                l5 5
                                                                v11
                                                                a2 2 0 01-2 2
                                                                H8
                                                                a2 2 0 01-2-2
                                                                V6
                                                                a2 2 0 012-2z
                                                            " />

                                                </svg>

                                            </div>


                                            <p
                                                class="
                                                    mt-4
                                                    text-sm
                                                    font-semibold
                                                    text-slate-700
                                                ">
                                                Preview {{ $fileExtensionLabel }}
                                                tidak tersedia di browser.
                                            </p>


                                            <p
                                                class="
                                                    mx-auto
                                                    mt-1
                                                    max-w-md
                                                    text-xs
                                                    leading-5
                                                    text-slate-500
                                                ">
                                                Silakan download file untuk membuka dokumen
                                                menggunakan aplikasi yang mendukung format tersebut.
                                            </p>


                                            <a href="{{ route('public.informasi.download', $dokumen->id) }}"
                                                class="
                                                    mt-5
                                                    inline-flex
                                                    items-center
                                                    justify-center
                                                    gap-2
                                                    rounded-lg
                                                    bg-green-800
                                                    px-4
                                                    py-2.5
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                    transition
                                                    hover:bg-green-950
                                                ">
                                                Download File
                                            </a>

                                        </div>


                                        {{-- ===============================
                                        FORMAT LAIN
                                    ================================ --}}
                                    @else
                                        <div
                                            class="
                                                rounded-xl
                                                border
                                                border-dashed
                                                border-slate-300
                                                bg-slate-50
                                                px-6
                                                py-10
                                                text-center
                                            ">

                                            <div
                                                class="
                                                    mx-auto
                                                    flex
                                                    h-14
                                                    w-14
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    bg-slate-200
                                                    text-slate-500
                                                ">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="
                                                                M9 12h6
                                                                m-6 4h6
                                                                M8 4h7
                                                                l5 5
                                                                v11
                                                                a2 2 0 01-2 2
                                                                H8
                                                                a2 2 0 01-2-2
                                                                V6
                                                                a2 2 0 012-2z
                                                            " />

                                                </svg>

                                            </div>


                                            <p
                                                class="
                                                    mt-4
                                                    text-sm
                                                    font-semibold
                                                    text-slate-700
                                                ">
                                                Preview tidak tersedia
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-500
                                                ">
                                                Format
                                                {{ $fileExtensionLabel ?? 'file' }}
                                                belum dapat ditampilkan langsung.
                                            </p>


                                            <a href="{{ route('public.informasi.download', $dokumen->id) }}"
                                                class="
                                                    mt-5
                                                    inline-flex
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    bg-green-800
                                                    px-4
                                                    py-2.5
                                                    text-sm
                                                    font-semibold
                                                    text-white
                                                    transition
                                                    hover:bg-green-950
                                                ">
                                                Download File
                                            </a>

                                        </div>
                                    @endif

                                </div>
                            @else
                                {{-- FILE TIDAK ADA --}}

                                <div
                                    class="
                                        rounded-xl
                                        border
                                        border-dashed
                                        border-slate-300
                                        bg-slate-50
                                        px-5
                                        py-8
                                        text-center
                                    ">

                                    <p class="text-sm text-slate-500">
                                        File informasi belum tersedia.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </section>

                </div>


                {{-- =====================================================
                    SIDEBAR
                ====================================================== --}}

                <aside class="space-y-6">

                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            shadow-sm
                        ">

                        <div
                            class="
                                border-b
                                border-slate-100
                                px-5
                                py-4
                            ">

                            <h2
                                class="
                                    text-base
                                    font-semibold
                                    text-slate-800
                                ">
                                Detail Informasi
                            </h2>

                        </div>


                        <dl
                            class="
                                divide-y
                                divide-slate-100
                            ">

                            {{-- ID --}}

                            <div class="px-5 py-4">

                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-slate-400
                                    ">
                                    ID Informasi
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        text-sm
                                        font-semibold
                                        text-slate-700
                                    ">
                                    {{ $dokumen->id }}
                                </dd>

                            </div>


                            {{-- Tanggal Upload --}}

                            <div class="px-5 py-4">

                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-slate-400
                                    ">
                                    Tanggal Upload
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        text-sm
                                        font-semibold
                                        text-slate-700
                                    ">
                                    {{ $tanggalUpload }}
                                </dd>

                            </div>


                            {{-- Kategori --}}

                            <div class="px-5 py-4">

                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-slate-400
                                    ">
                                    Kategori
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        text-sm
                                        font-semibold
                                        leading-6
                                        text-slate-700
                                    ">
                                    {{ $namaKategori }}
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
                                        text-slate-400
                                    ">
                                    PPID Pembantu
                                </dt>

                                <dd
                                    class="
                                        mt-1.5
                                        text-sm
                                        font-semibold
                                        leading-6
                                        text-slate-700
                                    ">
                                    {{ $namaPpid }}
                                </dd>

                            </div>


                            {{-- Status --}}

                            <div class="px-5 py-4">

                                <dt
                                    class="
                                        text-xs
                                        font-medium
                                        uppercase
                                        tracking-wide
                                        text-slate-400
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
                                            bg-green-50
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            text-green-700
                                        ">

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-green-500
                                            "></span>

                                        Terverifikasi

                                    </span>

                                </dd>

                            </div>

                        </dl>

                    </section>

                </aside>

            </div>

        </div>

    </section>

@endsection
