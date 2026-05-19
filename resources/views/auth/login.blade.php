@extends('layouts.app')

@section('title', 'Login - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 520px; margin: auto;">
    <h2>Login Sobat Kurir</h2>
    <p class="muted">
        Masuk menggunakan akun admin, kurir, atau customer. Sistem akan mengarahkan otomatis sesuai role akun.
    </p>

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button class="btn" type="submit">Login</button>
        <a class="btn btn-secondary" href="{{ route('home') }}">Kembali</a>
    </form>
</div>
@endsection
