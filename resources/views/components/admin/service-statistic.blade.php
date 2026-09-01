@props(['serviceStats'])


<article
    class="
    rounded-2xl
    border
    border-gray-200
    bg-white
    p-5
    shadow-sm
    dark:border-gray-800
    dark:bg-gray-800
    dark:shadow-none
    sm:p-6
    ">


    {{-- Header --}}
    <div>

        <h2 class="text-base font-semibold text-gray-900 dark:text-white">
            Penilaian Per Layanan
        </h2>


        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Perbandingan penilaian berdasarkan layanan.
        </p>

    </div>



    {{-- Content --}}
    <div class="mt-6 space-y-4">


        @forelse($serviceStats as $service)
            <div
                class="
                rounded-xl
                border
                border-gray-100
                p-4
                dark:border-gray-700
                ">


                <div class="flex items-start justify-between gap-4">


                    {{-- Nama Layanan --}}
                    <div class="min-w-0">


                        <p
                            class="
                            truncate
                            text-sm
                            font-semibold
                            text-gray-800
                            dark:text-gray-200
                            ">

                            {{ $service['service'] }}

                        </p>



                        <p class="mt-1 text-xs text-gray-400">

                            {{ number_format($service['total'], 0, ',', '.') }}
                            responden

                        </p>


                    </div>





                    {{-- Rating --}}
                    <div
                        class="
                        flex
                        shrink-0
                        items-center
                        gap-1
                        rounded-lg
                        bg-yellow-50
                        px-2.5
                        py-1.5
                        dark:bg-yellow-500/10
                        ">


                        <i class="ri-star-fill text-sm text-yellow-400"></i>


                        <span
                            class="
                            text-sm
                            font-bold
                            text-gray-700
                            dark:text-gray-200
                            ">

                            {{ number_format($service['average'], 1, ',', '.') }}

                        </span>


                    </div>


                </div>





                {{-- Progress --}}
                <div
                    class="
                    mt-3
                    h-1.5
                    overflow-hidden
                    rounded-full
                    bg-gray-100
                    dark:bg-gray-700
                    ">


                    <div class="
                        h-full
                        rounded-full
                        bg-blue-500
                        transition-all
                        duration-500
                        "
                        style="width: {{ min(100, max(0, $service['percentage'])) }}%;">
                    </div>


                </div>


            </div>



        @empty


            <div class="py-12 text-center">


                <div
                    class="
                    mx-auto
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-full
                    bg-gray-100
                    text-gray-400
                    dark:bg-gray-700
                    ">


                    <i class="ri-bar-chart-grouped-line text-xl"></i>


                </div>



                <p class="mt-3 text-sm text-gray-500">

                    Belum ada data layanan.

                </p>


            </div>
        @endforelse


    </div>


</article>
