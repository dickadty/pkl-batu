@props(['permohonan', 'ppidPembantuList' => []])

@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan Data Tabel
    |--------------------------------------------------------------------------
    */

    $isPaginated = $permohonan instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $permohonan->getCollection() : collect($permohonan);

    $ppidList = collect($ppidPembantuList ?? []);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id): bool => $id !== null && $id !== '')
        ->map(fn($id): string => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $permohonan->firstItem() ?? 1 : 1;

    /*
    |--------------------------------------------------------------------------
    | Jumlah Filter Aktif
    |--------------------------------------------------------------------------
    */

    $activeFilterCount = collect([request('q'), request('status'), request('ppid_pembantuid')])
        ->filter(fn($value): bool => $value !== null && $value !== '' && $value !== 'semua')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Pilihan Status Sesuai Alur Permohonan
    |--------------------------------------------------------------------------
    */

    $statusOptions = [
        'Diajukan',
        'Diproses',
        'Diteruskan ke PPID Pembantu',
        'Menunggu Validasi Admin Utama',
        'Revisi PPID Pembantu',
        'Selesai',
        'Ditolak',
    ];
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Permohonan Informasi"
    description="Pantau nomor permohonan, identitas pemohon, dokumen identitas, unit PPID tujuan, rincian kebutuhan, status, dan proses pelayanan informasi."
    :row-ids="$rowIds" :paginator="$isPaginated ? $permohonan : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[2050px]">
    {{-- ================================================================
        FILTER
    ================================================================= --}}

    <x-slot:filter>
        <form action="{{ route('admin.permohonan.index') }}" method="GET" class="space-y-5">
            <div
                class="
                    border-b
                    border-gray-100
                    pb-3
                    dark:border-gray-800
                ">
                <h4
                    class="
                        text-sm
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Filter Permohonan
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari permohonan berdasarkan nomor, identitas pemohon,
                    unit PPID, rincian, atau status.
                </p>
            </div>

            {{-- Pencarian --}}

            <div>
                <label for="permohonan_q"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Pencarian
                </label>

                <div class="relative">
                    <span
                        class="
                            pointer-events-none
                            absolute
                            inset-y-0
                            left-0
                            flex
                            items-center
                            pl-3.5
                            text-gray-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>
                    </span>

                    <input id="permohonan_q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari nomor, pemohon, atau rincian" autocomplete="off"
                        class="
                            h-11
                            w-full
                            rounded-lg
                            border
                            border-gray-300
                            bg-transparent
                            py-2.5
                            pl-11
                            pr-4
                            text-sm
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
                        ">
                </div>
            </div>

            {{-- Status --}}

            <div>
                <label for="permohonan_status"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Status
                </label>

                <select id="permohonan_status" name="status"
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
                    ">
                    <option value="">
                        Semua Status
                    </option>

                    @foreach ($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected(strtolower(trim((string) request('status'))) === strtolower($statusOption))>
                            {{ $statusOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PPID Pembantu --}}

            @if ($ppidList->isNotEmpty())
                <div>
                    <label for="permohonan_ppid"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-300
                        ">
                        PPID Pembantu
                    </label>

                    <select id="permohonan_ppid" name="ppid_pembantuid"
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
                        ">
                        <option value="">
                            Semua PPID Pembantu
                        </option>

                        @foreach ($ppidList as $ppid)
                            <option value="{{ $ppid->id }}" @selected((string) request('ppid_pembantuid') === (string) $ppid->id)>
                                {{ $ppid->nama ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Data per halaman --}}

            <div>
                <label for="permohonan_per_page"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Data per Halaman
                </label>

                <select id="permohonan_per_page" name="per_page"
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
                    ">
                    @foreach ([10, 15, 25, 50, 100] as $limit)
                        <option value="{{ $limit }}" @selected((int) request('per_page', 15) === $limit)>
                            {{ $limit }} data
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol filter --}}

            <div
                class="
                    flex
                    items-center
                    justify-end
                    gap-2
                    border-t
                    border-gray-100
                    pt-4
                    dark:border-gray-800
                ">
                <a href="{{ route('admin.permohonan.index') }}"
                    class="
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-4
                        text-sm
                        font-medium
                        text-gray-700
                        transition
                        hover:bg-gray-50
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                    ">
                    Reset
                </a>

                <button type="submit"
                    class="
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        rounded-lg
                        bg-brand-500
                        px-4
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-brand-600
                    ">
                    Terapkan
                </button>
            </div>
        </form>
    </x-slot:filter>

    {{-- ================================================================
        HEADER ACTION
    ================================================================= --}}

    <x-slot:headerActions>
        @if ($activeFilterCount > 0)
            <span
                class="
                    inline-flex
                    rounded-full
                    bg-blue-50
                    px-3
                    py-2
                    text-xs
                    font-semibold
                    text-blue-700
                    dark:bg-blue-500/15
                    dark:text-blue-400
                ">
                {{ $activeFilterCount }} Filter Aktif
            </span>
        @endif
    </x-slot:headerActions>

    {{-- ================================================================
        HEADER TABEL
    ================================================================= --}}

    <x-slot:head>
        <th scope="col"
            class="
                w-20
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            No
        </th>

        <th scope="col"
            class="
                min-w-[220px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            No. Permohonan
        </th>

        <th scope="col"
            class="
                min-w-[300px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Pemohon
        </th>

        <th scope="col"
            class="
                min-w-[250px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Gambar Identitas
        </th>

        <th scope="col"
            class="
                min-w-[280px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            PPID Pembantu
        </th>

        <th scope="col"
            class="
                min-w-[180px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Tanggal
        </th>

        <th scope="col"
            class="
                min-w-[440px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Rincian
        </th>

        <th scope="col"
            class="
                min-w-[230px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Status
        </th>

        <th scope="col"
            class="
                w-[130px]
                min-w-[130px]
                px-4
                py-3.5
                text-center
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Action
        </th>
    </x-slot:head>

    {{-- ================================================================
        ISI TABEL
    ================================================================= --}}

    @forelse ($currentItems as $index => $item)
        @php
            /*
            |--------------------------------------------------------------------------
            | Nomor Urut
            |--------------------------------------------------------------------------
            */

            $rowNumber = $firstNumber + $index;

            /*
            |--------------------------------------------------------------------------
            | Data Pemohon
            |--------------------------------------------------------------------------
            */

            $applicantName =
                data_get($item, 'userPublic.nama') ??
                (data_get($item, 'user_public.nama') ?? (data_get($item, 'nama_pemohon') ?? '-'));

            $applicantEmail =
                data_get($item, 'userPublic.email') ??
                (data_get($item, 'user_public.email') ?? (data_get($item, 'email_pemohon') ?? null));
            /*
            |--------------------------------------------------------------------------
            | Data PPID Pembantu
            |--------------------------------------------------------------------------
            */

            $ppidName = data_get($item, 'ppidPembantu.nama') ?? (data_get($item, 'ppid_pembantu.nama') ?? '-');

            /*
            |--------------------------------------------------------------------------
            | Tanggal Permohonan
            |--------------------------------------------------------------------------
            */

            $formattedDate = '-';

            if (!empty($item->tanggal)) {
                try {
                    if (is_numeric($item->tanggal) && (int) $item->tanggal > 100000000) {
                        $formattedDate = \Illuminate\Support\Carbon::createFromTimestamp((int) $item->tanggal)
                            ->locale('id')
                            ->translatedFormat('d F Y');
                    } else {
                        $formattedDate = \Illuminate\Support\Carbon::parse($item->tanggal)
                            ->locale('id')
                            ->translatedFormat('d F Y');
                    }
                } catch (\Throwable $exception) {
                    $formattedDate = (string) $item->tanggal;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $status = trim((string) ($item->status ?? 'Diajukan'));

            $statusKey = mb_strtolower($status);

            $statusClass = match ($statusKey) {
                'diajukan' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                'diproses' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

                'diteruskan ke ppid pembantu' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-400',

                'menunggu validasi admin utama'
                    => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',

                'revisi ppid pembantu' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',

                'selesai' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            };

            /*
            |--------------------------------------------------------------------------
            | Gambar atau Dokumen Identitas
            |--------------------------------------------------------------------------
            */

            $identitasPath = trim(
                (string) (data_get($item, 'file_identitas') ??
                    (data_get($item, 'userPublic.scanktp') ?? (data_get($item, 'user_public.scanktp') ?? ''))),
            );

            $identitasUrl = null;

            if ($identitasPath !== '') {
                if (\Illuminate\Support\Str::startsWith($identitasPath, ['http://', 'https://'])) {
                    $identitasUrl = $identitasPath;
                } elseif (\Illuminate\Support\Facades\Route::has('admin.permohonan.dokumen')) {
                    $identitasUrl = route('admin.permohonan.dokumen', [
                        'id' => $item->id,
                        'jenis' => 'identitas',
                    ]);
                }
            }

            $identitasPathWithoutQuery = parse_url($identitasPath, PHP_URL_PATH) ?: $identitasPath;

            $identitasExtension = strtolower(pathinfo($identitasPathWithoutQuery, PATHINFO_EXTENSION));

            $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'];

            $isIdentitasImage = $identitasUrl !== null && in_array($identitasExtension, $imageExtensions, true);

            /*
            |--------------------------------------------------------------------------
            | URL Detail
            |--------------------------------------------------------------------------
            */

            $showUrl = route('admin.permohonan.show', [
                'id' => $item->id,
            ]);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            {{-- Checkbox --}}

            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih permohonan ' . ($item->no_pemohon ?? $item->id)" />
            </td>

            {{-- Nomor urut --}}

            <td
                class="
                    whitespace-nowrap
                    px-4
                    py-4
                    text-sm
                    font-medium
                    text-gray-500
                    dark:text-gray-400
                    sm:px-6
                ">
                {{ $rowNumber }}
            </td>

            {{-- Nomor permohonan --}}

            <td class="px-4 py-4 sm:px-6">
                <a href="{{ $showUrl }}"
                    class="
                        group
                        block
                        rounded-xl
                        border
                        border-blue-100
                        bg-blue-50/70
                        px-4
                        py-3
                        transition
                        hover:border-blue-200
                        hover:bg-blue-100/70
                        dark:border-blue-500/20
                        dark:bg-blue-500/10
                        dark:hover:bg-blue-500/15
                    "
                    title="Lihat detail permohonan">
                    <div
                        class="
                            flex
                            items-start
                            justify-between
                            gap-2
                        ">
                        <p
                            class="
                                text-sm
                                font-semibold
                                text-blue-700
                                dark:text-blue-400
                            ">
                            {{ $item->no_pemohon ?? '-' }}
                        </p>

                        <i
                            class="
                                ri-arrow-right-up-line
                                shrink-0
                                text-blue-400
                                transition
                                group-hover:translate-x-0.5
                                group-hover:-translate-y-0.5
                            "></i>
                    </div>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-blue-500/70
                            dark:text-blue-400/70
                        ">
                        ID Data: {{ $item->id }}
                    </p>
                </a>
            </td>

            {{-- Pemohon --}}

            <td class="px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="min-w-0">
                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            {{ $applicantName }}
                        </p>

                        @if ($applicantEmail)
                            <p class="
                                    mt-1
                                    max-w-[220px]
                                    truncate
                                    text-xs
                                    text-gray-400
                                "
                                title="{{ $applicantEmail }}">
                                {{ $applicantEmail }}
                            </p>
                        @endif
                    </div>
                </div>
            </td>

            {{-- Gambar identitas --}}

            <td class="px-4 py-4 sm:px-6">
                @if ($isIdentitasImage)
                    <a href="{{ $identitasUrl }}" target="_blank" rel="noopener noreferrer"
                        class="
                            group
                            block
                            h-[120px]
                            w-[200px]
                            overflow-hidden
                            rounded-2xl
                            border
                            border-gray-200
                            bg-gray-100
                            shadow-theme-xs
                            transition
                            hover:border-brand-300
                            hover:ring-4
                            hover:ring-brand-500/10
                            dark:border-gray-700
                            dark:bg-gray-800
                        "
                        title="Buka gambar identitas {{ $applicantName }}">
                        <img src="{{ $identitasUrl }}" alt="Dokumen identitas {{ $applicantName }}" loading="lazy"
                            class="
                                h-full
                                w-full
                                object-cover
                                transition
                                duration-300
                                group-hover:scale-105
                            ">
                    </a>
                @elseif ($identitasUrl)
                    <a href="{{ $identitasUrl }}" target="_blank" rel="noopener noreferrer"
                        class="
                            group
                            flex
                            h-[120px]
                            w-[200px]
                            flex-col
                            items-center
                            justify-center
                            rounded-2xl
                            border
                            border-blue-200
                            bg-blue-50
                            px-4
                            text-center
                            transition
                            hover:border-blue-300
                            hover:bg-blue-100
                            dark:border-blue-500/20
                            dark:bg-blue-500/10
                            dark:hover:bg-blue-500/20
                        "
                        title="Buka dokumen identitas">
                        <span
                            class="
                                flex
                                h-11
                                w-11
                                items-center
                                justify-center
                                rounded-xl
                                bg-blue-100
                                text-blue-600
                                transition
                                group-hover:scale-105
                                dark:bg-blue-500/20
                                dark:text-blue-400
                            ">
                            @if ($identitasExtension === 'pdf')
                                <i class="ri-file-pdf-2-line text-2xl"></i>
                            @else
                                <i class="ri-file-text-line text-2xl"></i>
                            @endif
                        </span>

                        <span
                            class="
                                mt-2
                                text-xs
                                font-semibold
                                text-blue-700
                                dark:text-blue-400
                            ">
                            Buka Dokumen
                        </span>

                        @if ($identitasExtension !== '')
                            <span
                                class="
                                    mt-1
                                    text-[10px]
                                    uppercase
                                    text-blue-500/70
                                    dark:text-blue-400/70
                                ">
                                {{ $identitasExtension }}
                            </span>
                        @endif
                    </a>
                @else
                    <div
                        class="
                            flex
                            h-[120px]
                            w-[200px]
                            flex-col
                            items-center
                            justify-center
                            rounded-2xl
                            border
                            border-dashed
                            border-gray-300
                            bg-gray-50
                            px-4
                            text-center
                            text-gray-400
                            dark:border-gray-700
                            dark:bg-gray-900
                        ">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <span class="mt-2 text-xs">
                            Tidak ada identitas
                        </span>
                    </div>
                @endif
            </td>

            {{-- PPID Pembantu --}}

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-cyan-50
                        px-3
                        py-2
                        text-cyan-700
                        dark:bg-cyan-500/15
                        dark:text-cyan-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    <span class="line-clamp-2">
                        {{ $ppidName }}
                    </span>
                </div>
            </td>

            {{-- Tanggal --}}

            <td
                class="
                    whitespace-nowrap
                    px-4
                    py-4
                    text-sm
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                <span class="inline-flex items-center gap-2">
                    <span
                        class="
                            flex
                            h-8
                            w-8
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-gray-100
                            text-gray-500
                            dark:bg-gray-800
                            dark:text-gray-400
                        ">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </span>

                    {{ $formattedDate }}
                </span>
            </td>

            {{-- Rincian --}}

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    leading-7
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                <div
                    class="
                        rounded-xl
                        border
                        border-gray-100
                        bg-gray-50/70
                        px-4
                        py-3
                        dark:border-gray-800
                        dark:bg-gray-900/50
                    ">
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->rincian ?? '')), 220) ?: '-' }}
                </div>
            </td>

            {{-- Status --}}

            <td class="px-4 py-4 sm:px-6">
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
                        {{ $statusClass }}
                    ">
                    <span
                        class="
                            h-1.5
                            w-1.5
                            shrink-0
                            rounded-full
                            bg-current
                        "></span>

                    <span class="whitespace-nowrap">
                        {{ $status }}
                    </span>
                </span>
            </td>

            {{-- Action --}}

            <td
                class="
                    w-[130px]
                    min-w-[130px]
                    px-4
                    py-4
                    text-center
                    align-middle
                    sm:px-6
                ">
                <x-tables.row-actions :view-url="$showUrl" :view-label="'Lihat permohonan ' . ($item->no_pemohon ?? '')" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10"
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
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <h3
                    class="
                        mt-4
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Belum ada data permohonan
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Belum ada permohonan informasi yang dapat ditampilkan
                    atau data tidak sesuai dengan filter.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
