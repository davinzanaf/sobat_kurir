@extends('layouts.app')

@section('title', 'Login - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-12 lg:px-8">
    <div class="mx-auto max-w-md">
        <div class="mb-8 text-center">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Login
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">
                Masuk ke Sobat Kurir
            </h1>

            <p class="mt-3 text-sm leading-6 text-slate-600">
                Gunakan email dan password akun Anda untuk melanjutkan.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="h-12 w-full rounded-xl bg-blue-600 text-sm font-extrabold text-white transition hover:bg-blue-700"
                >
                    Login
                </button>
            </form>

            <div class="mt-6 border-t border-slate-200 pt-6 text-center">
                <p class="text-sm text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register.customer') }}" class="font-extrabold text-blue-600 hover:text-blue-700">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
