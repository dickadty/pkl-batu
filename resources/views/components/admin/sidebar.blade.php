@php
    use App\Models\Permohonan;

    $admin = Auth::guard('admin')->user();

    $role = (int) ($admin->role ?? 0);

    $roleLabel = $role === 1 ? 'PPID Utama' : 'PPID Pembantu';

    $jumlahPermohonanBaru = 0;
    $jumlahValidasiMasuk = 0;
    $jumlahPermohonanPembantu = 0;

    if ($admin) {
        if ($role === 1) {
            $jumlahPermohonanBaru = Permohonan::whereIn('status', ['Diajukan', 'Diproses'])->count();

            $jumlahValidasiMasuk = Permohonan::where('status', 'Menunggu Validasi Admin Utama')->count();
        }

        if ($role === 2) {
            $jumlahPermohonanPembantu = Permohonan::where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->whereIn('status', ['Diteruskan ke PPID Pembantu', 'Revisi PPID Pembantu'])
                ->count();
        }
    }
@endphp

<aside class="sidebar">
    <div class="brand-panel">
        <div class="d-flex align-items-center gap-2">
            <div style="width:42px;height:42px;background:white;border-radius:4px;"></div>

            <div>
                <div class="brand-title">
                    PPID KOTA BATU
                </div>

                <div class="brand-subtitle">
                    PEJABAT PENGELOLA INFORMASI DAN DOKUMENTASI
                </div>
            </div>
        </div>

        <div class="user-panel">
            <div>{{ $roleLabel }}</div>
            <div>{{ $admin->email ?? 'ppid@batukota.go.id' }}</div>
        </div>
    </div>

    <div class="nav-label">
        MAIN NAVIGATION - {{ strtoupper($roleLabel) }}
    </div>

    <nav class="sidebar-menu">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            Dashboard
        </a>

        {{-- Menu khusus Admin Utama --}}
        @if ($role === 1)
            <a href="{{ route('admin.ppid-pembantu.index') }}"
                class="{{ request()->routeIs('admin.ppid-pembantu.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                PPID Pembantu
            </a>

            <div class="submenu">
                <a href="{{ route('admin.ppid-pembantu.create') }}">
                    Tambah PPID Pembantu
                </a>

                <a href="{{ route('admin.ppid-pembantu.index') }}">
                    Daftar PPID Pembantu
                </a>
            </div>
        @endif

        {{-- Informasi dan Dokumentasi --}}
        <a href="{{ route('admin.informasi-publik.index') }}"
            class="{{ request()->routeIs('admin.informasi-publik.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            Informasi & Dokumentasi
        </a>

        <div class="submenu">
            <a href="{{ route('admin.informasi-publik.create') }}">
                Tambah Informasi
            </a>

            <a href="{{ route('admin.informasi-publik.index') }}">
                Daftar Informasi
            </a>
        </div>

        {{-- Berita --}}
        <a href="{{ route('admin.berita.index') }}"
            class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i>
            Berita
        </a>

        <div class="submenu">
            <a href="{{ route('admin.berita.create') }}">
                Tambah Berita
            </a>

            <a href="{{ route('admin.berita.index') }}">
                Daftar Berita
            </a>
        </div>

        {{-- Permohonan untuk Admin Utama --}}
        @if ($role === 1)
            <a href="{{ route('admin.permohonan.index') }}"
                class="{{ request()->routeIs('admin.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-inbox-fill"></i>
                Permohonan Informasi

                @if ($jumlahPermohonanBaru + $jumlahValidasiMasuk > 0)
                    <span class="badge bg-danger ms-auto">
                        {{ $jumlahPermohonanBaru + $jumlahValidasiMasuk }}
                    </span>
                @endif
            </a>

            <div class="submenu">
                <a href="{{ route('admin.permohonan.index') }}">
                    Daftar Permohonan
                </a>

                <a href="{{ route('admin.permohonan.index') }}">
                    Menunggu Validasi

                    @if ($jumlahValidasiMasuk > 0)
                        <span class="badge bg-warning text-dark ms-2">
                            {{ $jumlahValidasiMasuk }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        {{-- Permohonan untuk Admin Pembantu --}}
        @if ($role === 2)
            <a href="{{ route('admin.permohonan.index') }}"
                class="{{ request()->routeIs('admin.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i>
                Permohonan dari PPID Utama

                @if ($jumlahPermohonanPembantu > 0)
                    <span class="badge bg-danger ms-auto">
                        {{ $jumlahPermohonanPembantu }}
                    </span>
                @endif
            </a>

            <div class="submenu">
                <a href="{{ route('admin.permohonan.index') }}">
                    Laporan Masuk

                    @if ($jumlahPermohonanPembantu > 0)
                        <span class="badge bg-danger ms-2">
                            {{ $jumlahPermohonanPembantu }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        {{-- Menu lanjutan --}}
        <a href="#">
            <i class="bi bi-clipboard2-fill"></i>
            Ringkasan Informasi
            <span class="ms-auto">+</span>
        </a>

        <a href="#">
            <i class="bi bi-bell-fill"></i>
            Laporan Publik
            <span class="ms-auto">+</span>
        </a>

        <a href="#">
            <i class="bi bi-envelope-fill"></i>
            Pesan Masuk
        </a>
    </nav>

    <div class="sidebar-footer">
        Copyright © 2018 <b style="color:red;">PKL PTI - FILKOM UB 2018.</b><br>
        <b>Support:</b> Brawijaya University
    </div>
</aside>
