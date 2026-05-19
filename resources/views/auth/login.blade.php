@extends('layouts.app')

@section('title', 'Login - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-12 lg:px-8">
    <div class="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-center">
        <div class="hidden lg:block">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Login Akun
            </p>

            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-950">
                Masuk ke sistem Sobat Kurir
            </h1>

            <p class="mt-4 max-w-xl leading-7 text-slate-600">
                Gunakan satu halaman login untuk customer, kurir, dan admin. Sistem akan mengarahkan Anda sesuai role akun.
            </p>

            <div class="mt-8 grid gap-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="font-extrabold text-slate-950">Customer</p>
                    <p class="mt-1 text-sm text-slate-500">Buat pesanan, cek riwayat, dan tracking paket.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="font-extrabold text-slate-950">Kurir</p>
                    <p class="mt-1 text-sm text-slate-500">Ambil tugas baru dan update status pengiriman.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="font-extrabold text-slate-950">Admin</p>
                    <p class="mt-1 text-sm text-slate-500">Kelola kurir, tarif, pendaftar, dan pesanan.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-7">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">
                    Login Sobat Kurir
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Masukkan email dan password akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <button type="submit" class="h-12 w-full rounded-xl bg-blue-600 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Login
                </button>
            </form>

            <div class="mt-6 grid gap-3 border-t border-slate-200 pt-6 sm:grid-cols-2">
                <a href="{{ route('register.customer') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Daftar Customer
                </a>

                <a href="{{ route('register.kurir') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Daftar Kurir
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
