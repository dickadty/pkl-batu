@extends('layouts.public')

@section('title', 'Login Warga | PPID Kota Batu')

@section('content')
    <section class="max-w-md mx-auto px-4 py-12">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">
                Login Warga
            </h1>

            <p class="text-slate-600 mb-6">
                Silakan login untuk mengajukan permohonan informasi publik.
            </p>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('public.login.process') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg p-2"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">
                        Password
                    </label>
                    <input type="password" name="password" class="w-full border rounded-lg p-2" required>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-blue-700 text-white font-semibold hover:bg-blue-800">
                    Login
                </button>
            </form>
        </div>
    </section>
@endsection
