@extends('layouts.public')

@section('title', 'Percakapan Pesan | PPID Kota Batu')

@section('content')
    @php
        $statusLabel = trim((string) (data_get($pesan, 'status_label') ?? (data_get($pesan, 'status') ?? 'Aktif')));

        if ($statusLabel === '') {
            $statusLabel = 'Aktif';
        }

        $normalizedStatus = mb_strtolower($statusLabel);

        $isClosed = $pesan->isClosed();

        $publicUrl = route('public.pesan.show', $pesan->token);

        if (
            str_contains($normalizedStatus, 'tutup') ||
            str_contains($normalizedStatus, 'selesai') ||
            str_contains($normalizedStatus, 'closed')
        ) {
            $statusBadgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
            $statusDotClass = 'bg-slate-500';
        } elseif (str_contains($normalizedStatus, 'proses') || str_contains($normalizedStatus, 'menunggu')) {
            $statusBadgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
            $statusDotClass = 'bg-amber-500';
        } else {
            $statusBadgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
            $statusDotClass = 'bg-blue-500';
        }
    @endphp

    <main class="min-h-screen bg-slate-50">
        <section
            class="
                relative
                overflow-hidden
                border-b
                border-slate-200
                bg-white
            ">
            <div
                class="
                    pointer-events-none
                    absolute
                    -right-24
                    -top-32
                    h-80
                    w-80
                    rounded-full
                    bg-blue-100
                    blur-3xl
                ">
            </div>

            <div
                class="
                    relative
                    mx-auto
                    max-w-5xl
                    px-4
                    py-10
                    sm:px-6
                    lg:px-8
                ">
                <div
                    class="
                        flex
                        flex-col
                        gap-5
                        md:flex-row
                        md:items-start
                        md:justify-between
                    ">
                    <div>
                        <div
                            class="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                border
                                border-blue-200
                                bg-blue-50
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                text-blue-700
                            ">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H9z" />
                            </svg>

                            Layanan Pesan PPID
                        </div>

                        <h1
                            class="
                                mt-4
                                text-3xl
                                font-bold
                                tracking-tight
                                text-slate-900
                                sm:text-4xl
                            ">
                            Percakapan Pesan
                        </h1>

                        <p
                            class="
                                mt-3
                                max-w-2xl
                                text-base
                                leading-7
                                text-slate-600
                            ">
                            Pantau balasan Admin PPID dan lanjutkan percakapan melalui halaman ini.
                            Simpan link halaman dan jangan membagikannya kepada pihak lain.
                        </p>
                    </div>

                    <button type="button" id="copyConversationLink" data-link="{{ $publicUrl }}"
                        class="
                            inline-flex
                            h-11
                            shrink-0
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            border
                            border-blue-200
                            bg-blue-50
                            px-4
                            text-sm
                            font-semibold
                            text-blue-700
                            transition
                            hover:bg-blue-100
                            focus:outline-none
                            focus:ring-4
                            focus:ring-blue-100
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>

                        <span id="copyConversationLinkText">
                            Salin Link
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <section
            class="
                mx-auto
                max-w-5xl
                space-y-6
                px-4
                py-8
                sm:px-6
                lg:px-8
                lg:py-10
            ">
            @if (session('success'))
                <div class="
                        rounded-2xl
                        border
                        border-green-200
                        bg-green-50
                        p-4
                        text-sm
                        text-green-700
                    "
                    role="alert">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50
                        p-5
                        text-red-700
                    "
                    role="alert">
                    <strong class="block text-sm font-semibold">
                        Data belum valid.
                    </strong>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi percakapan --}}
            <section
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                ">
                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        border-b
                        border-slate-100
                        px-5
                        py-4
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                        sm:px-6
                    ">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Informasi Pesan
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Identitas dan status percakapan Anda.
                        </p>
                    </div>

                    <span id="statusBadge"
                        class="
                            inline-flex
                            w-fit
                            items-center
                            gap-2
                            rounded-full
                            border
                            px-3
                            py-1.5
                            text-xs
                            font-semibold
                            {{ $statusBadgeClass }}
                        ">
                        <span id="statusDot"
                            class="
                                h-2
                                w-2
                                rounded-full
                                {{ $statusDotClass }}
                            "></span>

                        <span id="statusLabel">
                            {{ $statusLabel }}
                        </span>
                    </span>
                </div>

                <dl
                    class="
                        grid
                        grid-cols-1
                        divide-y
                        divide-slate-100
                        md:grid-cols-2
                        md:divide-x
                        md:divide-y-0
                    ">
                    <div class="px-5 py-4 sm:px-6">
                        <dt
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wide
                                text-slate-400
                            ">
                            Nama
                        </dt>

                        <dd class="mt-1.5 text-sm font-semibold text-slate-800">
                            {{ $pesan->nama ?? '-' }}
                        </dd>
                    </div>

                    <div class="px-5 py-4 sm:px-6">
                        <dt
                            class="
                                text-xs
                                font-semibold
                                uppercase
                                tracking-wide
                                text-slate-400
                            ">
                            Email
                        </dt>

                        <dd class="mt-1.5 break-all text-sm font-semibold text-slate-800">
                            {{ $pesan->email ?? '-' }}
                        </dd>
                    </div>
                </dl>

                <div
                    class="
                        border-t
                        border-slate-100
                        px-5
                        py-4
                        sm:px-6
                    ">
                    <p
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-wide
                            text-slate-400
                        ">
                        Subjek
                    </p>

                    <p
                        class="
                            mt-1.5
                            text-sm
                            font-semibold
                            leading-6
                            text-slate-800
                        ">
                        {{ $pesan->subjek ?? '-' }}
                    </p>
                </div>
            </section>

            {{-- Isi percakapan --}}
            <section
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                ">
                <div
                    class="
                        flex
                        flex-col
                        gap-3
                        border-b
                        border-slate-100
                        px-5
                        py-4
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                        sm:px-6
                    ">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Isi Percakapan
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Pesan terbaru akan muncul secara otomatis.
                        </p>
                    </div>

                    <div
                        class="
                            inline-flex
                            items-center
                            gap-2
                            text-xs
                            font-medium
                            text-slate-500
                        ">
                        <span id="publicRefreshIndicator"
                            class="
                                h-2
                                w-2
                                rounded-full
                                bg-green-500
                            "></span>

                        Diperbarui setiap 5 detik
                    </div>
                </div>

                <div id="publicChatScrollContainer"
                    class="
                        h-[560px]
                        overflow-y-auto
                        bg-slate-50
                        p-4
                        sm:p-6
                    ">
                    <div id="chatBox" class="space-y-4" aria-live="polite">
                        <div
                            class="
                                flex
                                min-h-[440px]
                                items-center
                                justify-center
                                text-center
                                text-sm
                                text-slate-500
                            ">
                            <div>
                                <svg class="
                                        mx-auto
                                        h-7
                                        w-7
                                        animate-spin
                                        text-blue-600
                                    "
                                    viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>

                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z">
                                    </path>
                                </svg>

                                <p class="mt-3">
                                    Memuat percakapan...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if ($isClosed)
                <div
                    class="
                        rounded-2xl
                        border
                        border-slate-200
                        bg-slate-100
                        p-5
                        text-slate-600
                    ">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-10 0v4H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>

                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">
                                Percakapan sudah ditutup
                            </h3>

                            <p class="mt-1 text-sm leading-6">
                                Percakapan ini telah ditutup oleh Admin PPID dan tidak dapat menerima balasan baru.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Form balasan publik --}}
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-blue-200
                        bg-white
                        shadow-sm
                    ">
                    <div
                        class="
                            border-b
                            border-blue-100
                            bg-blue-50/60
                            px-5
                            py-4
                            sm:px-6
                        ">
                        <h2 class="text-lg font-bold text-blue-900">
                            Kirim Balasan
                        </h2>

                        <p class="mt-1 text-sm text-blue-700">
                            Tulis pesan lanjutan untuk Admin PPID.
                        </p>
                    </div>

                    <form id="publicReplyForm"
                        action="{{ route('public.pesan.reply', $pesan->token) }}"
                        method="POST" class="space-y-4 p-5 sm:p-6">
                        @csrf

                        <div>
                            <label for="pesan"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                ">
                                Isi Balasan

                                <span class="text-red-500">*</span>
                            </label>

                            <textarea id="pesan" name="pesan" rows="5" required placeholder="Tulis balasan Anda..."
                                class="
                                    w-full
                                    resize-y
                                    rounded-xl
                                    border
                                    border-slate-300
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    leading-7
                                    text-slate-800
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-100
                                    @error('pesan')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-100
                                    @enderror
                                ">{{ old('pesan') }}</textarea>

                            @error('pesan')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button id="publicReplyButton" type="submit"
                                class="
                                    inline-flex
                                    h-11
                                    min-w-[170px]
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    bg-blue-700
                                    px-5
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-sm
                                    transition
                                    hover:bg-blue-800
                                    focus:outline-none
                                    focus:ring-4
                                    focus:ring-blue-200
                                    disabled:cursor-not-allowed
                                    disabled:opacity-60
                                ">
                                <svg id="publicReplyIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>

                                <span id="publicReplyButtonText">
                                    Kirim Balasan
                                </span>
                            </button>
                        </div>
                    </form>
                </section>
            @endif
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatBox = document.getElementById('chatBox');

            const chatScrollContainer = document.getElementById(
                'publicChatScrollContainer'
            );

            const statusBadge = document.getElementById(
                'statusBadge'
            );

            const statusDot = document.getElementById(
                'statusDot'
            );

            const statusLabel = document.getElementById(
                'statusLabel'
            );

            const refreshIndicator = document.getElementById(
                'publicRefreshIndicator'
            );

            const copyButton = document.getElementById(
                'copyConversationLink'
            );

            const copyButtonText = document.getElementById(
                'copyConversationLinkText'
            );

            const replyForm = document.getElementById(
                'publicReplyForm'
            );

            const replyButton = document.getElementById(
                'publicReplyButton'
            );

            const replyButtonText = document.getElementById(
                'publicReplyButtonText'
            );

            const replyIcon = document.getElementById(
                'publicReplyIcon'
            );

            const messagesUrl = @json(route('public.pesan.messages', $pesan->token));

            let firstLoad = true;
            let requestController = null;

            function isNearBottom() {
                if (!chatScrollContainer) {
                    return true;
                }

                const distanceFromBottom =
                    chatScrollContainer.scrollHeight -
                    chatScrollContainer.scrollTop -
                    chatScrollContainer.clientHeight;

                return distanceFromBottom < 120;
            }

            function scrollToBottom() {
                if (!chatScrollContainer) {
                    return;
                }

                chatScrollContainer.scrollTo({
                    top: chatScrollContainer.scrollHeight,
                    behavior: firstLoad ? 'auto' : 'smooth'
                });
            }

            function createAvatar(message, isPublic) {
                const avatar = document.createElement('div');

                avatar.className = isPublic ?
                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white' :
                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-700';

                const senderName = String(
                    message.nama_pengirim ||
                    (isPublic ? 'Anda' : 'Admin')
                );

                avatar.textContent = senderName
                    .charAt(0)
                    .toUpperCase();

                return avatar;
            }

            function createMessageElement(message) {
                const isPublic = message.pengirim === 'publik';

                const wrapper = document.createElement('div');

                wrapper.className = isPublic ?
                    'flex items-end justify-end gap-2.5' :
                    'flex items-end justify-start gap-2.5';

                const bubble = document.createElement('div');

                bubble.className = isPublic ?
                    'max-w-[85%] rounded-2xl rounded-br-md bg-blue-700 px-4 py-3 text-white shadow-sm sm:max-w-[75%]' :
                    'max-w-[85%] rounded-2xl rounded-bl-md border border-slate-200 bg-white px-4 py-3 text-slate-800 shadow-sm sm:max-w-[75%]';

                const meta = document.createElement('div');

                meta.className = isPublic ?
                    'mb-1.5 flex flex-wrap items-center gap-1 text-xs text-blue-100' :
                    'mb-1.5 flex flex-wrap items-center gap-1 text-xs text-slate-500';

                const sender = document.createElement('span');
                sender.className = 'font-semibold';
                sender.textContent =
                    message.nama_pengirim ||
                    (isPublic ? 'Anda' : 'Admin PPID');

                const separator = document.createElement('span');
                separator.textContent = '•';

                const date = document.createElement('span');
                date.textContent = message.tanggal || '-';

                meta.appendChild(sender);
                meta.appendChild(separator);
                meta.appendChild(date);

                const content = document.createElement('div');
                content.className =
                    'whitespace-pre-line break-words text-sm leading-6';
                content.textContent = message.pesan || '';

                bubble.appendChild(meta);
                bubble.appendChild(content);

                const avatar = createAvatar(
                    message,
                    isPublic
                );

                if (isPublic) {
                    wrapper.appendChild(bubble);
                    wrapper.appendChild(avatar);
                } else {
                    wrapper.appendChild(avatar);
                    wrapper.appendChild(bubble);
                }

                return wrapper;
            }

            function updateStatus(status) {
                if (!statusLabel || !statusBadge || !statusDot) {
                    return;
                }

                const label = String(status || 'Aktif');
                const normalized = label.toLowerCase();

                statusLabel.textContent = label;

                statusBadge.className =
                    'inline-flex w-fit items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold';

                statusDot.className = 'h-2 w-2 rounded-full';

                if (
                    normalized.includes('tutup') ||
                    normalized.includes('selesai') ||
                    normalized.includes('closed')
                ) {
                    statusBadge.classList.add(
                        'bg-slate-100',
                        'text-slate-700',
                        'border-slate-200'
                    );

                    statusDot.classList.add('bg-slate-500');

                    return;
                }

                if (
                    normalized.includes('proses') ||
                    normalized.includes('menunggu')
                ) {
                    statusBadge.classList.add(
                        'bg-amber-50',
                        'text-amber-700',
                        'border-amber-200'
                    );

                    statusDot.classList.add('bg-amber-500');

                    return;
                }

                statusBadge.classList.add(
                    'bg-blue-50',
                    'text-blue-700',
                    'border-blue-200'
                );

                statusDot.classList.add('bg-blue-500');
            }

            function showEmptyMessage() {
                chatBox.innerHTML = '';

                const empty = document.createElement('div');

                empty.className =
                    'flex min-h-[440px] items-center justify-center text-center text-sm text-slate-500';

                empty.textContent =
                    'Belum ada pesan dalam percakapan ini.';

                chatBox.appendChild(empty);
            }

            async function loadMessages() {
                if (!chatBox) {
                    return;
                }

                const shouldScroll = firstLoad || isNearBottom();

                if (requestController) {
                    requestController.abort();
                }

                requestController = new AbortController();

                if (refreshIndicator) {
                    refreshIndicator.classList.add(
                        'animate-pulse'
                    );
                }

                try {
                    const response = await fetch(messagesUrl, {
                        method: 'GET',
                        cache: 'no-store',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: requestController.signal
                    });

                    if (!response.ok) {
                        throw new Error(
                            `HTTP ${response.status}`
                        );
                    }

                    const data = await response.json();

                    if (data.status_label) {
                        updateStatus(data.status_label);
                    }

                    const messages = Array.isArray(data.messages) ?
                        data.messages :
                        [];

                    chatBox.innerHTML = '';

                    if (messages.length === 0) {
                        showEmptyMessage();
                    } else {
                        messages.forEach((message) => {
                            chatBox.appendChild(
                                createMessageElement(message)
                            );
                        });
                    }

                    if (shouldScroll) {
                        requestAnimationFrame(scrollToBottom);
                    }

                    firstLoad = false;
                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    chatBox.innerHTML = '';

                    const errorBox = document.createElement('div');

                    errorBox.className =
                        'flex min-h-[440px] items-center justify-center text-center text-sm text-red-600';

                    errorBox.textContent =
                        'Percakapan gagal dimuat. Sistem akan mencoba kembali secara otomatis.';

                    chatBox.appendChild(errorBox);
                } finally {
                    if (refreshIndicator) {
                        refreshIndicator.classList.remove(
                            'animate-pulse'
                        );
                    }
                }
            }

            async function copyText(text) {
                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {
                    await navigator.clipboard.writeText(text);
                    return;
                }

                const textarea = document.createElement('textarea');

                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';

                document.body.appendChild(textarea);

                textarea.focus();
                textarea.select();

                document.execCommand('copy');

                textarea.remove();
            }

            if (copyButton) {
                copyButton.addEventListener('click', async () => {
                    try {
                        await copyText(copyButton.dataset.link);

                        if (copyButtonText) {
                            copyButtonText.textContent =
                                'Link Tersalin';
                        }

                        setTimeout(() => {
                            if (copyButtonText) {
                                copyButtonText.textContent =
                                    'Salin Link';
                            }
                        }, 2000);
                    } catch (error) {
                        if (copyButtonText) {
                            copyButtonText.textContent =
                                'Gagal Menyalin';
                        }
                    }
                });
            }

            if (replyForm) {
                replyForm.addEventListener('submit', () => {
                    if (replyButton) {
                        replyButton.disabled = true;
                    }

                    if (replyButtonText) {
                        replyButtonText.textContent =
                            'Mengirim...';
                    }

                    if (replyIcon) {
                        replyIcon.outerHTML = `
                            <svg
                                id="publicReplyIcon"
                                class="h-5 w-5 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                                ></path>
                            </svg>
                        `;
                    }
                });
            }

            loadMessages();

            const refreshTimer = window.setInterval(() => {
                if (!document.hidden) {
                    loadMessages();
                }
            }, 5000);

            window.addEventListener('beforeunload', () => {
                window.clearInterval(refreshTimer);

                if (requestController) {
                    requestController.abort();
                }
            });
        });
    </script>
@endsection
