@extends('layouts.admin.app')

@section('title', 'Edit Akun Admin')

@section('content')
    <div class="space-y-6">
        {{-- ============================================================
            JUDUL HALAMAN
        ============================================================= --}}

        <x-admin.page-header
            title="Edit Akun Admin"
            description="Perbarui username, email, role, unit PPID Pembantu, atau password akun administrator."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Master Data',
                ],
                [
                    'label' => 'Akun Admin',
                    'url' => route('admin.akun-admin.index'),
                ],
                [
                    'label' => 'Edit Akun Admin',
                ],
            ]"
        />

        {{-- ============================================================
            FLASH MESSAGE DAN VALIDATION ERROR
        ============================================================= --}}

        <x-ui.flash-messages />

        {{-- ============================================================
            INFORMASI AKUN YANG DIEDIT
        ============================================================= --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                px-5
                py-4
                shadow-theme-sm
                dark:border-gray-800
                dark:bg-gray-900
                sm:px-6
            "
        >
            <div
                class="
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >
                <div class="flex items-center gap-3">
                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-full
                            bg-indigo-50
                            text-base
                            font-bold
                            text-indigo-600
                            dark:bg-indigo-500/15
                            dark:text-indigo-400
                        "
                    >
                        {{
                            mb_strtoupper(
                                mb_substr(
                                    (string) ($akunAdmin->username ?? 'A'),
                                    0,
                                    1
                                )
                            )
                        }}
                    </div>

                    <div class="min-w-0">
                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            "
                        >
                            {{ $akunAdmin->username ?? 'Akun Admin' }}
                        </p>

                        <p
                            class="
                                mt-1
                                truncate
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            {{ $akunAdmin->email ?: 'Email belum diisi' }}
                        </p>
                    </div>
                </div>

                <span
                    class="
                        inline-flex
                        w-fit
                        items-center
                        gap-1.5
                        rounded-full
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        {{
                            (int) ($akunAdmin->role ?? 0) === 1
                                ? 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400'
                                : 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'
                        }}
                    "
                >
                    <span
                        class="
                            h-1.5
                            w-1.5
                            rounded-full
                            bg-current
                        "
                    ></span>

                    {{
                        (int) ($akunAdmin->role ?? 0) === 1
                            ? 'Admin Utama'
                            : 'Admin PPID Pembantu'
                    }}
                </span>
            </div>
        </div>

        {{-- ============================================================
            FORM EDIT AKUN ADMIN
        ============================================================= --}}

        <x-forms.akun-admin-form
            :action="route(
                'admin.akun-admin.update',
                [
                    'id' => $akunAdmin->id,
                ]
            )"
            method="PUT"
            :ppid-pembantu="$ppidPembantu"
            :akun-admin="$akunAdmin"
            title="Edit Informasi Akun Admin"
            description="Perbarui data akun di bawah ini. Kosongkan password dan konfirmasi password apabila tidak ingin mengganti password."
            submit-label="Simpan Perubahan"
            :cancel-url="route('admin.akun-admin.index')"
        />
    </div>
@endsection