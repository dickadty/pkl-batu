<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    {{-- Terapkan dark mode sebelum halaman tampil --}}
    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');

            const systemDark = window.matchMedia(
                '(prefers-color-scheme: dark)'
            ).matches;

            const isDark = savedTheme ?
                savedTheme === 'dark' :
                systemDark;

            document.documentElement.classList.toggle(
                'dark',
                isDark
            );
        })();
    </script>

    {{-- Mencegah elemen Alpine berkedip sebelum Alpine aktif --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Alpine global stores --}}
    <script>
        document.addEventListener('alpine:init', () => {
            /*
             * Theme store
             */
            if (!Alpine.store('theme')) {
                Alpine.store('theme', {
                    theme: document.documentElement.classList.contains('dark') ?
                        'dark' :
                        'light',

                    toggle() {
                        this.theme = this.theme === 'dark' ?
                            'light' :
                            'dark';

                        localStorage.setItem(
                            'theme',
                            this.theme
                        );

                        this.apply();
                    },

                    apply() {
                        document.documentElement.classList.toggle(
                            'dark',
                            this.theme === 'dark'
                        );
                    }
                });
            }

            /*
             * Sidebar store
             */
            if (!Alpine.store('sidebar')) {
                Alpine.store('sidebar', {
                    isExpanded: window.innerWidth >= 1280,
                    isMobileOpen: false,
                    isHovered: false,
                    isDesktop: window.innerWidth >= 1280,

                    init() {
                        this.isDesktop = window.innerWidth >= 1280;
                        this.isExpanded = this.isDesktop;

                        window.addEventListener('resize', () => {
                            this.handleResize();
                        });
                    },

                    handleResize() {
                        const desktopNow = window.innerWidth >= 1280;

                        /*
                         * State hanya diubah ketika berpindah
                         * antara ukuran desktop dan mobile.
                         */
                        if (desktopNow === this.isDesktop) {
                            return;
                        }

                        this.isDesktop = desktopNow;
                        this.isExpanded = desktopNow;
                        this.isMobileOpen = false;
                        this.isHovered = false;
                    },

                    /*
                     * Dipakai tombol toggle utama.
                     */
                    toggle() {
                        if (window.innerWidth < 1280) {
                            this.toggleMobileOpen();
                            return;
                        }

                        this.toggleExpanded();
                    },

                    toggleExpanded() {
                        this.isExpanded = !this.isExpanded;
                        this.isHovered = false;
                        this.isMobileOpen = false;
                    },

                    toggleMobileOpen() {
                        this.isMobileOpen = !this.isMobileOpen;
                    },

                    setMobileOpen(value) {
                        this.isMobileOpen = Boolean(value);
                    },

                    setHovered(value) {
                        if (
                            window.innerWidth >= 1280 &&
                            !this.isExpanded
                        ) {
                            this.isHovered = Boolean(value);
                            return;
                        }

                        this.isHovered = false;
                    },

                    /*
                     * Sidebar lebar menggunakan ukuran 272px.
                     */
                    isWide() {
                        return (
                            this.isExpanded ||
                            this.isMobileOpen ||
                            this.isHovered
                        );
                    },

                    /*
                     * Wrapper desktop mengikuti lebar sidebar 272px.
                     */
                    isDesktopWide() {
                        return (
                            this.isExpanded ||
                            this.isHovered
                        );
                    },

                    /*
                     * Sidebar compact menggunakan ukuran 82px.
                     */
                    isCompact() {
                        return !this.isWide();
                    }
                });
            }
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body x-data
    class="
        min-h-full
        overflow-x-hidden
        bg-gray-100
        text-gray-900
        antialiased
        dark:bg-gray-900
        dark:text-gray-100
    ">
    <div class="min-h-screen">
        {{-- ============================================================
            MOBILE BACKDROP
        ============================================================= --}}

        <div x-cloak x-show="$store.sidebar.isMobileOpen" x-transition:enter="transition-opacity duration-300 ease-out"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200 ease-in" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sidebar.setMobileOpen(false)"
            class="
                fixed
                inset-0
                z-40
                bg-gray-900/50
                xl:hidden
            ">
        </div>

        {{-- ============================================================
            SIDEBAR
        ============================================================= --}}

        @include('layouts.admin.sidebar')

        {{-- ============================================================
            HEADER DAN CONTENT WRAPPER
        ============================================================= --}}

        <div class="
                min-h-screen
                transition-[margin-left]
                duration-300
                ease-in-out
            "
            :class="{
                'xl:ml-[272px]': $store.sidebar.isDesktopWide(),
                'xl:ml-[82px]': !$store.sidebar.isDesktopWide()
            }">
            {{-- Header --}}
            @include('layouts.app-header')

            {{-- Page content --}}
            <main class="p-4 md:p-6">
                <div class="mx-auto w-full max-w-screen-2xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
