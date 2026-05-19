@extends('layouts.app')

@section('title', 'Dashboard Admin - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Admin
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Pusat Operasional Sobat Kurir
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Kelola data kurir, tarif pengiriman, pendaftar kurir, dan seluruh pesanan dari satu halaman.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.kurir.pendaftar') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Cek Pendaftar Kurir
                </a>

                <a href="{{ route('admin.tarif.create') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Tambah Tarif
                </a>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Kurir Aktif
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahKurir ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0h7.5m0 0a1.5 1.5 0 003 0m-3 0H15V6.75A.75.75 0 0014.25 6H3.75A.75.75 0 003 6.75V18a.75.75 0 00.75.75H5.25M15 9.75h3a.75.75 0 01.53.22l2.25 2.25a.75.75 0 01.22.53V18a.75.75 0 01-.75.75h-1.5" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Jumlah kurir yang sudah aktif dan bisa mengambil pesanan.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Pendaftar Kurir
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahPendaftarKurir ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Calon kurir yang masih menunggu persetujuan admin.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Data Tarif
                        </p>

                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            {{ $jumlahTarif ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Jumlah rute tarif pengiriman yang tersedia di sistem.
                </p>
            </div>

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

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15.75 9M12 3.75l7.5 3v5.25c0 4.625-3.075 8.75-7.5 10.5-4.425-1.75-7.5-5.875-7.5-10.5V6.75l7.5-3z" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Semua pesanan customer yang tercatat di aplikasi.
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-xl font-extrabold text-slate-950">
                        Menu Operasional
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Akses cepat untuk mengelola fitur utama admin.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('admin.kurir.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:bg-blue-50/40">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-extrabold text-slate-950">Kelola Kurir</p>
                                <p class="mt-1 text-sm text-slate-500">Tambah, hapus, dan lihat data kurir.</p>
                            </div>
                            <span class="text-xl font-bold text-slate-400 transition group-hover:text-blue-600">→</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.kurir.pendaftar') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:bg-blue-50/40">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-extrabold text-slate-950">Pendaftar Kurir</p>
                                <p class="mt-1 text-sm text-slate-500">Setujui atau tolak calon kurir.</p>
                            </div>
                            <span class="text-xl font-bold text-slate-400 transition group-hover:text-blue-600">→</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.tarif.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:bg-blue-50/40">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-extrabold text-slate-950">Kelola Tarif</p>
                                <p class="mt-1 text-sm text-slate-500">Atur ongkir antar kecamatan.</p>
                            </div>
                            <span class="text-xl font-bold text-slate-400 transition group-hover:text-blue-600">→</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.pesanan.index') }}"
                       class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:bg-blue-50/40">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-extrabold text-slate-950">Data Pesanan</p>
                                <p class="mt-1 text-sm text-slate-500">Pantau semua pesanan customer.</p>
                            </div>
                            <span class="text-xl font-bold text-slate-400 transition group-hover:text-blue-600">→</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-950">
                        Prioritas Hari Ini
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Beberapa hal yang perlu dicek agar operasional tetap berjalan lancar.
                    </p>

                    <div class="mt-5 space-y-3">
                        <a href="{{ route('admin.kurir.pendaftar') }}"
                           class="flex items-center justify-between rounded-2xl bg-slate-50 px-5 py-4 transition hover:bg-blue-50">
                            <div>
                                <p class="text-sm font-extrabold text-slate-950">Review pendaftar kurir</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $jumlahPendaftarKurir ?? 0 }} menunggu persetujuan</p>
                            </div>
                            <span class="text-slate-400">→</span>
                        </a>

                        <a href="{{ route('admin.tarif.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-slate-50 px-5 py-4 transition hover:bg-blue-50">
                            <div>
                                <p class="text-sm font-extrabold text-slate-950">Pastikan tarif aktif</p>
                                <p class="mt-1 text-sm text-slate-500">Cek rute asal dan tujuan yang tersedia</p>
                            </div>
                            <span class="text-slate-400">→</span>
                        </a>

                        <a href="{{ route('admin.pesanan.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-slate-50 px-5 py-4 transition hover:bg-blue-50">
                            <div>
                                <p class="text-sm font-extrabold text-slate-950">Pantau pesanan masuk</p>
                                <p class="mt-1 text-sm text-slate-500">Pastikan pesanan mendapat kurir</p>
                            </div>
                            <span class="text-slate-400">→</span>
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-sm font-bold uppercase tracking-wider text-blue-300">
                        Catatan Sistem
                    </p>

                    <h3 class="mt-3 text-xl font-extrabold">
                        Approval kurir menjaga kualitas layanan
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Calon kurir yang mendaftar tidak langsung aktif. Admin perlu menyetujui akun agar kurir bisa login dan mengambil tugas pengiriman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
