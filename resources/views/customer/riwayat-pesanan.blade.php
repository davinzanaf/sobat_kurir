@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Riwayat Pesanan Saya</h2>
    <p class="muted">Daftar pesanan yang pernah kamu buat.</p>

    <a class="btn btn-secondary" href="{{ route('customer.dashboard') }}">Kembali ke Dashboard</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Resi</th>
                    <th>Penerima</th>
                    <th>Rute</th>
                    <th>Total Harga</th>
                    <th>Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Kurir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_resi }}</td>
                        <td>{{ $item->nama_penerima }}</td>
                        <td>{{ $item->kecamatan_asal }} → {{ $item->kecamatan_tujuan }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $item->status_pembayaran }}</td>
                        <td><span class="badge">{{ $item->status_pesanan }}</span></td>
                        <td>{{ $item->kurir->nama_lengkap ?? 'Belum ada kurir' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
