@props(['akunAdmin', 'ppidPembantuList' => []])

@php
    $isPaginated = $akunAdmin instanceof \Illuminate\Pagination\AbstractPaginator;

    $currentItems = $isPaginated ? $akunAdmin->getCollection() : collect($akunAdmin);

    $ppidList = collect($ppidPembantuList ?? []);

    $rowIds = $currentItems
        ->pluck('id')
        ->filter(fn($id) => $id !== null && $id !== '')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->all();

    $firstNumber = $isPaginated ? $akunAdmin->firstItem() ?? 1 : 1;

    $activeFilterCount = collect([request('q'), request('role'), request('ppid_pembantuid')])
        ->filter(fn($value) => $value !== null && $value !== '')
        ->count();

    $currentAdminId = auth('admin')->id();

    $roleOptions = [
        1 => 'Super Admin',
        2 => 'Admin PPID Pembantu',
    ];
@endphp

<x-tables.basic-tables.basic-tables-two title="Data Akun Admin"
    description="Kelola username, email, role, unit PPID Pembantu, dan keamanan akun administrator." :row-ids="$rowIds"
    :paginator="$isPaginated ? $akunAdmin : null" :selectable="true" :show-actions="false" :show-pagination="true" :show-pagination-summary="true" :pagination-window="1"
    min-width="min-w-[1450px]">
    {{-- ================================================================
        FILTER
    ================================================================= --}}

    <x-slot:filter>
        <form action="{{ route('admin.akun-admin.index') }}" method="GET" class="space-y-5">
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
                    Filter Akun Admin
                </h4>

                <p
                    class="
                        mt-1
                        text-xs
                        leading-5
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Cari akun berdasarkan nama, username, email, role,
                    atau unit PPID Pembantu.
                </p>
            </div>

            {{-- Pencarian --}}

            <div>
                <label for="akun_admin_q"
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

                    <input id="akun_admin_q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama, username, atau email" autocomplete="off"
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

            {{-- Filter role --}}

            <div>
                <label for="akun_admin_role"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Role
                </label>

                <select id="akun_admin_role" name="role"
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
                    <option value="">
                        Semua Role
                    </option>

                    @foreach ($roleOptions as $roleValue => $roleLabel)
                        <option value="{{ $roleValue }}" @selected((string) request('role') === (string) $roleValue)>
                            {{ $roleLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter PPID Pembantu --}}

            @if ($ppidList->isNotEmpty())
                <div>
                    <label for="akun_admin_ppid"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-300
                        ">
                        PPID Pembantu
                    </label>

                    <select id="akun_admin_ppid" name="ppid_pembantuid"
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

            {{-- Data per halaman --}}

            <div>
                <label for="akun_admin_per_page"
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

                <select id="akun_admin_per_page" name="per_page"
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
                <a href="{{ route('admin.akun-admin.index') }}"
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

        <a href="{{ route('admin.akun-admin.create') }}"
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

            <span>Tambah Akun Admin</span>
        </a>
    </x-slot:headerActions>

    {{-- ================================================================
        TABLE HEADER
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
            Akun Admin
        </th>

        <th scope="col"
            class="
                min-w-[260px]
                px-4
                py-3.5
                text-left
                text-xs
                font-medium
                text-gray-500
                dark:text-gray-400
                sm:px-6
            ">
            Email
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
            Role
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
            Unit PPID
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
            Dibuat
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

    {{-- ================================================================
        TABLE BODY
    ================================================================= --}}

    @forelse ($currentItems as $index => $item)
        @php
            $rowNumber = $firstNumber + $index;

            $displayName =
                data_get($item, 'nama') ?? (data_get($item, 'name') ?? (data_get($item, 'username') ?? 'Admin'));

            $username = data_get($item, 'username', '-');

            $email = data_get($item, 'email', '-');

            $role = (int) data_get($item, 'role', 0);

            $roleLabel = match ($role) {
                1 => 'Super Admin',
                2 => 'Admin PPID Pembantu',
                default => 'Role Tidak Diketahui',
            };

            $roleClass = match ($role) {
                1 => 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                2 => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',

                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
            };

            $ppidName =
                data_get($item, 'ppidPembantu.nama') ??
                (data_get($item, 'ppid_pembantu.nama') ?? (data_get($item, 'ppidPembantuNama') ?? null));

            if ($role === 1) {
                $ppidName = 'PPID Utama';
            }

            if ($role === 2 && empty($ppidName)) {
                $ppidName = 'Belum ditentukan';
            }

            $createdAt = '-';

            if (!empty($item->created_at)) {
                try {
                    $createdAt = \Illuminate\Support\Carbon::parse($item->created_at)
                        ->locale('id')
                        ->translatedFormat('d F Y H:i');
                } catch (\Throwable $exception) {
                    $createdAt = (string) $item->created_at;
                }
            }

            $initial = !empty($displayName) ? mb_strtoupper(mb_substr($displayName, 0, 1)) : 'A';

            $editUrl = route('admin.akun-admin.edit', $item->id);

            $deleteUrl = route('admin.akun-admin.destroy', $item->id);

            $isCurrentAccount = (string) $currentAdminId === (string) $item->id;
        @endphp

        <tr
            class="
                transition-colors
                hover:bg-gray-50
                dark:hover:bg-white/[0.03]
            ">
            {{-- Checkbox --}}

            <td class="px-4 py-4 sm:px-6">
                <x-tables.row-checkbox :row-id="$item->id" :label="'Pilih akun admin ' . $displayName" />
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
                {{ $rowNumber }}
            </td>

            {{-- Akun admin --}}

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
                            bg-indigo-50
                            font-bold
                            text-indigo-600
                            dark:bg-indigo-500/15
                            dark:text-indigo-400
                        ">
                        {{ $initial }}
                    </div>

                    <div class="min-w-0">
                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            {{ $displayName }}
                        </p>

                        <div
                            class="
                                mt-1
                                flex
                                flex-wrap
                                items-center
                                gap-2
                            ">
                            <p
                                class="
                                    text-xs
                                    text-gray-400
                                    dark:text-gray-500
                                ">
                                Username: {{ $username }}
                            </p>

                            @if ($isCurrentAccount)
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-green-50
                                        px-2
                                        py-0.5
                                        text-[10px]
                                        font-semibold
                                        text-green-700
                                        dark:bg-green-500/15
                                        dark:text-green-400
                                    ">
                                    Akun Anda
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </td>

            {{-- Email --}}

            <td
                class="
                    px-4
                    py-4
                    text-sm
                    text-gray-600
                    dark:text-gray-400
                    sm:px-6
                ">
                @if ($email !== '-')
                    <a href="mailto:{{ $email }}"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            rounded-lg
                            transition
                            hover:text-brand-600
                            dark:hover:text-brand-400
                        ">
                        <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>

                        <span class="max-w-[210px] truncate">
                            {{ $email }}
                        </span>
                    </a>
                @else
                    <span class="text-gray-400">
                        -
                    </span>
                @endif
            </td>

            {{-- Role --}}

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
                        {{ $roleClass }}
                    ">
                    <span
                        class="
                            h-1.5
                            w-1.5
                            rounded-full
                            bg-current
                        "></span>

                    {{ $roleLabel }}
                </span>
            </td>

            {{-- Unit PPID --}}

            <td class="px-4 py-4 sm:px-6">
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-cyan-50
                        px-3
                        py-2
                        text-sm
                        font-medium
                        text-cyan-700
                        dark:bg-cyan-500/15
                        dark:text-cyan-400
                    ">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 21h8m-4-4v4M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>

                    <span class="line-clamp-2">
                        {{ $ppidName }}
                    </span>
                </div>
            </td>

            {{-- Tanggal dibuat --}}

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
                                d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                    </span>

                    {{ $createdAt }}
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
                @if ($isCurrentAccount)
                    <x-tables.row-actions :edit-url="$editUrl" :edit-label="'Edit akun ' . $displayName" />
                @else
                    <x-tables.row-actions :edit-url="$editUrl" :delete-url="$deleteUrl" :edit-label="'Edit akun ' . $displayName" :delete-label="'Hapus akun ' . $displayName"
                        delete-confirmation="Apakah Anda yakin ingin menghapus akun admin ini?" />
                @endif
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
                        h-16
                        w-16
                        items-center
                        justify-center
                        rounded-full
                        bg-indigo-50
                        text-indigo-500
                        dark:bg-indigo-500/15
                        dark:text-indigo-400
                    ">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m8-4a4 4 0 11-8 0 4 4 0 018 0z" />
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
                    Belum ada akun admin
                </h3>

                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Tambahkan akun admin baru atau ubah filter pencarian.
                </p>

                <a href="{{ route('admin.akun-admin.create') }}"
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

                    Tambah Akun Admin
                </a>
            </td>
        </tr>
    @endforelse
</x-tables.basic-tables.basic-tables-two>
