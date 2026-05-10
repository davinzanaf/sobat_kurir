@extends('layouts.app')

@section('title', 'Login Admin - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 480px; margin: auto;">
    <h2>Login Admin</h2>
    <p class="muted">Masuk sebagai admin untuk mengelola data Sobat Kurir.</p>

    <form method="POST" action="{{ route('admin.login.process') }}">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Login Admin</button>
    </form>
</div>
@endsection
