@extends('layouts.admin')

@section('title', 'Tambah Akun Admin')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Akun Admin / Tambah
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Data belum valid.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Tambah Akun Admin</span>
        </div>

        <form action="{{ route('admin.akun-admin.store') }}" method="POST" class="form-material">
            @csrf

            <div class="mb-4">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                    placeholder="Contoh: adminpembantu2" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                    placeholder="Contoh: adminpembantu2@ppid.test">
            </div>

            <div class="mb-4">
                <label>Role Admin</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">Pilih Role</option>
                    <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>
                        Admin Utama
                    </option>
                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>
                        Admin Pembantu
                    </option>
                </select>
            </div>

            <div class="mb-4" id="ppidPembantuWrapper">
                <label>PPID Pembantu</label>
                <select name="ppid_pembantuid" class="form-select">
                    <option value="">Pilih PPID Pembantu</option>
                    @foreach ($ppidPembantu as $ppid)
                        <option value="{{ $ppid->id }}" {{ old('ppid_pembantuid') == $ppid->id ? 'selected' : '' }}>
                            {{ $ppid->nama }}
                        </option>
                    @endforeach
                </select>

                <small class="text-muted">
                    Wajib dipilih jika role adalah Admin Pembantu.
                </small>
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-red">
                    Simpan Akun Admin
                </button>

                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const ppidWrapper = document.getElementById('ppidPembantuWrapper');

        function togglePpidPembantu() {
            if (roleSelect.value === '2') {
                ppidWrapper.style.display = 'block';
            } else {
                ppidWrapper.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', togglePpidPembantu);
        togglePpidPembantu();
    </script>
@endpush
