@props(['faq'])

@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan data
    |--------------------------------------------------------------------------
    */

    $isPaginated = $faq instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $faq->getCollection() : collect($faq);

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

    $firstNumber = $isPaginated ? $faq->firstItem() ?? 1 : 1;
@endphp

<x-tables.basic-tables.basic-tables-two title="Data FAQ"
    description="Kelola pertanyaan, jawaban, tanggal, status publikasi, dan informasi FAQ untuk masyarakat."
    :row-ids="$rowIds" :paginator="$isPaginated ? $faq : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1450px]">
    {{-- ============================================================
        ACTION HEADER
    ============================================================= --}}

    <x-slot:headerActions>
        <a href="{{ route('admin.faq.create') }}"
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

            <span>Tambah FAQ</span>
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
                min-w-[380px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Pertanyaan
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
            Jawaban
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
            Tanggal
        </th>

        <th scope="col"
            class="
                min-w-[140px]
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
            | Jawaban tanpa HTML
            |--------------------------------------------------------------------------
            */

            $plainAnswer = trim(strip_tags((string) ($item->jawaban ?? '')));

            /*
            |--------------------------------------------------------------------------
            | Tanggal FAQ
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
            | Status FAQ
            |--------------------------------------------------------------------------
            */

            $isActive = (int) data_get($item, 'status', 0) === 1;

            $statusLabel = $isActive ? 'Aktif' : 'Nonaktif';

            /*
            |--------------------------------------------------------------------------
            | URL halaman show admin
            |--------------------------------------------------------------------------
            */

            $showUrl = route('admin.faq.show', $item->id);
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
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih FAQ ' . ($item->pertanyaan ?? $item->id)" />
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

            {{-- Pertanyaan --}}
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
                            bg-blue-50
                            text-blue-600
                            ring-1
                            ring-blue-100
                            dark:bg-blue-500/15
                            dark:text-blue-400
                            dark:ring-blue-500/20
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9a3.001 3.001 0 115.544 1.607c-.622.873-1.772 1.393-1.772 2.393M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        {{-- Pertanyaan dapat diklik menuju halaman show --}}
                        <a href="{{ $showUrl }}"
                            class="
                                group/question
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
                            title="Lihat detail {{ $item->pertanyaan ?? 'FAQ' }}">
                            <span class="line-clamp-3">
                                {{ $item->pertanyaan ?? '-' }}
                            </span>

                            <svg class="
                                    mt-1
                                    h-4
                                    w-4
                                    shrink-0
                                    text-gray-400
                                    transition
                                    duration-200
                                    group-hover/question:translate-x-1
                                    group-hover/question:text-blue-500
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
                                Pertanyaan Umum
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

            {{-- Jawaban --}}
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
                        relative
                        rounded-xl
                        border
                        border-gray-100
                        bg-gray-50/70
                        px-4
                        py-3
                        dark:border-gray-800
                        dark:bg-gray-900/50
                    ">
                    <span
                        class="
                            absolute
                            left-4
                            top-3
                            text-lg
                            font-bold
                            leading-none
                            text-green-400
                            dark:text-green-500
                        "
                        aria-hidden="true">
                        “
                    </span>

                    <p class="pl-4">
                        {{ $plainAnswer !== '' ? \Illuminate\Support\Str::limit($plainAnswer, 190) : '-' }}
                    </p>
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
                            Tanggal dibuat
                        </p>
                    </div>
                </div>
            </td>

            {{-- Status --}}
            <td class="px-4 py-4 sm:px-6">
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
                        {{ $isActive
                            ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20'
                            : 'bg-gray-100 text-gray-700 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600/30' }}
                    ">
                    <span
                        class="
                            h-2
                            w-2
                            rounded-full
                            {{ $isActive ? 'bg-green-500' : 'bg-gray-400' }}
                        "></span>

                    {{ $statusLabel }}
                </span>
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
                <x-tables.row-actions :edit-url="route('admin.faq.edit', $item->id)" :delete-url="route('admin.faq.destroy', $item->id)" :edit-label="'Edit FAQ ' . ($item->pertanyaan ?? '')" :delete-label="'Hapus FAQ ' . ($item->pertanyaan ?? '')"
                    delete-confirmation="Apakah Anda yakin ingin menghapus FAQ ini?" />
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
                        bg-blue-50
                        text-blue-500
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9a3.001 3.001 0 115.544 1.607c-.622.873-1.772 1.393-1.772 2.393M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                    Belum ada data FAQ
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan pertanyaan baru untuk membantu masyarakat.
                </p>

                <a href="{{ route('admin.faq.create') }}"
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
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah FAQ
                </a>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
