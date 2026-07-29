@props(['keberatan', 'ppidPembantuList' => [], 'statusOptions' => []])

@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan data
    |--------------------------------------------------------------------------
    */

    $isPaginated = $keberatan instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $keberatan->getCollection() : collect($keberatan);

    $ppidList = collect($ppidPembantuList ?? []);

    $statuses = collect($statusOptions ?? []);

    if ($statuses->isEmpty()) {
        $statuses = collect([
            'Diajukan' => 'Diajukan',
            'Diproses' => 'Diproses',
            'Selesai' => 'Selesai',
            'Ditolak' => 'Ditolak',
        ]);
    }

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id): bool => $id !== null && $id !== '')
        ->map(fn($id): string => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $keberatan->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q'), request('status'), request('ppid_pembantuid')])
        ->filter(fn($value): bool => $value !== null && $value !== '' && $value !== 'semua')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Keberatan"
    description="Pantau nomor keberatan, pemohon, permohonan terkait, unit PPID, alasan, status, dan proses penyelesaian keberatan."
    :row-ids="$rowIds" :paginator="$isPaginated ? $keberatan : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1950px]">
    {{-- ================================================================
        FILTER
    ================================================================= --}}

    <x-slot:filter>
        <form action="{{ route('admin.keberatan.index') }}" method="GET" class="space-y-5">
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
                    Filter Keberatan
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari berdasarkan nomor keberatan, nomor permohonan,
                    identitas pemohon, unit PPID, alasan, atau status.
                </p>
            </div>

            {{-- Pencarian --}}

            <div>
                <label for="keberatan_q"
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
                        <i
                            class="
                                ri-search-line
                                text-lg
                            "></i>
                    </span>

                    <input id="keberatan_q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari nomor, pemohon, atau alasan" autocomplete="off"
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
                <label for="keberatan_status"
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

                <select id="keberatan_status" name="status"
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

                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected((string) request('status') === (string) $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PPID Pembantu --}}

            @if ($ppidList->isNotEmpty())
                <div>
                    <label for="keberatan_ppid"
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

                    <select id="keberatan_ppid" name="ppid_pembantuid"
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
                <label for="keberatan_per_page"
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

                <select id="keberatan_per_page" name="per_page"
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
                <a href="{{ route('admin.keberatan.index') }}"
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
        <th
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

        <th
            class="
                min-w-[240px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            No. Keberatan
        </th>

        <th
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

        <th
            class="
                min-w-[240px]
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

        <th
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

        <th
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

        <th
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
            Alasan Keberatan
        </th>

        <th
            class="
                min-w-[150px]
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

        <th
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
            $rowNumber = $firstNumber + $index;

            /*
            |--------------------------------------------------------------------------
            | Pemohon
            |--------------------------------------------------------------------------
            */

            $applicantName =
                data_get($item, 'permohonan.userPublic.nama') ?? (data_get($item, 'permohonan.nama_pemohon') ?? '-');

            $applicantEmail =
                data_get($item, 'permohonan.userPublic.email') ?? (data_get($item, 'permohonan.email_pemohon') ?? null);

            /*
            |--------------------------------------------------------------------------
            | Permohonan dan PPID
            |--------------------------------------------------------------------------
            */

            $nomorPermohonan = data_get($item, 'permohonan.no_pemohon') ?? '-';

            $ppidName = data_get($item, 'permohonan.ppidPembantu.nama') ?? '-';

            /*
            |--------------------------------------------------------------------------
            | Tanggal
            |--------------------------------------------------------------------------
            */

            $formattedDate = '-';

            if (!empty($item->tanggal_pengajuan)) {
                try {
                    $formattedDate = \Illuminate\Support\Carbon::parse($item->tanggal_pengajuan)
                        ->locale('id')
                        ->translatedFormat('d F Y');
                } catch (\Throwable $exception) {
                    $formattedDate = (string) $item->tanggal_pengajuan;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $status = trim((string) ($item->status ?? 'Diajukan'));

            $statusClass = match (mb_strtolower($status)) {
                'diajukan' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                'diproses' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

                'selesai' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            };

            $showUrl = route('admin.keberatan.show', [
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
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih keberatan ' . ($item->no_keberatan ?? $item->id)" />
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

            {{-- Nomor keberatan --}}

            <td class="px-4 py-4 sm:px-6">
                <a href="{{ $showUrl }}"
                    class="
                        group
                        block
                        rounded-xl
                        border
                        border-red-100
                        bg-red-50/70
                        px-4
                        py-3
                        transition
                        hover:border-red-200
                        hover:bg-red-100/70
                        dark:border-red-500/20
                        dark:bg-red-500/10
                        dark:hover:bg-red-500/15
                    ">
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
                                text-red-700
                                dark:text-red-400
                            ">
                            {{ $item->no_keberatan ?? '-' }}
                        </p>

                        <i
                            class="
                                ri-arrow-right-up-line
                                shrink-0
                                text-red-400
                                transition
                                group-hover:translate-x-0.5
                                group-hover:-translate-y-0.5
                            "></i>
                    </div>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-red-500/70
                            dark:text-red-400/70
                        ">
                        ID Data: {{ $item->id }}
                    </p>
                </a>
            </td>

            {{-- Pemohon --}}

            <td class="px-4 py-4 sm:px-6">
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
                                max-w-[250px]
                                truncate
                                text-xs
                                text-gray-400
                            "
                            title="{{ $applicantEmail }}">
                            {{ $applicantEmail }}
                        </p>
                    @endif
                </div>
            </td>

            {{-- Nomor permohonan --}}

            <td class="px-4 py-4 sm:px-6">
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-purple-50
                        px-3
                        py-2
                        text-sm
                        font-semibold
                        text-purple-700
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    <i class="ri-file-list-3-line"></i>

                    {{ $nomorPermohonan }}
                </div>
            </td>

            {{-- PPID Pembantu --}}

            <td class="px-4 py-4 sm:px-6">
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-cyan-50
                        px-3
                        py-2
                        text-sm
                        text-cyan-700
                        dark:bg-cyan-500/15
                        dark:text-cyan-400
                    ">
                    <i class="ri-government-line"></i>

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
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                    ">
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
                        <i class="ri-calendar-line"></i>
                    </span>

                    {{ $formattedDate }}
                </span>
            </td>

            {{-- Alasan --}}

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
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->alasan ?? '')), 220) ?: '-' }}
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
                <x-tables.row-actions :view-url="$showUrl" :view-label="'Lihat keberatan ' . ($item->no_keberatan ?? '')" />
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
                        bg-red-50
                        text-red-500
                        dark:bg-red-500/15
                        dark:text-red-400
                    ">
                    <i
                        class="
                            ri-file-warning-line
                            text-3xl
                        "></i>
                </div>

                <h3
                    class="
                        mt-4
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Belum ada data keberatan
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Belum ada keberatan yang dapat ditampilkan
                    atau data tidak sesuai dengan filter.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
