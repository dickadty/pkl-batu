<section class="relative h-[550px] overflow-hidden">

    {{-- Slide Background --}}
    <div class="absolute inset-0">
        <img
            src="{{ asset('assets/img/banner/1001824500.jpg') }}"
            alt="PPID Kota Batu"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    {{-- Content --}}
    <div class="relative h-full">
        <div class="max-w-7xl mx-auto px-6 h-full">
            <div class="flex items-center h-full">
                <div class="max-w-2xl text-white">

                    <h1 class="mt-5 text-4xl md:text-6xl font-bold leading-tight">
                        Pejabat Pengelola Informasi dan Dokumentasi
                    </h1>
                    <p class="mt-6 text-lg text-slate-200 leading-relaxed">
                        Menyediakan layanan informasi publik yang cepat,
                        transparan dan mudah diakses oleh masyarakat Kota Batu.
                    </p>

                    <!-- <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('public.informasi.index') }}"
                           class="px-6 py-3 rounded-lg bg-blue-700 text-white text-sm font-medium hover:bg-blue-800">
                            Lihat Informasi Publik
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>