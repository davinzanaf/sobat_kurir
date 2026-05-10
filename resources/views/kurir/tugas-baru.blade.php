@extends('layouts.app')

@section('title', 'Tugas Baru - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Tugas Baru</h2>
    <p class="muted">
        Daftar pesanan yang belum diambil kurir.
    </p>

    <a class="btn btn-secondary" href="{{ route('kurir.dashboard') }}">Kembali ke Dashboard</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Resi</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Rute</th>
                <th>Berat</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_resi }}</td>
                    <td>
                        {{ $item->nama_pengirim }}<br>
                        <span class="muted">{{ $item->alamat_pengirim }}</span><br>
                        <span class="muted">{{ $item->no_hp_pengirim }}</span>
                    </td>
                    <td>
                        {{ $item->nama_penerima }}<br>
                        <span class="muted">{{ $item->alamat_penerima }}</span><br>
                        <span class="muted">{{ $item->no_hp_penerima }}</span>
                    </td>
                    <td>
                        {{ $item->kecamatan_asal }}
                        →
                        {{ $item->kecamatan_tujuan }}
                    </td>
                    <td>{{ $item->berat }} kg</td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge">{{ $item->status_pesanan }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('kurir.ambil-pesanan', $item->id_pesanan) }}" onsubmit="return confirm('Ambil pesanan ini?');">
                            @csrf

                            <button class="btn" type="submit">Ambil Pesanan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada tugas baru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
