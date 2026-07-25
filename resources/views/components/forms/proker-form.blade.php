@props([
    'action',
    'method' => 'POST',
    'proker' => null,
    'admin',
    'ppidPembantuList' => [],
    'submitLabel' => 'Simpan Program Kerja',
    'cancelUrl',
])

@php
    $httpMethod = strtoupper($method);
    $isEdit = $proker !== null;

    $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

    $ppidList = collect($ppidPembantuList ?? []);

    $documentUrl = old('dokumen_url', $proker && $proker->isDokumenEksternal() ? $proker->dokumen : '');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data"
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
    @csrf

    @if (!in_array($httpMethod, ['GET', 'POST'], true))
        @method($httpMethod)
    @endif

    <div class="border-b border-gray-100 px-5 py-5 dark:border-gray-800 sm:px-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Informasi Program Kerja
        </h3>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Isi data program kerja secara lengkap. Field bertanda bintang wajib diisi.
        </p>
    </div>

    @if ($errors->any())
        <div
            class="mx-5 mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 sm:mx-6">
            <p class="font-semibold">
                Data belum dapat disimpan:
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 px-5 py-6 sm:px-6 lg:grid-cols-2">
        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nama Program Kerja
                <span class="text-red-500">*</span>
            </label>

            <input type="text" name="nama_proker" value="{{ old('nama_proker', $proker->nama_proker ?? '') }}"
                maxlength="255" required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">

            @error('nama_proker')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Anggaran
                <span class="text-red-500">*</span>
            </label>

            <textarea name="anggaran" rows="3" required placeholder="Contoh: Rp236.000.000 pada penyaluran 30 Maret 2026"
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">{{ old('anggaran', $proker->anggaran ?? '') }}</textarea>

            @error('anggaran')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Sumber Dana
                <span class="text-red-500">*</span>
            </label>

            <textarea name="sumber_dana" rows="3" required placeholder="Contoh: APBD Kota Batu dan CSR"
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">{{ old('sumber_dana', $proker->sumber_dana ?? '') }}</textarea>

            @error('sumber_dana')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Target Program
                <span class="text-red-500">*</span>
            </label>

            <textarea name="target" rows="5" required
                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">{{ old('target', $proker->target ?? '') }}</textarea>

            @error('target')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Jadwal Pelaksanaan
                <span class="text-red-500">*</span>
            </label>

            <input type="date" name="jadwal_pelaksanaan"
                value="{{ old('jadwal_pelaksanaan', $proker?->jadwal_pelaksanaan?->format('Y-m-d')) }}"
                required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:text-white/90">

            @error('jadwal_pelaksanaan')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if ($isAdminUtama)
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    PPID Pembantu
                    <span class="text-red-500">*</span>
                </label>

                <select name="ppid_pembantuid" required
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:text-white/90">
                    <option value="">
                        Pilih PPID Pembantu
                    </option>

                    @foreach ($ppidList as $ppid)
                        <option value="{{ $ppid->id }}" @selected((string) old('ppid_pembantuid', $proker->ppid_pembantuid ?? '') === (string) $ppid->id)>
                            {{ $ppid->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>

                @error('ppid_pembantuid')
                    <p class="mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        @else
            <input type="hidden" name="ppid_pembantuid" value="{{ $admin->ppid_pembantuid }}">
        @endif

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Penanggung Jawab
                <span class="text-red-500">*</span>
            </label>

            <input type="text" name="pj" value="{{ old('pj', $proker->pj ?? '') }}" maxlength="255" required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:text-white/90">

            @error('pj')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nomor Telepon
            </label>

            <input type="text" name="telp" value="{{ old('telp', $proker->telp ?? '') }}" maxlength="50"
                placeholder="Contoh: 0341-123456"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:text-white/90">

            @error('telp')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                URL Dokumen
            </label>

            <input type="url" name="dokumen_url" value="{{ $documentUrl }}" maxlength="2048"
                placeholder="https://batukota.go.id/..."
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none dark:border-gray-700 dark:text-white/90">

            <p class="mt-1 text-xs text-gray-500">
                Isi URL apabila dokumen berada di website lain.
            </p>

            @error('dokumen_url')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Unggah Dokumen
            </label>

            <input type="file" name="dokumen_file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                class="block w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">

            <p class="mt-1 text-xs text-gray-500">
                PDF, DOC, DOCX, XLS, atau XLSX. Maksimal 10 MB. File unggahan akan menggantikan URL dokumen.
            </p>

            @error('dokumen_file')
                <p class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if ($isEdit && $proker->dokumen)
            <div class="lg:col-span-2">
                <div
                    class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-500/20 dark:bg-blue-500/10">
                    <a href="{{ route('admin.proker.dokumen', ['id' => $proker->id]) }}"
                        target="_blank" class="text-sm font-semibold text-blue-700 hover:underline dark:text-blue-400">
                        Lihat dokumen saat ini
                    </a>

                    <label class="mt-3 flex items-center gap-2 text-sm text-red-600">
                        <input type="checkbox" name="hapus_dokumen" value="1" class="rounded border-gray-300">

                        Hapus dokumen saat menyimpan
                    </label>
                </div>
            </div>
        @endif
    </div>

    <div
        class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/50 px-5 py-4 dark:border-gray-800 dark:bg-white/[0.02] sm:px-6">
        <a href="{{ $cancelUrl }}"
            class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 bg-white px-5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
            Batal
        </a>

        <button type="submit"
            class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white hover:bg-brand-600">
            {{ $submitLabel }}
        </button>
    </div>
</form>
