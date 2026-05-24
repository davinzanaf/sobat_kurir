<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Kurir Sobat Kurir</title>
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
    <h1>Laporan Kinerja Kurir Sobat Kurir</h1>
    <p class="muted">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kurir</th>
                <th>Email</th>
                <th>Paket Sukses</th>
                <th>Paket Gagal</th>
                <th>Total Paket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kinerjaKurir as $kurir)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $kurir->nama_lengkap }}</td>
                    <td>{{ $kurir->email }}</td>
                    <td>{{ $kurir->paket_sukses }}</td>
                    <td>{{ $kurir->paket_gagal }}</td>
                    <td>{{ $kurir->paket_total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data kinerja kurir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
