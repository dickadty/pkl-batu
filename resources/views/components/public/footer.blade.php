<footer
    class="px-6 md:px-16 lg:px-24 xl:px-32 pt-10 w-full text-slate-100"
    style="background: linear-gradient(to top right, #022c22 0%, #064e3b 50%, #047857 100%);">

    <div class="flex flex-col md:flex-row justify-between gap-10 border-b border-white/10 pb-8">

        <!-- About -->
        <div class="md:max-w-md">
            <h2 class="text-2xl font-bold text-white">
                PPID Kota Batu
            </h2>

            <p class="mt-5 text-sm leading-7 text-emerald-50/90">
                Dalam konteks pemerintahan daerah, PPID adalah pejabat yang
                ditetapkan melalui SK Gubernur/Bupati/Walikota yang memiliki
                wewenang dalam bidang penyimpanan, pendokumentasian,
                penyediaan dan pelayanan informasi serta bertanggung jawab
                langsung kepada Sekretaris Daerah selaku atasan PPID.
            </p>
        </div>

        <!-- Kontak -->
        <div class="flex flex-col gap-6">
            <div>
                <h3 class="font-semibold text-white mb-4 uppercase tracking-wide">
                    Kontak
                </h3>

                <div class="space-y-3 text-sm text-white">
                    <p>Dinas Komunikasi dan Informatika Kota Batu</p>
                    <p>Jl. Panglima Sudirman, Kota Batu</p>
                    <p>Jawa Timur</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright -->
    <div class="py-5 text-center">
        <p class="text-sm text-emerald-100/80">
            © {{ date('Y') }}
            <a href="{{ url('/') }}"
                class="font-medium hover:text-amber-300 transition">
                PPID Kota Batu
            </a>
            . Semua hak dilindungi.
        </p>
    </div>

</footer>