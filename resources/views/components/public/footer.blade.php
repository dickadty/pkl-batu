<footer class="bg-slate-900 text-slate-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-white font-bold text-lg mb-3">
                    PPID Kota Batu
                </h3>
                <p class="text-sm leading-6">
                    Portal layanan informasi publik untuk mendukung keterbukaan, transparansi, dan akses informasi
                    masyarakat.
                </p>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-3">
                    Menu
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-white">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.informasi.index') }}" class="hover:text-white">
                            Informasi Publik
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white">
                            Profil PPID
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-3">
                    Kontak
                </h3>
                <p class="text-sm leading-6">
                    Dinas Komunikasi dan Informatika Kota Batu<br>
                    Jl. Panglima Sudirman, Kota Batu<br>
                    Jawa Timur
                </p>
            </div>
        </div>

        <div class="border-t border-slate-700 mt-8 pt-5 text-sm text-slate-400">
            &copy; {{ date('Y') }} PPID Kota Batu. Semua hak dilindungi.
        </div>
    </div>
</footer>
