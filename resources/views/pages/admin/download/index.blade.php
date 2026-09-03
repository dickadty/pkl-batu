@extends('layouts.admin.app')


@section('title', 'Histori Download Informasi Publik')



@section('content')

    <div class="space-y-6">



        {{-- HEADER --}}
        <x-admin.page-header title="Histori Download Informasi Publik"
            description="Monitoring aktivitas pengunduhan dokumen informasi publik oleh masyarakat." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Histori Download',
                ],
            ]" />



        <x-ui.flash-messages />






        {{-- SUMMARY CARD --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">



            {{-- TOTAL --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-4">


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">

                        <i class="ri-download-cloud-line text-2xl"></i>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Total Download
                        </p>


                        <h3 class="text-3xl font-bold text-gray-800">

                            {{ number_format($totalDownload) }}

                        </h3>

                    </div>


                </div>


            </div>







            {{-- HARI --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">


                <div class="flex items-center gap-4">


                    <div class="flex h-12 w-12 items-center-justify-center rounded-xl bg-blue-100 text-blue-700">

                        <i class="ri-calendar-check-line text-2xl"></i>

                    </div>


                    <div>


                        <p class="text-sm text-gray-500">
                            Hari Ini
                        </p>


                        <h3 class="text-3xl font-bold text-gray-800">

                            {{ number_format($todayDownload) }}

                        </h3>


                    </div>


                </div>


            </div>







            {{-- BULAN --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">


                <div class="flex items-center gap-4">


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700">


                        <i class="ri-calendar-line text-2xl"></i>


                    </div>


                    <div>


                        <p class="text-sm text-gray-500">
                            Bulan Ini
                        </p>


                        <h3 class="text-3xl font-bold text-gray-800">

                            {{ number_format($monthDownload) }}

                        </h3>


                    </div>


                </div>


            </div>







            {{-- TAHUN --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">


                <div class="flex items-center gap-4">


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-700">


                        <i class="ri-calendar-2-line text-2xl"></i>


                    </div>


                    <div>


                        <p class="text-sm text-gray-500">
                            Tahun Ini
                        </p>


                        <h3 class="text-3xl font-bold text-gray-800">

                            {{ number_format($yearDownload) }}

                        </h3>


                    </div>


                </div>


            </div>



        </div>









        {{-- PIE CHART --}}
        <div>


            <x-admin.download-pie-chart :downloadBySifat="$downloadBySifat" :periode="$periode ?? 'semua'" :tahun="$tahun ?? now()->year" />


        </div>









        {{-- POPULER --}}
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">


            <div class="mb-5">


                <h3 class="text-xl font-bold text-gray-800">

                    Dokumen Terpopuler

                </h3>


                <p class="text-sm text-gray-500">

                    Dokumen dengan jumlah pengunduhan terbanyak.

                </p>


            </div>





            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">


                @forelse($popularDocuments as $item)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition hover:shadow-md">


                        <div class="flex items-start justify-between gap-3">


                            <div>


                                <h4 class="font-semibold text-gray-800">

                                    {{ $item->dokumentasi->nama ?? '-' }}

                                </h4>


                                <p class="mt-2 text-xs text-gray-500">

                                    Total download

                                </p>


                            </div>



                            <div class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">


                                {{ $item->total }}

                            </div>


                        </div>


                    </div>


                @empty


                    <p class="text-sm text-gray-500">

                        Belum ada data download.

                    </p>
                @endforelse



            </div>



        </div>









        {{-- TABLE --}}
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">



            <div class="border-b border-gray-100 px-6 py-5">


                <h3 class="text-lg font-bold text-gray-800">

                    Riwayat Download

                </h3>


                <p class="text-sm text-gray-500">

                    Daftar aktivitas download informasi publik.

                </p>


            </div>







            <div class="overflow-x-auto">


                <table class="min-w-full">


                    <thead class="bg-gray-50">


                        <tr>


                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">

                                No

                            </th>


                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">

                                Dokumen

                            </th>


                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">

                                Sifat

                            </th>


                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">

                                IP Pengunjung

                            </th>


                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">

                                Waktu

                            </th>


                        </tr>


                    </thead>






                    <tbody class="divide-y divide-gray-100">



                        @forelse($downloads as $item)
                            <tr class="transition hover:bg-gray-50">



                                <td class="px-6 py-4 text-sm text-gray-600">

                                    {{ $loop->iteration + ($downloads->currentPage() - 1) * $downloads->perPage() }}

                                </td>




                                <td class="px-6 py-4">


                                    <div class="flex items-center gap-3">


                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                                            <i class="ri-file-download-line"></i>

                                        </div>


                                        <div>

                                            <p class="font-semibold text-gray-800">

                                                {{ $item->dokumentasi->nama ?? '-' }}

                                            </p>


                                        </div>


                                    </div>


                                </td>





                                <td class="px-6 py-4">


                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">


                                        {{ $item->dokumentasi->kategori->sifat ?? '-' }}


                                    </span>


                                </td>






                                <td class="px-6 py-4 text-sm text-gray-600">


                                    {{ $item->tujuan }}


                                </td>






                                <td class="px-6 py-4 text-sm text-gray-600">


                                    {{ $item->tanggal_format }}


                                </td>




                            </tr>



                        @empty


                            <tr>


                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">


                                    Belum ada histori download.


                                </td>


                            </tr>
                        @endforelse



                    </tbody>


                </table>


            </div>





            <div class="border-t border-gray-100 px-6 py-4">


                {{ $downloads->links() }}


            </div>



        </div>





    </div>


@endsection
