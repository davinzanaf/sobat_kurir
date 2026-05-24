@extends('layouts.app')

@section('title', 'Dashboard Owner - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                    Dashboard Owner
                </p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                    Selamat datang, Owner!
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                    Pantau pendapatan, performa operasional, dan aktivitas penting Sobat Kurir.
                </p>
            </div>

            <a href="{{ route('owner.laporan-keuangan.pdf') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                Export PDF Keuangan
            </a>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Pendapatan sukses tahun {{ $tahun }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Pesanan Selesai</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    {{ $jumlahPesananSelesai }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Total paket sukses terkirim</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Pesanan Aktif</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-950">
                    {{ $jumlahPesananAktif }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">Menunggu, dijemput, atau dikirim</p>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-950">
                    Grafik Pendapatan Bulanan
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Data dihitung dari pesanan berstatus selesai dan sudah bayar.
                </p>
            </div>

            <canvas id="revenueChart" height="110"></canvas>
        </div>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-950">
                    Activity Log Terbaru
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Jejak aktivitas penting yang terjadi pada sistem.
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueLabels = @json($labels);
    const revenueData = @json($data);

    const revenueChart = document.getElementById('revenueChart');

    new Chart(revenueChart, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Pendapatan Bulanan',
                data: revenueData,
                borderWidth: 3,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw || 0;
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
