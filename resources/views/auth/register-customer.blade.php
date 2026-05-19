@extends('layouts.app')

@section('title', 'Daftar Customer - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 620px; margin: auto;">
    <h2>Daftar Customer</h2>
    <p class="muted">
        Buat akun customer untuk membuat pesanan dan melihat riwayat pengiriman.
    </p>

    <form method="POST" action="{{ route('register.customer.process') }}">
        @csrf

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input
                type="text"
                name="nama_lengkap"
                value="{{ old('nama_lengkap') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input
                type="password"
                name="password_confirmation"
                required
            >
        </div>

        <div class="menu">
            <button class="btn" type="submit">Daftar Customer</button>
            <a class="btn btn-secondary" href="{{ route('login') }}">Sudah punya akun?</a>
        </div>
    </form>
</div>
@endsection
