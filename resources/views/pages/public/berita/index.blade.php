@extends('layouts.public')

@section('title', 'Berita | PPID Kota Batu')

@section('content')
    <div class="min-h-screen" style="background: linear-gradient(135deg, #033927 10%, #04853c 100%)">
        <section class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="text-3xl font-bold text-slate-900">
                    Berita PPID Kota Batu
                </h1>

                <p class="mt-3 text-slate-600 max-w-2xl">
                    Informasi terbaru terkait layanan, kegiatan, dan publikasi PPID Kota Batu.
                </p>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid md:grid-cols-3 gap-6">
        @foreach($berita as $item)
            @include('components.public.berita.card', [
                'item' => $item
            ])
        @endforeach
    </div>

    <div class="mt-8">
        {{ $berita->links() }}
    </div>
        </section>
    </div>
@endsection
