@extends('layouts.app')

@section('title', 'Kelola Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Kelola Kurir</h2>
    <p class="muted">Daftar akun kurir yang terdaftar di sistem.</p>

    <div class="menu">
        <a class="btn" href="{{ route('admin.kurir.create') }}">Tambah Kurir</a>
        <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kurir</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kurir as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                    <td>{{ $item->created_at ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data kurir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
