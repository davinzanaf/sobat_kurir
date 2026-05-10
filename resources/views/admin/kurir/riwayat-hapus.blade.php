@extends('layouts.app')

@section('title', 'Riwayat Hapus Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Riwayat Hapus Kurir</h2>
    <p class="muted">Daftar kurir yang pernah dihapus oleh admin beserta alasannya.</p>

    <a class="btn btn-secondary" href="{{ route('admin.kurir.index') }}">Kembali ke Kelola Kurir</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kurir</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alasan Hapus</th>
                <th>Dihapus Oleh</th>
                <th>Waktu Hapus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                    <td>{{ $item->alasan_hapus }}</td>
                    <td>{{ $item->dihapus_oleh_nama ?? '-' }}</td>
                    <td>{{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada riwayat hapus kurir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
