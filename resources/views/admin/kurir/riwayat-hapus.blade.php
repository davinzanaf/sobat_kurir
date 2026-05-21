@extends('layouts.app')

@section('title', 'Riwayat Hapus Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Riwayat Hapus Kurir
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Arsip Kurir yang Dihapus
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Catatan kurir yang pernah dihapus beserta alasan penghapusan dari admin.
                </p>
            </div>

            <a href="{{ route('admin.kurir.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                Kembali ke Kelola Kurir
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama Kurir</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">No HP</th>
                            <th class="px-4 py-3 font-bold">Alasan</th>
                            <th class="px-4 py-3 font-bold">Dihapus Oleh</th>
                            <th class="px-4 py-3 font-bold">Waktu</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayat as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-extrabold text-slate-950">{{ $item->nama_lengkap }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->email }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->no_hp ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-xl bg-red-50 px-3 py-2 text-xs font-bold leading-5 text-red-700">
                                        {{ $item->alasan_hapus }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->dihapus_oleh_nama ?? '-' }}</td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada riwayat hapus kurir.
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
