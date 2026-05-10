@extends('layouts.app')

@section('title', 'Login Kurir - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 480px; margin: auto;">
    <h2>Login Kurir</h2>
    <p class="muted">Masuk sebagai kurir untuk melihat dan mengambil pesanan.</p>

    <form method="POST" action="{{ route('kurir.login.process') }}">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Login Kurir</button>
    </form>
</div>
@endsection
