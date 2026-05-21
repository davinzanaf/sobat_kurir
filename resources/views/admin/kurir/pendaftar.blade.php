@extends('layouts.app')

@section('title', 'Pendaftar Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Pendaftar Kurir
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Approval Calon Kurir
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Setujui atau tolak calon kurir yang mendaftar dari halaman publik.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                Kembali ke Dashboard
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">No HP</th>
                            <th class="px-4 py-3 font-bold">Alamat</th>
                            <th class="px-4 py-3 font-bold">Setujui</th>
                            <th class="px-4 py-3 font-bold">Tolak</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendaftar as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>

                                <td class="px-4 py-4">
                                    <p class="font-extrabold text-slate-950">{{ $item->nama_lengkap }}</p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                                        Menunggu
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-slate-600">{{ $item->email }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $item->no_hp }}</td>
                                <td class="px-4 py-4">
                                    <p class="max-w-[260px] text-sm leading-6 text-slate-600">
                                        {{ $item->alamat ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.kurir.approve', $item->id_user) }}" onsubmit="return confirm('Setujui pendaftar kurir ini?');">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-blue-700">
                                            Setujui
                                        </button>
                                    </form>
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('admin.kurir.reject', $item->id_user) }}" onsubmit="return confirm('Tolak pendaftar kurir ini?');" class="min-w-[240px] space-y-3">
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="text"
                                            name="alasan_ditolak"
                                            placeholder="Alasan ditolak"
                                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                            required
                                        >

                                        <button type="submit"
                                                class="inline-flex rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700">
                                            Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada pendaftar kurir yang menunggu persetujuan.
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
