<div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">


    <div class="mb-6">

        <h3 class="text-xl font-bold text-green-950">

            Grafik Download Informasi Publik

        </h3>


        <p class="mt-1 text-sm text-gray-500">

            Statistik jumlah dokumen yang diunduh masyarakat.

        </p>


    </div>




    <div class="relative h-[420px]">


        <canvas id="downloadChart"></canvas>


    </div>


</div>





@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            () => {


                const canvas =
                    document.getElementById(
                        'downloadChart'
                    );



                if (!canvas) {
                    return;
                }




                new Chart(
                    canvas, {


                        type: 'line',



                        data: {


                            labels: @json($downloadChart->pluck('bulan')),



                            datasets: [{


                                label: 'Download',



                                data: @json($downloadChart->pluck('total')),



                                borderColor: '#166534',



                                backgroundColor: 'rgba(22,101,52,0.15)',



                                fill: true,



                                borderWidth: 4,



                                tension: .4,



                                pointRadius: 5,



                                pointBackgroundColor: '#166534'


                            }]



                        },



                        options: {


                            responsive: true,


                            maintainAspectRatio: false,



                            plugins: {


                                legend: {


                                    display: true


                                }


                            },



                            scales: {


                                y: {


                                    beginAtZero: true,


                                    ticks: {


                                        precision: 0


                                    }


                                },



                                x: {


                                    grid: {


                                        display: false


                                    }


                                }


                            }



                        }


                    }

                );


            });
    </script>
@endpush
