<section class="relative overflow-hidden bg-white py-7 lg:py-13">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="rounded-[28px] px-6 py-10 sm:px-8 lg:px-10 lg:py-12">

            <div class="grid items-center gap-10 lg:grid-cols-[1fr_2.5fr]">

                {{-- Judul --}}
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-green-950 sm:text-4xl">
                        Statistik Kunjungan
                    </h2>

                    <p class="mt-3 max-w-xs text-sm leading-6 text-green-900/70 sm:text-base">
                        Ringkasan aktivitas kunjungan website PPID Kota Batu

                    </p>
                </div>


                {{-- Statistik --}}
                <div
                    class="grid grid-cols-1 sm:grid-cols-3 sm:divide-x sm:divide-green-900/10">

                    {{-- Hari Ini --}}
                    <div class="py-4 sm:px-6 sm:py-0 lg:px-8">
                        <p class="tabular-nums text-4xl font-bold tracking-tight text-emerald-600">
                            {{ number_format((int) ($visitorStats['today_hits'] ?? 0), 0, ',', '.') }}
                        </p>

                        <p class="mt-2 text-sm font-medium text-green-950">
                            Kunjungan Hari Ini
                        </p>

                        <p class="mt-1 text-xs text-green-900/60">
                            {{ number_format((int) ($visitorStats['today_visitors'] ?? 0), 0, ',', '.') }}
                            pengunjung unik
                        </p>
                    </div>


                    {{-- Bulan Ini --}}
                    <div class="border-t border-green-900/10 py-4 sm:border-t-0 sm:px-6 sm:py-0 lg:px-8">
                        <p class="tabular-nums text-4xl font-bold tracking-tight text-emerald-600">
                            {{ number_format((int) ($visitorStats['month_hits'] ?? 0), 0, ',', '.') }}
                        </p>

                        <p class="mt-2 text-sm font-medium text-green-950">
                            Kunjungan Bulan Ini
                        </p>

                        <p class="mt-1 text-xs text-green-900/60">
                            {{ number_format((int) ($visitorStats['month_visitors'] ?? 0), 0, ',', '.') }}
                            pengunjung unik
                        </p>
                    </div>


                    {{-- Tahun Ini --}}
                    <div class="border-t border-green-900/10 py-4 sm:border-t-0 sm:px-6 sm:py-0 lg:px-8">
                        <p class="tabular-nums text-4xl font-bold tracking-tight text-emerald-600">
                            {{ number_format((int) ($visitorStats['year_hits'] ?? 0), 0, ',', '.') }}
                        </p>

                        <p class="mt-2 text-sm font-medium text-green-950">
                            Kunjungan Tahun Ini
                        </p>

                        <p class="mt-1 text-xs text-green-900/60">
                            {{ number_format((int) ($visitorStats['year_visitors'] ?? 0), 0, ',', '.') }}
                            pengunjung unik
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>