<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">


    @php

        $data = [
            [
                'title' => 'Total Pengunjung',
                'value' => $stats['total'],
            ],

            [
                'title' => 'Hari Ini',
                'value' => $stats['today'],
            ],

            [
                'title' => 'Bulan Ini',
                'value' => $stats['month'],
            ],

            [
                'title' => 'Tahun Ini',
                'value' => $stats['year'],
            ],
        ];

    @endphp



    @foreach ($data as $item)
        <div class="rounded-3xl border border-green-900/10 bg-white p-6 shadow-sm">


            <p class="text-sm text-green-900/70">

                {{ $item['title'] }}

            </p>


            <h3 class="mt-3 text-4xl font-bold text-emerald-600">

                {{ number_format($item['value'], 0, ',', '.') }}

            </h3>


        </div>
    @endforeach



</div>
