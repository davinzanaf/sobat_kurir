@extends('layouts.app')

@section('title', 'Customer - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Halaman Customer</h2>
    <p class="muted">Silakan pilih layanan yang ingin digunakan.</p>

    <div class="menu">
        <a class="btn" href="{{ route('customer.cek-ongkir') }}">Cek Ongkir</a>
        <a class="btn" href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
        <a class="btn" href="{{ route('customer.tracking') }}">Cek Tracking</a>
    </div>
</div>
@endsection
