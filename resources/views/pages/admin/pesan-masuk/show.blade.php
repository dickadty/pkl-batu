@extends('layouts.admin')

@section('title', 'Detail Pesan Masuk')

@section('content')
    <div class="breadcrumb-custom">
        Dashboard / Pesan Masuk / Detail
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
        <div class="panel-card-header d-flex justify-content-between align-items-center">
            <span>Detail Pesan</span>

            <span class="badge {{ $pesan->status_badge_class }}">
                {{ $pesan->status_label }}
            </span>
        </div>

        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nama</div>
                <div class="col-md-9">{{ $pesan->nama }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Email</div>
                <div class="col-md-9">{{ $pesan->email }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Subjek</div>
                <div class="col-md-9">{{ $pesan->subjek ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Tanggal</div>
                <div class="col-md-9">
                    {{ $pesan->tanggal ? date('d-m-Y H:i', (int) $pesan->tanggal) : '-' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Link Publik</div>
                <div class="col-md-9">
                    <a href="{{ route('public.pesan.show', $pesan->token) }}" target="_blank">
                        {{ route('public.pesan.show', $pesan->token) }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-card mb-4">
        <div class="panel-card-header d-flex justify-content-between align-items-center">
            <span>Percakapan</span>
            <small class="text-muted">Otomatis diperbarui tiap 5 detik</small>
        </div>

        <div class="p-4">
            <div id="chatBox" class="d-flex flex-column gap-3">
                <div class="text-muted">Memuat percakapan...</div>
            </div>
        </div>
    </div>

    @if ($pesan->isClosed())
        <div class="alert alert-secondary">
            Percakapan ini sudah ditutup.
        </div>
    @else
        <div class="panel-card mb-4">
            <div class="panel-card-header">
                <span>Kirim Balasan</span>
            </div>

            <form action="{{ route('admin.pesan-masuk.reply', $pesan->id) }}" method="POST" class="form-material">
                @csrf

                <div class="mb-4">
                    <label>Isi Balasan</label>
                    <textarea name="pesan" rows="5" class="form-control" required>{{ old('pesan') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-red">
                        Kirim Balasan
                    </button>

                    <a href="{{ route('admin.pesan-masuk.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.pesan-masuk.close', $pesan->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menutup percakapan ini?')">
            @csrf

            <button type="submit" class="btn btn-outline-secondary">
                Tutup Percakapan
            </button>
        </form>
    @endif

    <script>
        const chatBox = document.getElementById('chatBox');
        const messagesUrl = @json(route('admin.pesan-masuk.messages', $pesan->id));

        function createMessageElement(message) {
            const wrapper = document.createElement('div');
            wrapper.className = message.pengirim === 'admin' ?
                'd-flex justify-content-end' :
                'd-flex justify-content-start';

            const bubble = document.createElement('div');
            bubble.style.maxWidth = '650px';
            bubble.className = message.pengirim === 'admin' ?
                'p-3 rounded bg-primary text-white' :
                'p-3 rounded bg-light border';

            const meta = document.createElement('div');
            meta.className = message.pengirim === 'admin' ?
                'small mb-2 text-white-50' :
                'small mb-2 text-muted';

            meta.textContent = message.nama_pengirim + ' • ' + message.tanggal;

            const content = document.createElement('div');
            content.style.whiteSpace = 'pre-line';
            content.textContent = message.pesan;

            bubble.appendChild(meta);
            bubble.appendChild(content);
            wrapper.appendChild(bubble);

            return wrapper;
        }

        async function loadMessages() {
            try {
                const response = await fetch(messagesUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                chatBox.innerHTML = '';

                data.messages.forEach((message) => {
                    chatBox.appendChild(createMessageElement(message));
                });
            } catch (error) {
                chatBox.innerHTML = '<div class="text-danger">Gagal memuat percakapan.</div>';
            }
        }

        loadMessages();
        setInterval(loadMessages, 5000);
    </script>
@endsection
