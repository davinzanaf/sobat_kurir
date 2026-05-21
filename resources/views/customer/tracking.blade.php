@extends('layouts.app')

@section('title', 'Tracking Resi - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <x-back-button />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Tracking Resi
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Lacak Paket Anda
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Masukkan kode resi untuk melihat status dan riwayat pengiriman.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="POST" action="{{ route('customer.tracking.search') }}" class="flex flex-col gap-3 md:flex-row">
                @csrf

                <input
                    type="text"
                    name="kode_resi"
                    value="{{ old('kode_resi', $kodeResi) }}"
                    placeholder="Contoh: SK2026041312170788"
                    class="h-12 flex-1 rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    required
                >

                <button type="submit" class="h-12 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Cek Tracking
                </button>
            </form>
        </div>

        @if($kodeResi && !$pesanan)
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6">
                <h2 class="text-xl font-extrabold text-red-700">Resi tidak ditemukan</h2>
                <p class="mt-2 leading-7 text-red-700">
                    Tidak ada pesanan dengan kode resi <strong>{{ $kodeResi }}</strong>.
                </p>
            </div>
        @endif

        @if($pesanan)
            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-950">Detail Pesanan</h2>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full min-w-[520px] text-left text-sm">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <th class="w-48 bg-slate-50 px-4 py-3 font-bold text-slate-600">Kode Resi</th>
                                    <td class="px-4 py-3 font-extrabold text-slate-950">{{ $pesanan->kode_resi }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Status Pesanan</th>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                            {{ $pesanan->status_pesanan }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Pembayaran</th>
                                    <td class="px-4 py-3 text-slate-700">{{ $pesanan->status_pembayaran }} / {{ $pesanan->metode_pembayaran }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Total Harga</th>
                                    <td class="px-4 py-3 font-bold text-slate-950">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Kurir</th>
                                    <td class="px-4 py-3 text-slate-700">{{ $pesanan->kurir->nama_lengkap ?? 'Belum ditentukan' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-950">Riwayat Tracking</h2>

                    <div class="mt-5 space-y-3">
                        @forelse($pesanan->tracking as $item)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm font-extrabold text-slate-950">
                                    {{ $item->waktu_update ? $item->waktu_update->format('d-m-Y H:i') : '-' }}
                                </p>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    {{ $item->keterangan }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">
                                Belum ada riwayat tracking.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
