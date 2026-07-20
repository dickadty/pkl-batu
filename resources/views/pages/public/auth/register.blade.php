@extends('layouts.public')

@section('content')
    <style>
        .register-page {
            min-height: calc(100vh - 120px);
            padding: 56px 0;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.10), transparent 35%),
                linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%);
        }

        .register-wrapper {
            max-width: 1050px;
            margin: 0 auto;
        }

        .register-card {
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.10);
        }

        .register-header {
            padding: 32px 36px;
            color: #ffffff;
            background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        }

        .register-header h1 {
            margin-bottom: 8px;
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
        }

        .register-header p {
            max-width: 680px;
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.82);
        }

        .register-body {
            padding: 36px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 28px;
            border-bottom: 1px solid #e9edf3;
        }

        .form-section:last-of-type {
            margin-bottom: 24px;
            padding-bottom: 0;
            border-bottom: 0;
        }

        .section-heading {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 22px;
        }

        .section-number {
            display: inline-flex;
            flex: 0 0 38px;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            color: #0d6efd;
            font-size: 0.95rem;
            font-weight: 700;
            border: 1px solid rgba(13, 110, 253, 0.20);
            border-radius: 12px;
            background: rgba(13, 110, 253, 0.08);
        }

        .section-heading h2 {
            margin-bottom: 3px;
            color: #172033;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .section-heading p {
            margin-bottom: 0;
            color: #718096;
            font-size: 0.88rem;
        }

        .form-label {
            margin-bottom: 8px;
            color: #273247;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .required-mark {
            color: #dc3545;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            padding: 11px 14px;
            color: #1f2937;
            border-color: #d9e0e9;
            border-radius: 11px;
            background-color: #fbfcfe;
            box-shadow: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background-color 0.2s ease;
        }

        textarea.form-control {
            min-height: 105px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #70a7ff;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.10);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            background-color: #fffafa;
        }

        .form-control.is-valid,
        .form-select.is-valid {
            background-color: #fbfffc;
        }

        .form-text {
            color: #8792a6;
            font-size: 0.8rem;
        }

        .field-information {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            justify-content: space-between;
            margin-top: 6px;
        }

        .field-counter {
            flex: 0 0 auto;
            color: #8792a6;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .field-counter.valid {
            color: #198754;
        }

        .field-counter.invalid {
            color: #dc3545;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 92px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 9px;
            z-index: 4;
            padding: 6px 10px;
            color: #0d6efd;
            font-size: 0.78rem;
            font-weight: 600;
            border: 0;
            border-radius: 8px;
            background: transparent;
            transform: translateY(-50%);
        }

        .password-toggle:hover {
            background: rgba(13, 110, 253, 0.08);
        }

        .alert-validation {
            padding: 16px 18px;
            border: 1px solid rgba(220, 53, 69, 0.20);
            border-radius: 12px;
            background: #fff6f7;
        }

        .alert-validation strong {
            display: block;
            margin-bottom: 8px;
            color: #b02a37;
        }

        .alert-validation ul {
            margin-bottom: 0;
            padding-left: 20px;
            color: #842029;
            font-size: 0.9rem;
        }

        .register-actions {
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .login-link {
            color: #657084;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #0d6efd;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .register-button {
            min-width: 190px;
            min-height: 48px;
            padding: 11px 24px;
            font-weight: 700;
            border: 0;
            border-radius: 11px;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.22);
        }

        .register-button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }

        .button-spinner {
            display: none;
            width: 17px;
            height: 17px;
            margin-right: 8px;
            vertical-align: -3px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: register-spin 0.7s linear infinite;
        }

        .register-button.is-loading .button-spinner {
            display: inline-block;
        }

        @keyframes register-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 767.98px) {
            .register-page {
                padding: 24px 0;
            }

            .register-header,
            .register-body {
                padding: 24px 20px;
            }

            .register-card {
                border-radius: 16px;
            }

            .register-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .register-button {
                width: 100%;
            }

            .login-link {
                text-align: center;
            }
        }
    </style>

    <main class="register-page">
        <div class="container">
            <div class="register-wrapper">
                <div class="register-card">
                    <div class="register-header">
                        <h1>Registrasi Pemohon Informasi</h1>

                        <p>
                            Lengkapi identitas dengan data yang benar agar proses permohonan
                            informasi publik dapat diverifikasi dan diproses.
                        </p>
                    </div>

                    <div class="register-body">
                        @if ($errors->any())
                            <div class="alert-validation mb-4" role="alert">
                                <strong>
                                    Data registrasi belum lengkap atau tidak valid.
                                </strong>

                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="registerForm" method="POST" action="{{ route('public.register.store') }}">
                            @csrf

                            {{-- ============================================================
                                INFORMASI AKUN
                            ============================================================= --}}

                            <section class="form-section">
                                <div class="section-heading">
                                    <span class="section-number">01</span>

                                    <div>
                                        <h2>Informasi Akun</h2>

                                        <p>
                                            Gunakan email aktif untuk mengakses layanan PPID.
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            Email

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="contoh@email.com" autocomplete="email" maxlength="255" required>

                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telp" class="form-label">
                                            Nomor Telepon

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="text" id="telp" name="telp" value="{{ old('telp') }}"
                                            class="form-control @error('telp') is-invalid @enderror"
                                            placeholder="Contoh: 081234567890" inputmode="numeric" autocomplete="tel"
                                            minlength="9" maxlength="15" pattern="[0-9]{9,15}"
                                            title="Nomor telepon harus terdiri dari 9 sampai 15 angka." required>

                                        @error('telp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div class="form-text">
                                            Gunakan angka tanpa spasi, tanda hubung, atau simbol lainnya.
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- ============================================================
                                IDENTITAS PRIBADI
                            ============================================================= --}}

                            <section class="form-section">
                                <div class="section-heading">
                                    <span class="section-number">02</span>

                                    <div>
                                        <h2>Identitas Pribadi</h2>

                                        <p>
                                            Pastikan data sesuai dengan identitas resmi.
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label">
                                            Nama Lengkap

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan nama lengkap" autocomplete="name" maxlength="150"
                                            required>

                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nik" class="form-label">
                                            NIK

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                                            minlength="16" maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
                                            title="NIK harus terdiri dari tepat 16 angka." autocomplete="off"
                                            class="form-control @error('nik') is-invalid @enderror"
                                            placeholder="Masukkan 16 digit NIK" aria-describedby="nikHelp nikCounter"
                                            required>

                                        @error('nik')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div class="field-information">
                                            <div id="nikHelp" class="form-text">
                                                NIK harus terdiri dari tepat 16 angka.
                                            </div>

                                            <span id="nikCounter" class="field-counter">
                                                {{ mb_strlen(old('nik', '')) }}/16
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="l_kelamin" class="form-label">
                                            Jenis Kelamin

                                            <span class="required-mark">*</span>
                                        </label>

                                        <select id="l_kelamin" name="l_kelamin"
                                            class="form-select @error('l_kelamin') is-invalid @enderror" required>
                                            <option value="">
                                                Pilih jenis kelamin
                                            </option>

                                            <option value="Laki-laki" @selected(old('l_kelamin') === 'Laki-laki')>
                                                Laki-laki
                                            </option>

                                            <option value="Perempuan" @selected(old('l_kelamin') === 'Perempuan')>
                                                Perempuan
                                            </option>
                                        </select>

                                        @error('l_kelamin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pekerjaan" class="form-label">
                                            Pekerjaan
                                        </label>

                                        <input type="text" id="pekerjaan" name="pekerjaan"
                                            value="{{ old('pekerjaan') }}"
                                            class="form-control @error('pekerjaan') is-invalid @enderror"
                                            placeholder="Contoh: Mahasiswa" maxlength="100">

                                        @error('pekerjaan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tmp_lahir" class="form-label">
                                            Tempat Lahir

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="text" id="tmp_lahir" name="tmp_lahir"
                                            value="{{ old('tmp_lahir') }}"
                                            class="form-control @error('tmp_lahir') is-invalid @enderror"
                                            placeholder="Masukkan tempat lahir" maxlength="100" required>

                                        @error('tmp_lahir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tgl_lahir" class="form-label">
                                            Tanggal Lahir

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="date" id="tgl_lahir" name="tgl_lahir"
                                            value="{{ old('tgl_lahir') }}" max="{{ now()->subDay()->format('Y-m-d') }}"
                                            class="form-control @error('tgl_lahir') is-invalid @enderror" required>

                                        @error('tgl_lahir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- ============================================================
                                ALAMAT DOMISILI
                            ============================================================= --}}

                            <section class="form-section">
                                <div class="section-heading">
                                    <span class="section-number">03</span>

                                    <div>
                                        <h2>Alamat Domisili</h2>

                                        <p>
                                            Lengkapi informasi wilayah tempat tinggal saat ini.
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="alamat" class="form-label">
                                            Alamat Lengkap

                                            <span class="required-mark">*</span>
                                        </label>

                                        <textarea id="alamat" name="alamat" rows="3" maxlength="500"
                                            class="form-control @error('alamat') is-invalid @enderror"
                                            placeholder="Nama jalan, nomor rumah, RT/RW, dan informasi alamat lainnya" autocomplete="street-address" required>{{ old('alamat') }}</textarea>

                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="desa_kel" class="form-label">
                                            Desa atau Kelurahan
                                        </label>

                                        <input type="text" id="desa_kel" name="desa_kel"
                                            value="{{ old('desa_kel') }}"
                                            class="form-control @error('desa_kel') is-invalid @enderror"
                                            placeholder="Masukkan desa atau kelurahan" maxlength="100">

                                        @error('desa_kel')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kecamatan" class="form-label">
                                            Kecamatan
                                        </label>

                                        <input type="text" id="kecamatan" name="kecamatan"
                                            value="{{ old('kecamatan') }}"
                                            class="form-control @error('kecamatan') is-invalid @enderror"
                                            placeholder="Masukkan kecamatan" maxlength="100">

                                        @error('kecamatan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kota_kab" class="form-label">
                                            Kota atau Kabupaten
                                        </label>

                                        <input type="text" id="kota_kab" name="kota_kab"
                                            value="{{ old('kota_kab') }}"
                                            class="form-control @error('kota_kab') is-invalid @enderror"
                                            placeholder="Masukkan kota atau kabupaten" maxlength="100">

                                        @error('kota_kab')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="provinsi" class="form-label">
                                            Provinsi
                                        </label>

                                        <input type="text" id="provinsi" name="provinsi"
                                            value="{{ old('provinsi') }}"
                                            class="form-control @error('provinsi') is-invalid @enderror"
                                            placeholder="Masukkan provinsi" maxlength="100">

                                        @error('provinsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kode_pos" class="form-label">
                                            Kode Pos
                                        </label>

                                        <input type="text" id="kode_pos" name="kode_pos"
                                            value="{{ old('kode_pos') }}" inputmode="numeric" minlength="5"
                                            maxlength="5" pattern="[0-9]{5}"
                                            title="Kode pos harus terdiri dari 5 angka."
                                            class="form-control @error('kode_pos') is-invalid @enderror"
                                            placeholder="Contoh: 65111">

                                        @error('kode_pos')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div class="form-text">
                                            Masukkan 5 angka kode pos.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="wilayahkode" class="form-label">
                                            Kode Wilayah

                                            <span class="required-mark">*</span>
                                        </label>

                                        <input type="text" id="wilayahkode" name="wilayahkode"
                                            value="{{ old('wilayahkode') }}" inputmode="decimal" minlength="13"
                                            maxlength="13" pattern="[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{4}"
                                            title="Kode wilayah harus menggunakan format 00.00.00.0000."
                                            autocomplete="off"
                                            class="form-control @error('wilayahkode') is-invalid @enderror"
                                            placeholder="Contoh: 35.78.01.1001" aria-describedby="wilayahkodeHelp"
                                            required>

                                        @error('wilayahkode')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div id="wilayahkodeHelp" class="form-text">
                                            Gunakan kode desa atau kelurahan dengan format
                                            <strong>00.00.00.0000</strong>. Tanda titik tidak boleh dihapus.
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- ============================================================
                                KEAMANAN AKUN
                            ============================================================= --}}

                            <section class="form-section">
                                <div class="section-heading">
                                    <span class="section-number">04</span>

                                    <div>
                                        <h2>Keamanan Akun</h2>

                                        <p>
                                            Buat password yang kuat dan tidak mudah ditebak.
                                        </p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">
                                            Password

                                            <span class="required-mark">*</span>
                                        </label>

                                        <div class="password-wrapper">
                                            <input type="password" id="password" name="password" minlength="8"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Minimal 8 karakter" autocomplete="new-password" required>

                                            <button type="button" class="password-toggle" data-target="password"
                                                aria-label="Tampilkan password">
                                                Lihat
                                            </button>
                                        </div>

                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        <div class="form-text">
                                            Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">
                                            Konfirmasi Password

                                            <span class="required-mark">*</span>
                                        </label>

                                        <div class="password-wrapper">
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" minlength="8" class="form-control"
                                                placeholder="Ulangi password" autocomplete="new-password" required>

                                            <button type="button" class="password-toggle"
                                                data-target="password_confirmation"
                                                aria-label="Tampilkan konfirmasi password">
                                                Lihat
                                            </button>
                                        </div>

                                        <div id="passwordConfirmationFeedback" class="invalid-feedback d-none">
                                            Konfirmasi password tidak sama.
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- ============================================================
                                TOMBOL AKSI
                            ============================================================= --}}

                            <div class="register-actions">
                                <div class="login-link">
                                    Sudah memiliki akun?

                                    <a href="{{ route('login') }}">
                                        Masuk di sini
                                    </a>
                                </div>

                                <button type="submit" id="registerButton" class="btn btn-primary register-button">
                                    <span class="button-spinner" aria-hidden="true"></span>

                                    <span id="registerButtonText">
                                        Buat Akun
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const registerForm = document.getElementById('registerForm');
            const registerButton = document.getElementById('registerButton');
            const registerButtonText = document.getElementById('registerButtonText');

            const nikInput = document.getElementById('nik');
            const nikCounter = document.getElementById('nikCounter');

            const phoneInput = document.getElementById('telp');
            const postalCodeInput = document.getElementById('kode_pos');
            const regionCodeInput = document.getElementById('wilayahkode');

            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById(
                'password_confirmation'
            );

            const passwordConfirmationFeedback = document.getElementById(
                'passwordConfirmationFeedback'
            );

            /*
            |--------------------------------------------------------------------------
            | Tombol lihat dan sembunyikan password
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.password-toggle').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;
                    const input = document.getElementById(targetId);

                    if (!input) {
                        return;
                    }

                    const passwordIsHidden = input.type === 'password';

                    input.type = passwordIsHidden ?
                        'text' :
                        'password';

                    button.textContent = passwordIsHidden ?
                        'Sembunyikan' :
                        'Lihat';

                    button.setAttribute(
                        'aria-label',
                        passwordIsHidden ?
                        'Sembunyikan password' :
                        'Tampilkan password'
                    );
                });
            });

            /*
            |--------------------------------------------------------------------------
            | Fungsi mengambil angka saja
            |--------------------------------------------------------------------------
            */

            const digitsOnly = (value, maximumLength = null) => {
                const digits = String(value ?? '').replace(/\D/g, '');

                if (maximumLength === null) {
                    return digits;
                }

                return digits.slice(0, maximumLength);
            };

            /*
            |--------------------------------------------------------------------------
            | Validasi dan pembatasan NIK
            |--------------------------------------------------------------------------
            */

            const validateNik = () => {
                if (!nikInput) {
                    return true;
                }

                nikInput.value = digitsOnly(
                    nikInput.value,
                    16
                );

                const length = nikInput.value.length;
                const valid = length === 16;

                if (nikCounter) {
                    nikCounter.textContent = `${length}/16`;

                    nikCounter.classList.remove(
                        'valid',
                        'invalid'
                    );

                    if (length === 16) {
                        nikCounter.classList.add('valid');
                    } else if (length > 0) {
                        nikCounter.classList.add('invalid');
                    }
                }

                if (length === 0) {
                    nikInput.setCustomValidity(
                        'NIK wajib diisi.'
                    );
                } else if (!valid) {
                    nikInput.setCustomValidity(
                        'NIK harus terdiri dari tepat 16 angka.'
                    );
                } else {
                    nikInput.setCustomValidity('');
                }

                return valid;
            };

            if (nikInput) {
                nikInput.addEventListener(
                    'input',
                    validateNik
                );

                nikInput.addEventListener(
                    'blur',
                    validateNik
                );

                validateNik();
            }

            /*
            |--------------------------------------------------------------------------
            | Nomor telepon
            |--------------------------------------------------------------------------
            */

            if (phoneInput) {
                phoneInput.addEventListener('input', () => {
                    phoneInput.value = digitsOnly(
                        phoneInput.value,
                        15
                    );

                    if (
                        phoneInput.value.length > 0 &&
                        phoneInput.value.length < 9
                    ) {
                        phoneInput.setCustomValidity(
                            'Nomor telepon harus terdiri dari minimal 9 angka.'
                        );
                    } else {
                        phoneInput.setCustomValidity('');
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Kode pos
            |--------------------------------------------------------------------------
            */

            if (postalCodeInput) {
                postalCodeInput.addEventListener('input', () => {
                    postalCodeInput.value = digitsOnly(
                        postalCodeInput.value,
                        5
                    );

                    if (
                        postalCodeInput.value.length > 0 &&
                        postalCodeInput.value.length !== 5
                    ) {
                        postalCodeInput.setCustomValidity(
                            'Kode pos harus terdiri dari tepat 5 angka.'
                        );
                    } else {
                        postalCodeInput.setCustomValidity('');
                    }
                });
            }

            /*
            |--------------------------------------------------------------------------
            | Format kode wilayah
            |--------------------------------------------------------------------------
            |
            | Input angka:
            | 3578011001
            |
            | Otomatis menjadi:
            | 35.78.01.1001
            |
            | Kode wilayah tidak lagi dimasukkan ke proses numericFields lama
            | yang menghapus semua tanda titik.
            |
            */

            const formatRegionCode = (value) => {
                const digits = digitsOnly(
                    value,
                    10
                );

                const sections = [];

                if (digits.length > 0) {
                    sections.push(
                        digits.slice(0, 2)
                    );
                }

                if (digits.length > 2) {
                    sections.push(
                        digits.slice(2, 4)
                    );
                }

                if (digits.length > 4) {
                    sections.push(
                        digits.slice(4, 6)
                    );
                }

                if (digits.length > 6) {
                    sections.push(
                        digits.slice(6, 10)
                    );
                }

                return sections.join('.');
            };

            const validateRegionCode = () => {
                if (!regionCodeInput) {
                    return true;
                }

                regionCodeInput.value = formatRegionCode(
                    regionCodeInput.value
                );

                const validPattern =
                    /^[0-9]{2}\.[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/;

                const valid = validPattern.test(
                    regionCodeInput.value
                );

                if (regionCodeInput.value === '') {
                    regionCodeInput.setCustomValidity(
                        'Kode wilayah wajib diisi.'
                    );
                } else if (!valid) {
                    regionCodeInput.setCustomValidity(
                        'Kode wilayah harus menggunakan format 00.00.00.0000.'
                    );
                } else {
                    regionCodeInput.setCustomValidity('');
                }

                return valid;
            };

            if (regionCodeInput) {
                regionCodeInput.addEventListener(
                    'input',
                    validateRegionCode
                );

                regionCodeInput.addEventListener(
                    'blur',
                    validateRegionCode
                );

                /*
                |--------------------------------------------------------------------------
                | Normalisasi old input
                |--------------------------------------------------------------------------
                */

                if (regionCodeInput.value !== '') {
                    regionCodeInput.value = formatRegionCode(
                        regionCodeInput.value
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Konfirmasi password
            |--------------------------------------------------------------------------
            */

            const validatePasswordConfirmation = () => {
                if (
                    !passwordInput ||
                    !passwordConfirmationInput
                ) {
                    return true;
                }

                const confirmationHasValue =
                    passwordConfirmationInput.value !== '';

                const valid =
                    passwordInput.value ===
                    passwordConfirmationInput.value;

                if (
                    confirmationHasValue &&
                    !valid
                ) {
                    passwordConfirmationInput.setCustomValidity(
                        'Konfirmasi password tidak sama.'
                    );

                    passwordConfirmationInput.classList.add(
                        'is-invalid'
                    );

                    if (passwordConfirmationFeedback) {
                        passwordConfirmationFeedback.classList.remove(
                            'd-none'
                        );
                    }
                } else {
                    passwordConfirmationInput.setCustomValidity(
                        ''
                    );

                    passwordConfirmationInput.classList.remove(
                        'is-invalid'
                    );

                    if (passwordConfirmationFeedback) {
                        passwordConfirmationFeedback.classList.add(
                            'd-none'
                        );
                    }
                }

                return valid;
            };

            if (
                passwordInput &&
                passwordConfirmationInput
            ) {
                passwordInput.addEventListener(
                    'input',
                    validatePasswordConfirmation
                );

                passwordConfirmationInput.addEventListener(
                    'input',
                    validatePasswordConfirmation
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validasi sebelum formulir dikirim
            |--------------------------------------------------------------------------
            */

            if (registerForm) {
                registerForm.addEventListener('submit', (event) => {
                    const nikValid = validateNik();
                    const regionValid = validateRegionCode();
                    const passwordValid =
                        validatePasswordConfirmation();

                    if (
                        !nikValid ||
                        !regionValid ||
                        !passwordValid ||
                        !registerForm.checkValidity()
                    ) {
                        event.preventDefault();
                        event.stopPropagation();

                        registerForm.reportValidity();

                        return;
                    }

                    if (registerButton) {
                        registerButton.disabled = true;
                        registerButton.classList.add(
                            'is-loading'
                        );
                    }

                    if (registerButtonText) {
                        registerButtonText.textContent =
                            'Memproses...';
                    }
                });
            }
        });
    </script>
@endsection
