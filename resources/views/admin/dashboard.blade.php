@extends('layouts.app')

@section('title', 'Dashboard Admin - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Dashboard Admin</h2>
    <p class="muted">
        Selamat datang, {{ session('nama_lengkap', 'Admin') }}.
    </p>

    <div class="menu">
        <a class="btn" href="#">Kelola Kurir</a>
        <a class="btn" href="#">Kelola Tarif</a>
        <a class="btn" href="#">Data Pesanan</a>
    </div>
</div>
@endsection
