<section class="relative z-30 -mt-16 pb-16">
    <div class="mx-auto max-w-5xl px-4 lg:px-8">

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">

            {{-- Informasi Berkala --}}
            <a href="{{ route('public.informasi.berkala') }}"
                data-aos="fade-up"
                class="group flex min-h-[110px] flex-col items-center justify-center rounded-2xl bg-white px-3 py-4 text-center shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-8.25a2.25 2.25 0 00-2.25-2.25h-10.5A2.25 2.25 0 004.5 6v12a2.25 2.25 0 002.25 2.25h5.25" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 7.5h6M9 12h6M9 16.5h3" />
                    </svg>
                </div>

                <h3 class="text-xs font-semibold leading-relaxed text-green-950">
                    Informasi<br>
                    Berkala
                </h3>
            </a>


            {{-- Informasi Serta Merta --}}
            <a href="{{ route('public.informasi.serta-merta') }}"
                data-aos="fade-up"
                class="group flex min-h-[110px] flex-col items-center justify-center rounded-2xl bg-white px-3 py-4 text-center shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 3L4.5 14.25h6l-1.5 6.75L18 9.75h-6L13.5 3z" />
                    </svg>
                </div>

                <h3 class="text-xs font-semibold leading-relaxed text-green-950">
                    Informasi<br>
                    Serta Merta
                </h3>
            </a>


            {{-- Informasi Setiap Saat --}}
            <a href="{{ route('public.informasi.setiap-saat') }}"
                data-aos="fade-up"
                class="group flex min-h-[110px] flex-col items-center justify-center rounded-2xl bg-white px-3 py-4 text-center shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 18.75V8.25l7.5-4.5 7.5 4.5v10.5" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 21v-6h6v6" />
                    </svg>
                </div>

                <h3 class="text-xs font-semibold leading-relaxed text-green-950">
                    Informasi<br>
                    Setiap Saat
                </h3>
            </a>


            {{-- Informasi Dikecualikan --}}
            <a href="{{ route('public.informasi.dikecualikan') }}"
                data-aos="fade-up"
                class="group flex min-h-[110px] flex-col items-center justify-center rounded-2xl bg-white px-3 py-4 text-center shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-green-950 via-green-900 to-emerald-700 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9a3.75 3.75 0 117.5 0c0 1.33-.69 2.3-1.75 3.05-.97.69-1.75 1.3-1.75 2.45v.75" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 17.25h.008v.008H12v-.008z" />
                    </svg>
                </div>

                <h3 class="text-xs font-semibold leading-relaxed text-green-950">
                    Informasi<br>
                    Dikecualikan
                </h3>
            </a>

        </div>
    </div>
</section>