@php
    /* Admin yang Sedang Login */
    $admin = auth('admin')->user();

    $adminRole = (int) data_get($admin, 'role', 0);

    $isAdminUtama = $adminRole === 1 || ($admin && method_exists($admin, 'isAdminUtama') && $admin->isAdminUtama());

    $isAdminPembantu =
        $adminRole === 2 || ($admin && method_exists($admin, 'isAdminPembantu') && $admin->isAdminPembantu());

    $canAccessSharedModule = in_array($adminRole, [1, 2], true);

    /* Data Profil Admin */

    $adminDisplayName = data_get($admin, 'nama') ?? (data_get($admin, 'username') ?? 'Administrator');

    $adminInitial = mb_strtoupper(mb_substr((string) $adminDisplayName, 0, 1));

    $roleLabel = match ($adminRole) {
        1 => 'Admin Utama',
        2 => 'Admin PPID Pembantu',
        default => 'Administrator',
    };

    $ppidPembantuName = data_get($admin, 'ppidPembantu.nama') ?? (data_get($admin, 'ppid_pembantu.nama') ?? null);

    if ($isAdminPembantu && !empty($ppidPembantuName)) {
        $roleLabel .= ' · ' . $ppidPembantuName;
    }

    /* Status Menu Aktif */

    $dashboardActive = request()->routeIs('admin.dashboard');

    $masterActive = request()->routeIs('admin.ppid-pembantu.*', 'admin.akun-admin.*', 'admin.pejabat.*');

    $contentActive = request()->routeIs('admin.slider.*', 'admin.faq.*', 'admin.informasi-publik.*', 'admin.berita.*');

    $pengadaanActive = request()->routeIs('admin.pengadaan.*');

    $prokerActive = request()->routeIs('admin.proker.*');

    $permohonanActive = request()->routeIs('admin.permohonan.*');

    $notifikasiActive = request()->routeIs('admin.notifikasi.*');

    $chatActive = request()->routeIs('admin.pesan-masuk.*');

    /* Helper Filter Menu */

    $filterMenus = static function (\Illuminate\Support\Collection $menus) use (
        $adminRole,
    ): \Illuminate\Support\Collection {
        return $menus
            ->filter(static function (array $menu) use ($adminRole): bool {
                $routeExists = \Illuminate\Support\Facades\Route::has($menu['route']);

                $allowedRoles = $menu['roles'] ?? [1, 2];

                $roleAllowed = in_array($adminRole, $allowedRoles, true);

                return $routeExists && $roleAllowed;
            })
            ->values();
    };

    /*
    | Submenu Master Data
    */

    $masterMenus = $filterMenus(
        collect([
            [
                'label' => 'Daftar PPID Pembantu',
                'route' => 'admin.ppid-pembantu.index',
                'active' => ['admin.ppid-pembantu.index', 'admin.ppid-pembantu.show', 'admin.ppid-pembantu.edit'],
                'icon' => 'ri-government-line',
                'roles' => [1],
            ],
            [
                'label' => 'Tambah PPID Pembantu',
                'route' => 'admin.ppid-pembantu.create',
                'active' => ['admin.ppid-pembantu.create'],
                'icon' => 'ri-building-add-line',
                'roles' => [1],
            ],
            [
                'label' => 'Daftar Akun Admin',
                'route' => 'admin.akun-admin.index',
                'active' => ['admin.akun-admin.index', 'admin.akun-admin.edit'],
                'icon' => 'ri-admin-line',
                'roles' => [1],
            ],
            [
                'label' => 'Tambah Akun Admin',
                'route' => 'admin.akun-admin.create',
                'active' => ['admin.akun-admin.create'],
                'icon' => 'ri-user-add-line',
                'roles' => [1],
            ],
            [
                'label' => 'Daftar Pejabat',
                'route' => 'admin.pejabat.index',
                'active' => ['admin.pejabat.index', 'admin.pejabat.show', 'admin.pejabat.edit'],
                'icon' => 'ri-contacts-book-2-line',
                'roles' => [1],
            ],
            [
                'label' => 'Tambah Pejabat',
                'route' => 'admin.pejabat.create',
                'active' => ['admin.pejabat.create'],
                'icon' => 'ri-user-star-line',
                'roles' => [1],
            ],
        ]),
    );

    /* Submenu Konten dan Informasi */

    $contentMenus = $filterMenus(
        collect([
            [
                'label' => 'Daftar Informasi',
                'route' => 'admin.informasi-publik.index',
                'active' => [
                    'admin.informasi-publik.index',
                    'admin.informasi-publik.show',
                    'admin.informasi-publik.edit',
                ],
                'icon' => 'ri-file-list-3-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Tambah Informasi',
                'route' => 'admin.informasi-publik.create',
                'active' => ['admin.informasi-publik.create'],
                'icon' => 'ri-file-add-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Daftar Berita',
                'route' => 'admin.berita.index',
                'active' => ['admin.berita.index', 'admin.berita.show', 'admin.berita.edit'],
                'icon' => 'ri-newspaper-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Tambah Berita',
                'route' => 'admin.berita.create',
                'active' => ['admin.berita.create'],
                'icon' => 'ri-draft-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Daftar FAQ',
                'route' => 'admin.faq.index',
                'active' => ['admin.faq.index', 'admin.faq.show', 'admin.faq.edit'],
                'icon' => 'ri-question-answer-line',
                'roles' => [1],
            ],
            [
                'label' => 'Tambah FAQ',
                'route' => 'admin.faq.create',
                'active' => ['admin.faq.create'],
                'icon' => 'ri-questionnaire-line',
                'roles' => [1],
            ],
            [
                'label' => 'Daftar Slider',
                'route' => 'admin.slider.index',
                'active' => ['admin.slider.index', 'admin.slider.show', 'admin.slider.edit'],
                'icon' => 'ri-gallery-line',
                'roles' => [1],
            ],
            [
                'label' => 'Tambah Slider',
                'route' => 'admin.slider.create',
                'active' => ['admin.slider.create'],
                'icon' => 'ri-image-add-line',
                'roles' => [1],
            ],
        ]),
    );

    /*
    | Submenu Pengadaan
    */

    $pengadaanMenus = $filterMenus(
        collect([
            [
                'label' => 'Daftar Pengadaan',
                'route' => 'admin.pengadaan.index',
                'active' => ['admin.pengadaan.index', 'admin.pengadaan.show', 'admin.pengadaan.edit'],
                'icon' => 'ri-file-list-2-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Tambah Pengadaan',
                'route' => 'admin.pengadaan.create',
                'active' => ['admin.pengadaan.create'],
                'icon' => 'ri-shopping-cart-2-line',
                'roles' => [1, 2],
            ],
        ]),
    );

    /*
    | Submenu Program Kerja
    */

    $prokerMenus = $filterMenus(
        collect([
            [
                'label' => 'Daftar Program Kerja',
                'route' => 'admin.proker.index',
                'active' => ['admin.proker.index', 'admin.proker.show', 'admin.proker.edit', 'admin.proker.dokumen'],
                'icon' => 'ri-calendar-todo-line',
                'roles' => [1, 2],
            ],
            [
                'label' => 'Tambah Program Kerja',
                'route' => 'admin.proker.create',
                'active' => ['admin.proker.create'],
                'icon' => 'ri-calendar-event-line',
                'roles' => [1, 2],
            ],
        ]),
    );

    /* Jumlah Permohonan yang Membutuhkan Perhatian */

    $permohonanNotificationCount = (int) ($totalNotifikasiAdminUtama ?? 0);

    /* Jumlah Notifikasi Database yang Belum Dibaca */

    $unreadNotificationCount = 0;

    if ($admin && method_exists($admin, 'unreadNotifications')) {
        $unreadNotificationCount = (int) $admin->unreadNotifications()->count();
    }

    /*
    | Logo Pemerintah Kota Batu
    |
    | Aset wajib:
    | - assets/img/logo/LogoKotaBatu.webp
    |
    | Aset opsional untuk hasil terbaik:
    | - assets/img/logo/LogoKotaBatuDark.webp
    | - assets/img/logo/LogoKotaBatuIcon.webp
    | - assets/img/logo/LogoKotaBatuIconDark.webp
    */

    $logoRelativePath = 'assets/img/logo/LogoKotaBatu.webp';
    $logoDarkRelativePath = 'assets/img/logo/LogoKotaBatuDark.webp';
    $logoIconRelativePath = 'assets/img/logo/LogoKotaBatuIcon.webp';
    $logoIconDarkRelativePath = 'assets/img/logo/LogoKotaBatuIconDark.webp';

    $logoPath = asset($logoRelativePath);

    $hasLogoDark = file_exists(public_path($logoDarkRelativePath));
    $logoDarkPath = $hasLogoDark ? asset($logoDarkRelativePath) : $logoPath;

    $hasLogoIcon = file_exists(public_path($logoIconRelativePath));
    $logoIconPath = $hasLogoIcon ? asset($logoIconRelativePath) : $logoPath;

    $hasLogoIconDark = file_exists(public_path($logoIconDarkRelativePath));
    $logoIconDarkPath = $hasLogoIconDark ? asset($logoIconDarkRelativePath) : $logoIconPath;
@endphp

<aside id="sidebar" x-data="{
    activeMenus: {
        master: @js($masterActive),
        content: @js($contentActive),
        pengadaan: @js($pengadaanActive),
        proker: @js($prokerActive),
    },

    openSubmenus: {
        master: @js($masterActive),
        content: @js($contentActive),
        pengadaan: @js($pengadaanActive),
        proker: @js($prokerActive),
    },

    toggleSubmenu(key) {
        const shouldOpen = !this.openSubmenus[key];

        Object.keys(
            this.openSubmenus
        ).forEach((menuKey) => {
            this.openSubmenus[menuKey] = false;
        });

        this.openSubmenus[key] = shouldOpen;
    },

    isSubmenuOpen(key) {
        return Boolean(
            this.openSubmenus[key]
        );
    },

    isMenuActive(key) {
        return Boolean(
            this.activeMenus[key] ||
            this.openSubmenus[key]
        );
    },

    closeMobileSidebar() {
        this.$store.sidebar.setMobileOpen(false);
    }
}"
    class="
        fixed
        left-0
        top-0
        z-50
        flex
        h-screen
        flex-col
        border-r
        border-gray-200
        bg-white
        px-3
        text-gray-900
        shadow-sm
        transition-all
        duration-300
        ease-in-out
        dark:border-gray-800
        dark:bg-gray-900
        dark:text-white
        dark:shadow-none
    "
    :class="{
        'w-[272px]': $store.sidebar.isWide(),
        'w-[82px]': $store.sidebar.isCompact(),
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0':
            !$store.sidebar.isMobileOpen
    }"
    @mouseenter="$store.sidebar.setHovered(true)" @mouseleave="$store.sidebar.setHovered(false)">
    {{-- ================================================================
        LOGO
    ================================================================= --}}

    <div class="
            flex
            h-[84px]
            shrink-0
            items-center
            border-b
            border-gray-100
            px-2
            dark:border-gray-800
        "
        :class="$store.sidebar.isCompact() ?
            'xl:justify-center' :
            'justify-start'">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center overflow-hidden"
            aria-label="Dashboard PPID Kota Batu" @click="closeMobileSidebar()">

            {{-- Identitas lengkap ketika sidebar lebar --}}
            <span x-cloak x-show="$store.sidebar.isWide()" class="flex min-w-0 items-center gap-2.5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center bg-transparent">
                    <img src="{{ $logoIconPath }}" alt="Logo Pemerintah Kota Batu" width="44" height="44"
                        class="h-11 w-11 object-contain dark:hidden">

                    <img src="{{ $logoIconDarkPath }}" alt="Logo Pemerintah Kota Batu" width="44" height="44"
                        class="hidden h-11 w-11 object-contain dark:block">
                </span>

                <span class="flex min-w-0 flex-col leading-none">
                    <span
                        class="
                            text-[11px]
                            font-semibold
                            uppercase
                            tracking-[0.18em]
                            text-gray-500
                            dark:text-gray-400
                        ">
                        PPID
                    </span>

                    <span
                        class="
                            mt-1
                            truncate
                            text-[15px]
                            font-extrabold
                            uppercase
                            tracking-[0.04em]
                            text-gray-900
                            dark:text-white
                        ">
                        Kota Batu
                    </span>
                </span>
            </span>

            {{-- Lambang ringkas ketika sidebar compact --}}
            <span x-cloak x-show="$store.sidebar.isCompact()"
                class="flex h-10 w-10 items-center justify-center bg-transparent">
                <img src="{{ $logoIconPath }}" alt="Logo PPID Kota Batu" width="40" height="40"
                    class="h-10 w-10 object-contain dark:hidden">

                <img src="{{ $logoIconDarkPath }}" alt="Logo PPID Kota Batu" width="40" height="40"
                    class="hidden h-10 w-10 object-contain dark:block">
            </span>
        </a>
    </div>

    {{-- ================================================================
        ISI SIDEBAR
    ================================================================= --}}

    <div
        class="
            no-scrollbar
            flex
            min-h-0
            flex-1
            flex-col
            overflow-y-auto
            py-3
        ">
        <nav class="flex-1">
            <div class="
                    mb-2
                    flex
                    h-6
                    items-center
                    px-3
                "
                :class="$store.sidebar.isCompact() ?
                    'xl:justify-center' :
                    'justify-start'">
                <template x-if="$store.sidebar.isWide()">
                    <span
                        class="
                            text-[11px]
                            font-semibold
                            uppercase
                            tracking-[0.12em]
                            text-gray-400
                            dark:text-gray-500
                        ">
                        Navigasi
                    </span>
                </template>

                <template x-if="$store.sidebar.isCompact()">
                    <i
                        class="
                            ri-more-fill
                            text-xl
                            text-gray-400
                        "></i>
                </template>
            </div>

            <ul class="flex flex-col gap-1">
                {{-- ====================================================
                    DASHBOARD
                ===================================================== --}}

                <li>
                    <a href="{{ route('admin.dashboard') }}" title="Dashboard" @click="closeMobileSidebar()"
                        class="
                            menu-item
                            group
                            flex
                            min-h-10
                            w-full
                            items-center
                            gap-3
                            rounded-lg
                            px-3
                            py-2
                            text-[13px]
                            font-medium
                            leading-5
                            transition-colors
                            duration-200
                            {{ $dashboardActive ? 'menu-item-active' : 'menu-item-inactive' }}
                        "
                        :class="$store.sidebar.isCompact() ?
                            'xl:justify-center' :
                            'justify-start'">
                        <span
                            class="
                                flex
                                h-6
                                w-6
                                shrink-0
                                items-center
                                justify-center
                                {{ $dashboardActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}
                            ">
                            <i class="ri-dashboard-3-line text-lg"></i>
                        </span>

                        <span x-cloak x-show="$store.sidebar.isWide()" class="min-w-0 flex-1 truncate">
                            Dashboard
                        </span>
                    </a>
                </li>

                {{-- ====================================================
                    MASTER DATA
                ===================================================== --}}

                @if ($isAdminUtama && $masterMenus->isNotEmpty())
                    <li>
                        <button type="button" title="Master Data" @click="toggleSubmenu('master')"
                            :aria-expanded="isSubmenuOpen('master')"
                            class="
                                menu-item
                                group
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                            "
                            :class="[
                                isMenuActive('master') ?
                                'menu-item-active' :
                                'menu-item-inactive',
                            
                                $store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                "
                                :class="isMenuActive('master') ?
                                    'menu-item-icon-active' :
                                    'menu-item-icon-inactive'">
                                <i class="ri-database-2-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                    text-left
                                ">
                                Master Data
                            </span>

                            <i x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    ri-arrow-down-s-line
                                    ml-auto
                                    shrink-0
                                    text-base
                                    transition-transform
                                    duration-200
                                "
                                :class="isSubmenuOpen('master') ?
                                    'rotate-180 text-brand-500' :
                                    ''"></i>
                        </button>

                        <div x-cloak
                            x-show="
                                isSubmenuOpen('master')
                                && $store.sidebar.isWide()
                            "
                            x-transition>
                            <ul
                                class="
                                    ml-[22px]
                                    mt-1
                                    space-y-0.5
                                    border-l
                                    border-gray-200
                                    pl-[17px]
                                    dark:border-gray-700
                                ">
                                @foreach ($masterMenus as $menu)
                                    @php
                                        $menuPatterns = \Illuminate\Support\Arr::wrap(
                                            $menu['active'] ?? $menu['route'],
                                        );

                                        $menuActive = request()->routeIs(...$menuPatterns);
                                    @endphp

                                    <li>
                                        <a href="{{ route($menu['route']) }}" @click="closeMobileSidebar()"
                                            class="
                                                menu-dropdown-item
                                                flex
                                                min-h-9
                                                items-center
                                                gap-2.5
                                                rounded-lg
                                                px-2.5
                                                py-1.5
                                                text-[13px]
                                                font-medium
                                                leading-5
                                                transition-colors
                                                duration-200
                                                {{ $menuActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}
                                            ">
                                            <i
                                                class="
                                                    {{ $menu['icon'] }}
                                                    shrink-0
                                                    text-base
                                                "></i>

                                            <span
                                                class="
                                                    min-w-0
                                                    flex-1
                                                    truncate
                                                ">
                                                {{ $menu['label'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- ====================================================
                    KONTEN DAN INFORMASI
                ===================================================== --}}

                @if ($contentMenus->isNotEmpty())
                    <li>
                        <button type="button" title="Konten dan Informasi" @click="toggleSubmenu('content')"
                            :aria-expanded="isSubmenuOpen('content')"
                            class="
                                menu-item
                                group
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                            "
                            :class="[
                                isMenuActive('content') ?
                                'menu-item-active' :
                                'menu-item-inactive',
                            
                                $store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                "
                                :class="isMenuActive('content') ?
                                    'menu-item-icon-active' :
                                    'menu-item-icon-inactive'">
                                <i class="ri-layout-grid-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                    text-left
                                ">
                                Konten & Informasi
                            </span>

                            <i x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    ri-arrow-down-s-line
                                    ml-auto
                                    shrink-0
                                    text-base
                                    transition-transform
                                    duration-200
                                "
                                :class="isSubmenuOpen('content') ?
                                    'rotate-180 text-brand-500' :
                                    ''"></i>
                        </button>

                        <div x-cloak
                            x-show="
                                isSubmenuOpen('content')
                                && $store.sidebar.isWide()
                            "
                            x-transition>
                            <ul
                                class="
                                    ml-[22px]
                                    mt-1
                                    space-y-0.5
                                    border-l
                                    border-gray-200
                                    pl-[17px]
                                    dark:border-gray-700
                                ">
                                @foreach ($contentMenus as $menu)
                                    @php
                                        $menuPatterns = \Illuminate\Support\Arr::wrap(
                                            $menu['active'] ?? $menu['route'],
                                        );

                                        $menuActive = request()->routeIs(...$menuPatterns);
                                    @endphp

                                    <li>
                                        <a href="{{ route($menu['route']) }}" @click="closeMobileSidebar()"
                                            class="
                                                menu-dropdown-item
                                                flex
                                                min-h-9
                                                items-center
                                                gap-2.5
                                                rounded-lg
                                                px-2.5
                                                py-1.5
                                                text-[13px]
                                                font-medium
                                                leading-5
                                                transition-colors
                                                duration-200
                                                {{ $menuActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}
                                            ">
                                            <i
                                                class="
                                                    {{ $menu['icon'] }}
                                                    shrink-0
                                                    text-base
                                                "></i>

                                            <span
                                                class="
                                                    min-w-0
                                                    flex-1
                                                    truncate
                                                ">
                                                {{ $menu['label'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- ====================================================
                    PENGADAAN
                ===================================================== --}}

                @if ($canAccessSharedModule && $pengadaanMenus->isNotEmpty())
                    <li>
                        <button type="button" title="Pengadaan" @click="toggleSubmenu('pengadaan')"
                            :aria-expanded="isSubmenuOpen('pengadaan')"
                            class="
                                menu-item
                                group
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                            "
                            :class="[
                                isMenuActive('pengadaan') ?
                                'menu-item-active' :
                                'menu-item-inactive',
                            
                                $store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                "
                                :class="isMenuActive('pengadaan') ?
                                    'menu-item-icon-active' :
                                    'menu-item-icon-inactive'">
                                <i class="ri-shopping-cart-2-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                    text-left
                                ">
                                Pengadaan
                            </span>

                            <i x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    ri-arrow-down-s-line
                                    ml-auto
                                    shrink-0
                                    text-base
                                    transition-transform
                                    duration-200
                                "
                                :class="isSubmenuOpen('pengadaan') ?
                                    'rotate-180 text-brand-500' :
                                    ''"></i>
                        </button>

                        <div x-cloak
                            x-show="
                                isSubmenuOpen('pengadaan')
                                && $store.sidebar.isWide()
                            "
                            x-transition>
                            <ul
                                class="
                                    ml-[22px]
                                    mt-1
                                    space-y-0.5
                                    border-l
                                    border-gray-200
                                    pl-[17px]
                                    dark:border-gray-700
                                ">
                                @foreach ($pengadaanMenus as $menu)
                                    @php
                                        $menuPatterns = \Illuminate\Support\Arr::wrap(
                                            $menu['active'] ?? $menu['route'],
                                        );

                                        $menuActive = request()->routeIs(...$menuPatterns);
                                    @endphp

                                    <li>
                                        <a href="{{ route($menu['route']) }}" @click="closeMobileSidebar()"
                                            class="
                                                menu-dropdown-item
                                                flex
                                                min-h-9
                                                items-center
                                                gap-2.5
                                                rounded-lg
                                                px-2.5
                                                py-1.5
                                                text-[13px]
                                                font-medium
                                                leading-5
                                                transition-colors
                                                duration-200
                                                {{ $menuActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}
                                            ">
                                            <i
                                                class="
                                                    {{ $menu['icon'] }}
                                                    shrink-0
                                                    text-base
                                                "></i>

                                            <span
                                                class="
                                                    min-w-0
                                                    flex-1
                                                    truncate
                                                ">
                                                {{ $menu['label'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- ====================================================
                    PROGRAM KERJA
                ===================================================== --}}

                @if ($canAccessSharedModule && $prokerMenus->isNotEmpty())
                    <li>
                        <button type="button" title="Program Kerja" @click="toggleSubmenu('proker')"
                            :aria-expanded="isSubmenuOpen('proker')"
                            class="
                                menu-item
                                group
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                            "
                            :class="[
                                isMenuActive('proker') ?
                                'menu-item-active' :
                                'menu-item-inactive',
                            
                                $store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                "
                                :class="isMenuActive('proker') ?
                                    'menu-item-icon-active' :
                                    'menu-item-icon-inactive'">
                                <i class="ri-calendar-todo-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                    text-left
                                ">
                                Program Kerja
                            </span>

                            <i x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    ri-arrow-down-s-line
                                    ml-auto
                                    shrink-0
                                    text-base
                                    transition-transform
                                    duration-200
                                "
                                :class="isSubmenuOpen('proker') ?
                                    'rotate-180 text-brand-500' :
                                    ''"></i>
                        </button>

                        <div x-cloak
                            x-show="
                                isSubmenuOpen('proker')
                                && $store.sidebar.isWide()
                            "
                            x-transition>
                            <ul
                                class="
                                    ml-[22px]
                                    mt-1
                                    space-y-0.5
                                    border-l
                                    border-gray-200
                                    pl-[17px]
                                    dark:border-gray-700
                                ">
                                @foreach ($prokerMenus as $menu)
                                    @php
                                        $menuPatterns = \Illuminate\Support\Arr::wrap(
                                            $menu['active'] ?? $menu['route'],
                                        );

                                        $menuActive = request()->routeIs(...$menuPatterns);
                                    @endphp

                                    <li>
                                        <a href="{{ route($menu['route']) }}" @click="closeMobileSidebar()"
                                            class="
                                                menu-dropdown-item
                                                flex
                                                min-h-9
                                                items-center
                                                gap-2.5
                                                rounded-lg
                                                px-2.5
                                                py-1.5
                                                text-[13px]
                                                font-medium
                                                leading-5
                                                transition-colors
                                                duration-200
                                                {{ $menuActive ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}
                                            ">
                                            <i
                                                class="
                                                    {{ $menu['icon'] }}
                                                    shrink-0
                                                    text-base
                                                "></i>

                                            <span
                                                class="
                                                    min-w-0
                                                    flex-1
                                                    truncate
                                                ">
                                                {{ $menu['label'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- ====================================================
                    PERMOHONAN INFORMASI
                ===================================================== --}}

                @if (\Illuminate\Support\Facades\Route::has('admin.permohonan.index'))
                    <li>
                        <a href="{{ route('admin.permohonan.index') }}" title="Permohonan Informasi"
                            @click="closeMobileSidebar()"
                            class="
                                menu-item
                                group
                                relative
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                                {{ $permohonanActive ? 'menu-item-active' : 'menu-item-inactive' }}
                            "
                            :class="$store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                    {{ $permohonanActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}
                                ">
                                <i class="ri-file-search-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()" class="min-w-0 flex-1 truncate">
                                Permohonan Informasi
                            </span>

                            @if ($permohonanNotificationCount > 0)
                                <span x-cloak x-show="$store.sidebar.isWide()"
                                    class="
                                        ml-auto
                                        flex
                                        h-5
                                        min-w-5
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-full
                                        bg-red-500
                                        px-1.5
                                        text-[10px]
                                        font-bold
                                        leading-none
                                        text-white
                                    ">
                                    {{ $permohonanNotificationCount > 99 ? '99+' : $permohonanNotificationCount }}
                                </span>

                                <span x-cloak x-show="$store.sidebar.isCompact()"
                                    class="
                                        absolute
                                        right-2.5
                                        top-1.5
                                        h-2.5
                                        w-2.5
                                        rounded-full
                                        border-2
                                        border-white
                                        bg-red-500
                                        dark:border-gray-900
                                    "></span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- ====================================================
                    NOTIFIKASI
                ===================================================== --}}

                @if (\Illuminate\Support\Facades\Route::has('admin.notifikasi.index'))
                    <li>
                        <a href="{{ route('admin.notifikasi.index') }}" title="Notifikasi"
                            @click="closeMobileSidebar()"
                            class="
                                menu-item
                                group
                                relative
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                                {{ $notifikasiActive ? 'menu-item-active' : 'menu-item-inactive' }}
                            "
                            :class="$store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                    {{ $notifikasiActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}
                                ">
                                <i class="ri-notification-3-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()" class="min-w-0 flex-1 truncate">
                                Notifikasi
                            </span>

                            @if ($unreadNotificationCount > 0)
                                <span x-cloak x-show="$store.sidebar.isWide()"
                                    class="
                                        ml-auto
                                        flex
                                        h-5
                                        min-w-5
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-full
                                        bg-red-500
                                        px-1.5
                                        text-[10px]
                                        font-bold
                                        leading-none
                                        text-white
                                    ">
                                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                </span>

                                <span x-cloak x-show="$store.sidebar.isCompact()"
                                    class="
                                        absolute
                                        right-2.5
                                        top-1.5
                                        h-2.5
                                        w-2.5
                                        rounded-full
                                        border-2
                                        border-white
                                        bg-red-500
                                        dark:border-gray-900
                                    "></span>
                            @endif
                        </a>
                    </li>
                @endif

                {{-- ====================================================
                    CHAT
                ===================================================== --}}

                @if ($isAdminUtama && \Illuminate\Support\Facades\Route::has('admin.pesan-masuk.index'))
                    <li>
                        <a href="{{ route('admin.pesan-masuk.index') }}" title="Chat"
                            @click="closeMobileSidebar()"
                            class="
                                menu-item
                                group
                                relative
                                flex
                                min-h-10
                                w-full
                                items-center
                                gap-3
                                rounded-lg
                                px-3
                                py-2
                                text-[13px]
                                font-medium
                                leading-5
                                transition-colors
                                duration-200
                                {{ $chatActive ? 'menu-item-active' : 'menu-item-inactive' }}
                            "
                            :class="$store.sidebar.isCompact() ?
                                'xl:justify-center' :
                                'justify-start'">
                            <span
                                class="
                                    flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                    {{ $chatActive ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}
                                ">
                                <i class="ri-chat-3-line text-lg"></i>
                            </span>

                            <span x-cloak x-show="$store.sidebar.isWide()" class="min-w-0 flex-1 truncate">
                                Chat
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

{{-- ====================================================
    CMS
===================================================== --}}
@if (
    \Illuminate\Support\Facades\Route::has('admin.pages.index') ||
    \Illuminate\Support\Facades\Route::has('admin.menu.index')
)
    <li x-data="{ open: {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.menu.*') ? 'true' : 'false' }} }">

        <button
            @click="open = !open"
            class="
                menu-item
                group
                relative
                flex
                min-h-10
                w-full
                items-center
                gap-3
                rounded-lg
                px-3
                py-2
                text-[13px]
                font-medium
                leading-5
                transition-colors
                duration-200
                {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.menus.*')
                    ? 'menu-item-active'
                    : 'menu-item-inactive' }}
            "
            :class="$store.sidebar.isCompact()
                ? 'xl:justify-center'
                : 'justify-start'">

            <span
                class="
                    flex
                    h-6
                    w-6
                    shrink-0
                    items-center
                    justify-center
                ">
                <i class="ri-pages-line text-lg"></i>
            </span>

            <span
                x-cloak
                x-show="$store.sidebar.isWide()"
                class="min-w-0 flex-1 truncate">
                CMS
            </span>

            <i
                x-show="$store.sidebar.isWide()"
                :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'">
            </i>
        </button>

        <ul
            x-show="open"
            x-transition
            class="mt-1 ml-6 space-y-1">

            <li>
                <a href="{{ route('admin.pages.index') }}"
                    class="menu-item flex items-center rounded-lg px-3 py-2 text-sm">
                    Manajemen Halaman
                </a>
            </li>

            <li>
                <a href="{{ route('admin.menu.index') }}"
                    class="menu-item flex items-center rounded-lg px-3 py-2 text-sm">
                    Manajemen Menu
                </a>
            </li>

            <li>
    <a href="{{ route('admin.module.index') }}">
        Module
    </a>
</li>

        </ul>
    </li>
@endif

        {{-- ================================================================
            PROFIL ADMIN
        ================================================================= --}}

        <div
            class="
                mt-4
                shrink-0
                border-t
                border-gray-200
                pt-3
                dark:border-gray-800
            ">
            <div x-cloak x-show="$store.sidebar.isWide()" x-transition.opacity
                class="
                    flex
                    min-w-0
                    items-center
                    gap-2.5
                    rounded-xl
                    px-2
                    py-2
                    transition
                    hover:bg-gray-50
                    dark:hover:bg-white/[0.03]
                ">
                <div
                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-brand-500
                        text-sm
                        font-bold
                        text-white
                    ">
                    {{ $adminInitial }}
                </div>

                <div class="min-w-0 flex-1">
                    <div
                        class="
                            truncate
                            text-[13px]
                            font-semibold
                            text-gray-800
                            dark:text-white
                        ">
                        {{ $adminDisplayName }}
                    </div>

                    <div
                        class="
                            mt-0.5
                            truncate
                            text-[11px]
                            text-gray-500
                            dark:text-gray-400
                        ">
                        {{ $roleLabel }}
                    </div>
                </div>
            </div>

            <div x-cloak x-show="$store.sidebar.isCompact()"
                class="
                    hidden
                    justify-center
                    py-2
                    xl:flex
                "
                title="{{ $adminDisplayName }}">
                <div
                    class="
                        flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-full
                        bg-brand-500
                        text-sm
                        font-bold
                        text-white
                    ">
                    {{ $adminInitial }}
                </div>
            </div>
        </div>
    </div>
</aside>
