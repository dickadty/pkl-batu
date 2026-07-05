<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold">
                    P
                </div>

                <div>
                    <div class="font-bold text-slate-900 leading-tight">
                        PPID Kota Batu
                    </div>
                    <div class="text-xs text-slate-500">
                        Pejabat Pengelola Informasi dan Dokumentasi
                    </div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ url('/') }}" class="text-slate-600 hover:text-blue-700">
                    Beranda
                </a>

                <a href="{{ route('public.informasi.index') }}"
                    class="{{ request()->routeIs('public.informasi.*') ? 'text-blue-700' : 'text-slate-600 hover:text-blue-700' }}">
                    Informasi Publik
                </a>

                <a href="{{ route('public.berita.index') }}"
                    class="{{ request()->routeIs('public.berita.*') ? 'text-blue-700' : 'text-slate-600 hover:text-blue-700' }}">
                    Berita
                </a>
                <a href="#" class="text-slate-600 hover:text-blue-700">
                    Profil PPID
                </a>

                <a href="#" class="text-slate-600 hover:text-blue-700">
                    Kontak
                </a>

                <a href="{{ route('public.permohonan.create') }}"
                    class="{{ request()->routeIs('public.permohonan.*') ? 'text-blue-700' : 'text-slate-600 hover:text-blue-700' }}">
                    Ajukan Permohonan
                </a>

                @if (Auth::guard('public')->check())
                    <form action="{{ route('public.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-blue-700">
                            Logout Warga
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-700">
                        Login Warga
                    </a>
                @endif
            </nav>

            <a href="{{ route('admin.login') }}"
                class="hidden md:inline-flex px-4 py-2 rounded-lg bg-blue-700 text-white text-sm font-medium hover:bg-blue-800">
                Login Admin
            </a>

        </div>
    </div>
</header>
