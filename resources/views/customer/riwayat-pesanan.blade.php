@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
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

            <a href="{{ route('customer.pesanan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                Buat Pesanan
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
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
                                <td class="px-4 py-4">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-extrabold text-slate-950">{{ $item->kode_resi }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->nama_penerima }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->kecamatan_asal }} → {{ $item->kecamatan_tujuan }}</td>
                                <td class="px-4 py-4 font-bold text-slate-950">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->status_pembayaran }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                        {{ $item->status_pesanan }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->kurir->nama_lengkap ?? 'Belum ada kurir' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                                    Belum ada pesanan.
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
