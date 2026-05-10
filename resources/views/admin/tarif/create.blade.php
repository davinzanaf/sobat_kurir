@extends('layouts.app')

@section('title', 'Tambah Tarif - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 620px; margin: auto;">
    <h2>Tambah Tarif</h2>
    <p class="muted">Tambahkan tarif ongkir dari satu kecamatan ke kecamatan lain.</p>

    <form method="POST" action="{{ route('admin.tarif.store') }}">
        @csrf

        <div class="form-group">
            <label>Kecamatan Asal</label>
            <input type="text" name="kecamatan_asal" value="{{ old('kecamatan_asal') }}" required>
        </div>

        <div class="form-group">
            <label>Kecamatan Tujuan</label>
            <input type="text" name="kecamatan_tujuan" value="{{ old('kecamatan_tujuan') }}" required>
        </div>

        <div class="form-group">
            <label>Harga per Kg</label>
            <input type="number" name="harga_per_kg" value="{{ old('harga_per_kg') }}" min="0" required>
        </div>

        <button class="btn" type="submit">Simpan Tarif</button>
        <a class="btn btn-secondary" href="{{ route('admin.tarif.index') }}">Batal</a>
    </form>
</div>
@endsection
