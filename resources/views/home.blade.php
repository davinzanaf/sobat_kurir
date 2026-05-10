@extends('layouts.app')

@section('title', 'Beranda - Sobat Kurir')

@section('content')
<div class="card hero">
    <div>
        <h1>Kirim Paket Lebih Mudah dengan Sobat Kurir</h1>
        <p>
            Sobat Kurir membantu customer membuat pesanan, mengecek ongkir,
            dan melacak status pengiriman. Admin dapat mengelola kurir,
            tarif, dan data pesanan. Kurir dapat mengambil dan memperbarui
            status pengiriman.
        </p>

        <div class="menu">
            <a class="btn" href="{{ route('customer.dashboard') }}">Masuk Customer</a>
            <a class="btn btn-secondary" href="{{ route('admin.login') }}">Login Admin</a>
            <a class="btn btn-secondary" href="{{ route('kurir.login') }}">Login Kurir</a>
        </div>
    </div>

    <div class="card" style="background:#eff6ff; box-shadow:none;">
        <h3>Fitur Utama</h3>
        <p class="muted">Cek Ongkir</p>
        <p class="muted">Buat Pesanan</p>
        <p class="muted">Tracking Resi</p>
        <p class="muted">Dashboard Admin</p>
        <p class="muted">Dashboard Kurir</p>
    </div>
</div>
@endsection
