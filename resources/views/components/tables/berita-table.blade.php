@props(['berita'])

@php
    $isPaginated = $berita instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $berita->getCollection() : collect($berita);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $berita->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Berita"
    description="Kelola gambar utama, judul, isi publikasi, tanggal berita, dan data yang ditampilkan kepada masyarakat."
    :row-ids="$rowIds" :paginator="$isPaginated ? $berita : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1500px]">
    <x-slot:filter>
        <form action="{{ route('admin.berita.index') }}" method="GET" class="space-y-5">
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
                    Filter Berita
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari berita berdasarkan judul atau isi publikasi.
                </p>
            </div>

            <div>
                <label for="berita_q"
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

                    <input id="berita_q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari judul atau isi berita" autocomplete="off"
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

            <div>
                <label for="berita_per_page"
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

                <select id="berita_per_page" name="per_page"
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
                <a href="{{ route('admin.berita.index') }}"
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

        <a href="{{ route('admin.berita.create') }}"
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

            <span>Tambah Berita</span>
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
            Gambar
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
            Judul Berita
        </th>

        <th scope="col"
            class="
                min-w-[460px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Isi Singkat
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

            $imageUrl = null;

            if (!empty($item->gambar)) {
                $imageUrl = \Illuminate\Support\Str::startsWith($item->gambar, ['http://', 'https://'])
                    ? $item->gambar
                    : asset('storage/' . ltrim($item->gambar, '/'));
            }

            $plainCaption = trim(strip_tags((string) ($item->caption ?? '')));

            $formattedDate = '-';

            if (!empty($item->tanggal)) {
                try {
                    if (is_numeric($item->tanggal) && (int) $item->tanggal > 100000000) {
                        $formattedDate = \Illuminate\Support\Carbon::createFromTimestamp(
                            (int) $item->tanggal,
                        )->translatedFormat('d F Y');
                    } else {
                        $formattedDate = \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                    }
                } catch (\Throwable $exception) {
                    $formattedDate = (string) $item->tanggal;
                }
            }

            $showUrl = route('admin.berita.show', $item->id);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih berita ' . ($item->judul ?? $item->id)" />
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
                @if ($imageUrl)
                    <div
                        class="
                            h-[100px]
                            w-[200px]
                            overflow-hidden
                            rounded-2xl
                            border
                            border-gray-200
                            bg-gray-100
                            shadow-theme-xs
                            dark:border-gray-700
                            dark:bg-gray-800
                        ">
                        <img src="{{ $imageUrl }}" alt="{{ $item->judul ?? 'Gambar berita' }}" loading="lazy"
                            class="
                                h-full
                                w-full
                                object-cover
                                transition
                                duration-300
                                hover:scale-105
                            ">
                    </div>
                @else
                    <div
                        class="
                            flex
                            h-[100px]
                            w-[200px]
                            flex-col
                            items-center
                            justify-center
                            rounded-2xl
                            border
                            border-dashed
                            border-gray-300
                            bg-gray-50
                            text-gray-400
                            dark:border-gray-700
                            dark:bg-gray-900
                        ">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <span class="mt-1 text-xs">
                            Tidak ada gambar
                        </span>
                    </div>
                @endif
            </td>

            <td class="px-4 py-4 sm:px-6">
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
                            bg-orange-50
                            text-orange-600
                            dark:bg-orange-500/15
                            dark:text-orange-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM7 8h10M7 12h10M7 16h6" />
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
                                hover:text-orange-600
                                focus:outline-none
                                focus:ring-2
                                focus:ring-orange-500/20
                                dark:text-white/90
                                dark:hover:text-orange-400
                            "
                            title="Lihat detail {{ $item->judul ?? 'berita' }}">
                            <span class="line-clamp-3">
                                {{ $item->judul ?? '-' }}
                            </span>

                            <svg class="
                                    mt-1
                                    h-4
                                    w-4
                                    shrink-0
                                    text-gray-400
                                    transition
                                    group-hover:translate-x-0.5
                                    group-hover:text-orange-500
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
                            ID Berita: {{ $item->id }}
                        </p>
                    </div>
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
                    {{ $plainCaption !== '' ? \Illuminate\Support\Str::limit($plainCaption, 210) : '-' }}
                </div>
            </td>

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
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 00-2 2V7a2 2 0 012-2z" />
                        </svg>
                    </span>

                    {{ $formattedDate }}
                </span>
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
                <x-tables.row-actions :delete-url="route('admin.berita.destroy', $item->id)" :delete-label="'Hapus berita ' . ($item->judul ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus berita ini?" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7"
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
                        bg-orange-50
                        text-orange-500
                        dark:bg-orange-500/15
                        dark:text-orange-400
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM7 8h10M7 12h10M7 16h6" />
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
                    Belum ada data berita
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan berita baru atau ubah filter pencarian.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
