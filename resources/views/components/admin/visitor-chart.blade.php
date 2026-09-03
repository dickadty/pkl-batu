<div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">


    {{-- Header --}}
    <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h3 class="text-xl font-bold text-green-950">
                Grafik Kunjungan
            </h3>


            <p class="mt-1 text-sm text-gray-500">
                Pergerakan jumlah pengunjung berdasarkan periode waktu.
            </p>

        </div>




        {{-- FILTER --}}
        <form method="GET" class="flex flex-wrap items-end gap-3">


            <div>

                <label class="mb-1 block text-xs font-semibold text-gray-500">
                    Periode
                </label>


                <select name="periode"
                    class="rounded-lg border-gray-200 text-sm focus:border-green-700 focus:ring-green-700">


                    <option value="bulan" @selected(($periode ?? 'bulan') == 'bulan')>
                        Bulan
                    </option>


                    <option value="minggu" @selected(($periode ?? '') == 'minggu')>
                        Minggu
                    </option>


                    <option value="tahun" @selected(($periode ?? '') == 'tahun')>
                        Tahun
                    </option>


                </select>

            </div>




            <div>

                <label class="mb-1 block text-xs font-semibold text-gray-500">
                    Tanggal
                </label>


                <input type="{{ ($periode ?? 'bulan') == 'tahun' ? 'number' : 'month' }}" name="tanggal"
                    value="{{ $tanggal ?? now()->format('Y-m') }}"
                    class="rounded-lg border-gray-200 text-sm focus:border-green-700 focus:ring-green-700">

            </div>




            <button type="submit"
                class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800">


                Terapkan


            </button>


        </form>



    </div>





    {{-- Chart --}}
    <div class="relative h-[350px]">


        @if ($visitorChart->count())
            <canvas id="visitorChart"></canvas>
        @else
            <div class="flex h-full items-center justify-center">


                <div class="text-center">


                    <i class="ri-bar-chart-line text-5xl text-gray-300"></i>


                    <p class="mt-3 text-sm text-gray-500">

                        Belum ada data kunjungan

                    </p>


                </div>


            </div>
        @endif



    </div>



</div>




@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {


            const canvas = document.getElementById('visitorChart');


            if (!canvas) {
                return;
            }



            const labels = @json($visitorChart->pluck('bulan')->values());



            const values = @json($visitorChart->pluck('visitor')->values());





            new Chart(canvas, {


                type: 'line',



                data: {


                    labels: labels,


                    datasets: [{


                        label: 'Pengunjung',


                        data: values,


                        borderColor: '#166534',


                        backgroundColor: 'rgba(22,101,52,0.12)',


                        borderWidth: 3,


                        fill: true,


                        tension: 0.4,


                        pointRadius: 5,


                        pointHoverRadius: 8,


                        pointBackgroundColor: '#166534',


                        pointBorderColor: '#ffffff',


                        pointBorderWidth: 2


                    }]


                },



                options: {


                    responsive: true,


                    maintainAspectRatio: false,



                    interaction: {


                        intersect: false,


                        mode: 'index'


                    },



                    plugins: {


                        legend: {


                            display: true,


                            position: 'top',


                            labels: {


                                usePointStyle: true


                            }


                        },


                        tooltip: {


                            backgroundColor: '#052e16',


                            padding: 12


                        }


                    },



                    scales: {


                        y: {


                            beginAtZero: true,


                            ticks: {


                                precision: 0


                            },


                            grid: {


                                color: 'rgba(0,0,0,0.05)'


                            }


                        },



                        x: {


                            grid: {


                                display: false


                            }


                        }


                    }


                }



            });


        });
    </script>
@endpush
