@extends('layouts.app')

@section('title', 'Tracking Resi - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Tracking Resi</h2>
    <p class="muted">
        Masukkan kode resi untuk melihat status dan riwayat pengiriman paket.
    </p>

    <form method="POST" action="{{ route('customer.tracking.search') }}">
        @csrf

        <div class="form-group">
            <label>Kode Resi</label>
            <input
                type="text"
                name="kode_resi"
                value="{{ old('kode_resi', $kodeResi) }}"
                placeholder="Contoh: SK2026041312170788"
                required
            >
        </div>

        <div class="menu">
            <button class="btn" type="submit">Cek Tracking</button>
            <a class="btn btn-secondary" href="{{ route('customer.dashboard') }}">Kembali</a>
        </div>
    </form>
</div>

@if($kodeResi && !$pesanan)
    <div class="card">
        <h3>Resi tidak ditemukan</h3>
        <p class="muted">
            Tidak ada pesanan dengan kode resi <strong>{{ $kodeResi }}</strong>.
            Pastikan kode resi yang dimasukkan sudah benar.
        </p>
    </div>
@endif

@if($pesanan)
    <div class="card">
        <h3>Detail Pesanan</h3>

        <div class="table-responsive">
            <table>
                <tr>
                    <th>Kode Resi</th>
                    <td>{{ $pesanan->kode_resi }}</td>
                </tr>
                <tr>
                    <th>Status Pesanan</th>
                    <td>
                        <span class="badge">{{ $pesanan->status_pesanan }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>{{ $pesanan->status_pembayaran }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ $pesanan->metode_pembayaran }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Kurir</th>
                    <td>{{ $pesanan->kurir->nama_lengkap ?? 'Belum ditentukan' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>Informasi Pengirim dan Penerima</h3>

        <div class="table-responsive">
            <table>
                <tr>
                    <th>Nama Pengirim</th>
                    <td>{{ $pesanan->nama_pengirim }}</td>
                </tr>
                <tr>
                    <th>No HP Pengirim</th>
                    <td>{{ $pesanan->no_hp_pengirim }}</td>
                </tr>
                <tr>
                    <th>Alamat Pengirim</th>
                    <td>{{ $pesanan->alamat_pengirim }}</td>
                </tr>
                <tr>
                    <th>Nama Penerima</th>
                    <td>{{ $pesanan->nama_penerima }}</td>
                </tr>
                <tr>
                    <th>No HP Penerima</th>
                    <td>{{ $pesanan->no_hp_penerima }}</td>
                </tr>
                <tr>
                    <th>Alamat Penerima</th>
                    <td>{{ $pesanan->alamat_penerima }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>Riwayat Tracking</h3>

        @if($pesanan->tracking->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->tracking as $item)
                            <tr>
                                <td>
                                    {{ $item->waktu_update ? $item->waktu_update->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Belum ada riwayat tracking untuk pesanan ini.</p>
        @endif
    </div>
@endif
@endsection
