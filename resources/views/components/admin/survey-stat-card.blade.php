<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">


    @php

        $cards = [
            [
                'label' => 'Total Responden',
                'value' => $stats['total'] ?? 0,
                'desc' => 'Seluruh penilaian yang masuk',
                'icon' => 'ri-user-star-line',
                'class' => 'blue',
            ],

            [
                'label' => 'Rata-rata Rating',
                'value' => number_format($stats['average'] ?? 0, 1, ',', '.'),
                'desc' => 'Dari maksimal 5 bintang',
                'icon' => 'ri-star-fill',
                'class' => 'yellow',
            ],

            [
                'label' => 'Rating 5 Bintang',
                'value' => $stats['five_star'] ?? 0,
                'desc' => 'Responden sangat puas',
                'icon' => 'ri-emotion-happy-line',
                'class' => 'green',
            ],

            [
                'label' => 'Kritik & Saran',
                'value' => $stats['feedback'] ?? 0,
                'desc' => 'Masukan evaluasi',
                'icon' => 'ri-feedback-line',
                'class' => 'orange',
            ],
        ];

    @endphp



    @foreach ($cards as $card)
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
dark:border-gray-800 dark:bg-gray-800">


            <div class="flex justify-between">


                <div>

                    <p class="text-sm text-gray-500">
                        {{ $card['label'] }}
                    </p>


                    <h2 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">

                        {{ number_format((int) $card['value'], 0, ',', '.') }}

                    </h2>


                    <p class="mt-2 text-xs text-gray-400">
                        {{ $card['desc'] }}
                    </p>


                </div>


                <div class="flex h-11 w-11 items-center justify-center rounded-xl
bg-gray-100 text-gray-600">


                    <i class="{{ $card['icon'] }} text-xl"></i>


                </div>


            </div>


        </div>
    @endforeach


</div>
