@php
    use Illuminate\Support\Facades\Auth;

    $admin = Auth::guard('admin')->user();

    $username = trim((string) data_get($admin, 'username', ''));

    $email = trim((string) data_get($admin, 'email', ''));

    $initial = $username !== '' ? mb_strtoupper(mb_substr($username, 0, 1)) : 'A';

    $roleLabel = match ((int) data_get($admin, 'role', 0)) {
        1 => 'Admin PPID Utama',
        2 => 'Admin PPID Pembantu',
        default => 'Administrator',
    };

    $accountSettingsActive = request()->routeIs('admin.account-settings.*');

    $notificationActive = request()->routeIs('admin.notifikasi.*');
@endphp

<div class="relative" x-data="{
    dropdownOpen: false,

    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },

    closeDropdown() {
        this.dropdownOpen = false;
    },
}" @click.away="closeDropdown()" @keydown.escape.window="closeDropdown()">
    <button type="button"
        class="
            flex
            items-center
            rounded-xl
            p-1
            text-gray-700
            transition
            hover:bg-gray-100
            dark:text-gray-400
            dark:hover:bg-gray-800
        "
        @click.prevent="toggleDropdown()" :aria-expanded="dropdownOpen" aria-haspopup="true">
        <span
            class="
                mr-3
                flex
                h-11
                w-11
                shrink-0
                items-center
                justify-center
                overflow-hidden
                rounded-full
                bg-gradient-to-br
                from-brand-500
                to-blue-600
                text-sm
                font-bold
                text-white
                shadow-theme-xs
            ">
            {{ $initial }}
        </span>

        <span
            class="
                hidden
                min-w-0
                text-left
                sm:block
            ">
            <span
                class="
                    block
                    max-w-[150px]
                    truncate
                    text-sm
                    font-semibold
                    text-gray-800
                    dark:text-white/90
                ">
                {{ $username !== '' ? $username : 'Administrator' }}
            </span>

            <span
                class="
                    mt-0.5
                    block
                    text-xs
                    text-gray-500
                    dark:text-gray-400
                ">
                {{ $roleLabel }}
            </span>
        </span>

        <svg class="
                ml-2
                h-5
                w-5
                transition-transform
                duration-200
            "
            :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-cloak x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="
            absolute
            right-0
            z-50
            mt-3
            flex
            w-[290px]
            origin-top-right
            flex-col
            rounded-2xl
            border
            border-gray-200
            bg-white
            p-3
            shadow-theme-lg
            dark:border-gray-800
            dark:bg-gray-dark
        ">
        <div
            class="
                flex
                items-center
                gap-3
                rounded-xl
                bg-gray-50
                p-3
                dark:bg-white/[0.03]
            ">
            <div
                class="
                    flex
                    h-12
                    w-12
                    shrink-0
                    items-center
                    justify-center
                    rounded-full
                    bg-gradient-to-br
                    from-brand-500
                    to-blue-600
                    text-base
                    font-bold
                    text-white
                ">
                {{ $initial }}
            </div>

            <div class="min-w-0">
                <span
                    class="
                        block
                        truncate
                        text-sm
                        font-semibold
                        text-gray-800
                        dark:text-white/90
                    ">
                    {{ $username !== '' ? $username : 'Administrator' }}
                </span>

                <span
                    class="
                        mt-0.5
                        block
                        truncate
                        text-xs
                        text-gray-500
                        dark:text-gray-400
                    ">
                    {{ $email !== '' ? $email : $roleLabel }}
                </span>
            </div>
        </div>

        <ul
            class="
                mt-3
                flex
                flex-col
                gap-1
                border-b
                border-gray-200
                pb-3
                dark:border-gray-800
            ">
            <li>
                <a href="{{ route('admin.account-settings.index') }}" @click="closeDropdown()"
                    class="
                        group
                        flex
                        items-center
                        gap-3
                        rounded-lg
                        px-3
                        py-2.5
                        text-sm
                        font-medium
                        transition
                        {{ $accountSettingsActive
                            ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400'
                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300' }}
                    ">
                    <span
                        class="
                            flex
                            h-8
                            w-8
                            items-center
                            justify-center
                            rounded-lg
                            {{ $accountSettingsActive
                                ? 'bg-brand-100 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400'
                                : 'bg-gray-100 text-gray-500 group-hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:text-gray-300' }}
                        ">
                        <i class="ri-settings-3-line text-lg"></i>
                    </span>

                    Pengaturan Akun
                </a>
            </li>

            <li>
                <a href="{{ route('admin.notifikasi.index') }}" @click="closeDropdown()"
                    class="
                        group
                        flex
                        items-center
                        gap-3
                        rounded-lg
                        px-3
                        py-2.5
                        text-sm
                        font-medium
                        transition
                        {{ $notificationActive
                            ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400'
                            : 'text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300' }}
                    ">
                    <span
                        class="
                            flex
                            h-8
                            w-8
                            items-center
                            justify-center
                            rounded-lg
                            {{ $notificationActive
                                ? 'bg-brand-100 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400'
                                : 'bg-gray-100 text-gray-500 group-hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:text-gray-300' }}
                        ">
                        <i class="ri-notification-3-line text-lg"></i>
                    </span>

                    Notifikasi
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
            @csrf

            <button type="submit"
                class="
                    group
                    flex
                    w-full
                    items-center
                    gap-3
                    rounded-lg
                    px-3
                    py-2.5
                    text-left
                    text-sm
                    font-medium
                    text-gray-700
                    transition
                    hover:bg-error-50
                    hover:text-error-600
                    dark:text-gray-400
                    dark:hover:bg-error-500/10
                    dark:hover:text-error-400
                "
                @click="closeDropdown()">
                <span
                    class="
                        flex
                        h-8
                        w-8
                        items-center
                        justify-center
                        rounded-lg
                        bg-gray-100
                        text-gray-500
                        transition
                        group-hover:bg-error-100
                        group-hover:text-error-600
                        dark:bg-gray-800
                        dark:text-gray-400
                        dark:group-hover:bg-error-500/15
                        dark:group-hover:text-error-400
                    ">
                    <i class="ri-logout-box-r-line text-lg"></i>
                </span>

                Keluar
            </button>
        </form>
    </div>
</div>
