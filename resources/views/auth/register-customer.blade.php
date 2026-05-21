@extends('layouts.app')

@section('title', 'Daftar Customer - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-12 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Daftar Customer
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Buat akun customer
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Gunakan akun customer untuk membuat pesanan dan melihat riwayat pengiriman Anda.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('register.customer.process') }}" class="grid gap-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div class="grid gap-5 md:grid-cols-2">
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
                            No HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            placeholder="Contoh: 081234567890"
                            inputmode="numeric"
                            autocomplete="tel"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        placeholder="Masukkan alamat lengkap"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >{{ old('alamat') }}</textarea>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Minimal 4 karakter"
                            autocomplete="new-password"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700"
                    >
                        Daftar Customer
                    </button>

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600"
                    >
                        Sudah punya akun?
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
