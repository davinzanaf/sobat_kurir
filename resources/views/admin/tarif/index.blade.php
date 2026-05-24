@extends('layouts.app')

@section('title', 'Kelola Tarif - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-back-button url="{{ url('/admin/dashboard') }}" />

        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Kelola Tarif
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Tarif Pengiriman
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Atur ongkir berdasarkan kecamatan asal, kecamatan tujuan, dan harga per kilogram.
                </p>
            </div>

            <a href="{{ route('admin.tarif.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                Tambah Tarif
            </a>
        </div>

        <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.tarif.index') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cari Tarif
                    </label>

                    <input
                        type="text"
                        name="q"
                        value="{{ $q ?? request('q') }}"
                        placeholder="Cari kecamatan asal atau tujuan..."
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
                        href="{{ route('admin.tarif.index') }}"
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
                    Total Data: {{ $tarif->count() }}
                </p>

                @if(($q ?? '') !== '')
                    <p class="text-sm text-slate-500">
                        Hasil pencarian untuk:
                        <span class="font-bold text-slate-900">"{{ $q }}"</span>
                    </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Kecamatan Asal</th>
                            <th class="px-4 py-3 font-bold">Kecamatan Tujuan</th>
                            <th class="px-4 py-3 font-bold">Harga per Kg</th>
                            <th class="px-4 py-3 font-bold">Tanggal Dibuat</th>
                            <th class="px-4 py-3 font-bold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($tarif as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-4 font-bold text-slate-950">
                                    {{ $item->kecamatan_asal }}
                                </td>

                                <td class="px-4 py-4 font-bold text-slate-950">
                                    {{ $item->kecamatan_tujuan }}
                                </td>

                                <td class="px-4 py-4 font-extrabold text-slate-950">
                                    Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->created_at ?? '-' }}
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.tarif.destroy', $item->id_tarif) }}" onsubmit="return confirm('Yakin ingin menghapus tarif ini?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-6 w-6"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437A1.125 1.125 0 0021 17.304V5.57a1.125 1.125 0 00-1.628-1.006l-3.869 1.935a1.125 1.125 0 01-1.006 0L9.503 4.002a1.125 1.125 0 00-1.006 0L3.622 6.439A1.125 1.125 0 003 7.446V19.18a1.125 1.125 0 001.628 1.006l3.869-1.935a1.125 1.125 0 011.006 0l4.994 2.497a1.125 1.125 0 001.006 0z" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-extrabold text-slate-950">
                                            Data tarif tidak ditemukan
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Coba gunakan kata kunci kecamatan lain atau reset pencarian.
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
