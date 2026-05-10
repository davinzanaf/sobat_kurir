@extends('layouts.app')

@section('title', 'Tambah Kurir - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 620px; margin: auto;">
    <h2>Tambah Kurir</h2>
    <p class="muted">Isi data kurir baru yang akan digunakan untuk login kurir.</p>

    <form method="POST" action="{{ route('admin.kurir.store') }}">
        @csrf

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit">Simpan Kurir</button>
        <a class="btn btn-secondary" href="{{ route('admin.kurir.index') }}">Batal</a>
    </form>
</div>
@endsection
