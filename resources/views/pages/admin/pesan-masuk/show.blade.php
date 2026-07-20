@extends('layouts.admin.app')

@section('title', 'Detail Pesan Masuk')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Data dasar
        |--------------------------------------------------------------------------
        */

        $statusLabel = trim(
            (string) (
                data_get($pesan, 'status_label') ??
                data_get($pesan, 'status') ??
                'Aktif'
            )
        );

        if ($statusLabel === '') {
            $statusLabel = 'Aktif';
        }

        $normalizedStatus = mb_strtolower($statusLabel);

        $isClosed = $pesan->isClosed();

        /*
        |--------------------------------------------------------------------------
        | Tampilan status
        |--------------------------------------------------------------------------
        */

        if (
            str_contains($normalizedStatus, 'tutup') ||
            str_contains($normalizedStatus, 'selesai') ||
            str_contains($normalizedStatus, 'closed')
        ) {
            $statusBadgeClass = '
                bg-gray-100
                text-gray-700
                ring-gray-500/20
                dark:bg-gray-800
                dark:text-gray-300
                dark:ring-gray-600/30
            ';

            $statusDotClass = 'bg-gray-500';
        } elseif (
            str_contains($normalizedStatus, 'baru') ||
            str_contains($normalizedStatus, 'masuk')
        ) {
            $statusBadgeClass = '
                bg-blue-50
                text-blue-700
                ring-blue-600/20
                dark:bg-blue-500/15
                dark:text-blue-400
                dark:ring-blue-500/20
            ';

            $statusDotClass = 'bg-blue-500';
        } elseif (
            str_contains($normalizedStatus, 'proses') ||
            str_contains($normalizedStatus, 'menunggu')
        ) {
            $statusBadgeClass = '
                bg-yellow-50
                text-yellow-700
                ring-yellow-600/20
                dark:bg-yellow-500/15
                dark:text-yellow-400
                dark:ring-yellow-500/20
            ';

            $statusDotClass = 'bg-yellow-500';
        } else {
            $statusBadgeClass = '
                bg-green-50
                text-green-700
                ring-green-600/20
                dark:bg-green-500/15
                dark:text-green-400
                dark:ring-green-500/20
            ';

            $statusDotClass = 'bg-green-500';
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
                if (
                    is_numeric($value) &&
                    (int) $value > 100000000
                ) {
                    return \Illuminate\Support\Carbon::createFromTimestamp(
                        (int) $value
                    )->translatedFormat('d F Y, H:i');
                }

                return \Illuminate\Support\Carbon::parse(
                    $value
                )->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $formattedDate = $formatDateTime(
            data_get($pesan, 'tanggal')
        );

        /*
        |--------------------------------------------------------------------------
        | Data pengirim
        |--------------------------------------------------------------------------
        */

        $nama = trim((string) data_get($pesan, 'nama', ''));

        if ($nama === '') {
            $nama = 'Tanpa Nama';
        }

        $email = trim((string) data_get($pesan, 'email', ''));

        if ($email === '') {
            $email = '-';
        }

        $subjek = trim((string) data_get($pesan, 'subjek', ''));

        if ($subjek === '') {
            $subjek = 'Tanpa Subjek';
        }

        $initial = mb_strtoupper(
            mb_substr($nama, 0, 1)
        );

        $publicUrl = route(
            'public.pesan.show',
            $pesan->token
        );
    @endphp

    <div class="space-y-6">
        <x-admin.page-header
            title="Detail Pesan Masuk"
            description="Lihat informasi pengirim, pantau percakapan, kirim balasan, dan kelola status pesan masyarakat."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pesan Masuk',
                    'url' => route('admin.pesan-masuk.index'),
                ],
                [
                    'label' => 'Detail Pesan',
                ],
            ]"
        >
            <x-slot:actions>
                <a
                    href="{{ route('admin.pesan-masuk.index') }}"
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
                    "
                >
                    <i class="ri-arrow-left-line text-lg"></i>
                    Kembali
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        @if ($errors->any())
            <div
                class="
                    rounded-2xl
                    border
                    border-red-200
                    bg-red-50
                    p-5
                    dark:border-red-500/20
                    dark:bg-red-500/10
                "
                role="alert"
            >
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
                        "
                    >
                        <i class="ri-error-warning-line text-xl"></i>
                    </div>

                    <div class="min-w-0">
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            "
                        >
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
                            "
                        >
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Ringkasan pesan --}}
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
                    pointer-events-none
                    absolute
                    -right-20
                    -top-24
                    h-64
                    w-64
                    rounded-full
                    bg-blue-500/[0.08]
                    blur-3xl
                    dark:bg-blue-500/[0.12]
                "
            ></div>

            <div
                class="
                    relative
                    flex
                    flex-col
                    gap-5
                    lg:flex-row
                    lg:items-start
                    lg:justify-between
                "
            >
                <div class="flex min-w-0 items-start gap-4">
                    <div
                        class="
                            flex
                            h-14
                            w-14
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gradient-to-br
                            from-blue-50
                            to-purple-50
                            text-xl
                            font-bold
                            text-blue-600
                            ring-1
                            ring-blue-100
                            dark:from-blue-500/15
                            dark:to-purple-500/15
                            dark:text-blue-400
                            dark:ring-blue-500/20
                        "
                    >
                        {{ $initial }}
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
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
                                    {{ $statusBadgeClass }}
                                "
                            >
                                <span
                                    class="
                                        h-2
                                        w-2
                                        rounded-full
                                        {{ $statusDotClass }}
                                    "
                                ></span>

                                {{ $statusLabel }}
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
                                "
                            >
                                ID: {{ $pesan->id }}
                            </span>
                        </div>

                        <h2
                            class="
                                mt-3
                                break-words
                                text-xl
                                font-bold
                                leading-8
                                text-gray-900
                                dark:text-white
                                sm:text-2xl
                            "
                        >
                            {{ $subjek }}
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
                            "
                        >
                            <span class="inline-flex items-center gap-2">
                                <i class="ri-user-line"></i>
                                {{ $nama }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i class="ri-calendar-line"></i>
                                {{ $formattedDate }}
                            </span>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    id="copyPublicLinkButton"
                    data-link="{{ $publicUrl }}"
                    class="
                        inline-flex
                        h-11
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
                        focus:outline-none
                        focus:ring-3
                        focus:ring-blue-500/20
                        dark:border-blue-500/20
                        dark:bg-blue-500/10
                        dark:text-blue-400
                        dark:hover:bg-blue-500/20
                    "
                >
                    <i class="ri-file-copy-line text-lg"></i>

                    <span id="copyPublicLinkText">
                        Salin Link Publik
                    </span>
                </button>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                {{-- Percakapan --}}
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
                            flex
                            flex-col
                            gap-3
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            sm:px-6
                        "
                    >
                        <div class="flex items-center gap-3">
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
                                "
                            >
                                <i class="ri-chat-3-line text-xl"></i>
                            </div>

                            <div>
                                <h3
                                    class="
                                        text-base
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    "
                                >
                                    Percakapan
                                </h3>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    "
                                >
                                    Riwayat pesan antara masyarakat dan Admin PPID.
                                </p>
                            </div>
                        </div>

                        <div
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-xs
                                font-medium
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            <span
                                id="refreshIndicator"
                                class="
                                    h-2
                                    w-2
                                    rounded-full
                                    bg-green-500
                                "
                            ></span>

                            Diperbarui otomatis setiap 5 detik
                        </div>
                    </div>

                    <div
                        id="chatScrollContainer"
                        class="
                            h-[560px]
                            overflow-y-auto
                            bg-gray-50/70
                            p-4
                            dark:bg-gray-950/30
                            sm:p-6
                        "
                    >
                        <div
                            id="chatBox"
                            class="space-y-4"
                            aria-live="polite"
                        >
                            <div
                                class="
                                    flex
                                    h-full
                                    min-h-[440px]
                                    items-center
                                    justify-center
                                    text-sm
                                    text-gray-500
                                    dark:text-gray-400
                                "
                            >
                                <div class="text-center">
                                    <i
                                        class="
                                            ri-loader-4-line
                                            inline-block
                                            animate-spin
                                            text-2xl
                                            text-blue-500
                                        "
                                    ></i>

                                    <p class="mt-2">
                                        Memuat percakapan...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @if ($isClosed)
                    <div
                        class="
                            rounded-2xl
                            border
                            border-gray-200
                            bg-gray-50
                            p-5
                            dark:border-gray-700
                            dark:bg-gray-900
                        "
                    >
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
                                    bg-gray-200
                                    text-gray-600
                                    dark:bg-gray-800
                                    dark:text-gray-400
                                "
                            >
                                <i class="ri-lock-line text-xl"></i>
                            </div>

                            <div>
                                <h3
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-800
                                        dark:text-gray-200
                                    "
                                >
                                    Percakapan sudah ditutup
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        leading-6
                                        text-gray-500
                                        dark:text-gray-400
                                    "
                                >
                                    Admin dan masyarakat tidak dapat mengirim balasan baru pada percakapan ini.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Form balasan --}}
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
                        "
                    >
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
                            "
                        >
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
                                "
                            >
                                <i class="ri-reply-line text-xl"></i>
                            </div>

                            <div>
                                <h3
                                    class="
                                        text-base
                                        font-semibold
                                        text-blue-900
                                        dark:text-blue-300
                                    "
                                >
                                    Kirim Balasan
                                </h3>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-blue-700
                                        dark:text-blue-400
                                    "
                                >
                                    Balasan akan langsung muncul pada halaman percakapan masyarakat.
                                </p>
                            </div>
                        </div>

                        <form
                            id="adminReplyForm"
                            action="{{ route(
                                'admin.pesan-masuk.reply',
                                $pesan->id
                            ) }}"
                            method="POST"
                            class="space-y-5 p-5 sm:p-6"
                        >
                            @csrf

                            <div>
                                <label
                                    for="pesan"
                                    class="
                                        mb-1.5
                                        block
                                        text-sm
                                        font-medium
                                        text-gray-700
                                        dark:text-gray-300
                                    "
                                >
                                    Isi Balasan

                                    <span class="text-red-500">*</span>
                                </label>

                                <textarea
                                    id="pesan"
                                    name="pesan"
                                    rows="6"
                                    required
                                    placeholder="Tulis balasan untuk masyarakat..."
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
                                        focus:border-brand-300
                                        focus:ring-3
                                        focus:ring-brand-500/10
                                        dark:border-gray-700
                                        dark:bg-gray-900
                                        dark:text-white/90
                                        @error('pesan')
                                            border-red-500
                                            focus:border-red-500
                                            focus:ring-red-500/10
                                        @enderror
                                    "
                                >{{ old('pesan') }}</textarea>

                                @error('pesan')
                                    <p class="mt-1.5 text-xs text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div
                                class="
                                    flex
                                    flex-col-reverse
                                    gap-3
                                    border-t
                                    border-gray-100
                                    pt-5
                                    dark:border-gray-800
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-end
                                "
                            >
                                <a
                                    href="{{ route('admin.pesan-masuk.index') }}"
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
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        transition
                                        hover:bg-gray-50
                                        dark:border-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-300
                                        dark:hover:bg-gray-800
                                    "
                                >
                                    <i class="ri-arrow-left-line text-lg"></i>
                                    Kembali
                                </a>

                                <button
                                    id="adminReplyButton"
                                    type="submit"
                                    class="
                                        inline-flex
                                        h-11
                                        min-w-[170px]
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-brand-500
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        shadow-theme-xs
                                        transition
                                        hover:bg-brand-600
                                        focus:outline-none
                                        focus:ring-3
                                        focus:ring-brand-500/20
                                        disabled:cursor-not-allowed
                                        disabled:opacity-60
                                    "
                                >
                                    <i
                                        id="adminReplyIcon"
                                        class="ri-send-plane-line text-lg"
                                    ></i>

                                    <span id="adminReplyButtonText">
                                        Kirim Balasan
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                {{-- Informasi pengirim --}}
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
                            Informasi Pengirim
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
                                    bg-blue-50
                                    text-lg
                                    font-bold
                                    text-blue-600
                                    ring-1
                                    ring-blue-100
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                    dark:ring-blue-500/20
                                "
                            >
                                {{ $initial }}
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
                                >
                                    {{ $nama }}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        truncate
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    "
                                >
                                    Pengirim pesan
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
                                "
                            >
                                Email
                            </dt>

                            <dd class="mt-1.5">
                                @if ($email !== '-')
                                    <a
                                        href="mailto:{{ $email }}"
                                        class="
                                            break-all
                                            text-sm
                                            font-semibold
                                            text-blue-600
                                            hover:underline
                                            dark:text-blue-400
                                        "
                                    >
                                        {{ $email }}
                                    </a>
                                @else
                                    <span
                                        class="
                                            text-sm
                                            text-gray-500
                                            dark:text-gray-400
                                        "
                                    >
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
                                "
                            >
                                Tanggal
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                "
                            >
                                {{ $formattedDate }}
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
                                "
                            >
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
                                        {{ $statusBadgeClass }}
                                    "
                                >
                                    <span
                                        class="
                                            h-2
                                            w-2
                                            rounded-full
                                            {{ $statusDotClass }}
                                        "
                                    ></span>

                                    {{ $statusLabel }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </section>

                {{-- Link publik --}}
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
                            Akses Publik
                        </h3>
                    </div>

                    <div class="space-y-4 p-5">
                        <p
                            class="
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Link ini digunakan masyarakat untuk membuka dan membalas percakapan.
                        </p>

                        <div
                            class="
                                break-all
                                rounded-xl
                                border
                                border-gray-200
                                bg-gray-50
                                px-4
                                py-3
                                text-xs
                                leading-5
                                text-gray-600
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-400
                            "
                        >
                            {{ $publicUrl }}
                        </div>

                        <a
                            href="{{ $publicUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="
                                inline-flex
                                h-10
                                w-full
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
                            "
                        >
                            <i class="ri-external-link-line text-lg"></i>
                            Buka Halaman Publik
                        </a>
                    </div>
                </section>

                @unless ($isClosed)
                    {{-- Tutup percakapan --}}
                    <section
                        class="
                            rounded-2xl
                            border
                            border-gray-200
                            bg-white
                            p-5
                            shadow-theme-xs
                            dark:border-gray-800
                            dark:bg-white/[0.03]
                        "
                    >
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            Tutup Percakapan
                        </h3>

                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Setelah ditutup, masyarakat tidak dapat mengirim balasan baru.
                        </p>

                        <form
                            action="{{ route(
                                'admin.pesan-masuk.close',
                                $pesan->id
                            ) }}"
                            method="POST"
                            class="mt-4"
                            onsubmit="return confirm('Apakah Anda yakin ingin menutup percakapan ini?')"
                        >
                            @csrf

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
                                    border-gray-300
                                    bg-white
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    transition
                                    hover:border-red-300
                                    hover:bg-red-50
                                    hover:text-red-600
                                    focus:outline-none
                                    focus:ring-3
                                    focus:ring-red-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-gray-300
                                    dark:hover:border-red-500/30
                                    dark:hover:bg-red-500/10
                                    dark:hover:text-red-400
                                "
                            >
                                <i class="ri-lock-line text-lg"></i>
                                Tutup Percakapan
                            </button>
                        </form>
                    </section>
                @endunless
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatBox = document.getElementById('chatBox');
            const chatScrollContainer = document.getElementById(
                'chatScrollContainer'
            );

            const refreshIndicator = document.getElementById(
                'refreshIndicator'
            );

            const copyButton = document.getElementById(
                'copyPublicLinkButton'
            );

            const copyButtonText = document.getElementById(
                'copyPublicLinkText'
            );

            const replyForm = document.getElementById(
                'adminReplyForm'
            );

            const replyButton = document.getElementById(
                'adminReplyButton'
            );

            const replyButtonText = document.getElementById(
                'adminReplyButtonText'
            );

            const replyIcon = document.getElementById(
                'adminReplyIcon'
            );

            const messagesUrl = @json(
                route(
                    'admin.pesan-masuk.messages',
                    $pesan->id
                )
            );

            let firstLoad = true;
            let requestController = null;

            function isNearBottom() {
                if (!chatScrollContainer) {
                    return true;
                }

                const distanceFromBottom =
                    chatScrollContainer.scrollHeight -
                    chatScrollContainer.scrollTop -
                    chatScrollContainer.clientHeight;

                return distanceFromBottom < 120;
            }

            function scrollToBottom() {
                if (!chatScrollContainer) {
                    return;
                }

                chatScrollContainer.scrollTo({
                    top: chatScrollContainer.scrollHeight,
                    behavior: firstLoad ? 'auto' : 'smooth'
                });
            }

            function createAvatar(message, isAdmin) {
                const avatar = document.createElement('div');

                avatar.className = isAdmin
                    ? 'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white'
                    : 'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-200';

                const senderName = String(
                    message.nama_pengirim ||
                    (isAdmin ? 'Admin' : 'Masyarakat')
                );

                avatar.textContent = senderName
                    .charAt(0)
                    .toUpperCase();

                return avatar;
            }

            function createMessageElement(message) {
                const isAdmin = message.pengirim === 'admin';

                const wrapper = document.createElement('div');

                wrapper.className = isAdmin
                    ? 'flex items-end justify-end gap-2.5'
                    : 'flex items-end justify-start gap-2.5';

                const bubble = document.createElement('div');

                bubble.className = isAdmin
                    ? 'max-w-[85%] rounded-2xl rounded-br-md bg-blue-600 px-4 py-3 text-white shadow-sm sm:max-w-[75%]'
                    : 'max-w-[85%] rounded-2xl rounded-bl-md border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 sm:max-w-[75%]';

                const meta = document.createElement('div');

                meta.className = isAdmin
                    ? 'mb-1.5 flex flex-wrap items-center gap-1 text-xs text-blue-100'
                    : 'mb-1.5 flex flex-wrap items-center gap-1 text-xs text-gray-500 dark:text-gray-400';

                const sender = document.createElement('span');
                sender.className = 'font-semibold';
                sender.textContent =
                    message.nama_pengirim ||
                    (isAdmin ? 'Admin PPID' : 'Masyarakat');

                const separator = document.createElement('span');
                separator.textContent = '•';

                const date = document.createElement('span');
                date.textContent = message.tanggal || '-';

                meta.appendChild(sender);
                meta.appendChild(separator);
                meta.appendChild(date);

                const content = document.createElement('div');
                content.className = 'whitespace-pre-line break-words text-sm leading-6';
                content.textContent = message.pesan || '';

                bubble.appendChild(meta);
                bubble.appendChild(content);

                const avatar = createAvatar(message, isAdmin);

                if (isAdmin) {
                    wrapper.appendChild(bubble);
                    wrapper.appendChild(avatar);
                } else {
                    wrapper.appendChild(avatar);
                    wrapper.appendChild(bubble);
                }

                return wrapper;
            }

            function showEmptyMessage() {
                chatBox.innerHTML = '';

                const emptyState = document.createElement('div');

                emptyState.className =
                    'flex min-h-[440px] items-center justify-center text-center';

                const content = document.createElement('div');

                const icon = document.createElement('i');
                icon.className =
                    'ri-chat-off-line text-4xl text-gray-300 dark:text-gray-600';

                const title = document.createElement('p');
                title.className =
                    'mt-3 text-sm font-semibold text-gray-700 dark:text-gray-300';
                title.textContent = 'Belum ada percakapan';

                const description = document.createElement('p');
                description.className =
                    'mt-1 text-sm text-gray-500 dark:text-gray-400';
                description.textContent =
                    'Pesan dan balasan akan muncul pada bagian ini.';

                content.appendChild(icon);
                content.appendChild(title);
                content.appendChild(description);
                emptyState.appendChild(content);
                chatBox.appendChild(emptyState);
            }

            async function loadMessages() {
                if (!chatBox) {
                    return;
                }

                const shouldScroll = firstLoad || isNearBottom();

                if (requestController) {
                    requestController.abort();
                }

                requestController = new AbortController();

                if (refreshIndicator) {
                    refreshIndicator.classList.add('animate-pulse');
                }

                try {
                    const response = await fetch(messagesUrl, {
                        method: 'GET',
                        cache: 'no-store',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: requestController.signal
                    });

                    if (!response.ok) {
                        throw new Error(
                            `HTTP ${response.status}`
                        );
                    }

                    const data = await response.json();

                    const messages = Array.isArray(data.messages)
                        ? data.messages
                        : [];

                    chatBox.innerHTML = '';

                    if (messages.length === 0) {
                        showEmptyMessage();
                    } else {
                        messages.forEach((message) => {
                            chatBox.appendChild(
                                createMessageElement(message)
                            );
                        });
                    }

                    if (shouldScroll) {
                        requestAnimationFrame(scrollToBottom);
                    }

                    firstLoad = false;
                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    chatBox.innerHTML = '';

                    const errorBox = document.createElement('div');
                    errorBox.className =
                        'flex min-h-[440px] items-center justify-center text-center text-sm text-red-600 dark:text-red-400';

                    errorBox.textContent =
                        'Percakapan gagal dimuat. Sistem akan mencoba kembali secara otomatis.';

                    chatBox.appendChild(errorBox);
                } finally {
                    if (refreshIndicator) {
                        refreshIndicator.classList.remove(
                            'animate-pulse'
                        );
                    }
                }
            }

            async function copyText(text) {
                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {
                    await navigator.clipboard.writeText(text);
                    return;
                }

                const textarea = document.createElement('textarea');

                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';

                document.body.appendChild(textarea);

                textarea.focus();
                textarea.select();

                document.execCommand('copy');

                textarea.remove();
            }

            if (copyButton) {
                copyButton.addEventListener('click', async () => {
                    const link = copyButton.dataset.link;

                    try {
                        await copyText(link);

                        if (copyButtonText) {
                            copyButtonText.textContent =
                                'Link Tersalin';
                        }

                        setTimeout(() => {
                            if (copyButtonText) {
                                copyButtonText.textContent =
                                    'Salin Link Publik';
                            }
                        }, 2000);
                    } catch (error) {
                        if (copyButtonText) {
                            copyButtonText.textContent =
                                'Gagal Menyalin';
                        }
                    }
                });
            }

            if (replyForm) {
                replyForm.addEventListener('submit', () => {
                    if (replyButton) {
                        replyButton.disabled = true;
                    }

                    if (replyButtonText) {
                        replyButtonText.textContent =
                            'Mengirim...';
                    }

                    if (replyIcon) {
                        replyIcon.className =
                            'ri-loader-4-line animate-spin text-lg';
                    }
                });
            }

            loadMessages();

            const refreshTimer = window.setInterval(() => {
                if (!document.hidden) {
                    loadMessages();
                }
            }, 5000);

            window.addEventListener('beforeunload', () => {
                window.clearInterval(refreshTimer);

                if (requestController) {
                    requestController.abort();
                }
            });
        });
    </script>
@endsection