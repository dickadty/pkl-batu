@props(['proker', 'admin', 'ppidPembantuList' => []])

@php
    $isPaginated = $proker instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $proker->getCollection() : collect($proker);

    $ppidList = collect($ppidPembantuList ?? []);

    $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $proker->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q'), request('tahun'), request('ppid_pembantuid')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Program Kerja"
    description="Daftar program kerja berdasarkan unit PPID Pembantu." :row-ids="$rowIds" :paginator="$isPaginated ? $proker : null"
    :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" :pagination-window="1" min-width="min-w-[1850px]">
    <x-slot:filter>
        <form action="{{ route('admin.proker.index') }}" method="GET" class="space-y-5">
            <div>
                <label for="proker_q" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Pencarian
                </label>

                <input id="proker_q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama, target, sumber dana, atau PJ"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div>
                <label for="proker_tahun" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tahun Pelaksanaan
                </label>

                <input id="proker_tahun" type="number" name="tahun" value="{{ request('tahun') }}" min="2000"
                    max="2100" placeholder="Contoh: {{ now()->year }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            @if ($isAdminUtama && $ppidList->isNotEmpty())
                <div>
                    <label for="proker_ppid" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PPID Pembantu
                    </label>

                    <select id="proker_ppid" name="ppid_pembantuid"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">
                            Semua PPID Pembantu
                        </option>

                        @foreach ($ppidList as $ppid)
                            <option value="{{ $ppid->id }}" @selected((string) request('ppid_pembantuid') === (string) $ppid->id)>
                                {{ $ppid->nama ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label for="proker_per_page" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Data per Halaman
                </label>

                <select id="proker_per_page" name="per_page"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @foreach ([10, 15, 25, 50, 100] as $limit)
                        <option value="{{ $limit }}" @selected((int) request('per_page', 15) === $limit)>
                            {{ $limit }} data
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                <a href="{{ route('admin.proker.index') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    Reset
                </a>

                <button type="submit"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-brand-500 px-4 text-sm font-semibold text-white hover:bg-brand-600">
                    Terapkan
                </button>
            </div>
        </form>
    </x-slot:filter>

    <x-slot:headerActions>
        @if ($activeFilterCount > 0)
            <span
                class="inline-flex rounded-full bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                {{ $activeFilterCount }} Filter Aktif
            </span>
        @endif

        <a href="{{ route('admin.proker.create') }}"
            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-semibold text-white hover:bg-brand-600">
            <i class="ri-add-line text-lg"></i>
            Tambah Program Kerja
        </a>
    </x-slot:headerActions>

    <x-slot:head>
        <th class="w-20 px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            No
        </th>

        <th class="min-w-[330px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Program Kerja
        </th>

        <th class="min-w-[280px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Anggaran
        </th>

        <th class="min-w-[280px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Sumber Dana
        </th>

        <th class="min-w-[170px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Jadwal
        </th>

        <th class="min-w-[230px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            Penanggung Jawab
        </th>

        <th class="min-w-[260px] px-4 py-3.5 text-left text-xs font-medium text-gray-500 sm:px-6">
            PPID Pembantu
        </th>

        <th class="w-[220px] min-w-[220px] px-4 py-3.5 text-center text-xs font-medium text-gray-500 sm:px-6">
            Action
        </th>
    </x-slot:head>

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            $ppidName = data_get($item, 'ppidPembantu.nama') ?? '-';

            $showUrl = route('admin.proker.show', ['id' => $item->id]);

            $editUrl = route('admin.proker.edit', ['id' => $item->id]);

            $deleteUrl = route('admin.proker.destroy', ['id' => $item->id]);

            $downloadUrl = !empty($item->dokumen) ? route('admin.proker.dokumen', ['id' => $item->id]) : null;

            $tanggal = $item->jadwal_pelaksanaan
                ? $item->jadwal_pelaksanaan->locale('id')->translatedFormat('d F Y')
                : '-';
        @endphp

        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.03]">
            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih program kerja ' . $item->nama_proker" />
            </td>

            <td class="px-4 py-4 text-sm text-gray-500 sm:px-6">
                {{ $rowNumber }}
            </td>

            <td class="px-4 py-4 sm:px-6">
                <a href="{{ $showUrl }}"
                    class="text-sm font-semibold text-gray-800 hover:text-brand-600 dark:text-white/90">
                    {{ $item->nama_proker }}
                </a>

                <p class="mt-1 text-xs text-gray-400">
                    ID: {{ $item->id }}
                </p>
            </td>

            <td class="px-4 py-4 text-sm leading-6 text-gray-600 dark:text-gray-400 sm:px-6">
                {{ \Illuminate\Support\Str::limit($item->anggaran, 130) }}
            </td>

            <td class="px-4 py-4 text-sm leading-6 text-gray-600 dark:text-gray-400 sm:px-6">
                {{ \Illuminate\Support\Str::limit($item->sumber_dana, 130) }}
            </td>

            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-400 sm:px-6">
                {{ $tanggal }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400 sm:px-6">
                <p class="font-medium text-gray-800 dark:text-white/90">
                    {{ $item->pj }}
                </p>

                @if ($item->telp)
                    <p class="mt-1 text-xs text-gray-400">
                        {{ $item->telp }}
                    </p>
                @endif
            </td>

            <td class="px-4 py-4 sm:px-6">
                <span
                    class="inline-flex rounded-xl bg-purple-50 px-3 py-2 text-sm font-medium text-purple-700 dark:bg-purple-500/15 dark:text-purple-400">
                    {{ $ppidName }}
                </span>
            </td>

            <td class="w-[220px] min-w-[220px] px-4 py-4 text-center sm:px-6">
                <x-tables.row-actions :view-url="$showUrl" :download-url="$downloadUrl" :edit-url="$editUrl" :delete-url="$deleteUrl"
                    :view-label="'Lihat program kerja ' . $item->nama_proker" :download-label="'Buka dokumen ' . $item->nama_proker" :edit-label="'Edit program kerja ' . $item->nama_proker" :delete-label="'Hapus program kerja ' . $item->nama_proker"
                    delete-confirmation="Apakah Anda yakin ingin menghapus program kerja ini?" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="px-6 py-14 text-center text-sm text-gray-500">
                Belum ada data program kerja.
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
