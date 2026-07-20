<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPID Kota Batu')</title>

    <meta name="description" content="Portal layanan informasi publik PPID Kota Batu.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">

    @include('components.public.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.public.footer')

    @stack('scripts')
</body>

</html>
