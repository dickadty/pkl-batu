<div class="rounded-3xl border bg-white p-6">


    <h3 class="text-xl font-bold text-green-950">

        Grafik Kunjungan

    </h3>



    <canvas id="visitorChart" class="mt-5">
    </canvas>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        new Chart(

            document.getElementById('visitorChart'),

            {


                type: 'line',


                data: {


                    labels: @json($visitorChart->pluck('bulan')),


                    datasets: [{


                        label: 'Pengunjung',


                        data: @json($visitorChart->pluck('total')),


                        borderWidth: 3,


                        tension: .4


                    }]


                }


            }

        );
    </script>

</div>
