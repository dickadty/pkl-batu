<section x-data="{
    showCurrentPassword: false,
    showNewPassword: false,
    showPasswordConfirmation: false,
}"
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
                bg-orange-50
                text-orange-600
                dark:bg-orange-500/15
                dark:text-orange-400
            ">
            <i class="ri-lock-password-line text-xl"></i>
        </div>

        <div>
            <h3
                class="
                    text-base
                    font-semibold
                    text-gray-800
                    dark:text-white/90
                ">
                Ubah Password
            </h3>

            <p
                class="
                    mt-1
                    text-sm
                    leading-5
                    text-gray-500
                    dark:text-gray-400
                ">
                Gunakan password yang berbeda dan sulit ditebak untuk menjaga keamanan akun.
            </p>
        </div>
    </div>

    <form action="{{ route('admin.account-settings.password.update') }}" method="POST" class="space-y-5 p-5 sm:p-6">
        @csrf
        @method('PUT')

        <div>
            <label for="current_password"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
                ">
                Password Saat Ini

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
                    <i class="ri-key-2-line text-lg"></i>
                </span>

                <input id="current_password" :type="showCurrentPassword ? 'text' : 'password'" name="current_password"
                    required autocomplete="current-password" placeholder="Masukkan password saat ini"
                    class="
                        h-11
                        w-full
                        rounded-lg
                        border
                        bg-transparent
                        pl-10
                        pr-11
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
                        {{ $errors->has('current_password')
                            ? 'border-error-500 dark:border-error-500'
                            : 'border-gray-300 dark:border-gray-700' }}
                    ">

                <button type="button" @click="showCurrentPassword = !showCurrentPassword"
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
                        hover:text-gray-600
                        dark:hover:text-gray-300
                    "
                    aria-label="Tampilkan atau sembunyikan password saat ini">
                    <i class="text-lg"
                        :class="showCurrentPassword
                            ?
                            'ri-eye-off-line' :
                            'ri-eye-line'"></i>
                </button>
            </div>

            @error('current_password')
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
            <label for="password"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
                ">
                Password Baru

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
                    <i class="ri-lock-line text-lg"></i>
                </span>

                <input id="password" :type="showNewPassword ? 'text' : 'password'" name="password" required
                    autocomplete="new-password" placeholder="Masukkan password baru"
                    class="
                        h-11
                        w-full
                        rounded-lg
                        border
                        bg-transparent
                        pl-10
                        pr-11
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
                        {{ $errors->has('password') ? 'border-error-500 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}
                    ">

                <button type="button" @click="showNewPassword = !showNewPassword"
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
                        hover:text-gray-600
                        dark:hover:text-gray-300
                    "
                    aria-label="Tampilkan atau sembunyikan password baru">
                    <i class="text-lg"
                        :class="showNewPassword
                            ?
                            'ri-eye-off-line' :
                            'ri-eye-line'"></i>
                </button>
            </div>

            @error('password')
                <p
                    class="
                        mt-1.5
                        text-xs
                        text-error-500
                    ">
                    {{ $message }}
                </p>
            @enderror

            <p
                class="
                    mt-1.5
                    text-xs
                    leading-5
                    text-gray-400
                    dark:text-gray-500
                ">
                Minimal 8 karakter serta mengandung huruf dan angka.
            </p>
        </div>

        <div>
            <label for="password_confirmation"
                class="
                    mb-1.5
                    block
                    text-sm
                    font-medium
                    text-gray-700
                    dark:text-gray-300
                ">
                Konfirmasi Password Baru

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
                    <i class="ri-lock-check-line text-lg"></i>
                </span>

                <input id="password_confirmation" :type="showPasswordConfirmation ? 'text' : 'password'"
                    name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru"
                    class="
                        h-11
                        w-full
                        rounded-lg
                        border
                        border-gray-300
                        bg-transparent
                        pl-10
                        pr-11
                        text-sm
                        text-gray-800
                        outline-none
                        transition
                        placeholder:text-gray-400
                        focus:border-brand-300
                        focus:ring-3
                        focus:ring-brand-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-white/90
                    ">

                <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
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
                        hover:text-gray-600
                        dark:hover:text-gray-300
                    "
                    aria-label="Tampilkan atau sembunyikan konfirmasi password">
                    <i class="text-lg"
                        :class="showPasswordConfirmation
                            ?
                            'ri-eye-off-line' :
                            'ri-eye-line'"></i>
                </button>
            </div>
        </div>

        <div
            class="
                rounded-xl
                border
                border-blue-100
                bg-blue-50
                p-4
                dark:border-blue-500/20
                dark:bg-blue-500/10
            ">
            <div class="flex gap-3">
                <i
                    class="
                        ri-information-line
                        mt-0.5
                        text-lg
                        text-blue-600
                        dark:text-blue-400
                    "></i>

                <p
                    class="
                        text-xs
                        leading-5
                        text-blue-700
                        dark:text-blue-300
                    ">
                    Setelah password diperbarui, gunakan password baru pada proses login berikutnya.
                </p>
            </div>
        </div>

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
                    bg-gray-800
                    px-5
                    text-sm
                    font-semibold
                    text-white
                    shadow-theme-xs
                    transition
                    hover:bg-gray-900
                    focus:outline-none
                    focus:ring-3
                    focus:ring-gray-500/20
                    dark:bg-gray-700
                    dark:hover:bg-gray-600
                ">
                <i class="ri-lock-password-line text-lg"></i>

                Perbarui Password
            </button>
        </div>
    </form>
</section>
