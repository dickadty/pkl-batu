{{-- Notification Dropdown Component --}}

@php
    /*
    |--------------------------------------------------------------------------
    | Admin dan Data Notifikasi
    |--------------------------------------------------------------------------
    */

    $admin = auth('admin')->user();

    $notifications = collect();

    $unreadNotificationCount = 0;

    if ($admin) {
        $notifications = $admin->notifications()->latest('created_at')->limit(8)->get();

        $unreadNotificationCount = $admin->unreadNotifications()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    */

    $hasNotificationIndexRoute = \Illuminate\Support\Facades\Route::has('admin.notifikasi.index');

    $hasOpenNotificationRoute = \Illuminate\Support\Facades\Route::has('admin.notifikasi.buka');

    $hasMarkAllReadRoute = \Illuminate\Support\Facades\Route::has('admin.notifikasi.baca-semua');

    $notificationIndexUrl = $hasNotificationIndexRoute ? route('admin.notifikasi.index') : '#';

    /*
    |--------------------------------------------------------------------------
    | Metadata Warna Notifikasi
    |--------------------------------------------------------------------------
    */

    $getNotificationTone = static function (string $jenis): array {
        $jenis = mb_strtolower(trim($jenis));

        if (str_contains($jenis, 'tolak') || str_contains($jenis, 'ditolak') || str_contains($jenis, 'gagal')) {
            return [
                'icon_background' => 'bg-red-50 dark:bg-red-500/15',

                'icon_color' => 'text-red-600 dark:text-red-400',

                'dot' => 'bg-red-500',
            ];
        }

        if (str_contains($jenis, 'revisi') || str_contains($jenis, 'peringatan')) {
            return [
                'icon_background' => 'bg-amber-50 dark:bg-amber-500/15',

                'icon_color' => 'text-amber-600 dark:text-amber-400',

                'dot' => 'bg-amber-500',
            ];
        }

        if (str_contains($jenis, 'selesai') || str_contains($jenis, 'validasi') || str_contains($jenis, 'disetujui')) {
            return [
                'icon_background' => 'bg-green-50 dark:bg-green-500/15',

                'icon_color' => 'text-green-600 dark:text-green-400',

                'dot' => 'bg-green-500',
            ];
        }

        if (str_contains($jenis, 'pesan') || str_contains($jenis, 'chat') || str_contains($jenis, 'balasan')) {
            return [
                'icon_background' => 'bg-purple-50 dark:bg-purple-500/15',

                'icon_color' => 'text-purple-600 dark:text-purple-400',

                'dot' => 'bg-purple-500',
            ];
        }

        return [
            'icon_background' => 'bg-blue-50 dark:bg-blue-500/15',

            'icon_color' => 'text-blue-600 dark:text-blue-400',

            'dot' => 'bg-blue-500',
        ];
    };
@endphp

<div class="relative" x-data="{
    dropdownOpen: false,

    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },

    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()" @keydown.escape.window="closeDropdown()">
    {{-- ================================================================
        TOMBOL NOTIFIKASI
    ================================================================= --}}

    <button type="button" @click="toggleDropdown()"
        class="
            relative
            flex
            h-11
            w-11
            items-center
            justify-center
            rounded-full
            border
            border-gray-200
            bg-white
            text-gray-500
            transition-colors
            hover:bg-gray-100
            hover:text-gray-700
            focus:outline-none
            focus:ring-3
            focus:ring-brand-500/10
            dark:border-gray-800
            dark:bg-gray-900
            dark:text-gray-400
            dark:hover:bg-gray-800
            dark:hover:text-white
        "
        aria-label="Buka notifikasi" :aria-expanded="dropdownOpen">
        {{-- Badge jumlah notifikasi belum dibaca --}}

        @if ($unreadNotificationCount > 0)
            <span
                class="
                    absolute
                    -right-1
                    -top-1
                    z-10
                    flex
                    h-5
                    min-w-5
                    items-center
                    justify-center
                    rounded-full
                    border-2
                    border-white
                    bg-red-500
                    px-1
                    text-[9px]
                    font-bold
                    leading-none
                    text-white
                    dark:border-gray-900
                ">
                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
            </span>

            <span
                class="
                    absolute
                    right-0
                    top-0
                    h-2.5
                    w-2.5
                    animate-ping
                    rounded-full
                    bg-red-400
                    opacity-60
                "
                aria-hidden="true"></span>
        @endif

        {{-- Bell icon --}}

        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z" />
        </svg>
    </button>

    {{-- ================================================================
        DROPDOWN
    ================================================================= --}}

    <div x-cloak x-show="dropdownOpen" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="translate-y-1 scale-95 opacity-0"
        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
        x-transition:leave-end="translate-y-1 scale-95 opacity-0"
        class="
            absolute
            -right-[240px]
            z-50
            mt-[17px]
            flex
            h-[520px]
            w-[350px]
            origin-top-right
            flex-col
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
            shadow-theme-lg
            dark:border-gray-800
            dark:bg-gray-dark
            sm:w-[390px]
            lg:right-0
        ">
        {{-- ============================================================
            HEADER DROPDOWN
        ============================================================= --}}

        <div
            class="
                flex
                shrink-0
                items-center
                justify-between
                border-b
                border-gray-100
                px-4
                py-4
                dark:border-gray-800
            ">
            <div>
                <div class="flex items-center gap-2">
                    <h5
                        class="
                            text-base
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Notifikasi
                    </h5>

                    @if ($unreadNotificationCount > 0)
                        <span
                            class="
                                inline-flex
                                min-w-5
                                items-center
                                justify-center
                                rounded-full
                                bg-red-50
                                px-2
                                py-0.5
                                text-[10px]
                                font-bold
                                text-red-600
                                dark:bg-red-500/15
                                dark:text-red-400
                            ">
                            {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            baru
                        </span>
                    @endif
                </div>

                <p
                    class="
                        mt-1
                        text-xs
                        text-gray-500
                        dark:text-gray-400
                    ">
                    Aktivitas terbaru pada sistem PPID
                </p>
            </div>

            <button type="button" @click="closeDropdown()"
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-lg
                    text-gray-500
                    transition
                    hover:bg-gray-100
                    hover:text-gray-700
                    dark:text-gray-400
                    dark:hover:bg-white/5
                    dark:hover:text-white
                "
                aria-label="Tutup notifikasi">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        {{-- ============================================================
            ACTION BAR
        ============================================================= --}}

        @if ($unreadNotificationCount > 0 && $hasMarkAllReadRoute)
            <div
                class="
                    shrink-0
                    border-b
                    border-gray-100
                    bg-gray-50/70
                    px-4
                    py-2.5
                    dark:border-gray-800
                    dark:bg-gray-900/40
                ">
                <form
                    action="{{ route('admin.notifikasi.baca-semua') }}"
                    method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="
                            inline-flex
                            items-center
                            gap-1.5
                            text-xs
                            font-semibold
                            text-brand-600
                            transition
                            hover:text-brand-700
                            dark:text-brand-400
                            dark:hover:text-brand-300
                        ">
                        <i class="ri-check-double-line text-base"></i>

                        Tandai semua sudah dibaca
                    </button>
                </form>
            </div>
        @endif

        {{-- ============================================================
            DAFTAR NOTIFIKASI
        ============================================================= --}}

        <div
            class="
                min-h-0
                flex-1
                overflow-y-auto
                custom-scrollbar
            ">
            <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($notifications as $notification)
                    @php
                        $notificationData = is_array($notification->data) ? $notification->data : [];

                        $judul = trim((string) data_get($notificationData, 'judul', 'Notifikasi Sistem'));

                        $pesan = trim((string) data_get($notificationData, 'pesan', ''));

                        $jenis = trim((string) data_get($notificationData, 'jenis', 'Sistem'));

                        $icon = trim((string) data_get($notificationData, 'icon', 'ri-notification-3-line'));

                        $metadata = data_get($notificationData, 'metadata', []);

                        if (!is_array($metadata)) {
                            $metadata = [];
                        }

                        $nomorPermohonan = trim((string) data_get($metadata, 'no_pemohon', ''));

                        $actorUsername = trim((string) data_get($metadata, 'actor_username', ''));

                        $statusMetadata = trim((string) data_get($metadata, 'status', ''));

                        $isUnread = $notification->read_at === null;

                        $notificationTone = $getNotificationTone($jenis);

                        $notificationTime = $notification->created_at
                            ? $notification->created_at->locale('id')->diffForHumans()
                            : '-';
                    @endphp

                    <li
                        class="
                            relative
                            {{ $isUnread ? 'bg-blue-50/50 dark:bg-blue-500/[0.05]' : 'bg-white dark:bg-transparent' }}
                        ">
                        @if ($hasOpenNotificationRoute)
                            <form
                                action="{{ route('admin.notifikasi.buka', [
                                    'id' => $notification->id,
                                ]) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit" @click="closeDropdown()"
                                    class="
                                        group
                                        relative
                                        flex
                                        w-full
                                        gap-3
                                        px-4
                                        py-3.5
                                        text-left
                                        transition
                                        hover:bg-gray-50
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-inset
                                        focus:ring-brand-500/20
                                        dark:hover:bg-white/[0.04]
                                    ">
                                    {{-- Icon --}}

                                    <span
                                        class="
                                            relative
                                            flex
                                            h-11
                                            w-11
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            {{ $notificationTone['icon_background'] }}
                                            {{ $notificationTone['icon_color'] }}
                                        ">
                                        <i class="{{ $icon }} text-xl"></i>

                                        @if ($isUnread)
                                            <span
                                                class="
                                                    absolute
                                                    -right-1
                                                    -top-1
                                                    h-3
                                                    w-3
                                                    rounded-full
                                                    border-2
                                                    border-white
                                                    {{ $notificationTone['dot'] }}
                                                    dark:border-gray-900
                                                "></span>
                                        @endif
                                    </span>

                                    {{-- Isi notifikasi --}}

                                    <span
                                        class="
                                            min-w-0
                                            flex-1
                                        ">
                                        <span
                                            class="
                                                flex
                                                items-start
                                                justify-between
                                                gap-3
                                            ">
                                            <span
                                                class="
                                                    line-clamp-1
                                                    text-sm
                                                    {{ $isUnread ? 'font-bold text-gray-900 dark:text-white' : 'font-semibold text-gray-800 dark:text-white/90' }}
                                                ">
                                                {{ $judul }}
                                            </span>

                                            @if ($isUnread)
                                                <span
                                                    class="
                                                        shrink-0
                                                        rounded-full
                                                        bg-blue-100
                                                        px-2
                                                        py-0.5
                                                        text-[9px]
                                                        font-bold
                                                        uppercase
                                                        tracking-wide
                                                        text-blue-700
                                                        dark:bg-blue-500/20
                                                        dark:text-blue-400
                                                    ">
                                                    Baru
                                                </span>
                                            @endif
                                        </span>

                                        @if ($pesan !== '')
                                            <span
                                                class="
                                                    mt-1
                                                    line-clamp-2
                                                    text-xs
                                                    leading-5
                                                    text-gray-500
                                                    dark:text-gray-400
                                                ">
                                                {{ $pesan }}
                                            </span>
                                        @endif

                                        <span
                                            class="
                                                mt-2
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-1.5
                                                text-[10px]
                                                text-gray-400
                                                dark:text-gray-500
                                            ">
                                            <span
                                                class="
                                                    font-semibold
                                                    text-gray-500
                                                    dark:text-gray-400
                                                ">
                                                {{ $jenis }}
                                            </span>

                                            @if ($nomorPermohonan !== '')
                                                <span
                                                    class="
                                                        h-1
                                                        w-1
                                                        rounded-full
                                                        bg-gray-300
                                                        dark:bg-gray-600
                                                    "></span>

                                                <span>
                                                    {{ $nomorPermohonan }}
                                                </span>
                                            @endif

                                            @if ($statusMetadata !== '')
                                                <span
                                                    class="
                                                        h-1
                                                        w-1
                                                        rounded-full
                                                        bg-gray-300
                                                        dark:bg-gray-600
                                                    "></span>

                                                <span>
                                                    {{ $statusMetadata }}
                                                </span>
                                            @endif

                                            <span
                                                class="
                                                    h-1
                                                    w-1
                                                    rounded-full
                                                    bg-gray-300
                                                    dark:bg-gray-600
                                                "></span>

                                            <span>
                                                {{ $notificationTime }}
                                            </span>
                                        </span>

                                        @if ($actorUsername !== '')
                                            <span
                                                class="
                                                    mt-1
                                                    block
                                                    text-[10px]
                                                    text-gray-400
                                                    dark:text-gray-500
                                                ">
                                                Oleh:
                                                {{ $actorUsername }}
                                            </span>
                                        @endif
                                    </span>

                                    <span
                                        class="
                                            self-center
                                            text-gray-300
                                            transition
                                            group-hover:translate-x-0.5
                                            group-hover:text-brand-500
                                            dark:text-gray-600
                                        ">
                                        <i
                                            class="
                                                ri-arrow-right-s-line
                                                text-lg
                                            "></i>
                                    </span>
                                </button>
                            </form>
                        @else
                            <div
                                class="
                                    flex
                                    gap-3
                                    px-4
                                    py-3.5
                                ">
                                <span
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        {{ $notificationTone['icon_background'] }}
                                        {{ $notificationTone['icon_color'] }}
                                    ">
                                    <i class="{{ $icon }} text-xl"></i>
                                </span>

                                <div class="min-w-0">
                                    <p
                                        class="
                                            text-sm
                                            font-semibold
                                            text-gray-800
                                            dark:text-white/90
                                        ">
                                        {{ $judul }}
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-500
                                            dark:text-gray-400
                                        ">
                                        {{ $pesan }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </li>
                @empty
                    <li
                        class="
                            flex
                            min-h-[300px]
                            flex-col
                            items-center
                            justify-center
                            px-6
                            py-10
                            text-center
                        ">
                        <span
                            class="
                                flex
                                h-16
                                w-16
                                items-center
                                justify-center
                                rounded-full
                                bg-gray-100
                                text-gray-400
                                dark:bg-gray-800
                                dark:text-gray-500
                            ">
                            <i
                                class="
                                    ri-notification-off-line
                                    text-3xl
                                "></i>
                        </span>

                        <h6
                            class="
                                mt-4
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Belum ada notifikasi
                        </h6>

                        <p
                            class="
                                mt-1
                                max-w-[260px]
                                text-xs
                                leading-5
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Aktivitas dan pembaruan sistem akan
                            ditampilkan pada bagian ini.
                        </p>
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- ============================================================
            FOOTER DROPDOWN
        ============================================================= --}}

        <div
            class="
                shrink-0
                border-t
                border-gray-100
                bg-white
                p-3
                dark:border-gray-800
                dark:bg-gray-dark
            ">
            @if ($hasNotificationIndexRoute)
                <a href="{{ $notificationIndexUrl }}" @click="closeDropdown()"
                    class="
                        flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-4
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        dark:border-gray-700
                        dark:bg-gray-800
                        dark:text-gray-400
                        dark:hover:bg-white/[0.03]
                        dark:hover:text-gray-200
                    ">
                    Lihat Semua Notifikasi

                    <i class="ri-arrow-right-line"></i>
                </a>
            @else
                <button type="button" disabled
                    class="
                        flex
                        h-11
                        w-full
                        cursor-not-allowed
                        items-center
                        justify-center
                        rounded-lg
                        bg-gray-100
                        text-sm
                        font-medium
                        text-gray-400
                        dark:bg-gray-800
                        dark:text-gray-500
                    ">
                    Halaman notifikasi belum tersedia
                </button>
            @endif
        </div>
    </div>
</div>
