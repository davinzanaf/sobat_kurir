@extends('layouts.app')

@section('title', 'Kelola Tarif - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
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

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
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
                                <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-bold text-slate-950">{{ $item->kecamatan_asal }}</td>
                                <td class="px-4 py-4 font-bold text-slate-950">{{ $item->kecamatan_tujuan }}</td>
                                <td class="px-4 py-4 font-extrabold text-slate-950">
                                    Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->created_at ?? '-' }}</td>
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
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data tarif.
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
