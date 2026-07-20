@extends('layouts.admin.app')

@section('title', 'Pesan Masuk')

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Persiapan data
        |--------------------------------------------------------------------------
        */

        $isPaginated = $pesanMasuk instanceof \Illuminate\Pagination\AbstractPaginator;

        $currentItems = $isPaginated ? $pesanMasuk->getCollection() : collect($pesanMasuk);

        $firstNumber = $isPaginated ? $pesanMasuk->firstItem() ?? 1 : 1;

        $totalPesan = $isPaginated ? $pesanMasuk->total() : $currentItems->count();

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

        /*
        |--------------------------------------------------------------------------
        | Tampilan status
        |--------------------------------------------------------------------------
        */

        $getStatusMeta = static function ($item): array {
            $statusLabel = trim(
                (string) (data_get($item, 'status_label') ?? (data_get($item, 'status') ?? 'Belum diketahui')),
            );

            if ($statusLabel === '') {
                $statusLabel = 'Belum diketahui';
            }

            $normalizedStatus = mb_strtolower($statusLabel);

            if (
                str_contains($normalizedStatus, 'baru') ||
                str_contains($normalizedStatus, 'masuk') ||
                str_contains($normalizedStatus, 'terbuka') ||
                str_contains($normalizedStatus, 'open')
            ) {
                return [
                    'label' => $statusLabel,
                    'badge' => '
                        bg-blue-50
                        text-blue-700
                        ring-blue-600/20
                        dark:bg-blue-500/15
                        dark:text-blue-400
                        dark:ring-blue-500/20
                    ',
                    'dot' => 'bg-blue-500',
                ];
            }

            if (
                str_contains($normalizedStatus, 'menunggu') ||
                str_contains($normalizedStatus, 'proses') ||
                str_contains($normalizedStatus, 'pending')
            ) {
                return [
                    'label' => $statusLabel,
                    'badge' => '
                        bg-yellow-50
                        text-yellow-700
                        ring-yellow-600/20
                        dark:bg-yellow-500/15
                        dark:text-yellow-400
                        dark:ring-yellow-500/20
                    ',
                    'dot' => 'bg-yellow-500',
                ];
            }

            if (
                str_contains($normalizedStatus, 'selesai') ||
                str_contains($normalizedStatus, 'ditutup') ||
                str_contains($normalizedStatus, 'closed')
            ) {
                return [
                    'label' => $statusLabel,
                    'badge' => '
                        bg-green-50
                        text-green-700
                        ring-green-600/20
                        dark:bg-green-500/15
                        dark:text-green-400
                        dark:ring-green-500/20
                    ',
                    'dot' => 'bg-green-500',
                ];
            }

            if (str_contains($normalizedStatus, 'ditolak') || str_contains($normalizedStatus, 'gagal')) {
                return [
                    'label' => $statusLabel,
                    'badge' => '
                        bg-red-50
                        text-red-700
                        ring-red-600/20
                        dark:bg-red-500/15
                        dark:text-red-400
                        dark:ring-red-500/20
                    ',
                    'dot' => 'bg-red-500',
                ];
            }

            return [
                'label' => $statusLabel,
                'badge' => '
                    bg-gray-100
                    text-gray-700
                    ring-gray-500/20
                    dark:bg-gray-800
                    dark:text-gray-300
                    dark:ring-gray-600/30
                ',
                'dot' => 'bg-gray-400',
            ];
        };
    @endphp

    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Pesan Masuk"
            description="Kelola percakapan, periksa pesan dari masyarakat, berikan balasan, dan pantau status setiap percakapan."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Pesan Masuk',
                ],
            ]" />

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
                        <i class="ri-error-warning-line text-xl"></i>
                    </div>

                    <div class="min-w-0">
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            ">
                            Data belum dapat diproses
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
            RINGKASAN
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
                    -right-16
                    -top-20
                    h-52
                    w-52
                    rounded-full
                    bg-blue-500/[0.08]
                    blur-3xl
                    dark:bg-blue-500/[0.12]
                "
                aria-hidden="true"></div>

            <div
                class="
                    relative
                    flex
                    flex-col
                    gap-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">
                <div class="flex items-center gap-4">
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
                            ring-1
                            ring-blue-100
                            dark:bg-blue-500/15
                            dark:text-blue-400
                            dark:ring-blue-500/20
                        ">
                        <i class="ri-chat-3-line text-2xl"></i>
                    </div>

                    <div>
                        <h2
                            class="
                                text-lg
                                font-bold
                                text-gray-900
                                dark:text-white
                            ">
                            Percakapan Masyarakat
                        </h2>

                        <p
                            class="
                                mt-1
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Klik nama atau subjek pesan untuk membuka detail percakapan.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        flex
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
                            rounded-xl
                            bg-white
                            text-blue-600
                            shadow-theme-xs
                            dark:bg-gray-800
                            dark:text-blue-400
                        ">
                        <i class="ri-message-2-line text-xl"></i>
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
                            Total Pesan
                        </p>

                        <p
                            class="
                                mt-0.5
                                text-xl
                                font-bold
                                text-gray-900
                                dark:text-white
                            ">
                            {{ number_format($totalPesan) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================
            TABEL PESAN MASUK
        ============================================================= --}}

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
                ">
                <div>
                    <h3
                        class="
                            text-base
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Daftar Pesan Masuk
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-gray-500
                            dark:text-gray-400
                        ">
                        Pesan terbaru ditampilkan berdasarkan urutan data dari sistem.
                    </p>
                </div>

                <span
                    class="
                        inline-flex
                        w-fit
                        items-center
                        gap-2
                        rounded-full
                        bg-blue-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-blue-700
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <span
                        class="
                            h-2
                            w-2
                            rounded-full
                            bg-blue-500
                        "></span>

                    {{ number_format($totalPesan) }} percakapan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1250px]">
                    <thead
                        class="
                            border-b
                            border-gray-100
                            bg-gray-50
                            dark:border-gray-800
                            dark:bg-gray-900/50
                        ">
                        <tr>
                            <th scope="col"
                                class="
                                    w-20
                                    px-5
                                    py-3.5
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                No
                            </th>

                            <th scope="col"
                                class="
                                    min-w-[250px]
                                    px-5
                                    py-3.5
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Pengirim
                            </th>

                            <th scope="col"
                                class="
                                    min-w-[310px]
                                    px-5
                                    py-3.5
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Subjek
                            </th>

                            <th scope="col"
                                class="
                                    min-w-[190px]
                                    px-5
                                    py-3.5
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Tanggal
                            </th>

                            <th scope="col"
                                class="
                                    min-w-[150px]
                                    px-5
                                    py-3.5
                                    text-left
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Status
                            </th>

                            <th scope="col"
                                class="
                                    min-w-[150px]
                                    px-5
                                    py-3.5
                                    text-center
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Balasan
                            </th>

                            <th scope="col"
                                class="
                                    w-[170px]
                                    min-w-[170px]
                                    px-5
                                    py-3.5
                                    text-center
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-gray-500
                                    dark:text-gray-400
                                    sm:px-6
                                ">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        class="
                            divide-y
                            divide-gray-100
                            dark:divide-gray-800
                        ">
                        @forelse ($currentItems as $index => $item)
                            @php
                                $rowNumber = $firstNumber + $index;

                                $showUrl = route('admin.pesan-masuk.show', $item->id);

                                $destroyUrl = route('admin.pesan-masuk.destroy', $item->id);

                                $nama = trim((string) ($item->nama ?? ''));

                                if ($nama === '') {
                                    $nama = 'Tanpa Nama';
                                }

                                $email = trim((string) ($item->email ?? ''));

                                if ($email === '') {
                                    $email = '-';
                                }

                                $subjek = trim((string) ($item->subjek ?? ''));

                                if ($subjek === '') {
                                    $subjek = 'Tanpa Subjek';
                                }

                                $initial = mb_strtoupper(mb_substr($nama, 0, 1));

                                $tanggal = $formatDateTime(data_get($item, 'tanggal'));

                                $statusMeta = $getStatusMeta($item);

                                $jumlahBalasan = (int) data_get($item, 'balasan_count', 0);
                            @endphp

                            <tr
                                class="
                                    group
                                    transition-colors
                                    duration-200
                                    hover:bg-gray-50
                                    dark:hover:bg-white/[0.03]
                                ">
                                {{-- Nomor --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <span
                                        class="
                                            inline-flex
                                            h-8
                                            min-w-8
                                            items-center
                                            justify-center
                                            rounded-lg
                                            bg-gray-100
                                            px-2
                                            text-xs
                                            font-semibold
                                            text-gray-600
                                            dark:bg-gray-800
                                            dark:text-gray-300
                                        ">
                                        {{ $rowNumber }}
                                    </span>
                                </td>

                                {{-- Pengirim --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="
                                                flex
                                                h-11
                                                w-11
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-gradient-to-br
                                                from-blue-50
                                                to-purple-50
                                                text-sm
                                                font-bold
                                                text-blue-600
                                                ring-1
                                                ring-blue-100
                                                dark:from-blue-500/15
                                                dark:to-purple-500/15
                                                dark:text-blue-400
                                                dark:ring-blue-500/20
                                            ">
                                            {{ $initial }}
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ $showUrl }}"
                                                class="
                                                    block
                                                    max-w-[230px]
                                                    truncate
                                                    text-sm
                                                    font-semibold
                                                    text-gray-800
                                                    transition
                                                    hover:text-blue-600
                                                    dark:text-white/90
                                                    dark:hover:text-blue-400
                                                "
                                                title="Buka percakapan {{ $nama }}">
                                                {{ $nama }}
                                            </a>

                                            @if ($email !== '-')
                                                <a href="mailto:{{ $email }}"
                                                    class="
                                                        mt-1
                                                        block
                                                        max-w-[230px]
                                                        truncate
                                                        text-xs
                                                        text-gray-500
                                                        transition
                                                        hover:text-blue-600
                                                        hover:underline
                                                        dark:text-gray-400
                                                        dark:hover:text-blue-400
                                                    "
                                                    title="{{ $email }}">
                                                    {{ $email }}
                                                </a>
                                            @else
                                                <p
                                                    class="
                                                        mt-1
                                                        text-xs
                                                        text-gray-400
                                                    ">
                                                    Email tidak tersedia
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Subjek --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <a href="{{ $showUrl }}"
                                        class="
                                            group/subject
                                            inline-flex
                                            max-w-[290px]
                                            items-start
                                            gap-2
                                            rounded-md
                                            text-sm
                                            font-semibold
                                            leading-6
                                            text-gray-800
                                            transition
                                            hover:text-blue-600
                                            focus:outline-none
                                            focus:ring-2
                                            focus:ring-blue-500/20
                                            dark:text-white/90
                                            dark:hover:text-blue-400
                                        "
                                        title="Buka percakapan: {{ $subjek }}">
                                        <span class="line-clamp-2">
                                            {{ $subjek }}
                                        </span>

                                        <i
                                            class="
                                                ri-arrow-right-s-line
                                                mt-0.5
                                                shrink-0
                                                text-base
                                                text-gray-400
                                                transition
                                                group-hover/subject:translate-x-0.5
                                                group-hover/subject:text-blue-500
                                            "></i>
                                    </a>

                                    <p
                                        class="
                                            mt-1.5
                                            text-xs
                                            text-gray-400
                                            dark:text-gray-500
                                        ">
                                        ID Percakapan: {{ $item->id }}
                                    </p>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="
                                                flex
                                                h-9
                                                w-9
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-xl
                                                bg-gray-100
                                                text-gray-500
                                                dark:bg-gray-800
                                                dark:text-gray-400
                                            ">
                                            <i class="ri-calendar-line text-base"></i>
                                        </span>

                                        <div>
                                            <p
                                                class="
                                                    whitespace-nowrap
                                                    text-sm
                                                    font-medium
                                                    text-gray-700
                                                    dark:text-gray-300
                                                ">
                                                {{ $tanggal }}
                                            </p>

                                            <p
                                                class="
                                                    mt-0.5
                                                    text-xs
                                                    text-gray-400
                                                    dark:text-gray-500
                                                ">
                                                Pesan diterima
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 sm:px-6">
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
                                            {{ $statusMeta['badge'] }}
                                        ">
                                        <span
                                            class="
                                                h-2
                                                w-2
                                                rounded-full
                                                {{ $statusMeta['dot'] }}
                                            "></span>

                                        {{ $statusMeta['label'] }}
                                    </span>
                                </td>

                                {{-- Jumlah balasan --}}
                                <td
                                    class="
                                        px-5
                                        py-4
                                        text-center
                                        sm:px-6
                                    ">
                                    <span
                                        class="
                                            inline-flex
                                            h-9
                                            min-w-9
                                            items-center
                                            justify-center
                                            gap-1.5
                                            rounded-full
                                            px-3
                                            text-xs
                                            font-bold
                                            {{ $jumlahBalasan > 0
                                                ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400'
                                                : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}
                                        ">
                                        <i class="ri-reply-line text-sm"></i>

                                        {{ $jumlahBalasan }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td
                                    class="
                                        w-[170px]
                                        min-w-[170px]
                                        px-5
                                        py-4
                                        text-center
                                        sm:px-6
                                    ">
                                    <div
                                        class="
                                            inline-flex
                                            items-center
                                            justify-center
                                            gap-2
                                        ">
                                        <a href="{{ $showUrl }}" title="Buka detail percakapan"
                                            aria-label="Buka detail percakapan {{ $subjek }}"
                                            class="
                                                inline-flex
                                                h-9
                                                w-9
                                                items-center
                                                justify-center
                                                rounded-lg
                                                border
                                                border-blue-200
                                                bg-blue-50
                                                text-blue-600
                                                transition
                                                hover:border-blue-300
                                                hover:bg-blue-100
                                                focus:outline-none
                                                focus:ring-3
                                                focus:ring-blue-500/20
                                                dark:border-blue-500/20
                                                dark:bg-blue-500/10
                                                dark:text-blue-400
                                                dark:hover:bg-blue-500/20
                                            ">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>

                                        <form action="{{ $destroyUrl }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus percakapan ini? Seluruh pesan dan balasan yang terkait dapat ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" title="Hapus percakapan"
                                                aria-label="Hapus percakapan {{ $subjek }}"
                                                class="
                                                    inline-flex
                                                    h-9
                                                    w-9
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    border
                                                    border-red-200
                                                    bg-red-50
                                                    text-red-600
                                                    transition
                                                    hover:border-red-300
                                                    hover:bg-red-100
                                                    focus:outline-none
                                                    focus:ring-3
                                                    focus:ring-red-500/20
                                                    dark:border-red-500/20
                                                    dark:bg-red-500/10
                                                    dark:text-red-400
                                                    dark:hover:bg-red-500/20
                                                ">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    ">
                                    <div
                                        class="
                                            mx-auto
                                            flex
                                            h-16
                                            w-16
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-blue-50
                                            text-blue-500
                                            dark:bg-blue-500/15
                                            dark:text-blue-400
                                        ">
                                        <i class="ri-chat-off-line text-3xl"></i>
                                    </div>

                                    <h3
                                        class="
                                            mt-4
                                            text-base
                                            font-semibold
                                            text-gray-800
                                            dark:text-white/90
                                        ">
                                        Belum ada pesan masuk
                                    </h3>

                                    <p
                                        class="
                                            mx-auto
                                            mt-1
                                            max-w-md
                                            text-sm
                                            leading-6
                                            text-gray-500
                                            dark:text-gray-400
                                        ">
                                        Pesan yang dikirim oleh masyarakat akan ditampilkan pada halaman ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ========================================================
                PAGINATION
            ========================================================= --}}

            @if ($isPaginated && $pesanMasuk->hasPages())
                <div
                    class="
                        border-t
                        border-gray-100
                        px-5
                        py-4
                        dark:border-gray-800
                        sm:px-6
                    ">
                    {{ $pesanMasuk->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
