@extends('layouts.app')

@section('title', 'Data Pesanan - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Data Pesanan</h2>
    <p class="muted">Daftar seluruh pesanan customer di Sobat Kurir.</p>

    <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Resi</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Berat</th>
                <th>Total Harga</th>
                <th>Kurir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_resi }}</td>
                    <td>
                        {{ $item->nama_pengirim }}<br>
                        <span class="muted">{{ $item->no_hp_pengirim }}</span>
                    </td>
                    <td>
                        {{ $item->nama_penerima }}<br>
                        <span class="muted">{{ $item->no_hp_penerima }}</span>
                    </td>
                    <td>{{ $item->berat }} kg</td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $item->kurir->nama_lengkap ?? 'Belum ada kurir' }}</td>
                    <td>
                        <span class="badge">{{ $item->status_pesanan }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
