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
            <div>{{ $roleLabel ?? 'Admin PPID' }}</div>
            <div>{{ $admin->email ?? 'ppid@batukota.go.id' }}</div>
        </div>
    </div>

    <div class="nav-label">
        MAIN NAVIGATION - {{ strtoupper($roleLabel ?? 'ADMIN PPID') }}
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            Dashboard
        </a>

        @if ($isAdminUtama && ($hasPpidPembantuRoute ?? false))
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

        @if ($isAdminUtama && ($hasAkunAdminRoute ?? false))
            <a href="{{ route('admin.akun-admin.create') }}"
                class="{{ request()->routeIs('admin.akun-admin.*') ? 'active' : '' }}">
                <i class="bi bi-person-plus-fill"></i>
                Akun Admin
            </a>

            <div class="submenu">
                <a href="{{ route('admin.akun-admin.create') }}">
                    Tambah Akun Admin
                </a>
            </div>
        @endif

        @if ($isAdminUtama && ($hasPejabatRoute ?? false))
            <a href="{{ route('admin.pejabat.index') }}"
                class="{{ request()->routeIs('admin.pejabat.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i>
                Pejabat
            </a>

            <div class="submenu">
                <a href="{{ route('admin.pejabat.create') }}">
                    Tambah Pejabat
                </a>

                <a href="{{ route('admin.pejabat.index') }}">
                    Daftar Pejabat
                </a>
            </div>
        @endif

        @if ($isAdminUtama && ($hasSliderRoute ?? false))
            <a href="{{ route('admin.slider.index') }}"
                class="{{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i>
                Slider
            </a>

            <div class="submenu">
                <a href="{{ route('admin.slider.create') }}">
                    Tambah Slider
                </a>

                <a href="{{ route('admin.slider.index') }}">
                    Daftar Slider
                </a>
            </div>
        @endif

        @if ($isAdminUtama && ($hasFaqRoute ?? false))
            <a href="{{ route('admin.faq.index') }}" class="{{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle-fill"></i>
                FAQ
            </a>

            <div class="submenu">
                <a href="{{ route('admin.faq.create') }}">
                    Tambah FAQ
                </a>

                <a href="{{ route('admin.faq.index') }}">
                    Daftar FAQ
                </a>
            </div>
        @endif

        @if ($hasInformasiPublikRoute ?? false)
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
        @endif

        @if ($hasBeritaRoute ?? false)
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
        @endif

        @if ($isAdminUtama && ($hasPermohonanRoute ?? false))
            <a href="{{ route('admin.permohonan.index') }}"
                class="{{ request()->routeIs('admin.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-inbox-fill"></i>
                Permohonan Informasi

                @if (($totalNotifikasiAdminUtama ?? 0) > 0)
                    <span class="badge bg-danger ms-auto">
                        {{ $totalNotifikasiAdminUtama }}
                    </span>
                @endif
            </a>

            <div class="submenu">
                <a href="{{ route('admin.permohonan.index') }}">
                    Daftar Permohonan
                </a>

                <a href="{{ route('admin.permohonan.index') }}">
                    Menunggu Validasi

                    @if (($jumlahValidasiMasuk ?? 0) > 0)
                        <span class="badge bg-warning text-dark ms-2">
                            {{ $jumlahValidasiMasuk }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        @if ($isAdminPembantu && ($hasPermohonanRoute ?? false))
            <a href="{{ route('admin.permohonan.index') }}"
                class="{{ request()->routeIs('admin.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i>
                Permohonan dari PPID Utama

                @if (($jumlahPermohonanPembantu ?? 0) > 0)
                    <span class="badge bg-danger ms-auto">
                        {{ $jumlahPermohonanPembantu }}
                    </span>
                @endif
            </a>

            <div class="submenu">
                <a href="{{ route('admin.permohonan.index') }}">
                    Laporan Masuk

                    @if (($jumlahPermohonanPembantu ?? 0) > 0)
                        <span class="badge bg-danger ms-2">
                            {{ $jumlahPermohonanPembantu }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        @if ($isAdminUtama && ($hasPesanMasukRoute ?? false))
            <a href="{{ route('admin.pesan-masuk.index') }}"
                class="{{ request()->routeIs('admin.pesan-masuk.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-fill"></i>
                Pesan Masuk

                @if (($jumlahPesanMasukBaru ?? 0) > 0)
                    <span class="badge bg-danger ms-auto">
                        {{ $jumlahPesanMasukBaru }}
                    </span>
                @endif
            </a>
        @endif

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
    </nav>
</aside>
