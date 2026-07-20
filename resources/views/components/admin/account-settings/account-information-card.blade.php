@props(['admin'])

@php
    $roleLabel = match ((int) $admin->role) {
        1 => 'Admin PPID Utama',
        2 => 'Admin PPID Pembantu',
        default => 'Administrator',
    };

    $ppidPembantu = trim((string) data_get($admin, 'ppidPembantu.nama', ''));
@endphp

<section
    class="
        rounded-2xl
        border
        border-gray-200
        bg-white
        shadow-theme-xs
        dark:border-gray-800
        dark:bg-white/[0.03]
    ">
    <div
        class="
            flex
            items-start
            gap-3
            border-b
            border-gray-100
            px-5
            py-5
            dark:border-gray-800
            sm:px-6
        ">
        <div
            class="
                flex
                h-11
                w-11
                shrink-0
                items-center
                justify-center
                rounded-xl
                bg-brand-50
                text-brand-600
                dark:bg-brand-500/15
                dark:text-brand-400
            ">
            <i class="ri-user-settings-line text-xl"></i>
        </div>

        <div>
            <h3
                class="
                    text-base
                    font-semibold
                    text-gray-800
                    dark:text-white/90
                ">
                Informasi Akun
            </h3>

            <p
                class="
                    mt-1
                    text-sm
                    leading-5
                    text-gray-500
                    dark:text-gray-400
                ">
                Perbarui username dan email yang digunakan pada akun admin.
            </p>
        </div>
    </div>

    <form action="{{ route('admin.account-settings.profile.update') }}" method="POST" class="space-y-5 p-5 sm:p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="account_username"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
                ">
                Username

                <span class="text-error-500">*</span>
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

                <input id="account_username" type="text" name="username"
                    value="{{ old('username', $admin->username) }}" maxlength="100" required autocomplete="username"
                    class="
                        h-11
                        w-full
                        rounded-lg
                        border
                        bg-transparent
                        pl-10
                        pr-4
                        text-sm
                        text-gray-800
                        outline-none
                        transition
                        placeholder:text-gray-400
                        focus:border-brand-300
                        focus:ring-3
                        focus:ring-brand-500/10
                        dark:bg-gray-900
                        dark:text-white/90
                        {{ $errors->has('username') ? 'border-error-500 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}
                    ">
            </div>

            @error('username')
                <p
                    class="
                        mt-1.5
                        text-xs
                        text-error-500
                    ">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="account_email"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
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

                <input id="account_email" type="email" name="email" value="{{ old('email', $admin->email) }}"
                    maxlength="100" autocomplete="email" placeholder="Masukkan alamat email"
                    class="
                        h-11
                        w-full
                        rounded-lg
                        border
                        bg-transparent
                        pl-10
                        pr-4
                        text-sm
                        text-gray-800
                        outline-none
                        transition
                        placeholder:text-gray-400
                        focus:border-brand-300
                        focus:ring-3
                        focus:ring-brand-500/10
                        dark:bg-gray-900
                        dark:text-white/90
                        {{ $errors->has('email') ? 'border-error-500 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}
                    ">
            </div>

            @error('email')
                <p
                    class="
                        mt-1.5
                        text-xs
                        text-error-500
                    ">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="account_role"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
                ">
                Peran Akun
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
                    <i class="ri-shield-user-line text-lg"></i>
                </span>

                <input id="account_role" type="text" value="{{ $roleLabel }}" disabled
                    class="
                        h-11
                        w-full
                        cursor-not-allowed
                        rounded-lg
                        border
                        border-gray-200
                        bg-gray-50
                        pl-10
                        pr-4
                        text-sm
                        text-gray-500
                        dark:border-gray-800
                        dark:bg-gray-900/50
                        dark:text-gray-400
                    ">
            </div>
        </div>

        @if ((int) $admin->role === 2)
            <div>
                <label for="account_ppid"
                    class="
                        mb-1.5
                        block
                        text-sm
                        font-medium
                        text-gray-700
                        dark:text-gray-300
                    ">
                    Unit PPID Pembantu
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
                        <i class="ri-government-line text-lg"></i>
                    </span>

                    <input id="account_ppid" type="text"
                        value="{{ $ppidPembantu !== '' ? $ppidPembantu : 'Belum ditentukan' }}"
                        disabled
                        class="
                            h-11
                            w-full
                            cursor-not-allowed
                            rounded-lg
                            border
                            border-gray-200
                            bg-gray-50
                            pl-10
                            pr-4
                            text-sm
                            text-gray-500
                            dark:border-gray-800
                            dark:bg-gray-900/50
                            dark:text-gray-400
                        ">
                </div>
            </div>
        @endif

        <div
            class="
                flex
                justify-end
                border-t
                border-gray-100
                pt-5
                dark:border-gray-800
            ">
            <button type="submit"
                class="
                    inline-flex
                    h-11
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
                    focus:outline-none
                    focus:ring-3
                    focus:ring-brand-500/20
                ">
                <i class="ri-save-line text-lg"></i>

                Simpan Informasi
            </button>
        </div>
    </form>
</section>
