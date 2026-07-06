@extends('layouts.public')

@section('title', 'Beranda | PPID Kota Batu')

@section('content')

    @include('sections.hero')

    @include('sections.informasi')

    @include('sections.statistik')

    @include('sections.laporan')

    @include('sections.link-terkait')

@endsection