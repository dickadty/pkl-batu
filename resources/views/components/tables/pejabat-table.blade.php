@props(['pejabat'])

@php
    $isPaginated = $pejabat instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $pejabat->getCollection() : collect($pejabat);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $pejabat->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Pejabat"
    description="Kelola profil pejabat, jabatan, masa jabatan, data kelahiran, kontak, alamat, dan foto resmi."
    :row-ids="$rowIds" :paginator="$isPaginated ? $pejabat : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1750px]">
    <x-slot:filter>
        <form action="{{ route('admin.pejabat.index') }}" method="GET" class="space-y-5">
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
                    Filter Pejabat
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari pejabat berdasarkan nama, jabatan, masa jabatan, kelahiran, alamat, atau nomor telepon.
                </p>
            </div>

            <div>
                <label for="pejabat_q"
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

                <input id="pejabat_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama, jabatan, atau nomor telepon" autocomplete="off"
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
                <label for="pejabat_per_page"
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

                <select id="pejabat_per_page" name="per_page"
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
                <a href="{{ route('admin.pejabat.index') }}"
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

        <a href="{{ route('admin.pejabat.create') }}"
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

            <span>Tambah Pejabat</span>
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
            Foto
        </th>

        <th scope="col"
            class="
                min-w-[270px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Nama Pejabat
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
            Jabatan
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
            Masa Jabatan
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
            Tempat/Tanggal Lahir
        </th>

        <th scope="col"
            class="
                min-w-[200px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            No. Telepon
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

            $photoUrl = null;

            if (!empty($item->foto)) {
                $photoUrl = \Illuminate\Support\Str::startsWith($item->foto, ['http://', 'https://'])
                    ? $item->foto
                    : asset('storage/' . ltrim($item->foto, '/'));
            }

            $initial = !empty($item->nama) ? mb_strtoupper(mb_substr($item->nama, 0, 1)) : 'P';

            $telephoneUrl = !empty($item->no_telp) ? preg_replace('/[^0-9+]/', '', (string) $item->no_telp) : null;

            $showUrl = route('admin.pejabat.show', $item->id);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih pejabat ' . ($item->nama ?? $item->id)" />
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
                @if ($photoUrl)
                    <div
                        class="
                            h-[76px]
                            w-[76px]
                            overflow-hidden
                            rounded-2xl
                            border
                            border-gray-200
                            bg-gray-100
                            shadow-theme-xs
                            dark:border-gray-700
                            dark:bg-gray-800
                        ">
                        <img src="{{ $photoUrl }}" alt="{{ $item->nama ?? 'Foto pejabat' }}" loading="lazy"
                            class="
                                h-full
                                w-full
                                object-cover
                                object-center
                            ">
                    </div>
                @else
                    <div
                        class="
                            flex
                            h-[76px]
                            w-[76px]
                            items-center
                            justify-center
                            rounded-2xl
                            bg-gradient-to-br
                            from-blue-50
                            to-purple-50
                            text-xl
                            font-bold
                            text-blue-600
                            dark:from-blue-500/15
                            dark:to-purple-500/15
                            dark:text-blue-400
                        ">
                        {{ $initial }}
                    </div>
                @endif
            </td>

            <td class="px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-blue-50
                            text-blue-600
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9.004 9.004 0 0112 15c2.21 0 4.23.796 5.879 2.117M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
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
                                hover:text-purple-600
                                focus:outline-none
                                focus:ring-2
                                focus:ring-purple-500/20
                                dark:text-white/90
                                dark:hover:text-purple-400
                            "
                            title="Lihat detail {{ $item->nama ?? 'pejabat' }}">
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
                                    group-hover:text-purple-500
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
                            ID Pejabat: {{ $item->id }}
                        </p>
                    </div>
                </div>
            </td>

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    leading-6
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                <div
                    class="
                        inline-flex
                        items-start
                        gap-2
                        rounded-xl
                        bg-purple-50
                        px-3
                        py-2
                        text-purple-700
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    <svg class="
                            mt-0.5
                            h-4
                            w-4
                            shrink-0
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.18 0-6.21-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    <span>
                        {{ $item->jabatan ?? '-' }}
                    </span>
                </div>
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
                    <svg class="
                            h-4
                            w-4
                            shrink-0
                            text-gray-400
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                    {{ $item->masa ?? '-' }}
                </span>
            </td>

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    leading-6
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
                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                    <span>
                        {{ $item->tmp_tgl_lahir ?? '-' }}
                    </span>
                </div>
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
                            bg-white
                            px-3
                            py-2
                            transition
                            hover:border-blue-300
                            hover:bg-blue-50
                            hover:text-blue-600
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:hover:bg-blue-500/15
                        ">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>

                        {{ $item->no_telp }}
                    </a>
                @else
                    <span class="text-gray-400">
                        -
                    </span>
                @endif
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
                <x-tables.row-actions :edit-url="route('admin.pejabat.edit', $item->id)" :delete-url="route('admin.pejabat.destroy', $item->id)" :edit-label="'Edit pejabat ' . ($item->nama ?? '')" :delete-label="'Hapus pejabat ' . ($item->nama ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus data pejabat ini?" />
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
                        bg-purple-50
                        text-purple-500
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
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
                    Belum ada data pejabat
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan pejabat baru atau ubah filter pencarian.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
