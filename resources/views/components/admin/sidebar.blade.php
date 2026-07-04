@php
    $admin = Auth::guard('admin')->user();
    $roleLabel = (int) ($admin->role ?? 0) === 1 ? 'PPID Utama' : 'PPID Pembantu';
@endphp

<aside class="sidebar">

    <div class="brand-panel">
        <div class="d-flex align-items-center gap-2">
            <div style="width:42px;height:42px;background:white;border-radius:4px;"></div>

            <div>
                <div class="brand-title">PPID KOTA BATU</div>
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

        {{-- PPID Pembantu --}}
        @if ((int) ($admin->role ?? 0) === 1)
            <a href="javascript:void(0)" class="menu-toggle">
                <span>
                    <i class="bi bi-people-fill"></i>
                    PPID Pembantu
                </span>

                <i class="bi bi-chevron-down arrow"></i>
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


        {{-- Informasi Publik --}}

        <a href="javascript:void(0)" class="menu-toggle">
            <span>
                <i class="bi bi-file-earmark-text-fill"></i>
                Informasi & Dokumentasi
            </span>

            <i class="bi bi-chevron-down arrow"></i>
        </a>

        <div class="submenu">

            <a href="{{ route('admin.informasi-publik.create') }}">
                Tambah Informasi
            </a>

            <a href="{{ route('admin.informasi-publik.index') }}">
                Daftar Informasi
            </a>

        </div>

        <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
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


        Ringkasan Informasi

        {{-- <a href="javascript:void(0)" class="menu-toggle">
            <span>
                <i class="bi bi-clipboard2-fill"></i>
                Ringkasan Informasi
            </span>

            <i class="bi bi-chevron-down arrow"></i>
        </a>

        <div class="submenu">

            <a href="#">
                Ringkasan Tahunan
            </a>

            <a href="#">
                Ringkasan Bulanan
            </a>

        </div> --}}


        {{-- Laporan Publik --}}

        {{-- <a href="javascript:void(0)" class="menu-toggle">
            <span>
                <i class="bi bi-bell-fill"></i>
                Laporan Publik
            </span>

            <i class="bi bi-chevron-down arrow"></i>
        </a>

        <div class="submenu">

            <a href="#">
                Laporan Masuk
            </a>

            <a href="#">
                Laporan Selesai
            </a>

        </div> --}}


        {{-- Pesan Masuk --}}

        {{-- <a href="#">
            <i class="bi bi-envelope-fill"></i>
            Pesan Masuk
        </a> --}}

    </nav>

    <div class="sidebar-footer">
        Copyright © 2018
        <b style="color:red;">PKL PTI - FILKOM UB 2018.</b>
        <br>

        <b>Support:</b> Brawijaya University
    </div>

</aside>
