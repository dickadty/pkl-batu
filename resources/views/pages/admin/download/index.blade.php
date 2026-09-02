@extends('layouts.admin.app')


@section('content')
    <div class="container-fluid">


        <div class="card">


            <div class="card-header">

                <h5>
                    Histori Download Informasi Publik
                </h5>

            </div>



            <div class="card-body">


                <div class="table-responsive">

                    <table class="table table-bordered table-striped">


                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Dokumen</th>

                                <th>IP Pengunjung</th>

                                <th>Tanggal Download</th>

                            </tr>

                        </thead>



                        <tbody>


                            @forelse($downloads as $item)
                                <tr>


                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if ($item->dokumentasi)
                                            {{ $item->dokumentasi->nama }}
                                        @else
                                            -
                                        @endif

                                    </td>



                                    <td>

                                        {{ $item->tujuan }}

                                    </td>



                                    <td>

                                        {{ $item->tanggal_format }}

                                    </td>


                                </tr>



                            @empty


                                <tr>

                                    <td colspan="4" class="text-center">

                                        Belum ada histori download.

                                    </td>

                                </tr>
                            @endforelse



                        </tbody>


                    </table>

                </div>


                {{ $downloads->links() }}


            </div>


        </div>


    </div>
@endsection
