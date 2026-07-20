@props([
    'action',
    'method' => 'POST',
    'pengadaan' => null,
    'ppidPembantu' => [],
    'lockedPpid' => null,
    'title' => 'Informasi Pengadaan',
    'description' => null,
    'submitLabel' => 'Simpan Pengadaan',
    'cancelUrl' => null,
])

@php
    $formMethod = strtoupper((string) $method);

    $cancelUrl = $cancelUrl ?? route('admin.pengadaan.index');

    $ppidPembantuList = collect($ppidPembantu ?? []);

    $namaPaketValue = old('nama_paket', data_get($pengadaan, 'nama_paket', ''));

    $paguValue = old('pagu', data_get($pengadaan, 'pagu', ''));

    $sumberDanaValue = old('sumber_dana', data_get($pengadaan, 'sumber_dana', ''));

    $metodeValue = old('metode', data_get($pengadaan, 'metode', ''));

    $rencanaKegiatanValue = old('rencana_kegiatan', data_get($pengadaan, 'rencana_kegiatan', ''));

    $ppidValue = (string) old(
        'ppid_pembantuid',
        data_get($pengadaan, 'ppid_pembantuid', data_get($lockedPpid, 'id', '')),
    );
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
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

                <p
                    class="
                        text-sm
                        leading-6
                        text-gray-600
                        dark:text-gray-400
                    ">
                    {{ $description }}
                </p>
            </div>
        @endif

        <form action="{{ $action }}" method="POST" x-data="{
            submitting: false,
            pagu: @js((string) $paguValue),
        
            normalizePagu() {
                this.pagu = String(
                    this.pagu ?? ''
                ).replace(
                    /[^0-9]/g,
                    ''
                );
            },
        
            formattedPagu() {
                const digits = String(
                    this.pagu ?? ''
                ).replace(
                    /[^0-9]/g,
                    ''
                );
        
                if (digits === '') {
                    return '0';
                }
        
                return new Intl.NumberFormat(
                    'id-ID'
                ).format(
                    Number(digits)
                );
            }
        }"
            @submit="
                normalizePagu();
                submitting = true;
            " class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

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
                        <i class="ri-shopping-bag-3-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Informasi Paket
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Lengkapi identitas dan anggaran paket pengadaan.
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
                    <div class="lg:col-span-2">
                        <label for="nama_paket"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Nama Paket

                            <span class="text-red-500">*</span>
                        </label>

                        <input id="nama_paket" type="text" name="nama_paket" value="{{ $namaPaketValue }}"
                            maxlength="200" placeholder="Masukkan nama paket pengadaan" required autofocus
                            class="
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                px-4
                                text-sm
                                text-gray-800
                                shadow-theme-xs
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-brand-300
                                focus:ring-3
                                focus:ring-brand-500/10
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                @error('nama_paket')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                @enderror
                            ">

                        @error('nama_paket')
                            <p class="mt-1.5 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="pagu"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Pagu Anggaran

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
                                    pl-4
                                    text-sm
                                    font-semibold
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Rp
                            </span>

                            <input id="pagu" type="text" name="pagu" x-model="pagu" @input="normalizePagu()"
                                inputmode="numeric" maxlength="250" placeholder="Contoh: 150000000" required
                                class="
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-12
                                    pr-4
                                    text-sm
                                    text-gray-800
                                    shadow-theme-xs
                                    outline-none
                                    transition
                                    placeholder:text-gray-400
                                    focus:border-brand-300
                                    focus:ring-3
                                    focus:ring-brand-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    @error('pagu')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">
                        </div>

                        <p
                            class="
                                mt-1.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Nilai terbaca:
                            <strong
                                class="
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Rp <span x-text="formattedPagu()"></span>
                            </strong>
                        </p>

                        @error('pagu')
                            <p class="mt-1.5 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="sumber_dana"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Sumber Dana

                            <span class="text-red-500">*</span>
                        </label>

                        <input id="sumber_dana" type="text" name="sumber_dana" value="{{ $sumberDanaValue }}"
                            maxlength="250" placeholder="Contoh: APBD" required
                            class="
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                px-4
                                text-sm
                                text-gray-800
                                shadow-theme-xs
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-brand-300
                                focus:ring-3
                                focus:ring-brand-500/10
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                @error('sumber_dana')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                @enderror
                            ">

                        @error('sumber_dana')
                            <p class="mt-1.5 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="metode"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Metode Pengadaan

                            <span class="text-red-500">*</span>
                        </label>

                        <input id="metode" type="text" name="metode" value="{{ $metodeValue }}" maxlength="250"
                            placeholder="Contoh: Pengadaan Langsung" required
                            class="
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                px-4
                                text-sm
                                text-gray-800
                                shadow-theme-xs
                                outline-none
                                transition
                                placeholder:text-gray-400
                                focus:border-brand-300
                                focus:ring-3
                                focus:ring-brand-500/10
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                @error('metode')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                @enderror
                            ">

                        @error('metode')
                            <p class="mt-1.5 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
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

                        @if ($lockedPpid)
                            <div
                                class="
                                    flex
                                    h-11
                                    items-center
                                    gap-3
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-gray-50
                                    px-4
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:border-gray-700
                                    dark:bg-gray-800
                                    dark:text-gray-300
                                ">
                                <i
                                    class="
                                        ri-government-line
                                        text-lg
                                        text-purple-600
                                        dark:text-purple-400
                                    "></i>

                                {{ data_get($lockedPpid, 'nama', '-') }}
                            </div>

                            <input type="hidden" name="ppid_pembantuid"
                                value="{{ data_get($lockedPpid, 'id') }}">

                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Unit PPID ditentukan otomatis berdasarkan akun yang sedang login.
                            </p>
                        @else
                            <select id="ppid_pembantuid" name="ppid_pembantuid" required
                                class="
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    px-4
                                    text-sm
                                    text-gray-800
                                    shadow-theme-xs
                                    outline-none
                                    transition
                                    focus:border-brand-300
                                    focus:ring-3
                                    focus:ring-brand-500/10
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    @error('ppid_pembantuid')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                    @enderror
                                ">
                                <option value="">
                                    Pilih PPID Pembantu
                                </option>

                                @foreach ($ppidPembantuList as $ppid)
                                    <option value="{{ $ppid->id }}" @selected($ppidValue === (string) $ppid->id)>
                                        {{ $ppid->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @error('ppid_pembantuid')
                            <p class="mt-1.5 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

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
                        <i class="ri-calendar-todo-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Rencana Kegiatan
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Maksimal 500 karakter.
                        </p>
                    </div>
                </div>

                <div x-data="{
                    count: @js(mb_strlen((string) $rencanaKegiatanValue))
                }">
                    <label for="rencana_kegiatan"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Rencana Kegiatan

                        <span class="text-red-500">*</span>
                    </label>

                    <textarea id="rencana_kegiatan" name="rencana_kegiatan" rows="7" maxlength="500"
                        placeholder="Masukkan rencana kegiatan pengadaan" required
                        @input="
                            count =
                                $event.target.value.length
                        "
                        class="
                            w-full
                            resize-y
                            rounded-lg
                            border
                            border-gray-300
                            bg-transparent
                            px-4
                            py-3
                            text-sm
                            leading-7
                            text-gray-800
                            shadow-theme-xs
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-brand-300
                            focus:ring-3
                            focus:ring-brand-500/10
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-white/90
                            @error('rencana_kegiatan')
                                border-red-500
                                focus:border-red-500
                                focus:ring-red-500/10
                            @enderror
                        ">{{ $rencanaKegiatanValue }}</textarea>

                    <div
                        class="
                            mt-1.5
                            flex
                            justify-between
                            gap-3
                        ">
                        @error('rencana_kegiatan')
                            <p class="text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @else
                            <span></span>
                        @enderror

                        <span
                            class="
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            <span x-text="count"></span>/500
                        </span>
                    </div>
                </div>
            </section>

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
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                    ">
                    <i class="ri-arrow-left-line text-lg"></i>

                    Kembali
                </a>

                <button type="submit" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        min-w-[190px]
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
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    ">
                    <i x-show="!submitting" class="ri-save-line text-lg"></i>

                    <i x-cloak x-show="submitting"
                        class="
                            ri-loader-4-line
                            animate-spin
                            text-lg
                        "></i>

                    <span
                        x-text="submitting
                            ? 'Menyimpan...'
                            : @js($submitLabel)"></span>
                </button>
            </div>
        </form>
    </div>
</x-common.component-card>
