@extends('layouts.app')

@section('title', 'Dashboard Supervisor - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Dashboard Supervisor
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Selamat datang, Supervisor!
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Halaman ini akan digunakan untuk memantau aktivitas kurir, status pengiriman, dan kinerja operasional harian.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h7.5m-10.5 0H3.375A1.125 1.125 0 012.25 17.625V6.375c0-.621.504-1.125 1.125-1.125h10.5C14.496 5.25 15 5.754 15 6.375v11.25m-6.75 1.125h7.5m0 0a1.5 1.5 0 003 0m-3 0a1.5 1.5 0 013 0m0 0h1.125c.621 0 1.125-.504 1.125-1.125V12.75L18.75 9h-3.75" />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-extrabold text-slate-950">
                    Monitoring Pengiriman
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk daftar pengiriman aktif, pesanan terlambat, dan status paket.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-extrabold text-slate-950">
                    Performa Kurir
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk performa kurir, jumlah pesanan selesai, dan aktivitas harian.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-extrabold text-slate-950">
                    Validasi Operasional
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk pengecekan tarif, validasi data pesanan, dan pengawasan operasional.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
