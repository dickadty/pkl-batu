@props([
    'action',
    'method' => 'POST',
    'akunAdmin' => null,
    'ppidPembantu' => [],
    'title' => 'Informasi Akun Admin',
    'description' => 'Lengkapi identitas akun, hak akses, unit PPID, dan informasi keamanan.',
    'submitLabel' => 'Simpan Akun Admin',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.dashboard');

    $ppidPembantuList = collect($ppidPembantu ?? []);

    /*
    |--------------------------------------------------------------------------
    | Nilai awal field
    |--------------------------------------------------------------------------
    */

    $usernameValue = old('username', data_get($akunAdmin, 'username', ''));

    $emailValue = old('email', data_get($akunAdmin, 'email', ''));

    $roleValue = (string) old('role', data_get($akunAdmin, 'role', ''));

    $ppidPembantuValue = (string) old('ppid_pembantuid', data_get($akunAdmin, 'ppid_pembantuid', ''));

    /*
    |--------------------------------------------------------------------------
    | Password wajib hanya saat membuat akun
    |--------------------------------------------------------------------------
    */

    $passwordRequired = $akunAdmin === null;
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
        {{-- ============================================================
            INFORMASI FORM
        ============================================================= --}}

        @if ($description)
            <div
                class="
                    flex
                    items-start
                    gap-3
                    rounded-xl
                    border
                    border-blue-100
                    bg-blue-50/70
                    px-4
                    py-3.5
                    dark:border-blue-500/20
                    dark:bg-blue-500/10
                ">
                <div
                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-blue-100
                        text-blue-600
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <i class="ri-information-line text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p
                        class="
                            text-sm
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Data akun administrator
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-sm
                            leading-6
                            text-gray-600
                            dark:text-gray-400
                        ">
                        {{ $description }}
                    </p>
                </div>
            </div>
        @endif

        {{-- ============================================================
            FORM AKUN ADMIN
        ============================================================= --}}

        <form action="{{ $action }}" method="POST" x-data="{
            roleValue: @js($roleValue),
            ppidValue: @js($ppidPembantuValue),
            showPassword: false,
            showPasswordConfirmation: false,
            submitting: false,
        
            init() {
                this.$watch('roleValue', value => {
                    if (String(value) !== '2') {
                        this.ppidValue = '';
                    }
                });
            }
        }" @submit="submitting = true"
            class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            {{-- ========================================================
                IDENTITAS AKUN
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-blue-50
                            text-blue-600
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        <i class="ri-admin-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Identitas Akun
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Informasi utama yang digunakan untuk mengakses sistem.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-2
                    ">
                    {{-- Username --}}
                    <div>
                        <label for="username"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Username

                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-user-line text-lg"></i>
                            </span>

                            <input id="username" type="text" name="username" value="{{ $usernameValue }}"
                                placeholder="Contoh: adminpembantu2" autocomplete="username" required autofocus
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-4
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                    @error('username')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('username')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Gunakan username unik tanpa spasi.
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Email
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-mail-line text-lg"></i>
                            </span>

                            <input id="email" type="email" name="email" value="{{ $emailValue }}"
                                placeholder="Contoh: admin@ppid.go.id" autocomplete="email"
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-4
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                    @error('email')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('email')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ========================================================
                HAK AKSES
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-purple-50
                            text-purple-600
                            dark:bg-purple-500/15
                            dark:text-purple-400
                        ">
                        <i class="ri-shield-user-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Hak Akses
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Tentukan peran admin dan unit PPID yang dikelola.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-2
                    ">
                    {{-- Role --}}
                    <div>
                        <label for="role"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Role Admin

                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative z-20 bg-transparent">
                            <select id="role" name="role" x-model="roleValue" required
                                :class="roleValue !== ''
                                    ?
                                    'text-gray-800 dark:text-white/90' :
                                    'text-gray-400 dark:text-white/30'"
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    appearance-none
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    bg-none
                                    px-4
                                    py-2.5
                                    pr-11
                                    text-sm
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    @error('role')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                                <option value=""
                                    class="
                                        text-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-400
                                    ">
                                    Pilih role admin
                                </option>

                                <option value="1"
                                    class="
                                        text-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-400
                                    ">
                                    Admin Utama
                                </option>

                                <option value="2"
                                    class="
                                        text-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-400
                                    ">
                                    Admin Pembantu
                                </option>
                            </select>

                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    right-4
                                    top-1/2
                                    z-30
                                    -translate-y-1/2
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>

                        @error('role')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Admin Utama mempunyai akses pengelolaan sistem yang lebih luas.
                            </p>
                        @enderror
                    </div>

                    {{-- PPID Pembantu --}}
                    <div x-cloak x-show="String(roleValue) === '2'" x-transition>
                        <label for="ppid_pembantuid"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            PPID Pembantu

                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative z-20 bg-transparent">
                            <select id="ppid_pembantuid" name="ppid_pembantuid" x-model="ppidValue"
                                :required="String(roleValue) === '2'"
                                :disabled="String(roleValue) !== '2'"
                                :class="ppidValue !== ''
                                    ?
                                    'text-gray-800 dark:text-white/90' :
                                    'text-gray-400 dark:text-white/30'"
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    appearance-none
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    bg-none
                                    px-4
                                    py-2.5
                                    pr-11
                                    text-sm
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    @error('ppid_pembantuid')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                                <option value=""
                                    class="
                                        text-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-400
                                    ">
                                    Pilih PPID Pembantu
                                </option>

                                @foreach ($ppidPembantuList as $ppid)
                                    <option value="{{ (string) $ppid->id }}"
                                        class="
                                            text-gray-700
                                            dark:bg-gray-900
                                            dark:text-gray-400
                                        ">
                                        {{ $ppid->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>

                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    right-4
                                    top-1/2
                                    z-30
                                    -translate-y-1/2
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>

                        @error('ppid_pembantuid')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Wajib dipilih ketika role yang digunakan adalah Admin Pembantu.
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ========================================================
                KEAMANAN AKUN
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-green-50
                            text-green-600
                            dark:bg-green-500/15
                            dark:text-green-400
                        ">
                        <i class="ri-lock-password-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Keamanan Akun
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Gunakan password yang kuat dan sulit ditebak.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-2
                    ">
                    {{-- Password --}}
                    <div>
                        <label for="password"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Password

                            @if ($passwordRequired)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-lock-line text-lg"></i>
                            </span>

                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password"
                                placeholder="Masukkan password" autocomplete="new-password"
                                @if ($passwordRequired) required @endif
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-11
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                    @error('password')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">

                            <button type="button" @click="showPassword = !showPassword"
                                class="
                                    absolute
                                    inset-y-0
                                    right-0
                                    flex
                                    w-11
                                    items-center
                                    justify-center
                                    text-gray-400
                                    transition
                                    hover:text-gray-700
                                    focus:outline-hidden
                                    dark:hover:text-gray-200
                                "
                                :aria-label="showPassword
                                    ?
                                    'Sembunyikan password' :
                                    'Tampilkan password'">
                                <i :class="showPassword
                                    ?
                                    'ri-eye-off-line' :
                                    'ri-eye-line'"
                                    class="text-lg"></i>
                            </button>
                        </div>

                        @error('password')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Gunakan minimal delapan karakter dengan kombinasi huruf dan angka.
                            </p>
                        @enderror
                    </div>

                    {{-- Konfirmasi password --}}
                    <div>
                        <label for="password_confirmation"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Konfirmasi Password

                            @if ($passwordRequired)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-lock-check-line text-lg"></i>
                            </span>

                            <input id="password_confirmation"
                                :type="showPasswordConfirmation
                                    ?
                                    'text' :
                                    'password'"
                                name="password_confirmation" placeholder="Ulangi password"
                                autocomplete="new-password" @if ($passwordRequired) required @endif
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-11
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                ">

                            <button type="button"
                                @click="
                                    showPasswordConfirmation =
                                        !showPasswordConfirmation
                                "
                                class="
                                    absolute
                                    inset-y-0
                                    right-0
                                    flex
                                    w-11
                                    items-center
                                    justify-center
                                    text-gray-400
                                    transition
                                    hover:text-gray-700
                                    focus:outline-hidden
                                    dark:hover:text-gray-200
                                "
                                :aria-label="showPasswordConfirmation
                                    ?
                                    'Sembunyikan konfirmasi password' :
                                    'Tampilkan konfirmasi password'">
                                <i :class="showPasswordConfirmation
                                    ?
                                    'ri-eye-off-line' :
                                    'ri-eye-line'"
                                    class="text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ========================================================
                TOMBOL AKSI
            ========================================================= --}}

            <div
                class="
                    flex
                    flex-col-reverse
                    gap-3
                    border-t
                    border-gray-100
                    pt-6
                    dark:border-gray-800
                    sm:flex-row
                    sm:items-center
                    sm:justify-end
                ">
                <a href="{{ $cancelUrl }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-arrow-left-line text-lg"></i>

                    <span>Kembali</span>
                </a>

                <button type="reset" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-refresh-line text-lg"></i>

                    <span>Reset</span>
                </button>

                <button type="submit" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        min-w-[180px]
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-5
                        text-sm
                        font-semibold
                        text-white
                        shadow-theme-xs
                        transition
                        hover:bg-brand-600
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-brand-500/20
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    ">
                    <i x-show="!submitting" class="ri-save-line text-lg"></i>

                    <svg x-cloak x-show="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"
                        aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"></path>
                    </svg>

                    <span x-show="!submitting">
                        {{ $submitLabel }}
                    </span>

                    <span x-cloak x-show="submitting">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-common.component-card>
