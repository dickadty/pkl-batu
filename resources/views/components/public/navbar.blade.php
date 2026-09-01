@php
    $isHome = request()->is('/');

    $resolveMenuLink = static function ($menu): string {
        $menuName = strtolower(trim((string) data_get($menu, 'nama')));

        if (Auth::guard('public')->check() && str_contains($menuName, 'permohonan informasi')) {
            return route('public.permohonan.index');
        }

        return (string) data_get($menu, 'link', url('/'));
    };
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }
</style>

<!-- Main navbar -->
<nav id="navbar"
    class="
        fixed top-0 left-0 right-0 z-50
        transition-all duration-300

        {{ $isHome ? 'bg-transparent' : 'bg-white shadow-md' }}
    ">
    <div class="max-w-6xl mx-auto px-5 lg:px-8">

        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">

                <img src="{{ asset('assets/img/logo/LogoKotaBatu.webp') }}" class="h-12 w-auto" alt="PPID">

                <div>
                    <h1
                        class="navbar-title text-[1rem] font-bold leading-none tracking-tight transition-all duration-300
                    {{ $isHome ? 'text-white' : 'text-[#033927]' }}">

                        PPID Kota Batu

                    </h1>

                    <p
                        class="navbar-subtitle mt-0.5 text-[0.50rem] leading-5 transition-all duration-300
                    {{ $isHome ? 'text-slate-200' : 'text-slate-500' }}">

                        Pejabat Pengelola Informasi dan Dokumentasi

                    </p>
                </div>

            </a>

            {{-- Menu --}}
            <div
                class="hidden min-w-0 flex-1 items-center justify-center gap-4 lg:flex xl:gap-7 text-[0.875rem] font-medium">
                @foreach ($menus as $menu)
                    @if ($menu->children->count())
                        <div class="relative shrink-0 group">
                            <button type="button"
                                class="navbar-link inline-flex items-center gap-1 whitespace-nowrap text-[0.875rem] font-medium transition {{ $isHome ? 'text-white hover:text-emerald-700' : 'text-slate-700 hover:text-emerald-700' }}">
                                {{ $menu->nama }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M6 9l6 6l6-6" />
                                </svg>
                            </button>

                            <div
                                class="pointer-events-none absolute left-0 top-full z-50 hidden w-56 pt-2 group-hover:pointer-events-auto group-hover:block">
                                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xl">
                                    @foreach ($menu->children as $child)
                                        @if ($child->children->count())
                                            <div class="relative group/child">
                                                <a href="{{ $resolveMenuLink($child) }}"
                                                    @if ($child->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                                                    class="flex items-center justify-between gap-2 rounded-lg px-2 py-2 text-[0.875rem] text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
                                                    <span>{{ $child->nama }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" aria-hidden="true">
                                                        <path d="M9 6l6 6l-6 6" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="pointer-events-none absolute left-full top-0 z-50 hidden w-56 pl-2 group-hover/child:pointer-events-auto group-hover/child:block">
                                                    <div
                                                        class="rounded-xl border border-slate-200 bg-white p-3 shadow-xl">
                                                        @foreach ($child->children as $grandChild)
                                                            <a href="{{ $resolveMenuLink($grandChild) }}"
                                                                @if ($grandChild->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                                                                class="block rounded-lg px-2 py-2 text-[0.875rem] text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
                                                                {{ $grandChild->nama }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ $resolveMenuLink($child) }}"
                                                @if ($child->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                                                class="block rounded-lg px-2 py-2 text-[0.875rem] text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700">
                                                {{ $child->nama }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $resolveMenuLink($menu) }}"
                            @if ($menu->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                            class="navbar-link shrink-0 whitespace-nowrap text-[0.875rem] font-medium transition {{ $isHome ? 'text-white hover:text-emerald-700' : 'text-slate-700 hover:text-emerald-700' }}">
                            {{ $menu->nama }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Login --}}
            <div class="hidden lg:block">

                @if (Auth::guard('public')->check())
                    <form action="{{ route('public.logout') }}" method="POST">
                        @csrf

                        <button id="navbar-login" type="submit"
                            class="
                            px-4 py-2 rounded-full text-[0.875rem] font-medium transition-all duration-300

                            {{ $isHome
                                ? 'border border-white text-white hover:border-emerald-300 hover:text-emerald-300'
                                : 'border border-slate-300 text-slate-700 hover:border-emerald-700 hover:text-emerald-700' }}
                        ">

                            Logout

                        </button>

                    </form>
                @else
                    <a href="{{ route('login') }}" id="navbar-login"
                        class="
                        inline-block px-4 py-2 rounded-full text-[0.875rem] font-medium transition-all duration-300

                        {{ $isHome
                            ? 'border border-white text-white hover:border-emerald-300 hover:text-emerald-300'
                            : 'border border-slate-300 text-slate-700 hover:border-emerald-700 hover:text-emerald-700' }}
                    ">

                        Login

                    </a>
                @endif

            </div>

            <button id="public-menu-toggle" type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-current/20 text-current lg:hidden"
                aria-expanded="false" aria-controls="public-mobile-menu" aria-label="Buka menu navigasi">
                <i class="ri-menu-line text-xl"></i>
            </button>

        </div>

    </div>
</nav>

<div id="public-mobile-menu" hidden
    class="fixed inset-x-0 top-20 z-40 border-b border-slate-200 bg-white px-4 py-4 shadow-lg lg:hidden">
    <div class="mx-auto max-w-6xl space-y-1">
        @foreach ($menus as $menu)
            @if ($menu->children->count())
                <div class="border-b border-slate-100 pb-2 pt-1 last:border-0">
                    <p class="px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        {{ $menu->nama }}</p>
                    @foreach ($menu->children as $child)
                        <a href="{{ $resolveMenuLink($child) }}"
                            @if ($child->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            {{ $child->nama }}
                        </a>
                    @endforeach
                </div>
            @else
                <a href="{{ $resolveMenuLink($menu) }}"
                    @if ($menu->tipe === 'url') target="_blank" rel="noopener noreferrer" @endif
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    {{ $menu->nama }}
                </a>
            @endif
        @endforeach

        <div class="border-t border-slate-100 pt-3">
            @if (Auth::guard('public')->check())
                <form action="{{ route('public.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-left text-sm font-medium text-slate-700">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="block rounded-lg border border-slate-300 px-3 py-2.5 text-sm font-medium text-slate-700">Login</a>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('public-menu-toggle');
        const menu = document.getElementById('public-mobile-menu');

        if (!toggle || !menu) {
            return;
        }

        const closeMenu = () => {
            menu.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', () => {
            const isOpen = !menu.hidden;
            menu.hidden = isOpen;
            toggle.setAttribute('aria-expanded', String(!isOpen));
        });

        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeMenu();
            }
        });
    });
</script>


@if ($isHome)
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const navbar = document.getElementById('navbar');

            const title = document.querySelector('.navbar-title');
            const subtitle = document.querySelector('.navbar-subtitle');

            const links = document.querySelectorAll('.navbar-link');

            const login = document.getElementById('navbar-login');

            function updateNavbar() {

                if (window.scrollY > 50) {

                    navbar.classList.remove('bg-transparent');
                    navbar.classList.add('bg-white', 'shadow-md');

                    title.classList.remove('text-white');
                    title.classList.add('text-[#033927]');

                    subtitle.classList.remove('text-slate-200');
                    subtitle.classList.add('text-slate-500');

                    links.forEach(link => {
                        link.classList.remove('text-white');
                        link.classList.add('text-slate-700');
                    });

                    login.classList.remove(
                        'border-white',
                        'text-white'
                    );

                    login.classList.add(
                        'border-slate-300',
                        'text-slate-700'
                    );

                } else {

                    navbar.classList.remove('bg-white', 'shadow-md');
                    navbar.classList.add('bg-transparent');

                    title.classList.remove('text-[#033927]');
                    title.classList.add('text-white');

                    subtitle.classList.remove('text-slate-500');
                    subtitle.classList.add('text-slate-200');

                    links.forEach(link => {
                        link.classList.remove('text-slate-700');
                        link.classList.add('text-white');
                    });

                    login.classList.remove(
                        'border-slate-300',
                        'text-slate-700'
                    );

                    login.classList.add(
                        'border-white',
                        'text-white'
                    );
                }
            }

            updateNavbar();

            window.addEventListener('scroll', updateNavbar);

        });
    </script>
@endif
