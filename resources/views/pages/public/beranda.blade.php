@extends('layouts.public.app')

@section('title', 'Beranda | PPID Kota Batu')

@section('content')

    {{-- Hero / Sambutan --}}
    @include('components.public.sections.hero')


    {{-- Layanan Utama PPID --}}
    @include('components.public.sections.card-informasi')


    {{-- Informasi Terbaru --}}
    @include('components.public.sections.berita')


    {{-- Bantuan Pengguna --}}
    @include('components.public.sections.faq')


    {{-- Link Resmi / Integrasi --}}
    @include('components.public.sections.link-terkait')


    {{-- Kepuasan Masyarakat --}}
    @include('components.public.sections.survey')


    {{-- Statistik Website --}}
    @include('components.public.sections.visitor-counter')

@endsection
