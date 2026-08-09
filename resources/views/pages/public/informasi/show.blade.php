@extends('layouts.public')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded shadow p-6">
            <h1 class="text-3xl font-bold mb-4">
                {{ $dokumen->nama }}
            </h1>

            <div class="space-y-2 mb-4 text-gray-700">
                <p><strong>PPID:</strong> {{ $dokumen->ppidPembantu->nama ?? '-' }}</p>
                <p><strong>Tahun:</strong> {{ $dokumen->tahun ?? '-' }}</p>
                <p><strong>Sifat Informasi:</strong> {{ $dokumen->sifat ?? '-' }}</p>
                <p><strong>Tanggal Upload:</strong> {{ $dokumen->tanggal ? date('d-m-Y', $dokumen->tanggal) : '-' }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Ringkasan</h2>
                <p class="text-gray-700">
                    {{ $dokumen->ringkasan ?? 'Tidak ada ringkasan.' }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('public.informasi.download', $dokumen->id) }}"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                    Download File
                </a>

                <a href="{{ route('public.informasi.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
