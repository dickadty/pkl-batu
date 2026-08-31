@php
    $linkTerkait = [
        ['inisial' => 'KB', 'nama' => 'Jaringan Dokumentasi dan Informasi Hukum Kota Batu', 'url' => 'https://jdih.batukota.go.id', 'logo' =>'assets/img/logo/jdih.png'],
        ['inisial' => 'DK', 'nama' => 'Layanan Pengadaan Secara Elektronik', 'url' => 'http://lpse.batukota.go.id', 'logo' => 'assets/img/logo/lpse.png'],
        ['inisial' => 'JTM', 'nama' => 'Komisi Pemberantasan Korupsi', 'url' => 'https://elhkpn.kpk.go.id', 'logo' => 'assets/img/logo/lhkpn.jpeg'],
        ['inisial' => 'KI', 'nama' => 'Komisi Informasi Jawa Timur', 'url' => 'http://kip.jatimprov.go.id/', 'logo' => 'assets/img/logo/komisiinfojatim.jpeg'],
        ['inisial' => 'ORI', 'nama' => 'Ombudsman Republik Indonesia', 'url' => 'https://www.opengovindonesia.org', 'logo' => 'assets/img/logo/opengov.jpeg'],

    ];
@endphp

<section class="relative overflow-visible bg-white pb-24 pt-16 lg:pb-28 lg:pt-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
            <div class="mb-7 flex justify-center text-center">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">
                    Akses instansi terkait
                </h2>
                <span class="mx-auto mt-3 block h-1 w-20 rounded-full bg-emerald-600"></span>
            </div>
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-5">
            @foreach ($linkTerkait as $link)
                <a
                    href="{{ $link['url'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group flex min-h-48 flex-col items-center justify-center rounded-xl border border-emerald-700/30 bg-linear-to-br from-green-950 via-green-900 to-emerald-700 px-3 py-6 text-center transition hover:-translate-y-1 hover:from-green-900 hover:to-emerald-600">
                    @if ($link['logo'] && file_exists(public_path($link['logo'])))
                        <span
                            class="flex h-20 w-20 items-center justify-center rounded-full bg-white p-2.5 ring-4 ring-white/15">

                            <img
                                src="{{ asset($link['logo']) }}"
                                alt="Logo {{ $link['nama'] }}"
                                class="h-full w-full object-contain">

                        </span>
                    @else
                        <span
                            class="flex h-20 w-20 items-center justify-center rounded-full border border-white/20 bg-white/10 text-base font-bold tracking-wide text-white">

                            {{ $link['inisial'] }}

                        </span>
                    @endif
                    <span class="mt-5 w-full text-center text-xs font-semibold leading-5 text-white">
                        {{ $link['nama'] }}
                    </span>
                </a>
            @endforeach
            </div>
        </div>
    </div>

    <div class="absolute -bottom-8 left-0 z-10 w-full overflow-hidden leading-0">
        <svg
            class="block h-10 w-full rotate-180"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M0,60 C200,100 400,20 600,60 C800,100 1000,20 1200,60 V120 H0 Z"
                class="fill-white"
            ></path>
        </svg>
    </div>
</section>
