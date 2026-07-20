@props(['dokumentasi', 'admin', 'ppidPembantuList' => []])

@php
    $isPaginated = $dokumentasi instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $dokumentasi->getCollection() : collect($dokumentasi);

    $ppidList = collect($ppidPembantuList ?? []);

    $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $dokumentasi->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([
        request('q'),
        request('status'),
        request('sifat'),
        request('tahun'),
        request('ppid_pembantuid'),
    ])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();

    $sifatOptions = [
        'setiap saat' => 'Setiap Saat',
        'berkala' => 'Berkala',
        'serta merta' => 'Serta Merta',
        'dikecualikan' => 'Dikecualikan',
    ];
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Informasi Publik"
    description="Kelola dokumen, klasifikasi, tahun, unit PPID, ringkasan, status verifikasi, dan file informasi publik."
    :row-ids="$rowIds" :paginator="$isPaginated ? $dokumentasi : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1940px]">
    <x-slot:filter>
        <form action="{{ route('admin.informasi-publik.index') }}" method="GET"
            class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div
                class="
                    border-b
                    border-gray-100
                    pb-3
                    dark:border-gray-800
                    md:col-span-2
                ">
                <h4
                    class="
                        text-sm
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Filter Informasi Publik
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Filter dokumen berdasarkan nama, status, klasifikasi, tahun, dan unit PPID Pembantu.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="informasi_q"
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

                <input id="informasi_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama atau ringkasan informasi" autocomplete="off"
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
                        placeholder:text-gray-400
                        focus:border-brand-300
                        focus:ring-3
                        focus:ring-brand-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
            </div>

            <div>
                <label for="informasi_status"
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

                <select id="informasi_status" name="status"
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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
                    <option value="">
                        Semua Status
                    </option>

                    <option value="1" @selected((string) request('status') === '1')>
                        Terverifikasi
                    </option>

                    <option value="0" @selected((string) request('status') === '0')>
                        Belum Diverifikasi
                    </option>
                </select>
            </div>

            <div>
                <label for="informasi_sifat"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Sifat Informasi
                </label>

                <select id="informasi_sifat" name="sifat"
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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
                    <option value="">
                        Semua Sifat
                    </option>

                    @foreach ($sifatOptions as $value => $label)
                        <option value="{{ $value }}" @selected(strtolower((string) request('sifat')) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="informasi_tahun"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Tahun
                </label>

                <input id="informasi_tahun" type="number" name="tahun" value="{{ request('tahun') }}" min="2000"
                    max="2100" placeholder="Contoh: {{ now()->year }}"
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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
            </div>

            @if ($isAdminUtama && $ppidList->isNotEmpty())
                <div>
                    <label for="informasi_ppid"
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

                    <select id="informasi_ppid" name="ppid_pembantuid"
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

            <div>
                <label for="informasi_per_page"
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

                <select id="informasi_per_page" name="per_page"
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
                    md:col-span-2
                ">
                <a href="{{ route('admin.informasi-publik.index') }}"
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

        <a href="{{ route('admin.informasi-publik.create') }}"
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
            ">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            <span>Tambah Informasi</span>
        </a>
    </x-slot:headerActions>

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
                min-w-[360px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Nama Informasi
        </th>

        <th scope="col"
            class="
                min-w-[130px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Tahun
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
            Sifat
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
                min-w-[420px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Ringkasan
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
            Status
        </th>

        <th scope="col"
            class="
                w-[230px]
                min-w-[230px]
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

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            $ppidName = data_get($item, 'ppidPembantu.nama') ?? (data_get($item, 'ppid_pembantu.nama') ?? '-');

            $showUrl = route('admin.informasi-publik.show', $item->id);

            $rawStatus = data_get($item, 'status_label', data_get($item, 'status', 'Belum Diverifikasi'));

            $normalizedStatus = strtolower(trim((string) $rawStatus));

            $isVerified = in_array($normalizedStatus, ['1', 'verified', 'terverifikasi', 'disetujui', 'aktif'], true);

            $statusLabel = $isVerified
                ? (in_array($normalizedStatus, ['1', 'verified'], true)
                    ? 'Terverifikasi'
                    : (string) $rawStatus)
                : (in_array($normalizedStatus, ['', '0'], true)
                    ? 'Belum Diverifikasi'
                    : (string) $rawStatus);

            $verifyUrl = $isAdminUtama && !$isVerified ? route('admin.informasi-publik.verifikasi', $item->id) : null;

            $sifatKey = strtolower(trim((string) ($item->sifat ?? '')));

            $sifatClass = match ($sifatKey) {
                'berkala' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                'serta merta' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

                'setiap saat' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                'dikecualikan' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
            };
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih informasi ' . ($item->nama ?? $item->id)" />
            </td>

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

            <td class="px-4 py-4 sm:px-6">
                <div class="flex items-start gap-3">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-cyan-50
                            text-cyan-600
                            dark:bg-cyan-500/15
                            dark:text-cyan-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <a href="{{ $showUrl }}"
                            class="
                                group
                                inline-flex
                                max-w-full
                                items-start
                                gap-2
                                rounded-md
                                text-sm
                                font-semibold
                                leading-6
                                text-gray-800
                                transition
                                hover:text-brand-600
                                focus:outline-none
                                focus:ring-2
                                focus:ring-brand-500/20
                                dark:text-white/90
                                dark:hover:text-brand-400
                            "
                            title="Lihat detail {{ $item->nama ?? 'informasi' }}">
                            <span class="line-clamp-3">
                                {{ $item->nama ?? '-' }}
                            </span>

                            <svg class="
                                    mt-1
                                    h-4
                                    w-4
                                    shrink-0
                                    text-gray-400
                                    transition
                                    group-hover:translate-x-0.5
                                    group-hover:text-brand-500
                                "
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            ID Informasi: {{ $item->id }}
                        </p>
                    </div>
                </div>
            </td>

            <td class="px-4 py-4 text-sm sm:px-6">
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-3
                        py-2
                        font-medium
                        text-gray-600
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-400
                    ">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                    {{ $item->tahun ?? '-' }}
                </span>
            </td>

            <td class="px-4 py-4 sm:px-6">
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
                    {{ $item->sifat ? \Illuminate\Support\Str::title($item->sifat) : '-' }}
                </span>
            </td>

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
                        font-medium
                        text-purple-700
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    {{ $ppidName }}
                </div>
            </td>

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
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->ringkasan ?? '')), 210) ?: '-' }}
                </div>
            </td>

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
                        {{ $isVerified
                            ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                            : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400' }}
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
            </td>

            <td
                class="
                    w-[230px]
                    min-w-[230px]
                    px-4
                    py-4
                    text-center
                    align-middle
                    sm:px-6
                ">
                <x-tables.row-actions :download-url="route('public.informasi.download', $item->id)" :edit-url="route('admin.informasi-publik.edit', $item->id)" :verify-url="$verifyUrl" :delete-url="route('admin.informasi-publik.destroy', $item->id)"
                    :download-label="'Unduh informasi ' . ($item->nama ?? '')" :edit-label="'Edit informasi ' . ($item->nama ?? '')" :verify-label="'Verifikasi informasi ' . ($item->nama ?? '')" :delete-label="'Hapus informasi ' . ($item->nama ?? '')"
                    verify-confirmation="Apakah Anda yakin ingin memverifikasi informasi ini?"
                    delete-confirmation="Apakah Anda yakin ingin menghapus informasi publik ini?" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9"
                class="
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
                        bg-cyan-50
                        text-cyan-500
                        dark:bg-cyan-500/15
                        dark:text-cyan-400
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <h3
                    class="
                        mt-3
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Belum ada data informasi publik
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan dokumen baru atau ubah filter pencarian.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
