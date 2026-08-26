@extends('layouts.public.app')

@section('title', 'Ajukan Keberatan | PPID Kota Batu')

@section('content')
    <x-public.sections.page-hero
        eyebrow="Pengajuan Keberatan"
        title="Ajukan Keberatan"
        highlight="Layanan PPID Kota Batu"
        description="Pilih permohonan yang telah selesai atau ditolak, kemudian jelaskan alasan keberatan secara lengkap."
    />

    <section
        class="
            mx-auto
            max-w-4xl
            px-4
            py-10
            sm:px-6
            lg:px-8
        ">
        @if ($errors->any())
            <div
                class="
                    mb-6
                    rounded-xl
                    border
                    border-red-200
                    bg-red-50
                    p-5
                    text-red-700
                ">
                <p class="font-semibold">
                    Periksa kembali data berikut:
                </p>

                <ul
                    class="
                        mt-2
                        list-disc
                        space-y-1
                        pl-5
                        text-sm
                    ">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($permohonanList->isEmpty())
            <div
                class="
                    rounded-2xl
                    border
                    border-amber-200
                    bg-amber-50
                    p-6
                    text-amber-900
                ">
                <h2 class="font-bold">
                    Tidak ada permohonan yang dapat diajukan keberatan
                </h2>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-6
                    ">
                    Keberatan hanya dapat diajukan untuk permohonan
                    berstatus selesai atau ditolak dan belum pernah
                    memiliki keberatan.
                </p>

                <a href="{{ route('public.permohonan.index') }}"
                    class="
                        mt-5
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        rounded-lg
                        bg-amber-700
                        px-4
                        text-sm
                        font-semibold
                        text-white
                    ">
                    Lihat Permohonan
                </a>
            </div>
        @else
            <form action="{{ route('public.keberatan.store') }}"
                method="POST"
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                ">
                @csrf

                <div
                    class="
                        border-b
                        border-slate-200
                        px-6
                        py-5
                    ">
                    <h2
                        class="
                            text-lg
                            font-bold
                            text-slate-900
                        ">
                        Informasi Keberatan
                    </h2>
                </div>

                <div class="space-y-5 p-6">
                    <div>
                        <label for="permohonanid"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-semibold
                                text-slate-700
                            ">
                            Permohonan
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="permohonanid" name="permohonanid" required
                            class="
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                bg-white
                                px-3
                                text-sm
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500/20
                            ">
                            <option value="">
                                Pilih permohonan
                            </option>

                            @foreach ($permohonanList as $permohonan)
                                <option value="{{ $permohonan->id }}" @selected((string) old('permohonanid') === (string) $permohonan->id)>
                                    {{ $permohonan->no_pemohon ?? '#' . $permohonan->id }}
                                    —
                                    {{ $permohonan->status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="alasan"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-semibold
                                text-slate-700
                            ">
                            Alasan Keberatan
                            <span class="text-red-500">*</span>
                        </label>

                        <textarea id="alasan" name="alasan" rows="8" required minlength="20" maxlength="5000"
                            placeholder="Jelaskan alasan keberatan secara lengkap."
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                px-3
                                py-2.5
                                text-sm
                                leading-6
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500/20
                            ">{{ old('alasan') }}</textarea>

                        <p
                            class="
                                mt-1.5
                                text-xs
                                text-slate-500
                            ">
                            Minimal 20 dan maksimal 5.000 karakter.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        flex
                        flex-col-reverse
                        gap-3
                        border-t
                        border-slate-200
                        bg-slate-50
                        px-6
                        py-4
                        sm:flex-row
                        sm:justify-end
                    ">
                    <a href="{{ route('public.keberatan.index') }}"
                        class="
                            inline-flex
                            h-11
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-slate-300
                            bg-white
                            px-5
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Batal
                    </a>

                    <button type="submit"
                        class="
                            inline-flex
                            h-11
                            items-center
                            justify-center
                            rounded-lg
                            bg-red-700
                            px-6
                            text-sm
                            font-semibold
                            text-white
                            hover:bg-red-800
                        ">
                        Ajukan Keberatan
                    </button>
                </div>
            </form>
        @endif
    </section>
@endsection
