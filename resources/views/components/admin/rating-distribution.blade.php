@props(['ratingDistribution', 'average'])


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


    <div class="flex items-start justify-between gap-4">

        <div>

            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                Distribusi Rating
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Sebaran rating dari seluruh responden.
            </p>

        </div>



        <div class="
flex
items-center
gap-1
rounded-lg
bg-yellow-50
px-3
py-2
dark:bg-yellow-500/10
">

            <i class="ri-star-fill text-yellow-400"></i>

            <span class="text-sm font-bold text-gray-900 dark:text-white">

                {{ number_format((float) $average, 1, ',', '.') }}

            </span>

        </div>


    </div>




    <div class="mt-6 space-y-4">


        @foreach ($ratingDistribution as $rating => $data)
            <div>


                <div class="mb-1.5 flex justify-between">


                    <div class="flex items-center gap-1">

                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">

                            {{ $rating }}

                        </span>


                        <i class="ri-star-fill text-yellow-400"></i>


                    </div>



                    <div class="flex gap-2">

                        <span class="text-xs text-gray-400">

                            {{ number_format($data['percentage'], 1, ',', '.') }}%

                        </span>


                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">

                            {{ number_format($data['count'], 0, ',', '.') }}

                        </span>


                    </div>


                </div>




                <div class="h-2.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">


                    <div class="h-full rounded-full bg-yellow-400" style="width: {{ $data['percentage'] }}%">
                    </div>


                </div>


            </div>
        @endforeach


    </div>


</article>
