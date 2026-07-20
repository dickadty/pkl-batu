@php
    /*
    |--------------------------------------------------------------------------
    | Admin yang sedang login
    |--------------------------------------------------------------------------
    */

    $admin = auth('admin')->user();

    $adminRole = (int) data_get($admin, 'role', 0);

    $isAdminUtama = $adminRole === 1 || ($admin && method_exists($admin, 'isAdminUtama') && $admin->isAdminUtama());

    $canAccessPengadaan = in_array($adminRole, [1, 2], true);

    /*
    |--------------------------------------------------------------------------
    | Status menu aktif
    |--------------------------------------------------------------------------
    */

    $dashboardActive = request()->routeIs('admin.dashboard');

    $masterActive = request()->routeIs('admin.ppid-pembantu.*', 'admin.akun-admin.*', 'admin.pejabat.*');

    $contentActive = request()->routeIs('admin.slider.*', 'admin.faq.*', 'admin.informasi-publik.*', 'admin.berita.*');

    $pengadaanActive = request()->routeIs('admin.pengadaan.*');

    $permohonanActive = request()->routeIs('admin.permohonan.*');

    $chatActive = request()->routeIs('admin.pesan-masuk.*');

    /*
    |--------------------------------------------------------------------------
    | Daftar submenu Master Data
    |--------------------------------------------------------------------------
    */

    $masterMenus = collect([
        [
            'label' => 'Daftar PPID Pembantu',
            'route' => 'admin.ppid-pembantu.index',
            'active' => 'admin.ppid-pembantu.index',
            'icon' => 'ri-government-line',
        ],
        [
            'label' => 'Tambah PPID Pembantu',
            'route' => 'admin.ppid-pembantu.create',
            'active' => 'admin.ppid-pembantu.create',
            'icon' => 'ri-building-add-line',
        ],
        [
            'label' => 'Daftar Akun Admin',
            'route' => 'admin.akun-admin.index',
            'active' => 'admin.akun-admin.index',
            'icon' => 'ri-admin-line',
        ],
        [
            'label' => 'Tambah Akun Admin',
            'route' => 'admin.akun-admin.create',
            'active' => 'admin.akun-admin.create',
            'icon' => 'ri-user-add-line',
        ],
        [
            'label' => 'Daftar Pejabat',
            'route' => 'admin.pejabat.index',
            'active' => 'admin.pejabat.index',
            'icon' => 'ri-contacts-book-2-line',
        ],
        [
            'label' => 'Tambah Pejabat',
            'route' => 'admin.pejabat.create',
            'active' => 'admin.pejabat.create',
            'icon' => 'ri-user-star-line',
        ],
    ])
        ->filter(fn(array $menu): bool => \Illuminate\Support\Facades\Route::has($menu['route']))
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Daftar submenu Konten dan Informasi
    |--------------------------------------------------------------------------
    */

    $contentMenus = collect([
        [
            'label' => 'Daftar Slider',
            'route' => 'admin.slider.index',
            'active' => 'admin.slider.index',
            'icon' => 'ri-gallery-line',
        ],
        [
            'label' => 'Tambah Slider',
            'route' => 'admin.slider.create',
            'active' => 'admin.slider.create',
            'icon' => 'ri-image-add-line',
        ],
        [
            'label' => 'Daftar FAQ',
            'route' => 'admin.faq.index',
            'active' => 'admin.faq.index',
            'icon' => 'ri-question-answer-line',
        ],
        [
            'label' => 'Tambah FAQ',
            'route' => 'admin.faq.create',
            'active' => 'admin.faq.create',
            'icon' => 'ri-questionnaire-line',
        ],
        [
            'label' => 'Daftar Informasi',
            'route' => 'admin.informasi-publik.index',
            'active' => 'admin.informasi-publik.index',
            'icon' => 'ri-file-list-3-line',
        ],
        [
            'label' => 'Tambah Informasi',
            'route' => 'admin.informasi-publik.create',
            'active' => 'admin.informasi-publik.create',
            'icon' => 'ri-file-add-line',
        ],
        [
            'label' => 'Daftar Berita',
            'route' => 'admin.berita.index',
            'active' => 'admin.berita.index',
            'icon' => 'ri-newspaper-line',
        ],
        [
            'label' => 'Tambah Berita',
            'route' => 'admin.berita.create',
            'active' => 'admin.berita.create',
            'icon' => 'ri-draft-line',
        ],
    ])
        ->filter(fn(array $menu): bool => \Illuminate\Support\Facades\Route::has($menu['route']))
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Daftar submenu Pengadaan
    |--------------------------------------------------------------------------
    */

    $pengadaanMenus = collect([
        [
            'label' => 'Daftar Pengadaan',
            'route' => 'admin.pengadaan.index',
            'active' => 'admin.pengadaan.index',
            'icon' => 'ri-file-list-2-line',
        ],
        [
            'label' => 'Tambah Pengadaan',
            'route' => 'admin.pengadaan.create',
            'active' => 'admin.pengadaan.create',
            'icon' => 'ri-shopping-cart-2-line',
        ],
    ])
        ->filter(fn(array $menu): bool => \Illuminate\Support\Facades\Route::has($menu['route']))
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Notifikasi permohonan
    |--------------------------------------------------------------------------
    */

    $notificationCount = (int) ($totalNotifikasiAdminUtama ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    $logoPath = asset('assets/src/images/logo/logo.svg');

    $logoDarkPath = file_exists(public_path('assets/src/images/logo/logo-dark.svg'))
        ? asset('assets/src/images/logo/logo-dark.svg')
        : $logoPath;

    $logoIconPath = file_exists(public_path('assets/src/images/logo/logo-icon.svg'))
        ? asset('assets/src/images/logo/logo-icon.svg')
        : $logoPath;
@endphp

<aside id="sidebar" x-data="{
    activeMenus: {
        master: @js($masterActive),
        content: @js($contentActive),
        pengadaan: @js($pengadaanActive)
    },

    openSubmenus: {
        master: @js($masterActive),
        content: @js($contentActive),
        pengadaan: @js($pengadaanActive)
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
        $store.sidebar.setMobileOpen(false);
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
    @mouseenter="
        $store.sidebar.setHovered(true)
    "
    @mouseleave="
        $store.sidebar.setHovered(false)
    ">
    {{-- ================================================================
        LOGO
    ================================================================= --}}

    <div class="
            flex
            h-[72px]
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
        <a href="{{ route('admin.dashboard') }}"
            class="
                flex
                min-w-0
                items-center
            "
            aria-label="Dashboard Admin" @click="closeMobileSidebar()">
            {{-- Logo terang --}}
            <img x-cloak x-show="$store.sidebar.isWide()" src="{{ $logoPath }}" alt="Logo" width="150"
                height="38"
                class="
                    block
                    max-h-9
                    max-w-[165px]
                    object-contain
                    dark:hidden
                ">

            {{-- Logo gelap --}}
            <img x-cloak x-show="$store.sidebar.isWide()" src="{{ $logoDarkPath }}" alt="Logo" width="150"
                height="38"
                class="
                    hidden
                    max-h-9
                    max-w-[165px]
                    object-contain
                    dark:block
                ">

            {{-- Logo compact --}}
            <img x-cloak x-show="$store.sidebar.isCompact()" src="{{ $logoIconPath }}" alt="Logo" width="34"
                height="34"
                class="
                    h-[34px]
                    w-[34px]
                    object-contain
                ">
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
            {{-- Judul menu --}}
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

                        <span x-cloak x-show="$store.sidebar.isWide()"
                            class="
                                min-w-0
                                flex-1
                                truncate
                            ">
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
                                isSubmenuOpen('master') &&
                                $store.sidebar.isWide()
                            "
                            x-transition:enter="
                                transition
                                duration-200
                                ease-out
                            "
                            x-transition:enter-start="
                                opacity-0
                                -translate-y-1
                            "
                            x-transition:enter-end="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave="
                                transition
                                duration-150
                                ease-in
                            "
                            x-transition:leave-start="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave-end="
                                opacity-0
                                -translate-y-1
                            ">
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
                                        $menuActive = request()->routeIs($menu['active']);
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
                                isSubmenuOpen('content') &&
                                $store.sidebar.isWide()
                            "
                            x-transition:enter="
                                transition
                                duration-200
                                ease-out
                            "
                            x-transition:enter-start="
                                opacity-0
                                -translate-y-1
                            "
                            x-transition:enter-end="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave="
                                transition
                                duration-150
                                ease-in
                            "
                            x-transition:leave-start="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave-end="
                                opacity-0
                                -translate-y-1
                            ">
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
                                        $menuActive = request()->routeIs($menu['active']);
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

                @if ($canAccessPengadaan && $pengadaanMenus->isNotEmpty())
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
                                isSubmenuOpen('pengadaan') &&
                                $store.sidebar.isWide()
                            "
                            x-transition:enter="
                                transition
                                duration-200
                                ease-out
                            "
                            x-transition:enter-start="
                                opacity-0
                                -translate-y-1
                            "
                            x-transition:enter-end="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave="
                                transition
                                duration-150
                                ease-in
                            "
                            x-transition:leave-start="
                                opacity-100
                                translate-y-0
                            "
                            x-transition:leave-end="
                                opacity-0
                                -translate-y-1
                            ">
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
                                        $menuActive = request()->routeIs($menu['active']);
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

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                ">
                                Permohonan Informasi
                            </span>

                            @if ($notificationCount > 0)
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
                                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
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

                            <span x-cloak x-show="$store.sidebar.isWide()"
                                class="
                                    min-w-0
                                    flex-1
                                    truncate
                                ">
                                Chat
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

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
            {{-- Profil mode lebar --}}
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
                    {{ mb_strtoupper(mb_substr($admin->nama ?? 'A', 0, 1)) }}
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
                        {{ $admin->nama ?? 'Administrator' }}
                    </div>

                    <div
                        class="
                            mt-0.5
                            truncate
                            text-[11px]
                            text-gray-500
                            dark:text-gray-400
                        ">
                        {{ $roleLabel ?? ($adminRole === 1 ? 'Admin Utama' : ($adminRole === 2 ? 'Admin PPID Pembantu' : 'Admin')) }}
                    </div>
                </div>
            </div>

            {{-- Profil mode compact --}}
            <div x-cloak x-show="$store.sidebar.isCompact()"
                class="
                    hidden
                    justify-center
                    py-2
                    xl:flex
                "
                title="{{ $admin->nama ?? 'Administrator' }}">
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
                    {{ mb_strtoupper(mb_substr($admin->nama ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>
    </div>
</aside>
