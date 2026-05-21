@extends('layouts.app')

@section('title', 'Tambah Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <x-back-button />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Tambah Kurir
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Buat Akun Kurir Manual
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Akun kurir yang dibuat langsung oleh admin akan otomatis aktif dan dapat login ke dashboard kurir.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.kurir.store') }}" class="grid gap-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">No HP</label>
                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm font-semibold leading-6 text-blue-800">
                    Kurir yang dibuat dari halaman ini akan langsung berstatus aktif.
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Simpan Kurir
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
