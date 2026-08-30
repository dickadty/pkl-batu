<section class="relative overflow-hidden bg-white py-12 lg:py-16">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div
            class="relative overflow-hidden rounded-[28px] bg-linear-to-br from-green-950 via-green-900 to-emerald-700 shadow-xl shadow-emerald-950/10">

            <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full border border-white/10">
            </div>

            <div class="pointer-events-none absolute -left-10 -top-10 h-44 w-44 rounded-full border border-white/10">
            </div>

            <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full border border-white/10">
            </div>

            <div
                class="pointer-events-none absolute -bottom-32 -right-24 h-80 w-80 rounded-full bg-emerald-400/10 blur-2xl">
            </div>

            <div class="relative px-5 py-8 sm:px-7 lg:px-10 lg:py-10">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

                    <div class="max-w-xl">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-emerald-50 backdrop-blur-sm">

                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-50"></span>

                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                            </span>

                            Statistik Website

                        </span>

                        <h2 class="mt-5 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            Statistik Kunjungan PPID
                        </h2>

                        <p class="mt-3 text-sm leading-7 text-emerald-100/90">
                            Ringkasan aktivitas kunjungan website PPID Kota Batu
                            berdasarkan periode waktu.
                        </p>

                    </div>

                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-3.5 py-2.5 text-xs text-emerald-100 backdrop-blur-sm">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        Statistik diperbarui otomatis

                    </div>

                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div
                        class="group relative overflow-hidden rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:bg-white/15">

                        <div
                            class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-white/5 transition duration-300 group-hover:scale-125">
                        </div>

                        <div class="relative flex items-start justify-between gap-4">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 4.5h13.5A1.5 1.5 0 0120.25 6v13.5A1.5 1.5 0 0118.75 21H5.25a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5z" />
                                </svg>
                            </div>

                            <span
                                class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-100">
                                Hari Ini
                            </span>

                        </div>

                        <div class="relative mt-6">

                            <p class="tabular-nums text-4xl font-bold tracking-tight text-white">
                                {{ number_format((int) ($visitorStats['today_hits'] ?? 0), 0, ',', '.') }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-emerald-50">
                                Kunjungan Hari Ini
                            </p>

                            <div class="mt-4 flex items-center gap-2 border-t border-white/10 pt-4">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-emerald-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                                </svg>

                                <span class="text-xs text-emerald-100/80">
                                    {{ number_format((int) ($visitorStats['today_visitors'] ?? 0), 0, ',', '.') }}
                                    pengunjung unik
                                </span>

                            </div>

                        </div>

                    </div>

                    <div
                        class="group relative overflow-hidden rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:bg-white/15">

                        <div
                            class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-white/5 transition duration-300 group-hover:scale-125">
                        </div>

                        <div class="relative flex items-start justify-between gap-4">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 4.5h13.5A1.5 1.5 0 0120.25 6v13.5A1.5 1.5 0 0118.75 21H5.25a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5z" />

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 13.5h.008v.008H7.5V13.5zm4.5 0h.008v.008H12V13.5zm4.5 0h.008v.008H16.5V13.5zM7.5 17.25h.008v.008H7.5v-.008zm4.5 0h.008v.008H12v-.008z" />
                                </svg>
                            </div>

                            <span
                                class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-100">
                                Bulan Ini
                            </span>

                        </div>

                        <div class="relative mt-6">

                            <p class="tabular-nums text-4xl font-bold tracking-tight text-white">
                                {{ number_format((int) ($visitorStats['month_hits'] ?? 0), 0, ',', '.') }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-emerald-50">
                                Kunjungan Bulan Ini
                            </p>

                            <div class="mt-4 flex items-center gap-2 border-t border-white/10 pt-4">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-emerald-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                                </svg>

                                <span class="text-xs text-emerald-100/80">
                                    {{ number_format((int) ($visitorStats['month_visitors'] ?? 0), 0, ',', '.') }}
                                    pengunjung unik
                                </span>

                            </div>

                        </div>

                    </div>

                    <div
                        class="group relative overflow-hidden rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:bg-white/15">

                        <div
                            class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-white/5 transition duration-300 group-hover:scale-125">
                        </div>

                        <div class="relative flex items-start justify-between gap-4">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 5.25h16.5v13.5H3.75V5.25z" />

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 3v4.5M15.75 3v4.5M3.75 9h16.5" />
                                </svg>
                            </div>

                            <span
                                class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-100">
                                Tahun Ini
                            </span>

                        </div>

                        <div class="relative mt-6">

                            <p class="tabular-nums text-4xl font-bold tracking-tight text-white">
                                {{ number_format((int) ($visitorStats['year_hits'] ?? 0), 0, ',', '.') }}
                            </p>

                            <p class="mt-2 text-sm font-semibold text-emerald-50">
                                Kunjungan Tahun Ini
                            </p>

                            <div class="mt-4 flex items-center gap-2 border-t border-white/10 pt-4">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-emerald-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                                </svg>

                                <span class="text-xs text-emerald-100/80">
                                    {{ number_format((int) ($visitorStats['year_visitors'] ?? 0), 0, ',', '.') }}
                                    pengunjung unik
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
