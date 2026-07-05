@extends('layouts.public')

@section('title', 'Ajukan Permohonan Informasi | PPID Kota Batu')

@section('content')
    <section class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">
                Ajukan Permohonan Informasi
            </h1>

            <p class="text-slate-600 mb-6">
                Pemohon: <strong>{{ $user->nama }}</strong>
            </p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('public.permohonan.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Rincian Informasi yang Dimohon
                    </label>
                    <textarea name="rincian" rows="5" class="w-full border rounded-lg p-2"
                        placeholder="Contoh: Saya ingin memperoleh laporan realisasi anggaran Dinas Kominfo tahun 2026." required>{{ old('rincian') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Tujuan Penggunaan Informasi
                    </label>
                    <textarea name="tujuan" rows="4" class="w-full border rounded-lg p-2"
                        placeholder="Contoh: Untuk kebutuhan penelitian akademik." required>{{ old('tujuan') }}</textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                        Ajukan Permohonan
                    </button>

                    <a href="{{ route('public.informasi.index') }}"
                        class="px-5 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection
