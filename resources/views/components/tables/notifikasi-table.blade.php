@props(['notifications', 'status' => 'semua', 'totalSemua' => 0, 'totalBelumDibaca' => 0, 'totalSudahDibaca' => 0])

@php
    $isPaginated = $notifications instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $notifications->getCollection() : collect($notifications);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $notifications->firstItem() ?? 1 : 1;

    $availableStatuses = ['semua', 'belum_dibaca', 'sudah_dibaca'];

    $currentStatus = in_array($status, $availableStatuses, true) ? $status : 'semua';

    $activeFilterCount = collect([$currentStatus !== 'semua' ? $currentStatus : null])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Notifikasi"
    description="Kelola pemberitahuan aktivitas permohonan informasi pada akun admin." :row-ids="$rowIds"
    :paginator="$isPaginated ? $notifications : null" :selectable="false" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" :pagination-window="1"
    min-width="min-w-[1450px]">
    <x-slot:filter>
        <div class="space-y-5">
            <form action="{{ route('admin.notifikasi.index') }}" method="GET" class="space-y-5">
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
                        Filter Notifikasi
                    </h4>

                    <p
                        class="
                            mt-1
                            text-xs
                            leading-5
                            text-gray-500
                            dark:text-gray-400
                        ">
                        Tampilkan notifikasi berdasarkan status baca.
                    </p>
                </div>

                <div>
                    <label for="notifikasi_status"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-300
                        ">
                        Status Notifikasi
                    </label>

                    <select id="notifikasi_status" name="status"
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
                        <option value="semua" @selected($currentStatus === 'semua')>
                            Semua Notifikasi
                            ({{ number_format((int) $totalSemua) }})
                        </option>

                        <option value="belum_dibaca" @selected($currentStatus === 'belum_dibaca')>
                            Belum Dibaca
                            ({{ number_format((int) $totalBelumDibaca) }})
                        </option>

                        <option value="sudah_dibaca" @selected($currentStatus === 'sudah_dibaca')>
                            Sudah Dibaca
                            ({{ number_format((int) $totalSudahDibaca) }})
                        </option>
                    </select>
                </div>

                <div>
                    <label for="notifikasi_per_page"
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

                    <select id="notifikasi_per_page" name="per_page"
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
                    <a href="{{ route('admin.notifikasi.index') }}"
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

            @if ($totalSudahDibaca > 0)
                <div
                    class="
                        border-t
                        border-gray-100
                        pt-5
                        dark:border-gray-800
                    ">
                    <form action="{{ route('admin.notifikasi.hapus-semua-dibaca') }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi yang sudah dibaca?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="
                                inline-flex
                                h-10
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-red-200
                                bg-red-50
                                px-4
                                text-sm
                                font-semibold
                                text-red-600
                                transition
                                hover:bg-red-100
                                dark:border-red-500/20
                                dark:bg-red-500/10
                                dark:text-red-400
                                dark:hover:bg-red-500/20
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 11v6m4-6v6" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            Hapus Semua yang Dibaca
                        </button>
                    </form>
                </div>
            @endif
        </div>
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

        @if ($totalBelumDibaca > 0)
            <form action="{{ route('admin.notifikasi.baca-semua') }}" method="POST">
                @csrf
                @method('PATCH')

                <button type="submit"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l3 3" />
                    </svg>

                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        @endif
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
                min-w-[500px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Notifikasi
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
            Jenis
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
            Waktu
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
                w-[180px]
                min-w-[180px]
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

    @forelse ($currentItems as $index => $notification)
        @php
            $rowNumber = $firstNumber + $index;

            $data = is_array($notification->data) ? $notification->data : [];

            $judul = data_get($data, 'judul', 'Notifikasi');

            $pesan = data_get($data, 'pesan', 'Terdapat aktivitas baru.');

            $jenis = data_get($data, 'jenis', 'umum');

            $icon = data_get($data, 'icon', 'ri-notification-3-line');

            $jenisLabel = ucwords(str_replace('_', ' ', $jenis));

            $belumDibaca = is_null($notification->read_at);
        @endphp

        <tr @class([
            'transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.03]',
            'bg-brand-50/30 dark:bg-brand-500/[0.03]' => $belumDibaca,
        ])>
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
                <div class="flex items-start gap-3">
                    <div @class([
                        'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl',
                        'bg-gradient-to-br from-blue-50 to-cyan-50 text-blue-600 dark:from-blue-500/15 dark:to-cyan-500/15 dark:text-blue-400' => $belumDibaca,
                        'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' => !$belumDibaca,
                    ])>
                        <i class="{{ $icon }} text-xl"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 @class([
                                'text-sm leading-6 text-gray-800 dark:text-white/90',
                                'font-bold' => $belumDibaca,
                                'font-semibold' => !$belumDibaca,
                            ])>
                                {{ $judul }}
                            </h3>

                            @if ($belumDibaca)
                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        rounded-full
                                        bg-blue-50
                                        px-2
                                        py-0.5
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-wide
                                        text-blue-600
                                        dark:bg-blue-500/15
                                        dark:text-blue-400
                                    ">
                                    Baru
                                </span>
                            @endif
                        </div>

                        <p
                            class="
                                mt-1
                                line-clamp-2
                                max-w-2xl
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                            ">
                            {{ $pesan }}
                        </p>
                    </div>
                </div>
            </td>

            <td class="px-4 py-4 sm:px-6">
                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-purple-50
                        px-3
                        py-2
                        text-xs
                        font-semibold
                        text-purple-700
                        dark:bg-purple-500/15
                        dark:text-purple-400
                    ">
                    <i class="ri-price-tag-3-line"></i>

                    {{ $jenisLabel }}
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
                <div class="flex items-center gap-2">
                    <i class="ri-calendar-line text-gray-400"></i>

                    <span>
                        {{ optional($notification->created_at)->format('d M Y') ?? '-' }}
                    </span>
                </div>

                <div
                    class="
                        mt-1
                        flex
                        items-center
                        gap-2
                        text-xs
                        text-gray-400
                    ">
                    <i class="ri-time-line"></i>

                    <span>
                        {{ optional($notification->created_at)->format('H:i') ?? '-' }}
                    </span>

                    @if ($notification->created_at)
                        <span>
                            · {{ $notification->created_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </td>

            <td class="px-4 py-4 sm:px-6">
                @if ($belumDibaca)
                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-yellow-50
                            px-3
                            py-1.5
                            text-xs
                            font-semibold
                            text-yellow-700
                            dark:bg-yellow-500/15
                            dark:text-yellow-400
                        ">
                        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>

                        Belum Dibaca
                    </span>
                @else
                    <span
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            bg-green-50
                            px-3
                            py-1.5
                            text-xs
                            font-semibold
                            text-green-700
                            dark:bg-green-500/15
                            dark:text-green-400
                        ">
                        <i class="ri-check-line"></i>

                        Sudah Dibaca
                    </span>
                @endif
            </td>

            <td
                class="
                    w-[180px]
                    min-w-[180px]
                    px-4
                    py-4
                    text-center
                    align-middle
                    sm:px-6
                ">
                <x-tables.row-actions :view-url="route('admin.notifikasi.buka', $notification->id)" view-method="PATCH" view-label="Buka notifikasi"
                    :verify-url="$belumDibaca ? route('admin.notifikasi.baca', $notification->id) : null" verify-label="Tandai sudah dibaca"
                    verify-confirmation="Tandai notifikasi ini sebagai sudah dibaca?" :delete-url="route('admin.notifikasi.destroy', $notification->id)"
                    delete-label="Hapus notifikasi"
                    delete-confirmation="Apakah Anda yakin ingin menghapus notifikasi ini?" />
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
                    <i class="ri-notification-off-line text-2xl"></i>
                </div>

                <h3
                    class="
                        mt-3
                        text-base
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    Belum ada notifikasi
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Notifikasi aktivitas permohonan akan ditampilkan di sini.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
