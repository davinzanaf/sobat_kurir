@extends('layouts.app')

@section('title', 'Kelola Kurir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-back-button url="{{ url('/admin/dashboard') }}" />

        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Kelola Kurir
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Data Kurir Aktif
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Kelola akun kurir yang sudah aktif dan dapat mengambil tugas pengiriman.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.kurir.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Tambah Kurir
                </a>

                <a href="{{ route('admin.kurir.riwayat-hapus') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Riwayat Hapus
                </a>
            </div>
        </div>

        <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.kurir.index') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="flex-1">
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Cari Kurir
                    </label>

                    <input
                        type="text"
                        name="q"
                        value="{{ $q ?? request('q') }}"
                        placeholder="Cari nama, email, atau nomor HP..."
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
                        href="{{ route('admin.kurir.index') }}"
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
                    Total Data: {{ $kurir->count() }}
                </p>

                @if(($q ?? '') !== '')
                    <p class="text-sm text-slate-500">
                        Hasil pencarian untuk:
                        <span class="font-bold text-slate-900">"{{ $q }}"</span>
                    </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama Kurir</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">No HP</th>
                            <th class="px-4 py-3 font-bold">Tanggal Dibuat</th>
                            <th class="px-4 py-3 font-bold">Hapus Kurir</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($kurir as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-extrabold text-slate-950">
                                        {{ $item->nama_lengkap }}
                                    </p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-green-700">
                                        Aktif
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->email }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->no_hp ?? '-' }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->created_at ?? '-' }}
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST"
                                          action="{{ route('admin.kurir.destroy', $item->id_user) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus kurir ini? Data akan masuk ke riwayat hapus.');"
                                          class="min-w-[260px] space-y-3">
                                        @csrf
                                        @method('DELETE')

                                        <select
                                            name="alasan_hapus"
                                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                            required
                                        >
                                            <option value="">Pilih alasan hapus</option>
                                            <option value="Kurir tidak aktif">Kurir tidak aktif</option>
                                            <option value="Kurir mengundurkan diri">Kurir mengundurkan diri</option>
                                            <option value="Data kurir tidak valid">Data kurir tidak valid</option>
                                            <option value="Pelanggaran operasional">Pelanggaran operasional</option>
                                            <option value="Alasan lainnya">Alasan lainnya</option>
                                        </select>

                                        <input
                                            type="text"
                                            name="catatan_hapus"
                                            placeholder="Catatan tambahan opsional"
                                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                        >

                                        <button type="submit"
                                                class="inline-flex rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700">
                                            Hapus Kurir
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
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-extrabold text-slate-950">
                                            Data kurir tidak ditemukan
                                        </h3>

                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Coba gunakan kata kunci lain atau reset pencarian.
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
