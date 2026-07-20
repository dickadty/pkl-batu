@props(['slider'])

@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan data
    |--------------------------------------------------------------------------
    */

    $isPaginated = $slider instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $slider->getCollection() : collect($slider);

    /*
    |--------------------------------------------------------------------------
    | ID baris untuk checkbox
    |--------------------------------------------------------------------------
    */

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | Nomor awal tabel
    |--------------------------------------------------------------------------
    */

    $firstNumber = $isPaginated ? $slider->firstItem() ?? 1 : 1;
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Slider"
    description="Kelola judul, banner, tanggal publikasi, dan tampilan slider pada halaman utama." :row-ids="$rowIds"
    :paginator="$isPaginated ? $slider : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" :pagination-window="1"
    min-width="min-w-[1250px]">
    {{-- ============================================================
        ACTION HEADER
    ============================================================= --}}

    <x-slot:headerActions>
        <a href="{{ route('admin.slider.create') }}"
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
                duration-200
                hover:bg-brand-600
                focus:outline-none
                focus:ring-3
                focus:ring-brand-500/20
            ">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            <span>Tambah Slider</span>
        </a>
    </x-slot:headerActions>

    {{-- ============================================================
        HEADER TABEL
    ============================================================= --}}

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
                min-w-[310px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Banner
        </th>

        <th scope="col"
            class="
                min-w-[370px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Judul Slider
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
            Tanggal
        </th>

        <th scope="col"
            class="
                w-[150px]
                min-w-[150px]
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

    {{-- ============================================================
        DATA TABEL
    ============================================================= --}}

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            /*
            |--------------------------------------------------------------------------
            | URL banner
            |--------------------------------------------------------------------------
            */

            $bannerUrl = null;

            if (!empty($item->banner)) {
                $bannerUrl = \Illuminate\Support\Str::startsWith($item->banner, ['http://', 'https://'])
                    ? $item->banner
                    : asset('storage/' . ltrim($item->banner, '/'));
            }

            /*
            |--------------------------------------------------------------------------
            | Tanggal slider
            |--------------------------------------------------------------------------
            */

            $formattedDate = '-';

            if ($item->tanggal !== null && $item->tanggal !== '') {
                try {
                    if (is_numeric($item->tanggal) && (int) $item->tanggal > 100000000) {
                        $formattedDate = \Illuminate\Support\Carbon::createFromTimestamp(
                            (int) $item->tanggal,
                        )->translatedFormat('d F Y, H:i');
                    } else {
                        $formattedDate = \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat(
                            'd F Y, H:i',
                        );
                    }
                } catch (\Throwable $exception) {
                    $formattedDate = (string) $item->tanggal;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | URL halaman show admin
            |--------------------------------------------------------------------------
            */

            $showUrl = route('admin.slider.show', $item->id);
        @endphp

        <tr
            class="
                group/row
                transition-colors
                duration-200
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            {{-- Checkbox --}}
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih slider ' . ($item->title ?? $item->id)" />
            </td>

            {{-- Nomor --}}
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

            {{-- Banner --}}
            <td class="px-4 py-4 sm:px-6">
                @if ($bannerUrl)
                    <a href="{{ $showUrl }}"
                        class="
                            group/banner
                            relative
                            block
                            h-[118px]
                            w-[260px]
                            overflow-hidden
                            rounded-2xl
                            border
                            border-gray-200
                            bg-gray-100
                            shadow-theme-xs
                            transition
                            duration-300
                            hover:border-blue-300
                            hover:shadow-md
                            focus:outline-none
                            focus:ring-3
                            focus:ring-blue-500/20
                            dark:border-gray-700
                            dark:bg-gray-800
                        "
                        title="Lihat detail {{ $item->title ?? 'slider' }}">
                        <img src="{{ $bannerUrl }}" alt="{{ $item->title ?? 'Banner slider' }}" loading="lazy"
                            class="
                                h-full
                                w-full
                                object-cover
                                object-center
                                transition
                                duration-500
                                group-hover/banner:scale-105
                            ">

                        <span
                            class="
                                absolute
                                inset-0
                                flex
                                items-center
                                justify-center
                                bg-black/0
                                opacity-0
                                transition
                                duration-300
                                group-hover/banner:bg-black/35
                                group-hover/banner:opacity-100
                            ">
                            <span
                                class="
                                    inline-flex
                                    h-10
                                    w-10
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-white/90
                                    text-blue-600
                                    shadow-lg
                                    backdrop-blur
                                ">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                        </span>
                    </a>
                @else
                    <a href="{{ $showUrl }}"
                        class="
                            group/banner
                            flex
                            h-[118px]
                            w-[260px]
                            flex-col
                            items-center
                            justify-center
                            rounded-2xl
                            border
                            border-dashed
                            border-gray-300
                            bg-gray-50
                            text-gray-400
                            transition
                            duration-200
                            hover:border-blue-300
                            hover:bg-blue-50
                            hover:text-blue-500
                            focus:outline-none
                            focus:ring-3
                            focus:ring-blue-500/20
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-gray-500
                            dark:hover:bg-blue-500/10
                            dark:hover:text-blue-400
                        "
                        title="Lihat detail {{ $item->title ?? 'slider' }}">
                        <svg class="
                                h-8
                                w-8
                                transition
                                group-hover/banner:scale-110
                            "
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <span class="mt-2 text-xs font-medium">
                            Banner tidak tersedia
                        </span>
                    </a>
                @endif
            </td>

            {{-- Judul Slider --}}
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
                            bg-gradient-to-br
                            from-blue-50
                            to-purple-50
                            text-blue-600
                            ring-1
                            ring-blue-100
                            dark:from-blue-500/15
                            dark:to-purple-500/15
                            dark:text-blue-400
                            dark:ring-blue-500/20
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        {{-- Judul dapat diklik menuju halaman show --}}
                        <a href="{{ $showUrl }}"
                            class="
                                group/title
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
                                duration-200
                                hover:text-blue-600
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500/20
                                dark:text-white/90
                                dark:hover:text-blue-400
                            "
                            title="Lihat detail {{ $item->title ?? 'slider' }}">
                            <span class="line-clamp-3">
                                {{ $item->title ?? '-' }}
                            </span>

                            <svg class="
                                    mt-1
                                    h-4
                                    w-4
                                    shrink-0
                                    text-gray-400
                                    transition
                                    duration-200
                                    group-hover/title:translate-x-1
                                    group-hover/title:text-blue-500
                                    dark:text-gray-500
                                "
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <div
                            class="
                                mt-2
                                flex
                                flex-wrap
                                items-center
                                gap-2
                            ">
                            <span
                                class="
                                    inline-flex
                                    items-center
                                    rounded-full
                                    bg-blue-50
                                    px-2.5
                                    py-1
                                    text-xs
                                    font-medium
                                    text-blue-700
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                Slider Utama
                            </span>

                            <span
                                class="
                                    text-xs
                                    text-gray-400
                                    dark:text-gray-500
                                ">
                                ID: {{ $item->id }}
                            </span>
                        </div>
                    </div>
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
                <div class="inline-flex items-center gap-3">
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
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </span>

                    <div>
                        <p
                            class="
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            ">
                            {{ $formattedDate }}
                        </p>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            Tanggal publikasi
                        </p>
                    </div>
                </div>
            </td>

            {{-- Action --}}
            <td
                class="
                    w-[150px]
                    min-w-[150px]
                    px-4
                    py-4
                    text-center
                    align-middle
                    sm:px-6
                ">
                <x-tables.row-actions :edit-url="route('admin.slider.edit', $item->id)" :delete-url="route('admin.slider.destroy', $item->id)" :edit-label="'Edit slider ' . ($item->title ?? '')" :delete-label="'Hapus slider ' . ($item->title ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus slider ini?" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6"
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
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                    Belum ada data slider
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan slider baru untuk ditampilkan pada halaman utama.
                </p>

                <a href="{{ route('admin.slider.create') }}"
                    class="
                        mt-5
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-4
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-brand-600
                    ">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Slider
                </a>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
