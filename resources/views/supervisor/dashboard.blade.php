@extends('layouts.app')

@section('title', 'Dashboard Supervisor - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Supervisor
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Selamat datang, Supervisor!
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Pantau performa kurir dan setujui data penting yang diajukan admin.
                </p>
            </div>

            <a href="{{ route('supervisor.laporan-kinerja.pdf') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                Export PDF Kinerja
            </a>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Kurir Terdaftar</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    {{ $kinerjaKurir->count() }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Kurir aktif yang telah disetujui</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Pending Tarif</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    {{ $pendingTarif->count() }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Menunggu approval tarif</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Pending Kurir</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    {{ $pendingKurir->count() }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Menunggu approval kurir</p>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-950">
                    KPI Performa Kurir
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Perbandingan paket sukses, gagal, dan total paket yang ditangani kurir.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[780px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Nama Kurir</th>
                            <th class="px-4 py-3 font-bold">Email</th>
                            <th class="px-4 py-3 font-bold">Paket Sukses</th>
                            <th class="px-4 py-3 font-bold">Paket Gagal</th>
                            <th class="px-4 py-3 font-bold">Total Paket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kinerjaKurir as $kurir)
                            <tr>
                                <td class="px-4 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-bold text-slate-950">{{ $kurir->nama_lengkap }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $kurir->email }}</td>
                                <td class="px-4 py-4 font-bold text-green-700">{{ $kurir->paket_sukses }}</td>
                                <td class="px-4 py-4 font-bold text-red-700">{{ $kurir->paket_gagal }}</td>
                                <td class="px-4 py-4 font-bold text-slate-950">{{ $kurir->paket_total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                    Belum ada data kinerja kurir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-xl font-extrabold text-slate-950">
                        Approval Tarif
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Tarif yang diajukan admin tidak aktif sebelum disetujui.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse($pendingTarif as $tarif)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-extrabold text-slate-950">
                                        {{ $tarif->kecamatan_asal }} → {{ $tarif->kecamatan_tujuan }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Rp {{ number_format($tarif->harga_per_kg, 0, ',', '.') }} / kg
                                    </p>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <form method="POST" action="{{ route('supervisor.approval.approve', ['type' => 'tarif', 'id' => $tarif->id_tarif]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-blue-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('supervisor.approval.reject', ['type' => 'tarif', 'id' => $tarif->id_tarif]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="alasan_reject" value="Tarif tidak disetujui.">
                                        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-5 text-sm text-slate-500">
                            Tidak ada tarif yang menunggu approval.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-xl font-extrabold text-slate-950">
                        Approval Kurir
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Kurir tidak bisa login aktif sebelum disetujui supervisor atau owner.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse($pendingKurir as $kurir)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-extrabold text-slate-950">
                                        {{ $kurir->nama_lengkap }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $kurir->email }} · {{ $kurir->no_hp ?? '-' }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <form method="POST" action="{{ route('supervisor.approval.approve', ['type' => 'kurir', 'id' => $kurir->id_user]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-blue-700">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('supervisor.approval.reject', ['type' => 'kurir', 'id' => $kurir->id_user]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="alasan_reject" value="Kurir tidak disetujui.">
                                        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-5 text-sm text-slate-500">
                            Tidak ada kurir yang menunggu approval.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-950">
                    Activity Log Terbaru
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Riwayat aktivitas approval dan aksi penting sistem.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <th class="px-4 py-3 font-bold">Waktu</th>
                            <th class="px-4 py-3 font-bold">Aksi</th>
                            <th class="px-4 py-3 font-bold">Deskripsi</th>
                            <th class="px-4 py-3 font-bold">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $log->created_at ? $log->created_at->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-4 font-bold text-slate-950">
                                    {{ $log->action }}
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $log->description }}
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $log->user->nama_lengkap ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-slate-500">
                                    Belum ada activity log.
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
