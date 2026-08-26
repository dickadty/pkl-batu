@extends('layouts.public.app')

@section('title', 'Detail Keberatan | PPID Kota Batu')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Status Keberatan
        |--------------------------------------------------------------------------
        */

        $status = trim((string) ($keberatan->status ?: 'Diajukan'));

        $isDiajukan = $status === 'Diajukan';
        $isDiproses = $status === 'Diproses';
        $isSelesai = $status === 'Selesai';

        /*
        |--------------------------------------------------------------------------
        | Hasil Keberatan
        |--------------------------------------------------------------------------
        */

        $hasil = trim((string) ($keberatan->hasil ?? ''));

        $hasHasil = $hasil !== '';

        /*
        |--------------------------------------------------------------------------
        | Tampilan Status
        |--------------------------------------------------------------------------
        */

        $statusClass = match ($status) {
            'Diajukan' => 'border-blue-200 bg-blue-50 text-blue-700',

            'Diproses' => 'border-amber-200 bg-amber-50 text-amber-700',

            'Selesai' => 'border-green-200 bg-green-50 text-green-700',

            default => 'border-slate-200 bg-slate-100 text-slate-700',
        };

        $statusIcon = match ($status) {
            'Diajukan' => 'ri-file-add-line',
            'Diproses' => 'ri-loader-4-line',
            'Selesai' => 'ri-checkbox-circle-line',
            default => 'ri-information-line',
        };

        /*
        |--------------------------------------------------------------------------
        | Tampilan Hasil
        |--------------------------------------------------------------------------
        */

        $hasilClass = match ($hasil) {
            'Diterima' => 'border-green-200 bg-green-50 text-green-700',

            'Diterima Sebagian' => 'border-amber-200 bg-amber-50 text-amber-700',

            'Ditolak' => 'border-red-200 bg-red-50 text-red-700',

            default => 'border-slate-200 bg-slate-50 text-slate-700',
        };

        $hasilIcon = match ($hasil) {
            'Diterima' => 'ri-checkbox-circle-line',

            'Diterima Sebagian' => 'ri-error-warning-line',

            'Ditolak' => 'ri-close-circle-line',

            default => 'ri-information-line',
        };

        /*
        |--------------------------------------------------------------------------
        | Tanggal
        |--------------------------------------------------------------------------
        */

        $tanggalPengajuan = $keberatan->tanggal_pengajuan?->locale('id')->translatedFormat('d F Y') ?? '-';

        /*
        |--------------------------------------------------------------------------
        | File Tanggapan
        |--------------------------------------------------------------------------
        */

        $fileTanggapan = filled($keberatan->file_tanggapan) ? ltrim((string) $keberatan->file_tanggapan, '/') : null;

        $fileTanggapanUrl = $fileTanggapan
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($fileTanggapan)
            : null;

        $fileTanggapanName = $fileTanggapan ? basename($fileTanggapan) : null;

        $fileTanggapanExtension = $fileTanggapan ? strtoupper(pathinfo($fileTanggapan, PATHINFO_EXTENSION)) : null;

        /*
        |--------------------------------------------------------------------------
        | Nomor Permohonan
        |--------------------------------------------------------------------------
        */

        $nomorPermohonan = $keberatan->permohonan?->no_pemohon ?? '-';
    @endphp

    {{-- ================================================================
        HEADER
    ================================================================= --}}

    <section class="
            border-b
            border-slate-200
            bg-white
        ">
        <div
            class="
                mx-auto
                max-w-5xl
                px-4
                py-10
                sm:px-6
                lg:px-8
            ">
            <div
                class="
                    flex
                    flex-col
                    gap-5
                    sm:flex-row
                    sm:items-start
                    sm:justify-between
                ">
                <div>
                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-red-50
                            px-3
                            py-1
                            text-xs
                            font-semibold
                            text-red-700
                        ">
                        <i class="ri-file-warning-line"></i>

                        Keberatan Informasi Publik
                    </span>

                    <h1
                        class="
                            mt-4
                            break-words
                            text-3xl
                            font-bold
                            text-slate-900
                        ">
                        {{ $keberatan->no_keberatan }}
                    </h1>

                    <p
                        class="
                            mt-2
                            max-w-2xl
                            text-sm
                            leading-6
                            text-slate-600
                        ">
                        Pantau proses dan tanggapan resmi atas
                        keberatan yang telah Anda ajukan.
                    </p>
                </div>

                <span
                    class="
                        inline-flex
                        w-fit
                        items-center
                        gap-2
                        rounded-full
                        border
                        px-4
                        py-2
                        text-sm
                        font-semibold
                        {{ $statusClass }}
                    ">
                    <i class="{{ $statusIcon }}"></i>

                    {{ $status }}
                </span>
            </div>
        </div>
    </section>

    {{-- ================================================================
        CONTENT
    ================================================================= --}}

    <section
        class="
            mx-auto
            max-w-5xl
            space-y-6
            px-4
            py-10
            sm:px-6
            lg:px-8
        ">
        <x-ui.flash-messages />

        {{-- ============================================================
            INFORMASI STATUS
        ============================================================= --}}

        @if ($isDiajukan)
            <div
                class="
                    rounded-2xl
                    border
                    border-blue-200
                    bg-blue-50
                    p-5
                ">
                <div
                    class="
                        flex
                        items-start
                        gap-4
                    ">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-blue-100
                            text-blue-700
                        ">
                        <i
                            class="
                                ri-file-add-line
                                text-xl
                            "></i>
                    </div>

                    <div>
                        <h2
                            class="
                                font-bold
                                text-blue-900
                            ">
                            Keberatan Telah Diajukan
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-blue-800
                            ">
                            Keberatan Anda telah diterima oleh sistem
                            dan menunggu proses dari Admin Utama PPID.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($isDiproses)
            <div
                class="
                    rounded-2xl
                    border
                    border-amber-200
                    bg-amber-50
                    p-5
                ">
                <div
                    class="
                        flex
                        items-start
                        gap-4
                    ">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-amber-100
                            text-amber-700
                        ">
                        <i
                            class="
                                ri-loader-4-line
                                text-xl
                            "></i>
                    </div>

                    <div>
                        <h2
                            class="
                                font-bold
                                text-amber-900
                            ">
                            Keberatan Sedang Diproses
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-amber-800
                            ">
                            Admin Utama PPID sedang memeriksa
                            keberatan Anda. Hasil dan tanggapan resmi
                            akan ditampilkan pada halaman ini setelah
                            proses selesai.
                        </p>
                    </div>
                </div>
            </div>
        @elseif ($isSelesai)
            <div
                class="
                    rounded-2xl
                    border
                    border-green-200
                    bg-green-50
                    p-5
                ">
                <div
                    class="
                        flex
                        items-start
                        gap-4
                    ">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-green-100
                            text-green-700
                        ">
                        <i
                            class="
                                ri-checkbox-circle-line
                                text-xl
                            "></i>
                    </div>

                    <div>
                        <h2
                            class="
                                font-bold
                                text-green-900
                            ">
                            Keberatan Telah Selesai
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-green-800
                            ">
                            PPID telah memberikan keputusan dan
                            tanggapan resmi atas keberatan Anda.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================================================
            GRID
        ============================================================= --}}

        <div
            class="
                grid
                grid-cols-1
                gap-6
                lg:grid-cols-3
            ">
            {{-- ========================================================
                MAIN CONTENT
            ========================================================= --}}

            <div class="
                    space-y-6
                    lg:col-span-2
                ">
                {{-- ====================================================
                    DETAIL KEBERATAN
                ===================================================== --}}

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
                            border-slate-200
                            px-6
                            py-5
                        ">
                        <h2
                            class="
                                text-lg
                                font-bold
                                text-slate-900
                            ">
                            Detail Keberatan
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                text-slate-500
                            ">
                            Informasi pengajuan keberatan Anda.
                        </p>
                    </div>

                    <dl
                        class="
                            divide-y
                            divide-slate-200
                        ">
                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-6
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-600
                                ">
                                Nomor Keberatan
                            </dt>

                            <dd
                                class="
                                    break-words
                                    text-sm
                                    font-medium
                                    text-slate-900
                                ">
                                {{ $keberatan->no_keberatan }}
                            </dd>
                        </div>

                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-6
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-600
                                ">
                                Nomor Permohonan
                            </dt>

                            <dd
                                class="
                                    break-words
                                    text-sm
                                    text-slate-800
                                ">
                                {{ $nomorPermohonan }}
                            </dd>
                        </div>

                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-6
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-600
                                ">
                                Tanggal Pengajuan
                            </dt>

                            <dd
                                class="
                                    text-sm
                                    text-slate-800
                                ">
                                {{ $tanggalPengajuan }}
                            </dd>
                        </div>

                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-2
                                px-6
                                py-4
                                sm:grid-cols-[190px_minmax(0,1fr)]
                            ">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-600
                                ">
                                Status
                            </dt>

                            <dd>
                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        border
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        {{ $statusClass }}
                                    ">
                                    <i class="{{ $statusIcon }}"></i>

                                    {{ $status }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </section>

                {{-- ====================================================
                    ALASAN KEBERATAN
                ===================================================== --}}

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
                            border-slate-200
                            px-6
                            py-5
                        ">
                        <h2
                            class="
                                text-lg
                                font-bold
                                text-slate-900
                            ">
                            Alasan Keberatan
                        </h2>
                    </div>

                    <div class="p-6">
                        <div
                            class="
                                whitespace-pre-line
                                rounded-xl
                                bg-slate-50
                                p-5
                                text-sm
                                leading-7
                                text-slate-700
                            ">
                            {{ $keberatan->alasan ?: 'Alasan keberatan tidak tersedia.' }}
                        </div>
                    </div>
                </section>

                {{-- ====================================================
                    HASIL DAN TANGGAPAN FINAL
                ===================================================== --}}

                @if ($isSelesai)
                    <section
                        class="
                            overflow-hidden
                            rounded-2xl
                            border
                            border-green-200
                            bg-white
                            shadow-sm
                        ">
                        <div
                            class="
                                border-b
                                border-green-200
                                bg-green-50
                                px-6
                                py-5
                            ">
                            <div
                                class="
                                    flex
                                    items-start
                                    gap-3
                                ">
                                <div
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-green-100
                                        text-green-700
                                    ">
                                    <i
                                        class="
                                            ri-file-check-line
                                            text-xl
                                        "></i>
                                </div>

                                <div>
                                    <h2
                                        class="
                                            text-lg
                                            font-bold
                                            text-green-900
                                        ">
                                        Keputusan Keberatan
                                    </h2>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            text-green-700
                                        ">
                                        Hasil dan tanggapan resmi dari
                                        PPID.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6 p-6">
                            {{-- HASIL --}}

                            <div>
                                <p
                                    class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                    Hasil Keberatan
                                </p>

                                @if ($hasHasil)
                                    <span
                                        class="
                                            mt-2
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            border
                                            px-3
                                            py-1.5
                                            text-sm
                                            font-semibold
                                            {{ $hasilClass }}
                                        ">
                                        <i class="{{ $hasilIcon }}"></i>

                                        {{ $hasil }}
                                    </span>
                                @else
                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-slate-500
                                        ">
                                        Hasil keberatan belum tersedia.
                                    </p>
                                @endif
                            </div>

                            {{-- JENIS TINDAK LANJUT --}}

                            <div>
                                <p
                                    class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                    Jenis Tindak Lanjut
                                </p>

                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        font-semibold
                                        text-slate-800
                                    ">
                                    {{ $keberatan->jenis_tindak_lanjut ?: '-' }}
                                </p>
                            </div>

                            {{-- TANGGAPAN --}}

                            <div>
                                <p
                                    class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                    Tanggapan PPID
                                </p>

                                <div
                                    class="
                                        mt-3
                                        whitespace-pre-line
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        p-5
                                        text-sm
                                        leading-7
                                        text-slate-800
                                    ">
                                    {{ $keberatan->tanggapan ?: 'Tanggapan belum tersedia.' }}
                                </div>
                            </div>

                            {{-- FILE TANGGAPAN --}}

                            @if ($fileTanggapanUrl)
                                <div>
                                    <p
                                        class="
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-slate-400
                                        ">
                                        Dokumen Tanggapan
                                    </p>

                                    <div
                                        class="
                                            mt-3
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                            p-4
                                        ">
                                        <div
                                            class="
                                                flex
                                                flex-col
                                                gap-4
                                                sm:flex-row
                                                sm:items-center
                                                sm:justify-between
                                            ">
                                            <div
                                                class="
                                                    flex
                                                    min-w-0
                                                    items-center
                                                    gap-3
                                                ">
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
                                                        text-green-700
                                                        shadow-sm
                                                    ">
                                                    <i
                                                        class="
                                                            ri-file-text-line
                                                            text-2xl
                                                        "></i>
                                                </div>

                                                <div class="min-w-0">
                                                    <p
                                                        class="
                                                            text-sm
                                                            font-semibold
                                                            text-slate-900
                                                        ">
                                                        Dokumen Tanggapan
                                                    </p>

                                                    <p class="
                                                            mt-1
                                                            truncate
                                                            text-xs
                                                            text-slate-500
                                                        "
                                                        title="{{ $fileTanggapanName }}">
                                                        {{ $fileTanggapanName }}
                                                    </p>

                                                    @if ($fileTanggapanExtension)
                                                        <span
                                                            class="
                                                                mt-2
                                                                inline-flex
                                                                rounded-md
                                                                bg-slate-200
                                                                px-2
                                                                py-1
                                                                text-[10px]
                                                                font-bold
                                                                text-slate-600
                                                            ">
                                                            {{ $fileTanggapanExtension }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div
                                                class="
                                                    flex
                                                    shrink-0
                                                    flex-col
                                                    gap-2
                                                    sm:flex-row
                                                ">
                                                <a href="{{ $fileTanggapanUrl }}" target="_blank" rel="noopener noreferrer"
                                                    class="
                                                        inline-flex
                                                        h-10
                                                        items-center
                                                        justify-center
                                                        gap-2
                                                        rounded-lg
                                                        bg-green-700
                                                        px-4
                                                        text-sm
                                                        font-semibold
                                                        text-white
                                                        transition
                                                        hover:bg-green-800
                                                    ">
                                                    <i
                                                        class="
                                                            ri-eye-line
                                                            text-lg
                                                        "></i>

                                                    Lihat
                                                </a>

                                                <a href="{{ $fileTanggapanUrl }}" download
                                                    class="
                                                        inline-flex
                                                        h-10
                                                        items-center
                                                        justify-center
                                                        gap-2
                                                        rounded-lg
                                                        border
                                                        border-slate-300
                                                        bg-white
                                                        px-4
                                                        text-sm
                                                        font-semibold
                                                        text-slate-700
                                                        transition
                                                        hover:bg-slate-100
                                                    ">
                                                    <i
                                                        class="
                                                            ri-download-line
                                                            text-lg
                                                        "></i>

                                                    Unduh
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>
                @else
                    {{-- TANGGAPAN BELUM FINAL --}}

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
                                border-slate-200
                                px-6
                                py-5
                            ">
                            <h2
                                class="
                                    text-lg
                                    font-bold
                                    text-slate-900
                                ">
                                Tanggapan PPID
                            </h2>
                        </div>

                        <div class="p-6">
                            <div
                                class="
                                    flex
                                    items-start
                                    gap-3
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    p-5
                                ">
                                <i
                                    class="
                                        ri-time-line
                                        mt-0.5
                                        text-xl
                                        text-slate-400
                                    "></i>

                                <div>
                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-slate-800
                                        ">
                                        Belum ada tanggapan final
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            leading-6
                                            text-slate-500
                                        ">
                                        Tanggapan, hasil keputusan,
                                        dan dokumen akan ditampilkan
                                        setelah proses keberatan
                                        selesai.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            {{-- ========================================================
                SIDEBAR
            ========================================================= --}}

            <aside class="space-y-6">
                {{-- STATUS --}}

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
                            border-slate-200
                            px-6
                            py-5
                        ">
                        <h2
                            class="
                                text-lg
                                font-bold
                                text-slate-900
                            ">
                            Status
                        </h2>
                    </div>

                    <div class="p-6">
                        <span
                            class="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                border
                                px-3
                                py-1.5
                                text-sm
                                font-semibold
                                {{ $statusClass }}
                            ">
                            <i class="{{ $statusIcon }}"></i>

                            {{ $status }}
                        </span>

                        <div
                            class="
                                mt-5
                                border-t
                                border-slate-200
                                pt-5
                            ">
                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-400
                                ">
                                Diajukan pada
                            </p>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    font-medium
                                    text-slate-800
                                ">
                                {{ $tanggalPengajuan }}
                            </p>
                        </div>
                    </div>
                </section>

                {{-- HASIL --}}

                @if ($isSelesai && $hasHasil)
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
                                border-slate-200
                                px-6
                                py-5
                            ">
                            <h2
                                class="
                                    text-lg
                                    font-bold
                                    text-slate-900
                                ">
                                Hasil Keberatan
                            </h2>
                        </div>

                        <div class="p-6">
                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    border
                                    px-3
                                    py-1.5
                                    text-sm
                                    font-semibold
                                    {{ $hasilClass }}
                                ">
                                <i class="{{ $hasilIcon }}"></i>

                                {{ $hasil }}
                            </span>
                        </div>
                    </section>
                @endif

                {{-- DOKUMEN --}}

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
                            border-slate-200
                            px-6
                            py-5
                        ">
                        <div
                            class="
                                flex
                                items-center
                                gap-3
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
                                    text-blue-700
                                ">
                                <i
                                    class="
                                        ri-folder-open-line
                                        text-xl
                                    "></i>
                            </div>

                            <div>
                                <h2
                                    class="
                                        text-lg
                                        font-bold
                                        text-slate-900
                                    ">
                                    Dokumen
                                </h2>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-slate-500
                                    ">
                                    Dokumen tanggapan PPID
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($fileTanggapanUrl)
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    p-4
                                ">
                                <div
                                    class="
                                        flex
                                        items-start
                                        gap-3
                                    ">
                                    <div
                                        class="
                                            flex
                                            h-11
                                            w-11
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-white
                                            text-blue-700
                                            shadow-sm
                                        ">
                                        <i
                                            class="
                                                ri-file-text-line
                                                text-xl
                                            "></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p
                                            class="
                                                text-sm
                                                font-semibold
                                                text-slate-900
                                            ">
                                            Dokumen Tanggapan
                                        </p>

                                        <p class="
                                                mt-1
                                                truncate
                                                text-xs
                                                text-slate-500
                                            "
                                            title="{{ $fileTanggapanName }}">
                                            {{ $fileTanggapanName }}
                                        </p>

                                        @if ($fileTanggapanExtension)
                                            <span
                                                class="
                                                    mt-2
                                                    inline-flex
                                                    rounded-md
                                                    bg-slate-200
                                                    px-2
                                                    py-1
                                                    text-[10px]
                                                    font-bold
                                                    text-slate-600
                                                ">
                                                {{ $fileTanggapanExtension }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $fileTanggapanUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="
                                        mt-4
                                        inline-flex
                                        h-10
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-blue-700
                                        px-4
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-blue-800
                                    ">
                                    <i class="ri-eye-line"></i>

                                    Lihat Dokumen
                                </a>
                            </div>
                        @elseif ($isSelesai)
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-slate-200
                                    bg-slate-50
                                    p-4
                                ">
                                <div
                                    class="
                                        flex
                                        items-start
                                        gap-3
                                    ">
                                    <i
                                        class="
                                            ri-information-line
                                            mt-0.5
                                            text-lg
                                            text-slate-400
                                        "></i>

                                    <p
                                        class="
                                            text-sm
                                            leading-6
                                            text-slate-600
                                        ">
                                        Tidak ada dokumen tambahan
                                        pada tanggapan keberatan ini.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-blue-100
                                    bg-blue-50
                                    p-4
                                ">
                                <div
                                    class="
                                        flex
                                        items-start
                                        gap-3
                                    ">
                                    <i
                                        class="
                                            ri-time-line
                                            mt-0.5
                                            text-lg
                                            text-blue-600
                                        "></i>

                                    <p
                                        class="
                                            text-sm
                                            leading-6
                                            text-blue-800
                                        ">
                                        Dokumen tanggapan akan
                                        tersedia setelah keberatan
                                        selesai diproses.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- NOMOR PERMOHONAN --}}

                <section
                    class="
                        rounded-2xl
                        border
                        border-blue-200
                        bg-blue-50
                        p-5
                    ">
                    <div
                        class="
                            flex
                            items-start
                            gap-3
                        ">
                        <i
                            class="
                                ri-information-line
                                mt-0.5
                                text-xl
                                text-blue-700
                            "></i>

                        <div>
                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-blue-900
                                ">
                                Permohonan Terkait
                            </p>

                            <p
                                class="
                                    mt-1
                                    break-words
                                    text-sm
                                    text-blue-800
                                ">
                                {{ $nomorPermohonan }}
                            </p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        {{-- ============================================================
            FOOTER ACTION
        ============================================================= --}}

        <div
            class="
                flex
                flex-col
                gap-3
                border-t
                border-slate-200
                pt-6
                sm:flex-row
                sm:items-center
                sm:justify-between
            ">
            <a href="{{ route('public.keberatan.index') }}"
                class="
                    inline-flex
                    h-11
                    items-center
                    justify-center
                    gap-2
                    rounded-lg
                    border
                    border-slate-300
                    bg-white
                    px-5
                    text-sm
                    font-semibold
                    text-slate-700
                    transition
                    hover:bg-slate-50
                ">
                <i class="ri-arrow-left-line"></i>

                Kembali ke Daftar Keberatan
            </a>
        </div>
    </section>
@endsection
