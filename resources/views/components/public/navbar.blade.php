<header
    id="mainHeader"
    class="bg-white sticky top-0 z-50 border-b border-slate-200 transition-all duration-100">

    <div class="flex">

        {{-- LOGO --}}
        <a href="{{ url('/') }}"
            class="w-[390px] px-8 py-7 flex items-center gap-6 bg-white">

            <img
                src="{{ asset('assets/img/logo/Logo_Kota_Batu,_Jawa_Timur_(Seal_of_Batu,_East_Java).svg.webp') }}"
                alt="Logo PPID Kota Batu"
                class="w-14 h-14 object-contain flex-shrink-0">

            <div>
                <h1 class="text-[2rem] font-bold leading-none tracking-tight text-slate-800">
                    PPID Kota Batu
                </h1>

                <p class="mt-1 text-[15px] leading-5 text-slate-500">
                    Pejabat Pengelola Informasi dan Dokumentasi
                </p>
            </div>

        </a>

        {{-- BAGIAN KANAN --}}
        <div class="flex-1">

            {{-- BAR KONTAK --}}
            <div
                class="h-9 bg-blue-950 text-white flex items-center px-8 text-[13px]">

                <span>Pertanyaan? Hubungi (0341) 512178</span>

                <span class="mx-5 text-blue-300">|</span>

                <span>ppid@batukota.go.id</span>

                <div class="ml-auto">
                    <a href="{{ route('admin.login') }}"
                        class="hover:text-blue-200 transition-colors duration-200">
                        Keluar
                    </a>
                </div>

            </div>

            {{-- NAVBAR --}}
            <div
                class="h-[68px] flex items-center border-t border-slate-200 px-8">

                <nav class="flex items-center gap-10 text-[15px] font-medium">

                    <a href="{{ url('/') }}"
                        class="transition-colors duration-200 {{ request()->is('/') ? 'text-blue-700 border-b-2 border-blue-700 pb-1' : 'text-slate-700 hover:text-blue-700 pb-1 border-b-2 border-transparent hover:border-blue-200' }}">
                        Beranda
                    </a>

                    <a href="{{ route('public.informasi.index') }}"
                        class="transition-colors duration-200 {{ request()->routeIs('public.informasi.*') ? 'text-blue-700 border-b-2 border-blue-700 pb-1' : 'text-slate-700 hover:text-blue-700 pb-1 border-b-2 border-transparent hover:border-blue-200' }}">
                        Informasi Publik
                    </a>

                    <a href="{{ route('public.berita.index') }}"
                        class="transition-colors duration-200 {{ request()->routeIs('public.berita.*') ? 'text-blue-700 border-b-2 border-blue-700 pb-1' : 'text-slate-700 hover:text-blue-700 pb-1 border-b-2 border-transparent hover:border-blue-200' }}">
                        Berita
                    </a>

                    <a href="#"
                        class="text-slate-700 hover:text-blue-700 transition-colors duration-200 pb-1 border-b-2 border-transparent hover:border-blue-200">
                        Profil PPID
                    </a>

                    <a href="#"
                        class="text-slate-700 hover:text-blue-700 transition-colors duration-200 pb-1 border-b-2 border-transparent hover:border-blue-200">
                        Daftar Informasi
                    </a>
                    <a href="{{ route('public.permohonan.create') }}"
                        class="transition-colors duration-200 {{ request()->routeIs('public.permohonan.*') ? 'text-blue-700 border-b-2 border-blue-700 pb-1' : 'text-slate-700 hover:text-blue-700 pb-1 border-b-2 border-transparent hover:border-blue-200' }}">
                        Ajukan Permohonan
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
    const header = document.getElementById('mainHeader');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('shadow-lg');
        } else {
            header.classList.remove('shadow-lg');
        }
    });
</script>