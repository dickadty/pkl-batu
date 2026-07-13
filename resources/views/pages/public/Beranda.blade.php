@extends('layouts.public')

@section('title', 'Beranda | PPID Kota Batu')

@section('content')

    @include('components.public.sections.hero')

    @include('components.public.sections.card informasi')

    @include('components.public.sections.berita')

    @include('components.public.sections.statistik')

    @include('components.public.sections.laporan')

    @include('components.public.sections.link-terkait')

@endsection