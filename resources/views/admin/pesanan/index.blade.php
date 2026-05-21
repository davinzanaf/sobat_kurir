@extends('layouts.app')

@section('title', 'Data Pesanan - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Data Pesanan
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Seluruh Pesanan Customer
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Pantau semua pesanan, status pembayaran, status pengiriman, dan kurir yang menangani.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1150px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Kode Resi</th>
                            <th class="px-4 py-3 font-bold">Pengirim</th>
                            <th class="px-4 py-3 font-bold">Penerima</th>
                            <th class="px-4 py-3 font-bold">Rute</th>
                            <th class="px-4 py-3 font-bold">Berat</th>
                            <th class="px-4 py-3 font-bold">Total</th>
                            <th class="px-4 py-3 font-bold">Pembayaran</th>
                            <th class="px-4 py-3 font-bold">Kurir</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>

                                <td class="px-4 py-4">
                                    <span class="font-extrabold text-slate-950">
                                        {{ $item->kode_resi }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-950">{{ $item->nama_pengirim }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->no_hp_pengirim }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-950">{{ $item->nama_penerima }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->no_hp_penerima }}</p>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    <span class="font-semibold">{{ $item->kecamatan_asal }}</span>
                                    <span class="mx-1 text-slate-400">→</span>
                                    <span class="font-semibold">{{ $item->kecamatan_tujuan }}</span>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->berat }} kg
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

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->kurir->nama_lengkap ?? 'Belum ada kurir' }}
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data pesanan.
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
