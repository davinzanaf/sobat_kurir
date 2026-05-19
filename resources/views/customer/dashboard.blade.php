@extends('layouts.app')

@section('title', 'Dashboard Customer - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Customer
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Selamat datang, {{ session('nama_lengkap', auth()->user()->nama_lengkap ?? 'Customer') }}
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Pantau pesanan, cek ongkir, buat pengiriman baru, dan lacak paket Anda dari satu halaman.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('customer.pesanan.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Buat Pesanan
                </a>

                <a href="{{ route('customer.tracking') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Lacak Resi
                </a>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Total Pesanan
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahPesanan ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h10.5" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500">
                    Semua pesanan yang pernah Anda buat.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Menunggu Kurir
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahMenungguKurir ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500">
                    Pesanan baru yang belum diambil kurir.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Dalam Pengiriman
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahDalamPengiriman ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0h7.5m0 0a1.5 1.5 0 003 0m-3 0H15V6.75A.75.75 0 0014.25 6H3.75A.75.75 0 003 6.75V18a.75.75 0 00.75.75H5.25M15 9.75h3a.75.75 0 01.53.22l2.25 2.25a.75.75 0 01.22.53V18a.75.75 0 01-.75.75h-1.5" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500">
                    Paket yang sedang diproses atau dikirim.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Selesai
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahSelesai ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500">
                    Pesanan yang sudah sampai tujuan.
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-950">
                            Pesanan Terbaru
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Ringkasan pesanan terakhir yang Anda buat.
                        </p>
                    </div>

                    <a href="{{ route('customer.riwayat-pesanan') }}"
                       class="hidden rounded-xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600 sm:inline-flex">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                                <th class="px-4 py-3 font-bold">Resi</th>
                                <th class="px-4 py-3 font-bold">Penerima</th>
                                <th class="px-4 py-3 font-bold">Status</th>
                                <th class="px-4 py-3 font-bold">Pembayaran</th>
                                <th class="px-4 py-3 font-bold">Total</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @if(isset($pesananTerbaru) && $pesananTerbaru->count() > 0)
                                @foreach($pesananTerbaru as $item)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-4 font-bold text-slate-900">
                                            {{ $item->kode_resi }}
                                        </td>

                                        <td class="px-4 py-4 text-slate-600">
                                            {{ $item->nama_penerima }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                                {{ $item->status_pesanan }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-slate-600">
                                            {{ $item->status_pembayaran }}
                                        </td>

                                        <td class="px-4 py-4 font-bold text-slate-900">
                                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                        Belum ada pesanan terbaru.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('customer.riwayat-pesanan') }}"
                   class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600 sm:hidden">
                    Lihat Semua Pesanan
                </a>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-950">
                        Aksi Cepat
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Gunakan menu cepat berikut untuk membuat pesanan atau melacak paket.
                    </p>

                    <div class="mt-5 grid gap-3">
                        <a href="{{ route('customer.pesanan.create') }}"
                           class="flex items-center justify-between rounded-2xl bg-blue-600 px-5 py-4 text-sm font-extrabold text-white transition hover:bg-blue-700">
                            <span>Buat Pesanan Baru</span>
                            <span>→</span>
                        </a>

                        <a href="{{ route('customer.cek-ongkir') }}"
                           class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                            <span>Cek Ongkir</span>
                            <span>→</span>
                        </a>

                        <a href="{{ route('customer.tracking') }}"
                           class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                            <span>Lacak Resi</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-sm font-bold uppercase tracking-wider text-blue-300">
                        Tips
                    </p>

                    <h3 class="mt-3 text-xl font-extrabold">
                        Simpan nomor resi Anda
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Nomor resi digunakan untuk melacak status pengiriman. Anda juga bisa melihat semua pesanan melalui halaman riwayat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
