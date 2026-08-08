@extends('layouts.admin.app')

@section('title', 'Edit Module')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <x-admin.page-header title="Edit Module" description="Perbarui informasi module website." :breadcrumbs="[
            [
                'label' => 'Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'ri-dashboard-line',
            ],
            [
                'label' => 'Module',
                'url' => route('admin.module.index'),
            ],
            [
                'label' => 'Edit',
            ],
        ]">

            <a href="{{ route('admin.module.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">

                <i class="ri-arrow-left-line"></i>
                Kembali

            </a>

        </x-admin.page-header>

        <x-ui.flash-messages />

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <form action="{{ route('admin.module.update', $module->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">

                    {{-- Nama Module --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Nama Module
                        </label>

                        <input type="text" id="nama" name="nama" value="{{ old('nama', $module->nama) }}" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                    </div>

                    {{-- Slug --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Slug
                        </label>

                        <input type="text" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                    </div>

                    {{-- Route --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Route Name
                        </label>

                        <input type="text" id="route_name" name="route_name"
                            value="{{ old('route_name', $module->route_name) }}" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                    </div>

                    {{-- Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Status
                        </label>

                        <select name="is_active"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">

                            <option value="1" {{ old('is_active', $module->is_active) ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="0" {{ !old('is_active', $module->is_active) ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>

                        </select>

                    </div>

                    {{-- Deskripsi --}}
                    <div class="lg:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Deskripsi
                        </label>

                        <textarea name="description" rows="5"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $module->description) }}</textarea>

                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                    <a href="{{ route('admin.module.index') }}"
                        class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100">

                        Batal

                    </a>

                    <button type="submit"
                        class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">

                        <i class="ri-save-line mr-1"></i>
                        Update Module

                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const namaInput = document.getElementById('nama');
        const slugInput = document.getElementById('slug');
        const routeInput = document.getElementById('route_name');

        function generateSlug(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function updateField() {
            routeInput.value = `public.${slugInput.value}.index`;
        }

        namaInput.addEventListener('input', function () {
            slugInput.value = generateSlug(this.value);
            updateField();
        });

        slugInput.addEventListener('input', function () {
            this.value = generateSlug(this.value);
            updateField();
        });
    </script>
@endpush