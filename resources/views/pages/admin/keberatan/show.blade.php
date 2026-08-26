@extends('layouts.admin.app')

@section('title', 'Detail Keberatan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Detail Keberatan" description="Periksa alasan keberatan dan tindak lanjut keberatan."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Keberatan',
                    'url' => route('admin.keberatan.index'),
                ],
                [
                    'label' => 'Detail Keberatan',
                ],
            ]" />

        <x-ui.flash-messages />

        @if ($errors->any())
            <div
                class="
                    rounded-xl
                    border
                    border-red-200
                    bg-red-50
                    p-4
                    dark:border-red-900/50
                    dark:bg-red-500/10
                ">
                <div
                    class="
                        flex
                        items-start
                        gap-3
                    ">
                    <i
                        class="
                            ri-error-warning-line
                            mt-0.5
                            text-lg
                            text-red-600
                            dark:text-red-400
                        "></i>

                    <div>
                        <p
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            ">
                            Terdapat data yang belum valid.
                        </p>

                        <ul
                            class="
                                mt-2
                                list-disc
                                space-y-1
                                pl-5
                                text-sm
                                text-red-700
                                dark:text-red-400
                            ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-3
            ">
            {{-- Detail Keberatan --}}
            <div class="
                    space-y-6
                    xl:col-span-2
                ">
                <div
                    class="
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        p-6
                        shadow-theme-sm
                        dark:border-gray-800
                        dark:bg-gray-900
                    ">
                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            border-b
                            border-gray-100
                            pb-5
                            dark:border-gray-800
                            sm:flex-row
                            sm:items-start
                            sm:justify-between
                        ">
                        <div>
                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-brand-600
                                ">
                                {{ $keberatan->no_keberatan }}
                            </p>

                            <h2
                                class="
                                    mt-2
                                    text-xl
                                    font-bold
                                    text-gray-900
                                    dark:text-white
                                ">
                                Keberatan atas
                                {{ data_get($keberatan, 'permohonan.no_pemohon') ?? '-' }}
                            </h2>
                        </div>

                        @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIAJUKAN)
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-yellow-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-yellow-700
                                    dark:bg-yellow-500/15
                                    dark:text-yellow-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @elseif ($keberatan->status === \App\Models\Keberatan::STATUS_DIPROSES)
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-blue-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-blue-700
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @else
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-green-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-green-700
                                    dark:bg-green-500/15
                                    dark:text-green-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @endif
                    </div>

                    <dl
                        class="
                            mt-6
                            grid
                            grid-cols-1
                            gap-6
                            md:grid-cols-2
                        ">
                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Pemohon
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'permohonan.userPublic.nama') ??
                                    (data_get($keberatan, 'permohonan.nama_pemohon') ?? '-') }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Nomor Permohonan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'permohonan.no_pemohon') ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                PPID Pembantu
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'permohonan.ppidPembantu.nama') ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Tanggal Pengajuan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->tanggal_pengajuan?->locale('id')->translatedFormat('d F Y') ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Admin Penanggap
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->admin?->username ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Status
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->status }}
                            </dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Alasan Keberatan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-xl
                                    bg-gray-50
                                    p-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:bg-gray-800
                                    dark:text-gray-300
                                ">
                                {{ $keberatan->alasan ?: '-' }}
                            </dd>
                        </div>

                        @if ($keberatan->hasil)
                            <div>
                                <dt
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    Hasil Keberatan
                                </dt>

                                <dd
                                    class="
                                        mt-2
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $keberatan->hasil }}
                                </dd>
                            </div>
                        @endif

                        @if ($keberatan->jenis_tindak_lanjut)
                            <div>
                                <dt
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    Jenis Tindak Lanjut
                                </dt>

                                <dd
                                    class="
                                        mt-2
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $keberatan->jenis_tindak_lanjut }}
                                </dd>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Tanggapan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-xl
                                    border
                                    border-gray-200
                                    p-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $keberatan->tanggapan ?: 'Belum ada tanggapan.' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Panel Tindakan --}}
            <div>
                @if ($admin->isAdminUtama())
                    {{-- Status Diajukan --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIAJUKAN)
                        <div
                            class="
                                overflow-hidden
                                rounded-2xl
                                border
                                border-gray-200
                                bg-white
                                shadow-theme-sm
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-5
                                    py-5
                                    dark:border-gray-800
                                ">
                                <h3
                                    class="
                                        text-lg
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                    Proses Keberatan
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Tandai keberatan sebagai sedang diproses.
                                </p>
                            </div>

                            <form
                                action="{{ route('admin.keberatan.proses', ['id' => $keberatan->id]) }}"
                                method="POST" class="p-5">
                                @csrf

                                <button type="submit"
                                    class="
                                        inline-flex
                                        h-11
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-brand-500
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-brand-600
                                    ">
                                    <i class="ri-play-circle-line"></i>

                                    Proses Keberatan
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Status Diproses --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIPROSES)
                        <form
                            action="{{ route('admin.keberatan.selesaikan', ['id' => $keberatan->id]) }}"
                            method="POST" enctype="multipart/form-data"
                            class="
                                overflow-hidden
                                rounded-2xl
                                border
                                border-gray-200
                                bg-white
                                shadow-theme-sm
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            @csrf

                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-5
                                    py-5
                                    dark:border-gray-800
                                ">
                                <h3
                                    class="
                                        text-lg
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                    Selesaikan Keberatan
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Masukkan keputusan dan tanggapan final.
                                </p>
                            </div>

                            <div class="space-y-5 p-5">
                                {{-- Hasil --}}
                                <div>
                                    <label for="hasil"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Hasil Keberatan
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select id="hasil" name="hasil" required
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
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">
                                        <option value="">
                                            Pilih hasil keberatan
                                        </option>

                                        @foreach ($hasilOptions as $key => $label)
                                            @php
                                                $value = is_int($key) ? $label : $key;
                                            @endphp

                                            <option value="{{ $value }}" @selected(old('hasil') === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('hasil')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Jenis Tindak Lanjut --}}
                                <div>
                                    <label for="jenis_tindak_lanjut"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Jenis Tindak Lanjut
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select id="jenis_tindak_lanjut" name="jenis_tindak_lanjut" required
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
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">
                                        <option value="">
                                            Pilih jenis tindak lanjut
                                        </option>

                                        @foreach ($tindakLanjutOptions as $key => $label)
                                            @php
                                                $value = is_int($key) ? $label : $key;
                                            @endphp

                                            <option value="{{ $value }}" @selected(old('jenis_tindak_lanjut') === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('jenis_tindak_lanjut')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Tanggapan --}}
                                <div>
                                    <label for="tanggapan"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Tanggapan Final
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <textarea id="tanggapan" name="tanggapan" rows="10" minlength="10" maxlength="10000" required
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
                                            text-gray-800
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">{{ old('tanggapan', $keberatan->tanggapan) }}</textarea>

                                    <p
                                        class="
                                            mt-1.5
                                            text-xs
                                            text-gray-500
                                        ">
                                        Minimal 10 karakter dan maksimal
                                        10.000 karakter.
                                    </p>

                                    @error('tanggapan')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- File --}}
                                <div>
                                    <label for="file_tanggapan"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Dokumen Tanggapan
                                    </label>

                                    <input id="file_tanggapan" name="file_tanggapan" type="file"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx"
                                        class="
                                            block
                                            w-full
                                            rounded-lg
                                            border
                                            border-gray-300
                                            bg-transparent
                                            text-sm
                                            text-gray-700
                                            file:mr-4
                                            file:border-0
                                            file:bg-gray-100
                                            file:px-4
                                            file:py-3
                                            file:text-sm
                                            file:font-medium
                                            dark:border-gray-700
                                            dark:text-gray-300
                                            dark:file:bg-gray-800
                                            dark:file:text-gray-300
                                        ">

                                    <p
                                        class="
                                            mt-1.5
                                            text-xs
                                            text-gray-500
                                        ">
                                        PDF, DOC, DOCX, XLS, atau XLSX.
                                        Maksimal 10 MB.
                                    </p>

                                    @error('file_tanggapan')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="
                                    border-t
                                    border-gray-100
                                    px-5
                                    py-4
                                    dark:border-gray-800
                                ">
                                <button type="submit"
                                    class="
                                        inline-flex
                                        h-11
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-brand-500
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-brand-600
                                    ">
                                    <i class="ri-check-line"></i>

                                    Selesaikan Keberatan
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Status Selesai --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_SELESAI)
                        <div
                            class="
                                rounded-2xl
                                border
                                border-green-200
                                bg-green-50
                                p-5
                                dark:border-green-900/50
                                dark:bg-green-500/10
                            ">
                            <div
                                class="
                                    flex
                                    items-start
                                    gap-3
                                ">
                                <i
                                    class="
                                        ri-checkbox-circle-line
                                        text-xl
                                        text-green-600
                                        dark:text-green-400
                                    "></i>

                                <div>
                                    <h3
                                        class="
                                            font-semibold
                                            text-green-800
                                            dark:text-green-300
                                        ">
                                        Keberatan Selesai
                                    </h3>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            leading-6
                                            text-green-700
                                            dark:text-green-400
                                        ">
                                        Keberatan telah mendapatkan
                                        keputusan dan tanggapan final.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div
                        class="
                            rounded-2xl
                            border
                            border-gray-200
                            bg-white
                            p-5
                            shadow-theme-sm
                            dark:border-gray-800
                            dark:bg-gray-900
                        ">
                        <div class="flex items-start gap-3">
                            <i
                                class="
                                    ri-information-line
                                    text-xl
                                    text-brand-500
                                "></i>

                            <div>
                                <h3
                                    class="
                                        font-semibold
                                        text-gray-800
                                        dark:text-white
                                    ">
                                    Informasi Keberatan
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        leading-6
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Keputusan dan tanggapan keberatan
                                    ditetapkan oleh Admin Utama.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
