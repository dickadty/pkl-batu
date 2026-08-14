@extends('layouts.admin.app')

@section('title', 'Tambah Halaman')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
        JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header title="Tambah Halaman"
            description="Tambahkan halaman baru yang akan ditampilkan pada website. Isi judul, status publikasi, dan konten halaman sesuai kebutuhan."
            :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Konten & Informasi',
            ],
            [
                'label' => 'Halaman',
                'url' => route('admin.pages.create'),
            ],
            [
                'label' => 'Tambah Halaman',
            ],
        ]" />

        {{-- ============================================================
        FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
        FORM HALAMAN
        ============================================================= --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">
                    Form Input Halaman
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi informasi halaman yang akan dipublikasikan.
                </p>
            </div>

            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        {{-- =========================
                        KIRI
                        ========================== --}}
                        <div class="lg:col-span-2 space-y-6">

                            {{-- Judul --}}
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700">
                                    Judul Halaman <span class="text-red-500">*</span>
                                </label>

                                <input type="text" name="judul" value="{{ old('judul') }}"
                                    placeholder="Masukkan judul halaman..."
                                    class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">

                                @error('judul')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700">
                                    Status Publikasi
                                </label>

                                <select name="status"
                                    class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">

                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>

                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                        Published
                                    </option>

                                </select>

                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- =========================
                        KANAN
                        ========================== --}}
                        <div>

                            <label class="block mb-2 text-sm font-medium text-slate-700">
                                Gambar Halaman
                            </label>

                            <div id="drop-area"
                                class="relative flex min-h-[260px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 hover:border-emerald-500 hover:bg-emerald-50 transition">

                                <input type="file" id="gambar" name="gambar" accept="image/*"
                                    class="absolute inset-0 opacity-0 cursor-pointer">

                                <img id="preview-gambar" class="hidden max-h-60 rounded-lg object-cover">

                                <div id="upload-placeholder" class="text-center">

                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">

                                        <i class="ri-image-add-line text-3xl text-emerald-600"></i>

                                    </div>

                                    <h3 class="mt-4 font-semibold text-slate-700">
                                        Upload Gambar
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        JPG, PNG, WEBP
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        Maksimal 2 MB
                                    </p>

                                </div>

                            </div>

                            @error('gambar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-medium text-slate-700">
                                Lampiran File
                            </label>

                            <label for="file"
                                class="flex cursor-pointer items-center justify-center gap-3 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-8 transition hover:border-emerald-500 hover:bg-emerald-50">

                                <i class="ri-file-upload-line text-3xl text-emerald-600"></i>

                                <div>

                                    <label class="block mb-2 text-sm font-medium text-slate-700">
                                        Lampiran File
                                    </label>

                                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" class="block w-full text-sm text-slate-600
                                        file:mr-4
                                        file:rounded-lg
                                        file:border-0
                                        file:bg-emerald-600
                                        file:px-4
                                        file:py-2
                                        file:text-sm
                                        file:font-medium
                                        file:text-white
                                        hover:file:bg-emerald-700">

                                    <p class="mt-2 text-xs text-slate-500">
                                        PDF, DOCX, XLSX, ZIP (maks. 10 MB)
                                    </p>

                                    @error('file')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </label>

                            <input type="file" id="file" name="file" class="hidden">

                            <div id="file-name"
                                class="hidden mt-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            </div>

                        </div>

                    </div>

                    {{-- =========================
                    KONTEN
                    ========================== --}}

                    <div>

                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            Isi Halaman
                        </label>

                        <textarea name="content" rows="14" class="editor w-full">{{ old('content') }}</textarea>

                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                {{-- Footer --}}
                <div
                    class="flex items-center justify-end gap-3 rounded-b-xl border-t border-slate-200 bg-slate-50 px-6 py-5">

                    <a href="{{ route('admin.pages.index') }}"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">

                        Batal

                    </a>

                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">

                        <i class="ri-save-line mr-2"></i>

                        Simpan Halaman

                    </button>

                </div>

            </form>
        </div>
    </div>


@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const input = document.getElementById('gambar');
        const preview = document.getElementById('preview-gambar');
        const placeholder = document.getElementById('upload-placeholder');

        if (!input) return;

        input.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {

                preview.src = event.target.result;

                preview.classList.remove('hidden');

                placeholder.classList.add('hidden');

            };

            reader.readAsDataURL(file);

        });

    });

    document.addEventListener('DOMContentLoaded', () => {

        const input = document.getElementById('file');
        const fileName = document.getElementById('file-name');

        if (!input) return;

        input.addEventListener('change', function () {

            if (!this.files.length) {
                fileName.classList.add('hidden');
                return;
            }

            fileName.innerHTML = `
            <i class="ri-file-text-line mr-2"></i>
            ${this.files[0].name}
        `;

            fileName.classList.remove('hidden');

        });

    });

</script>
@endstack