

@php
    $isHome = request()->is('/');
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }
</style>

<!-- <svg class="size-full absolute -z-10 inset-0" width="1440" height="720" viewBox="0 0 1440 720" fill="none"
    xmlns="http://www.w3.org/2000/svg">
    <path stroke="#ffffff" stroke-opacity=".7" d="M-15.227 702.342H1439.7" />
    <circle cx="711.819" cy="372.562" r="308.334" stroke="#E2E8F0" stroke-opacity=".7" />
    <circle cx="16.942" cy="20.834" r="308.334" stroke="#E2E8F0" stroke-opacity=".7" />
    <path stroke="#E2E8F0" stroke-opacity=".7" d="M-15.227 573.66H1439.7M-15.227 164.029H1439.7" />
    <circle cx="782.595" cy="411.166" r="308.334" stroke="#E2E8F0" stroke-opacity=".7" />
</svg>
<!-- 
<- Top contact banner -->
<!-- <div class="hidden sm:flex items-center justify-between w-full px-4 md:px-8 lg:px-12 xl:px-16 py-1.5 text-xs text-white"
     style="background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);">
    <div class="flex items-center gap-5">
        <a href="tel:+62341591234" class="flex items-center gap-1.5 hover:text-white/80 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
            (0341) 591234
        </a>
        <a href="mailto:ppid@batukota.go.id" class="flex items-center gap-1.5 hover:text-white/80 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16v16H4z" stroke="none" />
                <path d="M22 6l-10 7L2 6" />
                <path d="M2 6h20v12H2z" />
            </svg>
            ppid@batukota.go.id
        </a>
        <span class="hidden lg:flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
            </svg>
            Jl. Panglima Sudirman, Kota Batu, Jawa Timur
        </span>
    </div>
</div>  -->

<!-- Main navbar -->
 <nav
    id="navbar"
    class="
        fixed top-0 left-0 right-0 z-50
        transition-all duration-300

        {{ $isHome
            ? 'bg-transparent'
            : 'bg-white shadow-md'
        }}
    ">
    <div class="max-w-6xl mx-auto px-5 lg:px-8">

    <div class="flex items-center justify-between h-20">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3">

            <img
                src="{{ asset('assets/img/logo/LogoKotaBatu.webp') }}"
                class="h-12 w-auto"
                alt="PPID">

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
        <div class="hidden md:flex items-center gap-8 text-[0.875rem] font-medium">

            @foreach($menus as $menu)

                @if($menu->children->count())

                    <div class="relative group">

                        <button
                            type="button"
                            class="
                                navbar-link
                                inline-flex items-center gap-1
                                text-[0.875rem]
                                font-medium
                                transition

                                {{ $isHome
                                    ? 'text-white hover:text-emerald-300'
                                    : 'text-slate-700 hover:text-emerald-900'
                                }}
                            ">

                            {{ $menu->nama }}

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">

                                <path d="M6 9l6 6l6-6"/>

                            </svg>

                        </button>

                        <div class="pointer-events-none absolute left-0 top-full pt-2 hidden w-50 group-hover:block group-hover:pointer-events-auto z-50">

                            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xl">

                                @foreach($menu->children as $child)

                                    <a
                                        href="{{ $child->link }}"
                                        @if($child->tipe === 'url')
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        @endif
                                        class="block rounded-xl px-1 py-1 text-[0.875rem] hover:bg-slate-50 transition">

                                        {{ $child->nama }}

                                    </a>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @else

                    <a
                        href="{{ $menu->link }}"
                        @if($menu->tipe === 'url')
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        class="
                            navbar-link
                            text-[0.875rem]
                            font-medium
                            transition

                            {{ $isHome
                                ? 'text-white hover:text-emerald-300'
                                : 'text-slate-700 hover:text-emerald-900'
                            }}
                        ">

                        {{ $menu->nama }}

                    </a>

                @endif

            @endforeach

        </div>

        {{-- Login --}}
        <div class="hidden md:block">

            @if (Auth::guard('public')->check())

                <form action="{{ route('public.logout') }}" method="POST">
                    @csrf

                    <button
                        id="navbar-login"
                        type="submit"
                        class="
                            px-4 py-2 rounded-full text-[0.875rem] font-medium transition-all duration-300

                            {{ $isHome
                                ? 'border border-white text-white'
                                : 'border border-slate-300 text-slate-700'
                            }}
                        ">

                        Logout

                    </button>

                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    id="navbar-login"
                    class="
                        inline-block px-4 py-2 rounded-full text-[0.875rem] font-medium transition-all duration-300

                        {{ $isHome
                            ? 'border border-white text-white'
                            : 'border border-slate-300 text-slate-700'
                        }}
                    ">

                    Login

                </a>

            @endif

        </div>

    </div>

</div>
</nav>


@if($isHome)
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