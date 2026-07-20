@props(['permohonan', 'ppidPembantuList' => []])

@php
    $isPaginated = $permohonan instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $permohonan->getCollection() : collect($permohonan);

    $ppidList = collect($ppidPembantuList ?? []);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $permohonan->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q'), request('status'), request('ppid_pembantuid')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();

    $statusOptions = ['Diajukan', 'Menunggu', 'Diproses', 'Diterima', 'Disetujui', 'Selesai', 'Ditolak'];
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Permohonan Informasi"
    description="Pantau nomor permohonan, identitas pemohon, unit PPID tujuan, rincian kebutuhan, status, dan proses pelayanan informasi."
    :row-ids="$rowIds" :paginator="$isPaginated ? $permohonan : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true"
    :pagination-window="1" min-width="min-w-[1750px]">
    <x-slot:filter>
        <form action="{{ route('admin.permohonan.index') }}" method="GET" class="space-y-5">
            <div class="border-b border-gray-100 pb-3 dark:border-gray-800">
                <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                    Filter Permohonan
                </h4>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Cari permohonan berdasarkan nomor, identitas pemohon, unit PPID, rincian, atau status.
                </p>
            </div>

            <div>
                <label for="permohonan_q" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Pencarian
                </label>

                <input id="permohonan_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari nomor, pemohon, atau rincian"
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
                        focus:border-brand-300
                        focus:ring-3
                        focus:ring-brand-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
            </div>

            <div>
                <label for="permohonan_status"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status
                </label>

                <select id="permohonan_status" name="status"
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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">
                    <option value="">Semua Status</option>

                    @foreach ($statusOptions as $status)
                        <option value="{{ $status }}" @selected(strtolower((string) request('status')) === strtolower($status))>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($ppidList->isNotEmpty())
                <div>
                    <label for="permohonan_ppid"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PPID Pembantu
                    </label>

                    <select id="permohonan_ppid" name="ppid_pembantuid"
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
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-white/90
                        ">
                        <option value="">Semua PPID Pembantu</option>

                        @foreach ($ppidList as $ppid)
                            <option value="{{ $ppid->id }}" @selected((string) request('ppid_pembantuid') === (string) $ppid->id)>
                                {{ $ppid->nama ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label for="permohonan_per_page"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Data per Halaman
                </label>

                <select id="permohonan_per_page" name="per_page"
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

            <div class="flex justify-end gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <a href="{{ route('admin.permohonan.index') }}"
                    class="
                        inline-flex
                        h-10
                        items-center
                        rounded-lg
                        border
                        border-gray-300
                        px-4
                        text-sm
                        text-gray-700
                        dark:border-gray-700
                        dark:text-gray-300
                    ">
                    Reset
                </a>

                <button type="submit"
                    class="
                        inline-flex
                        h-10
                        items-center
                        rounded-lg
                        bg-brand-500
                        px-4
                        text-sm
                        font-semibold
                        text-white
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

    <x-slot:head>
        <th class="w-20 px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            No
        </th>

        <th class="min-w-[220px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            No. Permohonan
        </th>

        <th class="min-w-[300px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Pemohon
        </th>

        <th class="min-w-[280px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            PPID Pembantu
        </th>

        <th class="min-w-[180px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Tanggal
        </th>

        <th class="min-w-[440px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Rincian
        </th>

        <th class="min-w-[160px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Status
        </th>

        <th
            class="
                w-[130px]
                min-w-[130px]
                px-4
                py-3.5
                text-center
                text-xs
                font-medium
                text-gray-500
                sm:px-6
            ">
            Action
        </th>
    </x-slot:head>

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            $applicantName = data_get($item, 'userPublic.nama') ?? (data_get($item, 'user_public.nama') ?? '-');

            $applicantEmail = data_get($item, 'userPublic.email') ?? (data_get($item, 'user_public.email') ?? null);

            $ppidName = data_get($item, 'ppidPembantu.nama') ?? (data_get($item, 'ppid_pembantu.nama') ?? '-');

            $formattedDate = '-';

            if (!empty($item->tanggal)) {
                try {
                    $formattedDate =
                        is_numeric($item->tanggal) && (int) $item->tanggal > 100000000
                            ? \Illuminate\Support\Carbon::createFromTimestamp((int) $item->tanggal)->translatedFormat(
                                'd F Y',
                            )
                            : \Illuminate\Support\Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                } catch (\Throwable $exception) {
                    $formattedDate = (string) $item->tanggal;
                }
            }

            $status = trim((string) ($item->status ?? 'Diajukan'));

            $statusKey = strtolower($status);

            $statusClass = match ($statusKey) {
                'diajukan' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                'menunggu' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',

                'diproses', 'proses' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',

                'diterima' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-400',

                'disetujui', 'selesai' => 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            };

            $applicantInitial = $applicantName !== '-' ? mb_strtoupper(mb_substr($applicantName, 0, 1)) : 'P';
        @endphp

        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.03]">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih permohonan ' . ($item->no_pemohon ?? $item->id)" />
            </td>

            <td class="px-4 py-4 text-sm font-medium text-gray-500 sm:px-6">
                {{ $rowNumber }}
            </td>

            <td class="px-4 py-4 sm:px-6">
                <div
                    class="
                        rounded-xl
                        border
                        border-blue-100
                        bg-blue-50/70
                        px-4
                        py-3
                        dark:border-blue-500/20
                        dark:bg-blue-500/10
                    ">
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">
                        {{ $item->no_pemohon ?? '-' }}
                    </p>

                    <p class="mt-1 text-xs text-blue-500/70 dark:text-blue-400/70">
                        ID Data: {{ $item->id }}
                    </p>
                </div>
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
                            rounded-full
                            bg-purple-50
                            font-bold
                            text-purple-600
                            dark:bg-purple-500/15
                            dark:text-purple-400
                        ">
                        {{ $applicantInitial }}
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ $applicantName }}
                        </p>

                        @if ($applicantEmail)
                            <p class="mt-1 text-xs text-gray-400">
                                {{ $applicantEmail }}
                            </p>
                        @endif
                    </div>
                </div>
            </td>

            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400 sm:px-6">
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-cyan-50
                        px-3
                        py-2
                        text-cyan-700
                        dark:bg-cyan-500/15
                        dark:text-cyan-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    {{ $ppidName }}
                </div>
            </td>

            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-400 sm:px-6">
                <span class="inline-flex items-center gap-2">
                    <span
                        class="
                            flex
                            h-8
                            w-8
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

            <td class="px-4 py-4 text-sm leading-7 text-gray-600 dark:text-gray-400 sm:px-6">
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
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->rincian ?? '')), 220) ?: '-' }}
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
                        {{ $statusClass }}
                    ">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                    {{ $status }}
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
                <x-tables.row-actions :view-url="route('admin.permohonan.show', $item->id)" :view-label="'Lihat permohonan ' . ($item->no_pemohon ?? '')" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="px-6 py-16 text-center">
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
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <h3 class="mt-4 text-base font-semibold text-gray-800 dark:text-white/90">
                    Belum ada data permohonan
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada permohonan informasi yang dapat ditampilkan.
                </p>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
