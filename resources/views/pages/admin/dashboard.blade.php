@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $isAdminUtama = $admin->isAdminUtama();

        /*
        |--------------------------------------------------------------------------
        | ID data untuk checkbox
        |--------------------------------------------------------------------------
        */

        $latestDokumentasiIds = $latestDokumentasi->pluck('id')->map(fn($id) => (string) $id)->values();

        /*
        |--------------------------------------------------------------------------
        | Statistik dashboard
        |--------------------------------------------------------------------------
        */

        $statCards = [];

        if ($isAdminUtama) {
            $statCards[] = [
                'label' => 'PPID Pembantu',
                'value' => $stats['total_ppid_pembantu'] ?? 0,
                'icon' => 'ri-government-line',
                'icon_class' => 'bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                'value_class' => 'text-gray-900 dark:text-white',
            ];
        }

        $statCards[] = [
            'label' => 'Total Informasi',
            'value' => $stats['total_informasi'] ?? 0,
            'icon' => 'ri-file-list-3-line',
            'icon_class' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
            'value_class' => 'text-gray-900 dark:text-white',
        ];

        $statCards[] = [
            'label' => 'Menunggu Verifikasi',
            'value' => $stats['informasi_menunggu'] ?? 0,
            'icon' => 'ri-time-line',
            'icon_class' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
            'value_class' => 'text-yellow-500 dark:text-yellow-400',
        ];

        $statCards[] = [
            'label' => 'Terverifikasi',
            'value' => $stats['informasi_terverifikasi'] ?? 0,
            'icon' => 'ri-checkbox-circle-line',
            'icon_class' => 'bg-green-100 text-green-600 dark:bg-green-500/15 dark:text-green-400',
            'value_class' => 'text-green-600 dark:text-green-400',
        ];

        if ($isAdminUtama) {
            $statCards = array_merge($statCards, [
                [
                    'label' => 'Permohonan',
                    'value' => $stats['total_permohonan'] ?? 0,
                    'icon' => 'ri-inbox-line',
                    'icon_class' => 'bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400',
                    'value_class' => 'text-gray-900 dark:text-white',
                ],
                [
                    'label' => 'Keberatan',
                    'value' => $stats['total_keberatan'] ?? 0,
                    'icon' => 'ri-error-warning-line',
                    'icon_class' => 'bg-orange-100 text-orange-600 dark:bg-orange-500/15 dark:text-orange-400',
                    'value_class' => 'text-gray-900 dark:text-white',
                ],
                [
                    'label' => 'Pesan Masuk',
                    'value' => $stats['total_pesan_masuk'] ?? 0,
                    'icon' => 'ri-mail-line',
                    'icon_class' => 'bg-cyan-100 text-cyan-600 dark:bg-cyan-500/15 dark:text-cyan-400',
                    'value_class' => 'text-gray-900 dark:text-white',
                ],
                [
                    'label' => 'Download',
                    'value' => $stats['total_download'] ?? 0,
                    'icon' => 'ri-download-cloud-2-line',
                    'icon_class' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
                    'value_class' => 'text-gray-900 dark:text-white',
                ],
            ]);
        }
    @endphp

    <div class="space-y-6">
        {{-- =============================================================
            HEADER DASHBOARD
        ============================================================== --}}

        <section
            class="
                flex
                flex-col
                gap-5
                sm:flex-row
                sm:items-center
                sm:justify-between
            ">
            <div>
                <h1
                    class="
                        text-2xl
                        font-bold
                        tracking-tight
                        text-gray-900
                        dark:text-white
                        sm:text-3xl
                    ">
                    Dashboard
                </h1>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Selamat datang,

                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $admin->username }}
                    </span>
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-500">
                    @if ($isAdminUtama)
                        Admin PPID Utama
                    @else
                        Admin PPID Pembantu

                        @if ($admin->ppidPembantu)
                            <span class="text-gray-400 dark:text-gray-500">
                                •
                            </span>

                            {{ $admin->ppidPembantu->nama }}
                        @endif
                    @endif
                </p>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                @csrf

                <button type="submit"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-red-600
                        px-5
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        shadow-sm
                        transition
                        hover:bg-red-700
                        focus:outline-none
                        focus:ring-4
                        focus:ring-red-200
                        dark:focus:ring-red-900/50
                    ">
                    <i class="ri-logout-box-r-line text-lg"></i>

                    <span>Logout</span>
                </button>
            </form>
        </section>

        {{-- =============================================================
            STATISTIK
        ============================================================== --}}

        <section
            class="
                grid
                grid-cols-1
                gap-5
                sm:grid-cols-2
                xl:grid-cols-4
            ">
            @foreach ($statCards as $card)
                <article
                    class="
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        p-5
                        shadow-sm
                        transition
                        duration-200
                        hover:-translate-y-0.5
                        hover:shadow-md
                        dark:border-gray-800
                        dark:bg-gray-800
                        dark:shadow-none
                    ">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p
                                class="
                                    truncate
                                    text-sm
                                    font-medium
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                {{ $card['label'] }}
                            </p>

                            <h2
                                class="
                                    mt-3
                                    text-3xl
                                    font-bold
                                    tracking-tight
                                    {{ $card['value_class'] }}
                                ">
                                {{ number_format((int) $card['value'], 0, ',', '.') }}
                            </h2>
                        </div>

                        <div
                            class="
                                flex
                                h-11
                                w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                {{ $card['icon_class'] }}
                            ">
                            <i class="{{ $card['icon'] }} text-xl"></i>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- =============================================================
            INFORMASI PUBLIK TERBARU
        ============================================================== --}}

        <x-tables.basic-tables.basic-tables-two title="Informasi Publik Terbaru"
            description="Daftar informasi publik yang baru ditambahkan." :see-all-url="route('admin.informasi-publik.index')" see-all-text="Lihat Semua"
            :row-ids="$latestDokumentasiIds" :selectable="true" :show-actions="true" min-width="min-w-[1150px]">
            {{-- =========================================================
                FILTER
            ========================================================== --}}

            <x-slot:filter>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
                    {{-- Filter status --}}
                    <div>
                        <label for="status"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            ">
                            Status Verifikasi
                        </label>

                        <select id="status" name="status"
                            class="
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                text-gray-700
                                outline-none
                                transition
                                focus:border-blue-500
                                focus:ring-2
                                focus:ring-blue-500/20
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-300
                            ">
                            <option value="">
                                Semua Status
                            </option>

                            <option value="verified" @selected(request('status') === 'verified')>
                                Terverifikasi
                            </option>

                            <option value="pending" @selected(request('status') === 'pending')>
                                Menunggu Verifikasi
                            </option>
                        </select>
                    </div>

                    {{-- Filter sifat --}}
                    <div>
                        <label for="sifat"
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

                        <select id="sifat" name="sifat"
                            class="
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3
                                py-2.5
                                text-sm
                                text-gray-700
                                outline-none
                                transition
                                focus:border-blue-500
                                focus:ring-2
                                focus:ring-blue-500/20
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-300
                            ">
                            <option value="">
                                Semua Sifat
                            </option>

                            <option value="setiap saat" @selected(request('sifat') === 'setiap saat')>
                                Setiap Saat
                            </option>

                            <option value="berkala" @selected(request('sifat') === 'berkala')>
                                Berkala
                            </option>

                            <option value="serta merta" @selected(request('sifat') === 'serta merta')>
                                Serta Merta
                            </option>

                            <option value="dikecualikan" @selected(request('sifat') === 'dikecualikan')>
                                Dikecualikan
                            </option>
                        </select>
                    </div>

                    {{-- Tombol filter --}}
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-lg
                                border
                                border-gray-300
                                px-3
                                py-2
                                text-sm
                                font-medium
                                text-gray-600
                                transition
                                hover:bg-gray-50
                                dark:border-gray-700
                                dark:text-gray-300
                                dark:hover:bg-gray-700
                            ">
                            Reset
                        </a>

                        <button type="submit"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                rounded-lg
                                bg-blue-600
                                px-3
                                py-2
                                text-sm
                                font-medium
                                text-white
                                transition
                                hover:bg-blue-700
                                focus:outline-none
                                focus:ring-4
                                focus:ring-blue-200
                                dark:focus:ring-blue-900/50
                            ">
                            Terapkan
                        </button>
                    </div>
                </form>
            </x-slot:filter>

            {{-- =========================================================
                TABLE HEADER
            ========================================================== --}}

            <x-slot:head>
                {{-- Jangan tambahkan tag tr di dalam slot ini --}}

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
                    Nama Informasi
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
                    PPID Pembantu
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
                    Sifat
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
                    Tanggal
                </th>

                <th scope="col"
                    class="
                        min-w-[160px]
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
            </x-slot:head>

            {{-- =========================================================
                TABLE BODY
            ========================================================== --}}

            @forelse ($latestDokumentasi as $item)
                <tr
                    class="
                        transition-colors
                        hover:bg-gray-50
                        dark:hover:bg-white/[0.03]
                    ">
                    {{-- Checkbox --}}
                    <td class="px-4 py-3.5 sm:px-6">
                        <button type="button" @click="toggleRow(@js((string) $item->id))"
                            class="
                                flex
                                h-5
                                w-5
                                items-center
                                justify-center
                                rounded-md
                                border-[1.25px]
                                transition
                            "
                            :class="isSelected(@js((string) $item->id)) ?
                                'border-blue-500 bg-blue-500' :
                                'border-gray-300 bg-white dark:border-gray-700 dark:bg-transparent'"
                            aria-label="Pilih data {{ $item->nama }}">
                            <svg x-cloak x-show="isSelected(@js((string) $item->id))" width="14" height="14"
                                viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.67 3.5L5.25 9.92L2.33 7" stroke="white" stroke-width="1.9"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </td>

                    {{-- Nomor --}}
                    <td
                        class="
                            whitespace-nowrap
                            px-4
                            py-3.5
                            text-sm
                            text-gray-500
                            dark:text-gray-400
                            sm:px-6
                        ">
                        {{ $loop->iteration }}
                    </td>

                    {{-- Nama informasi --}}
                    <td class="px-4 py-3.5 sm:px-6">
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
                                    bg-indigo-50
                                    text-indigo-600
                                    dark:bg-indigo-500/15
                                    dark:text-indigo-400
                                ">
                                <i class="ri-file-text-line text-lg"></i>
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="
                                        line-clamp-2
                                        text-sm
                                        font-medium
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    {{ $item->nama ?? '-' }}
                                </p>

                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-500
                                    ">
                                    ID: {{ $item->id }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- PPID Pembantu --}}
                    <td
                        class="
                            px-4
                            py-3.5
                            text-sm
                            text-gray-700
                            dark:text-gray-400
                            sm:px-6
                        ">
                        {{ $item->ppidPembantu?->nama ?? '-' }}
                    </td>

                    {{-- Sifat --}}
                    <td
                        class="
                            whitespace-nowrap
                            px-4
                            py-3.5
                            text-sm
                            text-gray-700
                            dark:text-gray-400
                            sm:px-6
                        ">
                        {{ ucfirst($item->sifat ?? '-') }}
                    </td>

                    {{-- Tanggal --}}
                    <td
                        class="
                            whitespace-nowrap
                            px-4
                            py-3.5
                            text-sm
                            text-gray-700
                            dark:text-gray-400
                            sm:px-6
                        ">
                        {{ $item->created_at?->format('d-m-Y') ?? '-' }}
                    </td>

                    {{-- Status --}}
                    <td class="whitespace-nowrap px-4 py-3.5 sm:px-6">
                        @if ($item->is_verifikasi)
                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    bg-green-50
                                    px-2.5
                                    py-1
                                    text-xs
                                    font-medium
                                    text-green-700
                                    dark:bg-green-500/15
                                    dark:text-green-400
                                ">
                                <span
                                    class="
                                        h-1.5
                                        w-1.5
                                        rounded-full
                                        bg-green-500
                                    "></span>

                                Terverifikasi
                            </span>
                        @else
                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    bg-yellow-50
                                    px-2.5
                                    py-1
                                    text-xs
                                    font-medium
                                    text-yellow-700
                                    dark:bg-yellow-500/15
                                    dark:text-yellow-400
                                ">
                                <span
                                    class="
                                        h-1.5
                                        w-1.5
                                        rounded-full
                                        bg-yellow-500
                                    "></span>

                                Menunggu
                            </span>
                        @endif
                    </td>

                    {{-- Action --}}
                    <td class="px-4 py-3.5 text-center sm:px-6">
                        <div class="flex items-center justify-center gap-1">
                            {{-- Edit --}}
                            <a href="{{ route('admin.informasi-publik.edit', $item->id) }}"
                                class="
                                    inline-flex
                                    h-9
                                    w-9
                                    items-center
                                    justify-center
                                    rounded-lg
                                    text-gray-500
                                    transition
                                    hover:bg-blue-50
                                    hover:text-blue-600
                                    dark:text-gray-400
                                    dark:hover:bg-blue-500/15
                                    dark:hover:text-blue-400
                                "
                                title="Edit informasi" aria-label="Edit informasi {{ $item->nama }}">
                                <i class="ri-edit-line text-lg"></i>
                            </a>

                            {{-- Hapus --}}
                            <form
                                action="{{ route('admin.informasi-publik.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Apakah Anda yakin ingin menghapus data ini?'
                                    )
                                ">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="
                                        inline-flex
                                        h-9
                                        w-9
                                        items-center
                                        justify-center
                                        rounded-lg
                                        text-gray-500
                                        transition
                                        hover:bg-red-50
                                        hover:text-red-600
                                        dark:text-gray-400
                                        dark:hover:bg-red-500/15
                                        dark:hover:text-red-400
                                    "
                                    title="Hapus informasi" aria-label="Hapus informasi {{ $item->nama }}">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8"
                        class="
                            px-6
                            py-14
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
                                bg-gray-100
                                text-gray-400
                                dark:bg-gray-800
                                dark:text-gray-500
                            ">
                            <i class="ri-file-list-3-line text-2xl"></i>
                        </div>

                        <p
                            class="
                                mt-4
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            ">
                            Belum ada informasi publik
                        </p>

                        <p
                            class="
                                mt-1
                                text-sm
                                text-gray-500
                                dark:text-gray-500
                            ">
                            Data informasi publik terbaru akan ditampilkan di sini.
                        </p>
                    </td>
                </tr>
            @endforelse
        </x-tables.basic-tables.basic-tables-two>
    </div>
@endsection
