<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel Administrasi PPID')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #e9e9e9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #ddd;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
        }

        .brand-panel {
            background: linear-gradient(135deg, #f44336, #d81b60, #ff9800);
            color: white;
            padding: 18px 16px;
            min-height: 145px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 8px;
        }

        .brand-subtitle {
            font-size: 11px;
            line-height: 1.2;
        }

        .user-panel {
            font-size: 14px;
            margin-top: 20px;
        }

        .nav-label {
            background: #eeeeee;
            color: #333;
            font-size: 12px;
            font-weight: bold;
            padding: 8px 16px;
        }

        .sidebar-menu {
            padding: 12px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #222;
            text-decoration: none;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #f92828;
            background: #fafafa;
        }

        .sidebar-menu .submenu {
            padding-left: 44px;
        }

        .sidebar-menu .submenu a {
            font-weight: normal;
            font-size: 13px;
            padding: 8px 12px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 14px;
            font-size: 12px;
            color: #333;
            background: #fff;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
        }

        .topbar {
            height: 66px;
            background: #ff1f1f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .topbar-title {
            font-weight: bold;
            font-size: 18px;
        }

        .topbar-title span {
            color: #ffff00;
        }

        .content {
            padding: 28px;
        }

        .breadcrumb-custom {
            color: #666;
            font-size: 14px;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .panel-card {
            background: white;
            border-radius: 2px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        .panel-card-header {
            padding: 18px 20px;
            font-size: 18px;
            font-weight: 500;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .section-title {
            background: #08b8c8;
            color: white;
            text-align: center;
            font-weight: bold;
            padding: 14px;
        }

        .form-material {
            padding: 26px 22px;
        }

        .form-material label {
            font-weight: bold;
            font-size: 13px;
            color: #444;
            margin-bottom: 6px;
        }

        .form-material .form-control,
        .form-material .form-select {
            border: none;
            border-bottom: 1px solid #ddd;
            border-radius: 0;
            box-shadow: none;
            padding-left: 0;
            font-size: 14px;
        }

        .form-material .form-control:focus,
        .form-material .form-select:focus {
            border-bottom: 2px solid #08b8c8;
            box-shadow: none;
        }

        .btn-red {
            background: #f92828;
            color: white;
            border: none;
        }

        .btn-red:hover {
            background: #d81f1f;
            color: white;
        }
    </style>
</head>

<body>
<div class="admin-wrapper">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="brand-panel">
            <div class="d-flex align-items-center gap-2">
                <div style="width:42px;height:42px;background:white;border-radius:4px;"></div>
                <div>
                    <div class="brand-title">PPID KOTA BATU</div>
                    <div class="brand-subtitle">PEJABAT PENGELOLA INFORMASI DAN DOKUMENTASI</div>
                </div>
            </div>

            <div class="user-panel">
                <div>PPID Utama</div>
                <div>ppid@batukota.go.id</div>
            </div>
        </div>

        <div class="nav-label">MAIN NAVIGATION - PPID UTAMA</div>

        <nav class="sidebar-menu">
            <a href="#">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.ppid-pembantu.create') }}" class="active">
                <i class="bi bi-people-fill"></i>
                PPID Pembantu
            </a>

            <div class="submenu">
                <a href="{{ route('admin.ppid-pembantu.create') }}">Tambah PPID Pembantu</a>
                <a href="#">Daftar PPID Pembantu</a>
            </div>

            <a href="#">
                <i class="bi bi-file-earmark-text-fill"></i>
                Informasi & Dokumentasi
                <span class="ms-auto">+</span>
            </a>

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

    {{-- Main --}}
    <main class="main">
        <div class="topbar">
            <div class="topbar-title">
                PANEL <span>ADMINISTRASI</span>
            </div>

            <div class="d-flex align-items-center gap-4 fs-5">
                <i class="bi bi-search"></i>
                <i class="bi bi-bell-fill"></i>
                <i class="bi bi-power"></i>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>

</div>
</body>
</html>