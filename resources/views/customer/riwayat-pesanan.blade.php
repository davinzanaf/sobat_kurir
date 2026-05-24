@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-back-button url="{{ url('/customer/dashboard') }}" />

        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Riwayat Pesanan
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Pesanan Saya
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Daftar semua pesanan yang pernah Anda buat.
                </p>
            </div>

            <a href="{{ route('customer.pesanan.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                Buat Pesanan
            </a>
        </div>

        <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('customer.riwayat-pesanan') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cari Pesanan
                    </label>

                    <input
                        type="text"
                        name="q"
                        value="{{ $q ?? request('q') }}"
                        placeholder="Cari kode resi, nama penerima, atau nama pengirim..."
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <div class="flex items-end gap-3">
                    <button
                        type="submit"
                        class="h-12 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('customer.riwayat-pesanan') }}"
                        class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                <p class="text-sm font-bold text-slate-700">
                    Total Data: {{ $pesanan->count() }}
                </p>

                @if(($q ?? '') !== '')
                    <p class="text-sm text-slate-500">
                        Hasil pencarian untuk:
                        <span class="font-bold text-slate-900">"{{ $q }}"</span>
                    </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Kode Resi</th>
                            <th class="px-4 py-3 font-bold">Penerima</th>
                            <th class="px-4 py-3 font-bold">Rute</th>
                            <th class="px-4 py-3 font-bold">Total</th>
                            <th class="px-4 py-3 font-bold">Pembayaran</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                            <th class="px-4 py-3 font-bold">Kurir</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-4 font-extrabold text-slate-950">
                                    {{ $item->kode_resi }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-950">
                                        {{ $item->nama_penerima }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $item->no_hp_penerima ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    <span class="font-semibold">{{ $item->kecamatan_asal }}</span>
                                    <span class="mx-1 text-slate-400">→</span>
                                    <span class="font-semibold">{{ $item->kecamatan_tujuan }}</span>
                                </td>

                                <td class="px-4 py-4 font-bold text-slate-950">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4">
                                    @php
                                        $paymentClass = $item->status_pembayaran === 'SUDAH_BAYAR'
                                            ? 'bg-green-50 text-green-700'
                                            : 'bg-amber-50 text-amber-700';
                                    @endphp

                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $paymentClass }}">
                                        {{ $item->status_pembayaran }}
                                    </span>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $item->metode_pembayaran }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = match ($item->status_pesanan) {
                                            'SELESAI' => 'bg-green-50 text-green-700',
                                            'DALAM_PENGIRIMAN' => 'bg-blue-50 text-blue-700',
                                            'DIJEMPUT' => 'bg-amber-50 text-amber-700',
                                            'MENUNGGU_KURIR' => 'bg-slate-100 text-slate-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp

                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                        {{ $item->status_pesanan }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->kurir->nama_lengkap ?? 'Belum ada kurir' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-6 w-6"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 0h17.25m-17.25 0V6.375c0-.621.504-1.125 1.125-1.125h9.75c.621 0 1.125.504 1.125 1.125v7.875m0 0h1.5l2.25-3.75h-3.75" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-extrabold text-slate-950">
                                            Pesanan tidak ditemukan
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Coba gunakan kode resi atau nama penerima lain.
                                        </p>

                                        <a href="{{ route('customer.pesanan.create') }}"
                                           class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                            Buat Pesanan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
