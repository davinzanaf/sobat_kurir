@extends('layouts.app')

@section('title', 'Dashboard Admin - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Dashboard Admin</h2>
    <p class="muted">
        Selamat datang, {{ session('nama_lengkap', 'Admin') }}.
    </p>

    <div class="menu">
        <a class="btn" href="{{ route('admin.kurir.index') }}">Kelola Kurir</a>
        <a class="btn" href="{{ route('admin.tarif.index') }}">Kelola Tarif</a>
        <a class="btn" href="{{ route('admin.pesanan.index') }}">Data Pesanan</a>
        <a class="btn" href="{{ route('admin.kurir.pendaftar') }}">Pendaftar Kurir</a>
    </div>
</div>

<div class="card">
    <h3>Ringkasan Data</h3>

    <table>
        <tr>
            <th>Total Kurir</th>
            <td>{{ $jumlahKurir }}</td>
        </tr>
        <tr>
            <th>Total Tarif</th>
            <td>{{ $jumlahTarif }}</td>
        </tr>
        <tr>
            <th>Total Pesanan</th>
            <td>{{ $jumlahPesanan }}</td>
        </tr>
        <tr>
            <th>Pendaftar Kurir Menunggu</th>
            <td>{{ $jumlahPendaftarKurir ?? 0 }}</td>
        </tr>
    </table>
</div>
@endsection
