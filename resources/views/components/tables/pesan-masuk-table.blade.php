@props(['pesanMasuk', 'statusOptions' => []])

@php
    /*
    |--------------------------------------------------------------------------
    | Persiapan Data Tabel
    |--------------------------------------------------------------------------
    */

    $isPaginated = $pesanMasuk instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $pesanMasuk->getCollection() : collect($pesanMasuk);

    $statuses = collect($statusOptions ?? []);

    if ($statuses->isEmpty()) {
        $statuses = collect([
            'semua' => 'Semua Status',
            'baru' => 'Pesan Baru',
            'dibaca' => 'Sudah Dibaca',
            'dibalas' => 'Sudah Dibalas',
            'ditutup' => 'Ditutup',
        ]);
    }

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id): bool => $id !== null && $id !== '')
        ->map(fn($id): string => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $pesanMasuk->firstItem() ?? 1 : 1;

    /*
    |--------------------------------------------------------------------------
    | Jumlah Filter Aktif
    |--------------------------------------------------------------------------
    */

    $activeFilterCount = collect([request('q'), request('status')])
        ->filter(fn($value): bool => $value !== null && $value !== '' && $value !== 'semua')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Format Tanggal
    |--------------------------------------------------------------------------
    */

    $formatDateTime = static function (mixed $value): string {
        if ($value === null || $value === '') {
            return '-';
        }

        try {
            if (is_numeric($value) && (int) $value > 100000000) {
                return \Illuminate\Support\Carbon::createFromTimestamp((int) $value)
                    ->locale('id')
                    ->translatedFormat('d F Y, H:i');
            }

            return \Illuminate\Support\Carbon::parse($value)->locale('id')->translatedFormat('d F Y, H:i');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Tampilan Status
    |--------------------------------------------------------------------------
    */

    $getStatusMeta = static function (mixed $item): array {
        $statusValue = (int) data_get($item, 'status', \App\Models\PesanMasuk::STATUS_BARU);

        $statusLabel = trim(
            (string) (data_get($item, 'status_label') ??
                match ($statusValue) {
                    \App\Models\PesanMasuk::STATUS_BARU => 'Baru',
                    \App\Models\PesanMasuk::STATUS_DIBACA => 'Dibaca',
                    \App\Models\PesanMasuk::STATUS_DIBALAS => 'Dibalas',
                    \App\Models\PesanMasuk::STATUS_DITUTUP => 'Ditutup',
                    default => 'Belum diketahui',
                }),
        );

        return match ($statusValue) {
            \App\Models\PesanMasuk::STATUS_BARU => [
                'label' => $statusLabel,
                'class' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
            ],

            \App\Models\PesanMasuk::STATUS_DIBACA => [
                'label' => $statusLabel,
                'class' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',
            ],

            \App\Models\PesanMasuk::STATUS_DIBALAS => [
                'label' => $statusLabel,
                'class' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
            ],

            \App\Models\PesanMasuk::STATUS_DITUTUP => [
                'label' => $statusLabel,
                'class' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',
            ],

            default => [
                'label' => $statusLabel,
                'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            ],
        };
    };
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Pesan Masuk"
    description="Pantau pengirim, subjek, isi pesan, tanggal, status percakapan, jumlah balasan, dan proses tindak lanjut admin."
    :row-ids="$rowIds" :paginator="$isPaginated ? $pesanMasuk : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1700px]">
    {{-- ================================================================
        FILTER
    ================================================================= --}}

    <x-slot:filter>
        <form action="{{ route('admin.pesan-masuk.index') }}" method="GET" class="space-y-5">
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
                    Filter Pesan Masuk
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari percakapan berdasarkan nama, email, subjek,
                    isi pesan, atau status penanganan.
                </p>
            </div>

            {{-- Pencarian --}}

            <div>
                <label for="pesan_q"
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

                    <input id="pesan_q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama, email, subjek, atau pesan" autocomplete="off"
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
                <label for="pesan_status"
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

                <select id="pesan_status" name="status"
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
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected((string) request('status', 'semua') === (string) $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Data per halaman --}}

            <div>
                <label for="pesan_per_page"
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

                <select id="pesan_per_page" name="per_page"
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
                <a href="{{ route('admin.pesan-masuk.index') }}"
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
            Pengirim
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
            Subjek
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
            Tanggal
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
            Isi Pesan
        </th>

        <th scope="col"
            class="
                min-w-[170px]
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
                min-w-[140px]
                px-4
                py-3.5
                text-center
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Balasan
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
            $rowNumber = $firstNumber + $index;

            $nama = trim((string) ($item->nama ?? ''));
            $nama = $nama !== '' ? $nama : 'Tanpa Nama';

            $email = trim((string) ($item->email ?? ''));

            $subjek = trim((string) ($item->subjek ?? ''));
            $subjek = $subjek !== '' ? $subjek : 'Tanpa Subjek';

            $isiPesan = trim(strip_tags((string) ($item->pesan ?? '')));

            $formattedDate = $formatDateTime(data_get($item, 'tanggal'));

            $statusMeta = $getStatusMeta($item);

            $jumlahBalasan = (int) data_get($item, 'balasan_count', 0);

            $isUnread = (int) ($item->status ?? -1) === \App\Models\PesanMasuk::STATUS_BARU;

            $showUrl = route('admin.pesan-masuk.show', [
                'id' => $item->id,
            ]);
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
                {{ $isUnread ? 'bg-blue-50/30 dark:bg-blue-500/[0.03]' : '' }}
            ">
            {{-- Checkbox --}}

            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih pesan ' . $subjek" />
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

            {{-- Pengirim --}}

            <td class="px-4 py-4 sm:px-6">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="
                                max-w-[240px]
                                truncate
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                            title="{{ $nama }}">
                            {{ $nama }}
                        </p>

                        @if ($isUnread)
                            <span
                                class="
                                    inline-flex
                                    shrink-0
                                    rounded-full
                                    bg-blue-50
                                    px-2
                                    py-0.5
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-blue-700
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                Baru
                            </span>
                        @endif
                    </div>

                    @if ($email !== '')
                        <p class="
                                mt-1
                                max-w-[240px]
                                truncate
                                text-xs
                                text-gray-400
                            "
                            title="{{ $email }}">
                            {{ $email }}
                        </p>
                    @else
                        <p class="mt-1 text-xs text-gray-400">
                            Email tidak tersedia
                        </p>
                    @endif
                </div>
            </td>

            {{-- Subjek --}}

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
                    title="Lihat detail percakapan">
                    <div
                        class="
                            flex
                            items-start
                            justify-between
                            gap-2
                        ">
                        <p
                            class="
                                line-clamp-2
                                text-sm
                                font-semibold
                                text-blue-700
                                dark:text-blue-400
                            ">
                            {{ $subjek }}
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

            {{-- Isi pesan --}}

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
                    {{ \Illuminate\Support\Str::limit($isiPesan, 220) ?: '-' }}
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
                        {{ $statusMeta['class'] }}
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
                        {{ $statusMeta['label'] }}
                    </span>
                </span>
            </td>

            {{-- Jumlah balasan --}}

            <td class="px-4 py-4 text-center sm:px-6">
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
                        {{ $jumlahBalasan > 0
                            ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400'
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}
                    ">
                    <i class="ri-reply-line"></i>

                    {{ $jumlahBalasan }}
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
                <x-tables.row-actions :view-url="$showUrl" :view-label="'Lihat pesan ' . $subjek" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9"
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
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
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
                    Belum ada pesan masuk
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Belum ada pesan masuk yang dapat ditampilkan
                    atau data tidak sesuai dengan filter.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
