@extends('layouts.app')

@section('title', 'Dashboard Owner - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Dashboard Owner
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Selamat datang, Owner!
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Halaman ini akan menjadi pusat pemantauan performa bisnis, laporan keuangan, dan statistik operasional Sobat Kurir.
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-extrabold text-slate-950">
                    Statistik Bisnis
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk grafik pertumbuhan pesanan, pendapatan, dan performa layanan.
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-9.75h6m-7.5 8.25h9A2.25 2.25 0 0018.75 14.25v-4.5A2.25 2.25 0 0016.5 7.5h-9A2.25 2.25 0 005.25 9.75v4.5A2.25 2.25 0 007.5 16.5z" />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-extrabold text-slate-950">
                    Laporan Keuangan
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk rekap pemasukan, transaksi COD, transfer, dan laporan profit.
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
                    Kontrol Operasional
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Placeholder untuk monitoring admin, supervisor, kurir, dan performa pengiriman.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
