@extends('layouts.admin.app')

@section('title', 'Detail Keberatan')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Detail Keberatan" description="Periksa alasan keberatan dan berikan tanggapan resmi."
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

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-3
            ">
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
                                {{ $keberatan->alasan }}
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
                                Tanggapan Saat Ini
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

            @if ($admin->isAdminUtama())
                <div>
                    <form
                        action="{{ route('admin.keberatan.update', [
                            'id' => $keberatan->id,
                        ]) }}"
                        method="POST"
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
                        @method('PUT')

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
                                Tanggapan Keberatan
                            </h3>
                        </div>

                        <div class="space-y-5 p-5">
                            <div>
                                <label for="status"
                                    class="
                                        mb-1.5
                                        block
                                        text-sm
                                        font-medium
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    Status
                                </label>

                                <select id="status" name="status" required
                                    class="
                                        h-11
                                        w-full
                                        rounded-lg
                                        border
                                        border-gray-300
                                        bg-transparent
                                        px-4
                                        text-sm
                                        dark:border-gray-700
                                        dark:bg-gray-900
                                        dark:text-white
                                    ">
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $keberatan->status) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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
                                    Tanggapan
                                </label>

                                <textarea id="tanggapan" name="tanggapan" rows="10" maxlength="10000"
                                    class="
                                        w-full
                                        rounded-lg
                                        border
                                        border-gray-300
                                        bg-transparent
                                        px-4
                                        py-3
                                        text-sm
                                        dark:border-gray-700
                                        dark:text-white
                                    ">{{ old('tanggapan', $keberatan->tanggapan) }}</textarea>

                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-gray-500
                                    ">
                                    Wajib diisi ketika status
                                    selesai atau ditolak.
                                </p>
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
                                    rounded-lg
                                    bg-brand-500
                                    px-5
                                    text-sm
                                    font-semibold
                                    text-white
                                    hover:bg-brand-600
                                ">
                                Simpan Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
