@extends('layouts.app')

@section('title', 'Pesanan Saya - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Pesanan Saya</h2>
    <p class="muted">
        Daftar pesanan yang sudah kamu ambil.
    </p>

    <a class="btn btn-secondary" href="{{ route('kurir.dashboard') }}">Kembali ke Dashboard</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Resi</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Rute</th>
                    <th>Total Harga</th>
                    <th>Status Saat Ini</th>
                    <th>Update Status</th>
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
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge">{{ $item->status_pesanan }}</span>
                    </td>
                    <td>
                        @if($item->status_pesanan !== 'SELESAI')
                        <form method="POST" action="{{ route('kurir.update-status', $item->id_pesanan) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <select name="status_pesanan" required>
                                    <option value="">Pilih status</option>
                                    <option value="DIJEMPUT" {{ $item->status_pesanan == 'DIJEMPUT' ? 'selected' : '' }}>DIJEMPUT</option>
                                    <option value="DALAM_PENGIRIMAN" {{ $item->status_pesanan == 'DALAM_PENGIRIMAN' ? 'selected' : '' }}>DALAM PENGIRIMAN</option>
                                    <option value="SELESAI" {{ $item->status_pesanan == 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                                </select>
                            </div>

                            <button class="btn" type="submit">Update</button>
                        </form>
                        @else
                        <span class="badge">SELESAI</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">Belum ada pesanan yang kamu ambil.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
