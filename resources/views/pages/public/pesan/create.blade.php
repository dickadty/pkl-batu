@extends('layouts.public')

@section('title', 'Kirim Pesan | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-bold text-slate-900">Kirim Pesan</h1>
            <p class="mt-3 text-slate-600">
                Gunakan form ini untuk mengirim pertanyaan, saran, atau kendala kepada PPID Kota Batu.
            </p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                <strong>Data belum valid.</strong>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <form action="{{ route('public.pesan.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Subjek</label>
                    <input type="text" name="subjek" value="{{ old('subjek') }}" class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Pesan</label>
                    <textarea name="pesan" rows="6" class="w-full border rounded-lg p-2" required>{{ old('pesan') }}</textarea>
                </div>

                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </section>
@endsection
