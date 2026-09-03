<div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">


    {{-- HEADER --}}
    <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">


        <div>

            <h3 class="text-xl font-bold text-green-950">

                Distribusi Download Informasi

            </h3>


            <p class="mt-1 text-sm text-gray-500">

                Persentase dokumen yang diunduh berdasarkan sifat informasi publik.

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


                    <option value="semua" @selected(($periode ?? 'semua') == 'semua')>

                        Semua

                    </option>


                    <option value="bulan" @selected(($periode ?? '') == 'bulan')>

                        Bulan

                    </option>


                    <option value="tahun" @selected(($periode ?? '') == 'tahun')>

                        Tahun

                    </option>


                </select>

            </div>






            <div>

                <label class="mb-1 block text-xs font-semibold text-gray-500">
                    Tahun
                </label>


                <input type="number" name="tahun" value="{{ $tahun ?? now()->year }}"
                    class="w-28 rounded-lg border-gray-200 text-sm focus:border-green-700 focus:ring-green-700">

            </div>





            <button type="submit"
                class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800">


                Terapkan


            </button>



        </form>



    </div>








    {{-- PIE --}}
    <div class="relative mx-auto h-[420px] max-w-xl">


        @if (collect($downloadBySifat)->sum() > 0)
            <canvas id="downloadPieChart"></canvas>
        @else
            <div class="flex h-full items-center justify-center">


                <div class="text-center">


                    <i class="ri-pie-chart-line text-5xl text-gray-300"></i>


                    <p class="mt-3 text-sm text-gray-500">

                        Belum ada data download

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


            const canvas = document.getElementById(
                'downloadPieChart'
            );


            if (!canvas) {

                return;

            }



            const labels = [

                'Berkala',

                'Setiap Saat',

                'Serta Merta',

                'Dikecualikan'

            ];




            const values = [

                {{ $downloadBySifat['berkala'] ?? 0 }},

                {{ $downloadBySifat['setiap_saat'] ?? 0 }},

                {{ $downloadBySifat['serta_merta'] ?? 0 }},

                {{ $downloadBySifat['dikecualikan'] ?? 0 }}

            ];






            new Chart(canvas, {


                type: 'doughnut',



                data: {


                    labels: labels,


                    datasets: [{

                        data: values,


                        backgroundColor: [

                            '#166534',

                            '#2563eb',

                            '#f97316',

                            '#dc2626'

                        ],


                        borderWidth: 4,


                        borderColor: '#ffffff'


                    }]


                },



                options: {


                    responsive: true,


                    maintainAspectRatio: false,



                    cutout: '65%',



                    plugins: {


                        legend: {


                            position: 'bottom',


                            labels: {


                                padding: 20,


                                usePointStyle: true


                            }


                        },



                        tooltip: {


                            backgroundColor: '#052e16',


                            padding: 12,


                            callbacks: {


                                label: function(context) {


                                    let total =
                                        context.dataset.data.reduce(
                                            (a, b) => a + b,
                                            0
                                        );


                                    let value =
                                        context.raw;



                                    let persen =
                                        total > 0 ?
                                        ((value / total) * 100).toFixed(1) :
                                        0;



                                    return (

                                        context.label +
                                        ': ' +
                                        value +
                                        ' (' +
                                        persen +
                                        '%)'

                                    );


                                }


                            }


                        }


                    }


                }


            });



        });
    </script>
@endpush
