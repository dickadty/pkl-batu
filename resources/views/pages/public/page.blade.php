@extends('layouts.public.app')

@section('title', $page->judul)

@section('content')

<div class="container mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">
        {{ $page->judul }}
    </h1>

    <div class="prose max-w-none">
        {!! $page->content !!}
    </div>

</div>

@endsection