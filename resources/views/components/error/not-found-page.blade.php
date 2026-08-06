@extends('layouts.public.app')

@section('title', 'Halaman Belum Tersedia')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="text-center">

        <h1 class="text-6xl font-bold text-emerald-700">

            404

        </h1>

        <h2 class="text-2xl font-semibold mt-4">

            Halaman Belum Tersedia

        </h2>

        <p class="mt-3 text-slate-600">

            Fitur atau halaman yang Anda pilih masih dalam proses pengembangan.

        </p>

        <a
            href="{{ route('beranda') }}"
            class="mt-6 inline-flex px-5 py-3 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">

            Kembali ke Beranda

        </a>

    </div>

</div>

@endsection