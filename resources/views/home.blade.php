@extends('layouts.app')

@section('title', 'Sobat Kurir - Lacak Kiriman dan Cek Tarif')

@section('content')
    <section id="tracking" class="subtle-grid border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
            <div class="mx-auto max-w-4xl text-center">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Tracking Kiriman Cepat
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Lacak Kiriman Anda dengan Cepat
                </h1>

                <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                    Masukkan nomor resi untuk melihat status paket dan riwayat pengiriman secara praktis.
                </p>
            </div>

            <div class="mx-auto mt-10 max-w-5xl">
                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/10 sm:p-5">
                    <form method="POST" action="{{ route('customer.tracking.search') }}" class="flex flex-col gap-3 lg:flex-row">
                        @csrf

                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                </svg>
                            </div>

                            <input
                                type="text"
                                name="kode_resi"
                                placeholder="Masukkan Nomor Resi (contoh: SBK123456789)..."
                                class="h-16 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-14 pr-5 text-base font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                required
                            >
                        </div>

                        <button type="submit" class="h-16 rounded-2xl bg-blue-600 px-8 text-base font-extrabold text-white transition hover:bg-blue-700 lg:min-w-48">
                            Lacak Paket
                        </button>
                    </form>
                </div>

                <div class="mt-5 grid gap-3 text-sm text-slate-600 sm:grid-cols-3">
                    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        Status paket terupdate
                    </div>

                    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                            </svg>
                        </span>
                        Riwayat tracking jelas
                    </div>

                    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0h7.5m0 0a1.5 1.5 0 003 0m-3 0H15V6.75A.75.75 0 0014.25 6H3.75A.75.75 0 003 6.75V18a.75.75 0 00.75.75H5.25m13.5 0H20.25A.75.75 0 0021 18v-5.25a.75.75 0 00-.22-.53l-2.25-2.25a.75.75 0 00-.53-.22H15" />
                            </svg>
                        </span>
                        Informasi pengiriman praktis
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cek-ongkir" class="bg-slate-50 px-5 py-14 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.4fr] lg:items-start">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                        Cek Tarif
                    </p>

                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">
                        Cek Tarif Pengiriman
                    </h2>

                    <p class="mt-4 max-w-md leading-7 text-slate-600">
                        Masukkan kecamatan asal, tujuan, dan berat paket untuk mengetahui estimasi ongkir sebelum membuat pesanan.
                    </p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <form method="POST" action="{{ route('customer.cek-ongkir.process') }}" class="grid gap-4 md:grid-cols-4">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Asal</label>
                            <input
                                type="text"
                                name="kecamatan_asal"
                                placeholder="Kecamatan asal"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                required
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Tujuan</label>
                            <input
                                type="text"
                                name="kecamatan_tujuan"
                                placeholder="Kecamatan tujuan"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                required
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Berat</label>
                            <input
                                type="number"
                                name="berat"
                                min="1"
                                placeholder="Kg"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                required
                            >
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="h-12 w-full rounded-xl bg-slate-900 px-5 text-sm font-extrabold text-white transition hover:bg-slate-800">
                                Cek Ongkir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white px-5 py-16 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-10 max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Keunggulan
                </p>

                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">
                    Pengiriman lebih jelas dari awal sampai selesai
                </h2>

                <p class="mt-4 leading-7 text-slate-600">
                    Sobat Kurir membantu pelanggan mengirim paket dengan proses yang mudah dipahami, status yang jelas, dan layanan yang praktis.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-6 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg hover:shadow-slate-900/10">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-950">
                        Cepat
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Proses cek tarif, pembuatan pesanan, dan tracking dibuat ringkas agar pelanggan tidak membuang waktu.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-6 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg hover:shadow-slate-900/10">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15.75 9M12 3.75l7.5 3v5.25c0 4.625-3.075 8.75-7.5 10.5-4.425-1.75-7.5-5.875-7.5-10.5V6.75l7.5-3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-950">
                        Aman
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Setiap pesanan memiliki kode resi dan riwayat pengiriman sehingga status paket lebih mudah dipantau.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-6 transition hover:-translate-y-1 hover:bg-white hover:shadow-lg hover:shadow-slate-900/10">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9h16.5M3.75 13.5h10.5M3.75 18h7.5" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-950">
                        Terpantau
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Pelanggan dapat melihat status pembayaran, status pengiriman, dan informasi paket melalui halaman tracking.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 px-5 py-16 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="rounded-3xl border border-slate-200 bg-slate-900 p-8 text-white shadow-sm md:p-10">
                <div class="grid gap-8 md:grid-cols-[1.3fr_auto] md:items-center">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-blue-300">
                            Mitra Kurir
                        </p>

                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight">
                            Punya Kendaraan? Jadilah Mitra Kurir Kami
                        </h2>

                        <p class="mt-4 max-w-2xl leading-7 text-slate-300">
                            Bergabung sebagai mitra kurir Sobat Kurir dan bantu pelanggan mengirim paket dengan layanan lokal yang lebih cepat dan terpercaya.
                        </p>
                    </div>

                    <div class="flex md:justify-end">
                        <a href="{{ route('kurir.daftar') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-7 py-4 text-sm font-extrabold text-white transition hover:bg-blue-500 sm:w-auto">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
