@props(['ppidPembantu', 'kategoriPpidList' => []])

@php
    $isPaginated = $ppidPembantu instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $ppidPembantu->getCollection() : collect($ppidPembantu);

    $kategoriList = collect($kategoriPpidList ?? []);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $ppidPembantu->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q'), request('kategori_ppidid')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data PPID Pembantu"
    description="Kelola profil, kategori, keterangan, website, kontak, alamat, dan identitas unit PPID Pembantu."
    :row-ids="$rowIds" :paginator="$isPaginated ? $ppidPembantu : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1800px]">
    <x-slot:filter>
        <form action="{{ route('admin.ppid-pembantu.index') }}" method="GET" class="space-y-5">
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
                    Filter PPID Pembantu
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari unit berdasarkan nama, kategori, keterangan, website, telepon, atau alamat.
                </p>
            </div>

            <div>
                <label for="ppid_q"
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

                <input id="ppid_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama atau informasi PPID" autocomplete="off"
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

            @if ($kategoriList->isNotEmpty())
                <div>
                    <label for="kategori_ppidid"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-300
                        ">
                        Kategori PPID
                    </label>

                    <select id="kategori_ppidid" name="kategori_ppidid"
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
                            Semua Kategori
                        </option>

                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) request('kategori_ppidid') === (string) $kategori->id)>
                                {{ $kategori->kategori ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label for="ppid_per_page"
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

                <select id="ppid_per_page" name="per_page"
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
                ">
                <a href="{{ route('admin.ppid-pembantu.index') }}"
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

        <a href="{{ route('admin.ppid-pembantu.create') }}"
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

            <span>Tambah PPID</span>
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
                min-w-[290px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Nama PPID
        </th>

        <th scope="col"
            class="
                min-w-[190px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Kategori
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
            Keterangan
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
            Website
        </th>

        <th scope="col"
            class="
                min-w-[190px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Telepon
        </th>

        <th scope="col"
            class="
                min-w-[340px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Alamat
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

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            $categoryName =
                data_get($item, 'kategoriPpid.kategori') ?? (data_get($item, 'kategori_ppid.kategori') ?? '-');

            $websiteUrl = null;

            if (!empty($item->linkweb)) {
                $websiteUrl = \Illuminate\Support\Str::startsWith($item->linkweb, ['http://', 'https://'])
                    ? $item->linkweb
                    : 'https://' . ltrim($item->linkweb, '/');
            }

            $telephoneUrl = !empty($item->telp) ? preg_replace('/[^0-9+]/', '', (string) $item->telp) : null;

            $initial = !empty($item->nama) ? mb_strtoupper(mb_substr($item->nama, 0, 1)) : 'P';

            $showUrl = route('admin.ppid-pembantu.show', $item->id);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih PPID ' . ($item->nama ?? $item->id)" />
            </td>

            <td
                class="
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
                <div class="flex items-center gap-3">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-gradient-to-br
                            from-blue-50
                            to-cyan-50
                            font-bold
                            text-blue-600
                            dark:from-blue-500/15
                            dark:to-cyan-500/15
                            dark:text-blue-400
                        ">
                        {{ $initial }}
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
                                hover:text-blue-600
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500/20
                                dark:text-white/90
                                dark:hover:text-blue-400
                            "
                            title="Lihat detail {{ $item->nama ?? 'PPID' }}">
                            <span class="line-clamp-2">
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
                                    group-hover:text-blue-500
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
                            ID PPID: {{ $item->id }}
                        </p>
                    </div>
                </div>
            </td>

            <td class="px-4 py-4 sm:px-6">
                <span
                    class="
                        inline-flex
                        rounded-full
                        bg-purple-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-purple-700
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    {{ $categoryName }}
                </span>
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
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->keterangan ?? '')), 170) ?: '-' }}
                </div>
            </td>

            <td class="px-4 py-4 text-sm sm:px-6">
                @if ($websiteUrl)
                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer"
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
                            text-blue-600
                            transition
                            hover:border-blue-300
                            hover:bg-blue-50
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-blue-400
                            dark:hover:bg-blue-500/15
                        ">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 015.656 5.656l-3 3a4 4 0 01-5.656 0M10.172 13.828a4 4 0 01-5.656-5.656l3-3a4 4 0 015.656 0" />
                        </svg>

                        <span class="max-w-[180px] truncate">
                            {{ $item->linkweb }}
                        </span>
                    </a>
                @else
                    <span class="text-gray-400">
                        -
                    </span>
                @endif
            </td>

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                @if ($telephoneUrl)
                    <a href="tel:{{ $telephoneUrl }}"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-lg
                            border
                            border-gray-200
                            px-3
                            py-2
                            transition
                            hover:bg-blue-50
                            hover:text-blue-600
                            dark:border-gray-700
                            dark:hover:bg-blue-500/15
                        ">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>

                        {{ $item->telp }}
                    </a>
                @else
                    <span class="text-gray-400">
                        -
                    </span>
                @endif
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
                <div class="flex items-start gap-2">
                    <svg class="
                            mt-1
                            h-4
                            w-4
                            shrink-0
                            text-gray-400
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <span>
                        {{ $item->alamat ?? '-' }}
                    </span>
                </div>
            </td>

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
                <x-tables.row-actions :edit-url="route('admin.ppid-pembantu.edit', $item->id)" :delete-url="route('admin.ppid-pembantu.destroy', $item->id)" :edit-label="'Edit PPID ' . ($item->nama ?? '')" :delete-label="'Hapus PPID ' . ($item->nama ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus PPID Pembantu ini?" />
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
                        bg-blue-50
                        text-blue-500
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
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
                    Belum ada data PPID Pembantu
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan unit PPID baru atau ubah filter pencarian.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
