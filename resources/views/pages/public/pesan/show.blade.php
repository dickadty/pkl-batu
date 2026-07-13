@extends('layouts.public')

@section('title', 'Percakapan Pesan | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-bold text-slate-900">
                Percakapan Pesan
            </h1>

            <p class="mt-3 text-slate-600">
                Simpan link halaman ini untuk melihat balasan dari admin PPID.
            </p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                <strong>Data belum valid.</strong>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-slate-500">Nama</div>
                    <div class="font-semibold">{{ $pesan->nama }}</div>
                </div>

                <div>
                    <div class="text-sm text-slate-500">Email</div>
                    <div class="font-semibold">{{ $pesan->email }}</div>
                </div>

                <div>
                    <div class="text-sm text-slate-500">Subjek</div>
                    <div class="font-semibold">{{ $pesan->subjek }}</div>
                </div>

                <div>
                    <div class="text-sm text-slate-500">Status</div>
                    <div class="font-semibold" id="statusLabel">{{ $pesan->status_label }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-slate-900">Isi Percakapan</h2>
                <span class="text-sm text-slate-500">Otomatis diperbarui tiap 5 detik</span>
            </div>

            <div id="chatBox" class="space-y-4">
                <div class="text-slate-500">Memuat percakapan...</div>
            </div>
        </div>

        @if ($pesan->isClosed())
            <div class="p-4 rounded-lg bg-slate-100 border border-slate-200 text-slate-600">
                Percakapan ini sudah ditutup oleh admin.
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Kirim Balasan</h2>

                <form action="{{ route('public.pesan.reply', $pesan->token) }}" method="POST" class="space-y-4">
                    @csrf

                    <textarea name="pesan" rows="4" class="w-full border rounded-lg p-2" placeholder="Tulis balasan Anda..."
                        required>{{ old('pesan') }}</textarea>

                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                        Kirim Balasan
                    </button>
                </form>
            </div>
        @endif
    </section>

    <script>
        const chatBox = document.getElementById('chatBox');
        const statusLabel = document.getElementById('statusLabel');
        const messagesUrl = @json(route('public.pesan.messages', $pesan->token));

        function createMessageElement(message) {
            const wrapper = document.createElement('div');
            wrapper.className = message.pengirim === 'publik' ?
                'flex justify-end' :
                'flex justify-start';

            const bubble = document.createElement('div');
            bubble.className = message.pengirim === 'publik' ?
                'max-w-xl rounded-lg p-4 bg-blue-700 text-white' :
                'max-w-xl rounded-lg p-4 bg-slate-100 text-slate-900 border';

            const meta = document.createElement('div');
            meta.className = message.pengirim === 'publik' ?
                'text-xs mb-2 text-blue-100' :
                'text-xs mb-2 text-slate-500';

            meta.textContent = message.nama_pengirim + ' • ' + message.tanggal;

            const content = document.createElement('div');
            content.className = 'whitespace-pre-line';
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

                statusLabel.textContent = data.status_label;
                chatBox.innerHTML = '';

                data.messages.forEach((message) => {
                    chatBox.appendChild(createMessageElement(message));
                });
            } catch (error) {
                chatBox.innerHTML = '<div class="text-red-600">Gagal memuat percakapan.</div>';
            }
        }

        loadMessages();
        setInterval(loadMessages, 5000);
    </script>
@endsection
