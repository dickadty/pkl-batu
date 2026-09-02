@extends('layouts.admin.app')

@section('title', 'Dashboard Visitor')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <x-admin.page-header title="Dashboard Statistik Kunjungan"
            description="Monitoring aktivitas pengunjung website PPID Kota Batu secara realtime." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Statistik Kunjungan',
                ],
            ]" />


        <x-ui.flash-messages />


        {{-- Welcome Card --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 text-white shadow-lg">

            <div class="relative z-10">

                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20">
                        <i class="ri-line-chart-line text-2xl"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold">
                            Statistik Pengunjung Website
                        </h2>

                        <p class="text-sm text-blue-100">
                            Pantau perkembangan trafik dan halaman populer PPID Kota Batu.
                        </p>
                    </div>
                </div>


                <div class="mt-5 flex flex-wrap gap-3">

                    <div class="rounded-lg bg-white/20 px-4 py-2 backdrop-blur">
                        <span class="text-xs text-blue-100">
                            Status
                        </span>

                        <p class="font-semibold">
                            Monitoring Aktif
                        </p>
                    </div>


                    <div class="rounded-lg bg-white/20 px-4 py-2 backdrop-blur">

                        <span class="text-xs text-blue-100">
                            Sistem
                        </span>

                        <p class="font-semibold">
                            Visitor Analytics
                        </p>

                    </div>

                </div>

            </div>


            <div class="absolute right-0 top-0 opacity-20">

                <i class="ri-bar-chart-box-line text-[160px]"></i>

            </div>

        </div>



        {{-- Statistik Utama --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">

            <div class="mb-5 flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Ringkasan Statistik
                    </h3>

                    <p class="text-sm text-gray-500">
                        Data aktivitas kunjungan website
                    </p>
                </div>


                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    LIVE
                </span>

            </div>


            <x-admin.visitor-stat-card :stats="$stats" />

        </div>




        {{-- Grafik --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


            <div class="xl:col-span-2 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="mb-4">

                    <h3 class="font-bold text-gray-800">
                        Grafik Kunjungan
                    </h3>

                    <p class="text-sm text-gray-500">
                        Pergerakan jumlah pengunjung berdasarkan periode waktu.
                    </p>

                </div>


                <x-admin.visitor-chart :visitorChart="$visitorChart" />

            </div>




            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="mb-4">

                    <h3 class="font-bold text-gray-800">
                        Halaman Terpopuler
                    </h3>

                    <p class="text-sm text-gray-500">
                        Halaman yang paling sering dikunjungi.
                    </p>

                </div>


                <x-admin.popular-pages :popularPages="$popularPages" />

            </div>


        </div>



        {{-- Insight --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">


            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="rounded-lg bg-blue-100 p-3 text-blue-600">
                        <i class="ri-user-line text-xl"></i>
                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Visitor Tracking
                        </p>

                        <h4 class="font-bold text-gray-800">
                            Aktif
                        </h4>

                    </div>

                </div>

            </div>



            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="rounded-lg bg-green-100 p-3 text-green-600">
                        <i class="ri-eye-line text-xl"></i>
                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Page Monitoring
                        </p>

                        <h4 class="font-bold text-gray-800">
                            Berjalan
                        </h4>

                    </div>

                </div>

            </div>




            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="rounded-lg bg-purple-100 p-3 text-purple-600">
                        <i class="ri-database-2-line text-xl"></i>
                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Database Visitor
                        </p>

                        <h4 class="font-bold text-gray-800">
                            Tersimpan
                        </h4>

                    </div>

                </div>

            </div>


        </div>


    </div>

@endsection
