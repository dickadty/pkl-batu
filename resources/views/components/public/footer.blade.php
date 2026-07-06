<footer class="px-6 md:px-16 lg:px-24 xl:px-32 pt-8 w-full bg-blue-950 text-slate-300">
    <div class="flex flex-col md:flex-row justify-between w-full gap-10 border-b border-gray-500/30 pb-6">
        <div class="md:max-w-96">
            <h2 class="text-xl font-bold text-white">
                PPID Kota Batu
            </h2>
            <p class="mt-6 text-[13px] leading-6">
                Dalam konteks pemerintahan daerah, PPID adalah pejabat yang ditetapkan melalui SK Gubernur/Bupati/ Walikota, yang memiliki wewenang dalam bidang penyimpanan, pendokumentasian, penyediaan dan pelayanan informasi serta bertanggungjawab langsung kepada Sekretaris Daerah selaku atasan PPID.
            </p>
        </div>
        <div class="flex-1 flex items-start md:justify-end gap-20">
            <div>
                <h2 class="font-semibold mb-5 text-white">KABAR TERKINI</h2>
                <ul class="text-[13px] space-y-2">
                    <li><a href="{{ url('/') }}" class="text-slate-300">Beranda</a></li>
                    <li><a href="{{ route('public.informasi.index') }}" class="text-slate-300 hover:text-gray-300">Informasi Publik</a></li>
                    <li><a href="#" class="text-slate-300 hover:text-gray-50">Profil PPID</a></li>
                </ul>
            </div>
            <div>
                <h2 class="font-semibold mb-5 text-white">Sosial Media</h2>
                <div class="text-[13px] space-y-2">
                    <p>Dinas Komunikasi dan Informatika Kota Batu</p>
                    <p>Jl. Panglima Sudirman, Kota Batu</p>
                    <p>Jawa Timur</p>
                </div>
            </div>
        </div>
    </div>
    <p class="pt-4 text-center text-xs md:text-sm pb-5">
        &copy; {{ date('Y') }} <a href="{{ url('/') }}">PPID Kota Batu</a>. Semua hak dilindungi.
    </p>
</footer>