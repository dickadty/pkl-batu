@extends('layouts.admin')

@section('title', 'Detail Permohonan')

@section('content')
<div class="breadcrumb-custom">
    Dashboard / Permohonan Informasi / Detail
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

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

<div class="panel-card mb-4">
    <div class="panel-card-header">
        <span>Detail Permohonan Informasi</span>
    </div>

    <div class="p-4">
        <div class="row mb-3">
            <div class="col-md-4 fw-bold">No Permohonan</div>
            <div class="col-md-8">{{ $permohonan->no_pemohon }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Tanggal</div>
            <div class="col-md-8">{{ $permohonan->tanggal ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Pemohon</div>
            <div class="col-md-8">
                {{ $permohonan->userPublic->nama ?? '-' }}
                <div class="text-muted small">
                    {{ $permohonan->userPublic->email ?? '-' }}
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Rincian Informasi</div>
            <div class="col-md-8">{{ $permohonan->rincian ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Tujuan Penggunaan</div>
            <div class="col-md-8">{{ $permohonan->tujuan ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">PPID Pembantu Tujuan</div>
            <div class="col-md-8">{{ $permohonan->ppidPembantu->nama ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Status</div>
            <div class="col-md-8">
                <span class="badge bg-primary">
                    {{ $permohonan->status ?? 'Diajukan' }}
                </span>
            </div>
        </div>

        @if ($permohonan->catatan_utama)
            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Catatan Admin Utama</div>
                <div class="col-md-8">{{ $permohonan->catatan_utama }}</div>
            </div>
        @endif

        @if ($permohonan->jawaban_pembantu)
            <hr>

            <h5 class="mb-3">Laporan dari PPID Pembantu</h5>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Tanggal Jawab PPID Pembantu</div>
                <div class="col-md-8">{{ $permohonan->tanggal_jawab_pembantu ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Jawaban PPID Pembantu</div>
                <div class="col-md-8">{{ $permohonan->jawaban_pembantu }}</div>
            </div>

            @if ($permohonan->file_pembantu)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">File Laporan</div>
                    <div class="col-md-8">
                        <a href="{{ asset('storage/' . $permohonan->file_pembantu) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Lihat File Laporan
                        </a>
                    </div>
                </div>
            @endif
        @endif

        @if ($permohonan->catatan_revisi)
            <div class="alert alert-warning mt-3">
                <strong>Catatan Revisi:</strong><br>
                {{ $permohonan->catatan_revisi }}
            </div>
        @endif

        @if ($permohonan->jawaban)
            <hr>

            <h5 class="mb-3">Jawaban Final untuk Warga</h5>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Tanggal Jawab</div>
                <div class="col-md-8">{{ $permohonan->tanggal_jawab ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Jawaban Final</div>
                <div class="col-md-8">{{ $permohonan->jawaban }}</div>
            </div>

            @if ($permohonan->file_jawaban)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">File Jawaban</div>
                    <div class="col-md-8">
                        <a href="{{ asset('storage/' . $permohonan->file_jawaban) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-success">
                            Lihat File Jawaban
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@if ((int) $admin->role === 1 && in_array($permohonan->status, ['Diajukan', 'Diproses', null]))
    <div class="panel-card mb-4">
        <div class="panel-card-header">
            <span>Teruskan ke PPID Pembantu</span>
        </div>

        <form action="{{ route('admin.permohonan.teruskan', $permohonan->id) }}"
              method="POST"
              class="form-material">
            @csrf

            <div class="mb-4">
                <label>Pilih PPID Pembantu</label>
                <select name="ppid_pembantuid" class="form-select" required>
                    <option value="">Pilih PPID Pembantu</option>
                    @foreach ($ppidPembantu as $ppid)
                        <option value="{{ $ppid->id }}">
                            {{ $ppid->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Catatan untuk PPID Pembantu</label>
                <textarea name="catatan_utama"
                          rows="4"
                          class="form-control"
                          placeholder="Contoh: Mohon siapkan laporan sesuai rincian permohonan warga.">{{ old('catatan_utama') }}</textarea>
            </div>

            <button type="submit" class="btn btn-red">
                Teruskan Permohonan
            </button>
        </form>
    </div>
@endif

@if ((int) $admin->role === 2 && in_array($permohonan->status, ['Diteruskan ke PPID Pembantu', 'Revisi PPID Pembantu']))
    <div class="panel-card mb-4">
        <div class="panel-card-header">
            <span>Kirim Laporan ke Admin Utama</span>
        </div>

        <form action="{{ route('admin.permohonan.jawab-pembantu', $permohonan->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="form-material">
            @csrf

            <div class="mb-4">
                <label>Jawaban atau Laporan PPID Pembantu</label>
                <textarea name="jawaban_pembantu"
                          rows="6"
                          class="form-control"
                          required>{{ old('jawaban_pembantu', $permohonan->jawaban_pembantu) }}</textarea>
            </div>

            <div class="mb-4">
                <label>File Laporan</label>
                <input type="file"
                       name="file_pembantu"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

                <small class="text-muted">
                    Format: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Maksimal 5 MB.
                </small>
            </div>

            <button type="submit" class="btn btn-red">
                Kirim ke Admin Utama
            </button>
        </form>
    </div>
@endif

@if ((int) $admin->role === 1 && $permohonan->status === 'Menunggu Validasi Admin Utama')
    <div class="panel-card mb-4">
        <div class="panel-card-header">
            <span>Validasi dan Kirim ke Warga</span>
        </div>

        <form action="{{ route('admin.permohonan.validasi', $permohonan->id) }}"
              method="POST"
              class="form-material">
            @csrf

            <div class="mb-4">
                <label>Jawaban Final untuk Warga</label>
                <textarea name="jawaban_final"
                          rows="6"
                          class="form-control"
                          required>{{ old('jawaban_final', $permohonan->jawaban_pembantu) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Validasi dan Kirim ke Warga
            </button>
        </form>
    </div>

    <div class="panel-card">
        <div class="panel-card-header">
            <span>Minta Revisi ke PPID Pembantu</span>
        </div>

        <form action="{{ route('admin.permohonan.revisi', $permohonan->id) }}"
              method="POST"
              class="form-material">
            @csrf

            <div class="mb-4">
                <label>Catatan Revisi</label>
                <textarea name="catatan_revisi"
                          rows="4"
                          class="form-control"
                          required>{{ old('catatan_revisi') }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">
                Kirim Revisi
            </button>
        </form>
    </div>
@endif
@endsection