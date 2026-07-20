@props(['pengadaanList'])

@php
    $isPaginated = $pengadaanList instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $pengadaanList->getCollection() : collect($pengadaanList);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $pengadaanList->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Pengadaan"
    description="Kelola nama paket, nilai pagu, sumber dana, metode pengadaan, dan unit PPID Pembantu." :row-ids="$rowIds"
    :paginator="$isPaginated ? $pengadaanList : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" :pagination-window="1"
    min-width="min-w-[1450px]">
    <x-slot:filter>
        <form action="{{ route('admin.pengadaan.index') }}" method="GET" class="space-y-5">
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
                    Filter Pengadaan
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari paket berdasarkan nama, sumber dana, metode pengadaan, atau PPID Pembantu.
                </p>
            </div>

            <div>
                <label for="pengadaan_q"
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

                <input id="pengadaan_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari paket, metode, atau PPID" autocomplete="off"
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
                <label for="pengadaan_per_page"
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

                <select id="pengadaan_per_page" name="per_page"
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
                <a href="{{ route('admin.pengadaan.index') }}"
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

        <a href="{{ route('admin.pengadaan.create') }}"
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

            <span>Tambah Pengadaan</span>
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
            Nama Paket
        </th>

        <th scope="col"
            class="
                min-w-[210px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Pagu
        </th>

        <th scope="col"
            class="
                min-w-[210px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Sumber Dana
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
            Metode
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
            PPID Pembantu
        </th>

        <th scope="col"
            class="
                w-[170px]
                min-w-[170px]
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

            $namaPaket = $item->nama_paket ?? '-';

            $pagu = $item->pagu_rupiah ?? '-';

            $sumberDana = $item->sumber_dana ?? '-';

            $metode = $item->metode ?? '-';

            $ppidPembantu = data_get($item, 'ppidPembantu.nama') ?? (data_get($item, 'ppid_pembantu.nama') ?? '-');

            $initial = !empty($namaPaket) ? mb_strtoupper(mb_substr($namaPaket, 0, 1)) : 'P';

            $showUrl = route('admin.pengadaan.show', $item->id);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih pengadaan ' . $namaPaket" />
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
                        <a href="{{ $showUrl }}" title="Lihat detail {{ $namaPaket }}"
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
                            ">
                            <span class="line-clamp-2">
                                {{ $namaPaket }}
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
                            ID Pengadaan: {{ $item->id }}
                        </p>
                    </div>
                </div>
            </td>

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    font-semibold
                    text-gray-800
                    dark:text-white/90
                    sm:px-6
                ">
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        whitespace-nowrap
                        rounded-xl
                        bg-green-50
                        px-3
                        py-2
                        text-green-700
                        dark:bg-green-500/15
                        dark:text-green-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m-7-6a7 7 0 1114 0 7 7 0 01-14 0z" />
                    </svg>

                    {{ $pagu }}
                </span>
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
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-200
                        bg-gray-50
                        px-3
                        py-2
                        dark:border-gray-700
                        dark:bg-gray-800
                    ">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m8-6a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>

                    {{ $sumberDana }}
                </span>
            </td>

            <td class="px-4 py-4 sm:px-6">
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-blue-50
                        px-3
                        py-2
                        text-sm
                        font-medium
                        text-blue-700
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>

                    {{ $metode }}
                </span>
            </td>

            <td class="px-4 py-4 sm:px-6">
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
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
                    <i class="ri-government-line"></i>

                    {{ $ppidPembantu }}
                </span>
            </td>

            <td
                class="
                    w-[170px]
                    min-w-[170px]
                    px-4
                    py-4
                    text-center
                    align-middle
                    sm:px-6
                ">
                <x-tables.row-actions :view-url="route('admin.pengadaan.show', $item->id)" :edit-url="route('admin.pengadaan.edit', $item->id)" :delete-url="route('admin.pengadaan.destroy', $item->id)" :view-label="'Lihat pengadaan ' . $namaPaket"
                    :edit-label="'Edit pengadaan ' . $namaPaket" :delete-label="'Hapus pengadaan ' . $namaPaket"
                    delete-confirmation="Apakah Anda yakin ingin menghapus data pengadaan ini?" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8"
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
                    <i class="ri-shopping-bag-3-line text-2xl"></i>
                </div>

                <h3
                    class="
                        mt-3
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Belum ada data pengadaan
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan paket pengadaan baru atau ubah filter pencarian.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
