@extends('layouts.public.app')

@section('title', 'Detail Tiket Permohonan | PPID Kota Batu')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Status Permohonan
        |--------------------------------------------------------------------------
        */

        $status = trim((string) ($permohonan->status ?: 'Diajukan'));

        $status = $status !== '' ? $status : 'Diajukan';

        $isFinished = $status === 'Selesai';

        $isRejected = $status === 'Ditolak';

        /*
        |--------------------------------------------------------------------------
        | Hak Mengajukan Keberatan
        |--------------------------------------------------------------------------
        |
        | Keberatan hanya dapat diajukan untuk permohonan yang:
        | - telah selesai; atau
        | - telah ditolak.
        |
        */

        $canSubmitObjection = in_array($status, ['Selesai', 'Ditolak'], true);

        $isPublicLoggedIn = auth('public')->check();

        $hasObjectionRoute = \Illuminate\Support\Facades\Route::has('public.keberatan.create');

        /*
        |--------------------------------------------------------------------------
        | URL Keberatan
        |--------------------------------------------------------------------------
        */

        $objectionUrl = $hasObjectionRoute
            ? route('public.keberatan.create', [
                'permohonanid' => $permohonan->id,
            ])
            : null;

        /*
        |--------------------------------------------------------------------------
        | URL Login
        |--------------------------------------------------------------------------
        |
        | Parameter redirect menyimpan tujuan setelah warga login.
        | Controller login dapat menggunakan parameter ini apabila
        | mekanisme intended URL telah diterapkan.
        |
        */

        $loginForObjectionUrl = route(
            'login',
            array_filter([
                'redirect' => $objectionUrl,
            ]),
        );

        /*
        |--------------------------------------------------------------------------
        | Tanggal
        |--------------------------------------------------------------------------
        */

        $tanggal = $permohonan->tanggal?->locale('id')->translatedFormat('d F Y') ?? '-';

        $tanggalSelesai = $permohonan->tanggal_selesai?->locale('id')->translatedFormat('d F Y') ?? '-';

        $tanggalPenolakan = $isRejected
            ? $permohonan->tanggal_revisi?->locale('id')->translatedFormat('d F Y') ?? '-'
            : '-';

        $alasanPenolakan = $isRejected ? trim((string) $permohonan->catatan_revisi) : '';

        /*
        |--------------------------------------------------------------------------
        | Tampilan Status
        |--------------------------------------------------------------------------
        */

        $statusBadgeClass = match ($status) {
            'Selesai' => 'bg-green-100 text-green-700',

            'Ditolak' => 'bg-red-100 text-red-700',

            'Revisi PPID Pembantu' => 'bg-yellow-100 text-yellow-800',

            'Menunggu Validasi Admin Utama' => 'bg-orange-100 text-orange-800',

            'Diteruskan ke PPID Pembantu' => 'bg-purple-100 text-purple-700',

            default => 'bg-blue-100 text-blue-700',
        };

        $statusLabel = match ($status) {
            'Selesai' => 'Selesai',
            'Ditolak' => 'Ditolak',
            default => 'Dalam Proses',
        };
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
            <span
                class="
                    inline-flex
                    rounded-full
                    bg-blue-50
                    px-3
                    py-1
                    text-xs
                    font-semibold
                    text-blue-700
                ">
                Tiket Permohonan Informasi
            </span>

            <h1
                class="
                    mt-4
                    break-words
                    text-3xl
                    font-bold
                    text-slate-900
                ">
                {{ $permohonan->no_pemohon }}
            </h1>

            <p class="
                    mt-2
                    text-slate-600
                ">
                Simpan tautan halaman ini untuk memantau perkembangan
                permohonan tanpa login.
            </p>
        </div>
    </section>

    {{-- ================================================================
        KONTEN
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
        {{-- Flash message --}}

        @if (session('success'))
            <div
                class="
                    rounded-xl
                    border
                    border-green-200
                    bg-green-50
                    p-5
                    text-green-700
                ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div
                class="
                    rounded-xl
                    border
                    border-amber-200
                    bg-amber-50
                    p-5
                    text-amber-800
                ">
                {{ session('warning') }}
            </div>
        @endif

        {{-- ============================================================
            STATUS PERMOHONAN
        ============================================================= --}}

        <div
            class="
                rounded-2xl
                border
                bg-white
                p-6
                shadow-sm
                {{ $isRejected ? 'border-red-200' : 'border-slate-200' }}
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
                    <p
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-400
                        ">
                        Status Saat Ini
                    </p>

                    <h2
                        class="
                            mt-2
                            text-2xl
                            font-bold
                            {{ $isRejected ? 'text-red-700' : 'text-slate-900' }}
                        ">
                        {{ $status }}
                    </h2>

                    <p
                        class="
                            mt-2
                            text-sm
                            text-slate-500
                        ">
                        Diajukan pada {{ $tanggal }}
                    </p>
                </div>

                <span
                    class="
                        inline-flex
                        w-fit
                        rounded-full
                        px-4
                        py-2
                        text-sm
                        font-semibold
                        {{ $statusBadgeClass }}
                    ">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- ========================================================
                TOMBOL KEBERATAN
            ========================================================= --}}

            @if ($canSubmitObjection)
                <div
                    class="
                        mt-6
                        border-t
                        border-slate-200
                        pt-5
                    ">
                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            rounded-xl
                            border
                            border-amber-200
                            bg-amber-50
                            p-5
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                        ">
                        <div
                            class="
                                flex
                                items-start
                                gap-3
                            ">
                            <span
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
                                        ri-error-warning-line
                                        text-2xl
                                    "></i>
                            </span>

                            <div>
                                <h3
                                    class="
                                        text-sm
                                        font-bold
                                        text-amber-950
                                    ">
                                    Tidak puas dengan hasil pelayanan?
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        max-w-2xl
                                        text-sm
                                        leading-6
                                        text-amber-800
                                    ">
                                    Anda dapat mengajukan keberatan atas
                                    permohonan ini dengan menyampaikan
                                    alasan secara lengkap.
                                </p>
                            </div>
                        </div>

                        @if ($hasObjectionRoute)
                            @if ($isPublicLoggedIn)
                                <a href="{{ $objectionUrl }}"
                                    class="
                                        inline-flex
                                        h-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-amber-700
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        hover:bg-amber-800
                                        focus:outline-none
                                        focus:ring-4
                                        focus:ring-amber-500/20
                                    ">
                                    <i
                                        class="
                                            ri-file-warning-line
                                            text-lg
                                        "></i>

                                    Ajukan Keberatan
                                </a>
                            @else
                                <a href="{{ $loginForObjectionUrl }}"
                                    class="
                                        inline-flex
                                        h-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-amber-700
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-sm
                                        transition
                                        hover:bg-amber-800
                                        focus:outline-none
                                        focus:ring-4
                                        focus:ring-amber-500/20
                                    ">
                                    <i
                                        class="
                                            ri-login-box-line
                                            text-lg
                                        "></i>

                                    Masuk untuk Mengajukan
                                </a>
                            @endif
                        @else
                            <span
                                class="
                                    inline-flex
                                    h-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    bg-slate-200
                                    px-5
                                    text-sm
                                    font-semibold
                                    text-slate-500
                                ">
                                <i
                                    class="
                                        ri-information-line
                                        text-lg
                                    "></i>

                                Layanan Belum Tersedia
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ============================================================
            INFORMASI PENOLAKAN
        ============================================================= --}}

        @if ($isRejected)
            <section
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-red-200
                    bg-white
                    shadow-sm
                ">
                <div
                    class="
                        border-b
                        border-red-200
                        bg-red-50
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
                                h-11
                                w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                bg-red-100
                                text-red-700
                            ">
                            <i
                                class="
                                    ri-close-circle-line
                                    text-2xl
                                "></i>
                        </div>

                        <div>
                            <h2
                                class="
                                    text-lg
                                    font-bold
                                    text-red-900
                                ">
                                Permohonan Informasi Ditolak
                            </h2>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-red-700
                                ">
                                Ditolak pada
                                {{ $tanggalPenolakan }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-5 p-6">
                    <div
                        class="
                            rounded-xl
                            border
                            border-red-100
                            bg-red-50
                            p-5
                        ">
                        <p
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wider
                                text-red-600
                            ">
                            Alasan Penolakan
                        </p>

                        <div
                            class="
                                mt-3
                                whitespace-pre-line
                                text-sm
                                leading-7
                                text-red-900
                            ">
                            {{ $alasanPenolakan !== ''
                                ? $alasanPenolakan
                                : 'Data atau dokumen permohonan belum memenuhi kelengkapan yang dipersyaratkan.' }}
                        </div>
                    </div>

                    <div
                        class="
                            rounded-xl
                            border
                            border-cyan-200
                            bg-cyan-50
                            p-5
                            text-sm
                            leading-7
                            text-cyan-900
                        ">
                        <p class="font-semibold">
                            Status Permintaan Informasi:
                            Ditolak
                        </p>

                        <p class="mt-2">
                            Setelah dilakukan pemeriksaan, data atau
                            dokumen yang dikirimkan belum memenuhi
                            kelengkapan dan validitas yang
                            dipersyaratkan. Permohonan ini tidak dapat
                            diproses lebih lanjut.
                        </p>

                        <p class="mt-4">
                            Anda dapat mengajukan permohonan baru dengan
                            data yang benar dan lengkap, atau
                            menggunakan layanan keberatan yang tersedia
                            pada bagian status permohonan.
                        </p>
                    </div>
                </div>
            </section>
        @endif

        {{-- ============================================================
            DETAIL PERMOHONAN
        ============================================================= --}}

        <div
            class="
                grid
                grid-cols-1
                gap-6
                lg:grid-cols-3
            ">
            <div class="
                    space-y-6
                    lg:col-span-2
                ">
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
                            Detail Permohonan
                        </h2>
                    </div>

                    <dl
                        class="
                            divide-y
                            divide-slate-200
                        ">
                        @foreach ([
            'Rincian Informasi' => $permohonan->rincian,

            'Tujuan Penggunaan' => $permohonan->tujuan,

            'Cara Memperoleh' => $permohonan->cara_memperoleh,

            'Cara Pengiriman' => $permohonan->cara_pengiriman,
        ] as $label => $value)
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
                                    {{ $label }}
                                </dt>

                                <dd
                                    class="
                                        whitespace-pre-line
                                        text-sm
                                        leading-6
                                        text-slate-800
                                    ">
                                    {{ $value ?: '-' }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                {{-- ====================================================
                    JAWABAN FINAL
                ===================================================== --}}

                @if ($isFinished)
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
                            <h2
                                class="
                                    text-lg
                                    font-bold
                                    text-green-900
                                ">
                                Jawaban Final
                            </h2>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-green-700
                                ">
                                Diselesaikan pada
                                {{ $tanggalSelesai }}
                            </p>
                        </div>

                        <div class="space-y-5 p-6">
                            <div
                                class="
                                    whitespace-pre-line
                                    rounded-xl
                                    bg-slate-50
                                    p-5
                                    text-sm
                                    leading-7
                                    text-slate-800
                                ">
                                {{ $permohonan->jawaban ?: 'Jawaban final tersedia pada file lampiran.' }}
                            </div>

                            @if ($permohonan->file_jawaban)
                                <a href="{{ asset('storage/' . ltrim($permohonan->file_jawaban, '/')) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        h-11
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-green-700
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-green-800
                                    ">
                                    <i
                                        class="
                                            ri-file-download-line
                                            text-lg
                                        "></i>

                                    Lihat File Jawaban
                                </a>
                            @endif
                        </div>
                    </section>
                @endif
            </div>

            {{-- ========================================================
                SIDEBAR INFORMASI PEMOHON
            ========================================================= --}}

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
                            Pemohon
                        </h2>
                    </div>

                    <dl
                        class="
                            divide-y
                            divide-slate-200
                        ">
                        @foreach ([
            'Nama' => $permohonan->namaWarga(),

            'Kategori' => $permohonan->kategori_pemohon,

            'Email' => $permohonan->emailWarga(),

            'Telepon' => $permohonan->teleponWarga(),
        ] as $label => $value)
                            <div class="px-6 py-4">
                                <dt
                                    class="
                                        text-xs
                                        font-semibold
                                        uppercase
                                        tracking-wider
                                        text-slate-400
                                    ">
                                    {{ $label }}
                                </dt>

                                <dd
                                    class="
                                        mt-1
                                        break-words
                                        text-sm
                                        font-medium
                                        text-slate-800
                                    ">
                                    {{ $value ?: '-' }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section
                    class="
                        rounded-2xl
                        border
                        p-5
                        text-sm
                        leading-6
                        {{ $isRejected ? 'border-red-200 bg-red-50 text-red-800' : 'border-blue-200 bg-blue-50 text-blue-800' }}
                    ">
                    @if ($isRejected)
                        Pemberitahuan penolakan dan alasan penolakan
                        telah dikirim ke email pemohon.
                    @else
                        Email dikirim saat permohonan diterima,
                        ditolak, atau jawaban final telah tersedia.
                        Proses internal admin berlangsung melalui
                        dashboard.
                    @endif
                </section>
            </aside>
        </div>
    </section>
@endsection
