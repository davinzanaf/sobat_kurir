@extends('layouts.app')

@section('title', 'Dashboard Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Kurir
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Selamat bertugas, {{ session('nama_lengkap', 'Kurir') }}
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Lihat tugas baru, ambil pesanan, dan perbarui status pengiriman paket langsung dari dashboard kurir.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('kurir.tugas-baru') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Lihat Tugas Baru
                </a>

                <a href="{{ route('kurir.pesanan-saya') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Pesanan Saya
                </a>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Tugas Baru
                        </p>

                        <p class="mt-3 text-4xl font-extrabold text-slate-950">
                            {{ $jumlahTugasBaru ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0h7.5m0 0a1.5 1.5 0 003 0m-3 0H15V6.75A.75.75 0 0014.25 6H3.75A.75.75 0 003 6.75V18a.75.75 0 00.75.75H5.25M15 9.75h3a.75.75 0 01.53.22l2.25 2.25a.75.75 0 01.22.53V18a.75.75 0 01-.75.75h-1.5" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Pesanan yang belum diambil kurir dan siap diproses.
                </p>

                <a href="{{ route('kurir.tugas-baru') }}"
                   class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Buka Tugas Baru
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-slate-500">
                            Pesanan Saya
                        </p>

                        <p class="mt-3 text-4xl font-extrabold text-slate-950">
                            {{ $jumlahPesananSaya ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15.75 9M12 3.75l7.5 3v5.25c0 4.625-3.075 8.75-7.5 10.5-4.425-1.75-7.5-5.875-7.5-10.5V6.75l7.5-3z" />
                        </svg>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-500">
                    Pesanan yang sudah kamu ambil dan sedang kamu tangani.
                </p>

                <a href="{{ route('kurir.pesanan-saya') }}"
                   class="mt-5 inline-flex rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Buka Pesanan Saya
                </a>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">
                    Alur Kerja Kurir
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Ikuti alur berikut agar status pengiriman tetap rapi dan mudah dipantau customer.
                </p>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-extrabold text-white">
                            1
                        </span>

                        <h3 class="mt-4 font-extrabold text-slate-950">
                            Ambil Pesanan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Pilih tugas baru yang siap diproses.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-extrabold text-white">
                            2
                        </span>

                        <h3 class="mt-4 font-extrabold text-slate-950">
                            Update Status
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Perbarui status paket saat dijemput dan dikirim.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-extrabold text-white">
                            3
                        </span>

                        <h3 class="mt-4 font-extrabold text-slate-950">
                            Selesaikan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Tandai selesai setelah paket diterima.
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-blue-300">
                    Catatan
                </p>

                <h2 class="mt-3 text-xl font-extrabold">
                    Status tracking penting untuk customer
                </h2>

                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Setiap update status akan masuk ke riwayat tracking. Pastikan status diperbarui sesuai kondisi pengiriman di lapangan.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
