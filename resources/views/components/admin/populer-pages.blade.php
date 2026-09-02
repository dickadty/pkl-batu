<div class="space-y-3">

    @forelse($popularPages as $index => $page)
        @php
            /*
            |--------------------------------------------------------------------------
            | PATH
            |--------------------------------------------------------------------------
            */

            $path = $page->last_path;

            /*
            |--------------------------------------------------------------------------
            | NAMA HALAMAN
            |--------------------------------------------------------------------------
            */

            if ($path === '/') {
                $pageName = 'Beranda';
            } else {
                // Hilangkan slash di awal dan akhir
                $cleanPath = trim($path, '/');

                // Ambil bagian terakhir URL
                $segments = explode('/', $cleanPath);

                $pageName = end($segments);

                // Ubah - dan _ menjadi spasi
                $pageName = str_replace(['-', '_'], ' ', $pageName);

                // Huruf kapital setiap kata
                $pageName = ucwords($pageName);
            }
        @endphp


        <div
            class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 transition hover:bg-gray-50">

            {{-- =====================================================
                KIRI
            ====================================================== --}}

            <div class="flex min-w-0 items-center gap-3">

                {{-- Ranking --}}
                <div
                    class="
                        flex h-9 w-9 shrink-0
                        items-center justify-center
                        rounded-full
                        text-sm font-bold
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}
                    ">
                    {{ $index + 1 }}
                </div>


                {{-- Informasi halaman --}}
                <div class="min-w-0">

                    {{-- Nama halaman --}}
                    <p class="max-w-[200px] truncate font-semibold text-gray-800" title="{{ $pageName }}">
                        {{ $pageName }}
                    </p>


                    {{-- URL --}}
                    <p class="max-w-[220px] truncate text-xs text-gray-400" title="{{ $path }}">
                        {{ $path }}
                    </p>

                </div>

            </div>


            {{-- =====================================================
                KANAN
            ====================================================== --}}

            <div class="ml-4 shrink-0 text-right">

                <p class="font-bold text-emerald-600">
                    {{ number_format((int) $page->total) }}
                </p>

                <p class="text-xs text-gray-400">
                    kunjungan
                </p>

            </div>

        </div>


    @empty

        {{-- =====================================================
            TIDAK ADA DATA
        ====================================================== --}}

        <div class="rounded-xl border border-dashed border-gray-200 py-10 text-center">

            <div class="mb-3 flex justify-center">

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z" />
                    </svg>
                </div>

            </div>


            <p class="font-medium text-gray-500">
                Tidak ada data kunjungan
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Data halaman populer akan muncul setelah ada kunjungan.
            </p>

        </div>
    @endforelse

</div>
