@extends('layouts.app')

@section('title', 'Tugas Baru - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-back-button />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Tugas Baru
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Pesanan Menunggu Kurir
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Daftar pesanan yang belum diambil kurir. Ambil pesanan untuk mulai proses penjemputan dan pengiriman.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Kode Resi</th>
                            <th class="px-4 py-3 font-bold">Pengirim</th>
                            <th class="px-4 py-3 font-bold">Penerima</th>
                            <th class="px-4 py-3 font-bold">Rute</th>
                            <th class="px-4 py-3 font-bold">Berat</th>
                            <th class="px-4 py-3 font-bold">Total</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                            <th class="px-4 py-3 font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="font-extrabold text-slate-950">
                                        {{ $item->kode_resi }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-950">{{ $item->nama_pengirim }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->no_hp_pengirim }}</p>
                                    <p class="mt-1 max-w-[220px] text-xs leading-5 text-slate-500">{{ $item->alamat_pengirim }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-950">{{ $item->nama_penerima }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->no_hp_penerima }}</p>
                                    <p class="mt-1 max-w-[220px] text-xs leading-5 text-slate-500">{{ $item->alamat_penerima }}</p>
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
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                        {{ $item->status_pesanan }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('kurir.ambil-pesanan', $item->id_pesanan) }}" onsubmit="return confirm('Ambil pesanan ini?');">
                                        @csrf

                                        <button type="submit" class="inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-blue-700">
                                            Ambil Pesanan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h10.5" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-extrabold text-slate-950">
                                            Belum ada tugas baru
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Pesanan baru akan muncul di sini setelah customer membuat pesanan dan belum ada kurir yang mengambilnya.
                                        </p>
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
