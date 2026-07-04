@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Tambah Informasi Publik</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.informasi-publik.store') }}" enctype="multipart/form-data"
            class="bg-white rounded shadow p-6 space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-medium">Nama Informasi</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Tahun</label>
                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-1 font-medium">Sifat Informasi</label>
                <select name="sifat" class="w-full border rounded p-2">
                    <option value="">Pilih Sifat Informasi</option>
                    <option value="Berkala">Berkala</option>
                    <option value="Serta Merta">Serta Merta</option>
                    <option value="Setiap Saat">Setiap Saat</option>
                    <option value="Dikecualikan">Dikecualikan</option>
                </select>
            </div>

            @if ((int) $admin->role === 1)
                <div>
                    <label class="block mb-1 font-medium">PPID Pembantu</label>
                    <select name="ppid_pembantuid" class="w-full border rounded p-2">
                        <option value="">Pilih PPID Pembantu</option>
                        @foreach ($ppidPembantu as $ppid)
                            <option value="{{ $ppid->id }}">
                                {{ $ppid->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block mb-1 font-medium">Ringkasan</label>
                <textarea name="ringkasan" rows="4" class="w-full border rounded p-2">{{ old('ringkasan') }}</textarea>
            </div>

            <div>
                <label class="block mb-1 font-medium">File Informasi</label>
                <input type="file" name="file" class="w-full border rounded p-2" required>
                <p class="text-sm text-gray-500 mt-1">
                    Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 5 MB.
                </p>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan Informasi
                </button>

                <a href="{{ route('admin.informasi-publik.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
