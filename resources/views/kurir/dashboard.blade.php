@extends('layouts.app')

@section('title', 'Dashboard Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Dashboard Kurir</h2>
    <p class="muted">
        Selamat datang, {{ session('nama_lengkap', 'Kurir') }}.
    </p>

    <div class="menu">
        <a class="btn" href="{{ route('kurir.tugas-baru') }}">Lihat Tugas Baru</a>
        <a class="btn" href="{{ route('kurir.pesanan-saya') }}">Pesanan Saya</a>
    </div>
</div>

<div class="card">
    <h3>Ringkasan Tugas</h3>

    <table>
        <tr>
            <th>Tugas Baru</th>
            <td>{{ $jumlahTugasBaru ?? 0 }}</td>
        </tr>
        <tr>
            <th>Pesanan Saya</th>
            <td>{{ $jumlahPesananSaya ?? 0 }}</td>
        </tr>
    </table>
</div>
@endsection
