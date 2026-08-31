@extends('layouts.public.app')

@section('title', 'Beranda | PPID Kota Batu')

@section('content')

    @include('components.public.sections.hero')
    @include('components.public.sections.card-informasi')
    @include('components.public.sections.visitor-counter')
    @include('components.public.sections.berita')
    @include('components.public.sections.faq')
    @include('components.public.sections.link-terkait')
    @include('components.public.sections.survey')

@endsection
