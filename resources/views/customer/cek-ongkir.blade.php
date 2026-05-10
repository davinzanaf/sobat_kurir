@extends('layouts.app')

@section('title', 'Cek Ongkir - Sobat Kurir')

@section('content')
<div class="card" style="max-width: 640px; margin: auto;">
    <h2>Cek Ongkir</h2>
    <p class="muted">Pilih kecamatan asal, kecamatan tujuan, dan berat paket.</p>

    <form method="POST" action="{{ route('customer.cek-ongkir.process') }}">
        @csrf

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

        <button class="btn" type="submit">Cek Ongkir</button>
        <a class="btn btn-secondary" href="{{ route('customer.dashboard') }}">Kembali</a>
    </form>
</div>

@if($hasil)
    <div class="card" style="max-width: 640px; margin: 24px auto;">
        <h3>Hasil Cek Ongkir</h3>

        <table>
            <tr>
                <th>Kecamatan Asal</th>
                <td>{{ $hasil['kecamatan_asal'] }}</td>
            </tr>
            <tr>
                <th>Kecamatan Tujuan</th>
                <td>{{ $hasil['kecamatan_tujuan'] }}</td>
            </tr>
            <tr>
                <th>Berat</th>
                <td>{{ $hasil['berat'] }} kg</td>
            </tr>
            <tr>
                <th>Harga per Kg</th>
                <td>Rp {{ number_format($hasil['harga_per_kg'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Ongkir</th>
                <td><strong>Rp {{ number_format($hasil['total_harga'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <div class="menu">
            <a class="btn" href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
        </div>
    </div>
@endif
@endsection
