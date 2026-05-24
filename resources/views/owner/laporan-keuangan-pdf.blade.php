<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Sobat Kurir</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .muted {
            color: #64748b;
        }

        .summary {
            margin-top: 24px;
            margin-bottom: 20px;
            width: 100%;
        }

        .summary td {
            border: 1px solid #e2e8f0;
            padding: 12px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }

        table.data th {
            background: #f1f5f9;
        }
    </style>
</head>
<body>
    <h1>Laporan Keuangan Sobat Kurir</h1>
    <p class="muted">Tahun laporan: {{ $tahun }}</p>
    <p class="muted">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table class="summary">
        <tr>
            <td>
                <strong>Total Pendapatan</strong><br>
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </td>
            <td>
                <strong>Total Pesanan Sukses</strong><br>
                {{ $totalPesanan }} pesanan
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Jumlah Pesanan</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatanBulanan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::create($tahun, $item->bulan, 1)->translatedFormat('F') }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada data pendapatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
