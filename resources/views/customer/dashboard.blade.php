@extends('layouts.app')

@section('title', 'Dashboard Customer - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Customer
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Selamat datang, {{ session('nama_lengkap', 'Customer') }}
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Buat pesanan, cek ongkir, lacak paket, dan pantau riwayat pengiriman Anda.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('customer.pesanan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Buat Pesanan
                </a>

                <a href="{{ route('customer.tracking') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Lacak Resi
                </a>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-slate-500">Total Pesanan</p>
                <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $jumlahPesanan ?? 0 }}</p>
                <p class="mt-4 text-sm text-slate-500">Semua pesanan yang pernah Anda buat.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold text-slate-500">Aksi Cepat</p>
                <div class="mt-4 grid gap-3">
                    <a href="{{ route('customer.cek-ongkir') }}" class="rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                        Cek Ongkir
                    </a>

                    <a href="{{ route('customer.riwayat-pesanan') }}" class="rounded-xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600">
                        Riwayat Pesanan
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:col-span-2">
                <p class="text-sm font-bold text-slate-500">Catatan</p>
                <h2 class="mt-3 text-xl font-extrabold text-slate-950">Simpan nomor resi Anda</h2>
                <p class="mt-3 leading-7 text-slate-600">
                    Nomor resi digunakan untuk melacak status paket. Anda juga bisa melihat semua pesanan melalui halaman riwayat.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
