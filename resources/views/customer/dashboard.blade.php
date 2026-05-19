@extends('layouts.app')

@section('title', 'Dashboard Customer - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Dashboard Customer</h2>
    <p class="muted">
        Selamat datang, {{ session('nama_lengkap', 'Customer') }}.
    </p>

    <div class="menu">
        <a class="btn" href="{{ route('customer.cek-ongkir') }}">Cek Ongkir</a>
        <a class="btn" href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
        <a class="btn" href="{{ route('customer.riwayat-pesanan') }}">Riwayat Pesanan</a>
        <a class="btn" href="{{ route('customer.tracking') }}">Tracking Resi</a>
    </div>
</div>

<div class="card">
    <h3>Ringkasan</h3>

    <div class="table-responsive">
        <table>
            <tr>
                <th>Total Pesanan Saya</th>
                <td>{{ $jumlahPesanan ?? 0 }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
