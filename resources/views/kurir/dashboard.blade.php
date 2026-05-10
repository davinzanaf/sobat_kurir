@extends('layouts.app')

@section('title', 'Dashboard Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Dashboard Kurir</h2>
    <p class="muted">
        Selamat datang, {{ session('nama_lengkap', 'Kurir') }}.
    </p>

    <div class="menu">
        <a class="btn" href="#">Lihat Tugas Baru</a>
        <a class="btn" href="#">Pesanan Saya</a>
    </div>
</div>
@endsection
