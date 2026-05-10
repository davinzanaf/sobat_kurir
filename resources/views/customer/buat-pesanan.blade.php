@extends('layouts.app')

@section('title', 'Buat Pesanan - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 760px; margin: auto;">
    <h2>Buat Pesanan</h2>
    <p class="muted">Isi data pengirim, penerima, dan detail pengiriman.</p>

    <form method="POST" action="{{ route('customer.pesanan.store') }}">
        @csrf

        <h3>Data Pengirim</h3>

        <div class="form-group">
            <label>Nama Pengirim</label>
            <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim') }}" required>
        </div>

        <div class="form-group">
            <label>No HP Pengirim</label>
            <input type="text" name="no_hp_pengirim" value="{{ old('no_hp_pengirim') }}" required>
        </div>

        <div class="form-group">
            <label>Alamat Pengirim</label>
            <textarea name="alamat_pengirim" rows="3" required>{{ old('alamat_pengirim') }}</textarea>
        </div>

        <h3>Data Penerima</h3>

        <div class="form-group">
            <label>Nama Penerima</label>
            <input type="text" name="nama_penerima" value="{{ old('nama_penerima') }}" required>
        </div>

        <div class="form-group">
            <label>No HP Penerima</label>
            <input type="text" name="no_hp_penerima" value="{{ old('no_hp_penerima') }}" required>
        </div>

        <div class="form-group">
            <label>Alamat Penerima</label>
            <textarea name="alamat_penerima" rows="3" required>{{ old('alamat_penerima') }}</textarea>
        </div>

        <h3>Detail Pengiriman</h3>

        <div class="form-group">
            <label>Kecamatan Asal</label>
            <select name="kecamatan_asal" required>
                <option value="">Pilih kecamatan asal</option>
                @foreach($kecamatanAsal as $asal)
                    <option value="{{ $asal }}" {{ old('kecamatan_asal') == $asal ? 'selected' : '' }}>
                        {{ $asal }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kecamatan Tujuan</label>
            <select name="kecamatan_tujuan" required>
                <option value="">Pilih kecamatan tujuan</option>
                @foreach($kecamatanTujuan as $tujuan)
                    <option value="{{ $tujuan }}" {{ old('kecamatan_tujuan') == $tujuan ? 'selected' : '' }}>
                        {{ $tujuan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Berat Paket</label>
            <input type="number" name="berat" min="1" value="{{ old('berat') }}" required>
        </div>

        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="metode_pembayaran" required>
                <option value="">Pilih metode pembayaran</option>
                <option value="COD" {{ old('metode_pembayaran') == 'COD' ? 'selected' : '' }}>COD</option>
                <option value="TRANSFER" {{ old('metode_pembayaran') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>

        <button class="btn" type="submit">Buat Pesanan</button>
        <a class="btn btn-secondary" href="{{ route('customer.dashboard') }}">Kembali</a>
    </form>
</div>
@endsection
