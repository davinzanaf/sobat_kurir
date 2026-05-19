@extends('layouts.app')

@section('title', 'Sobat Kurir - Layanan Pengiriman')

@section('content')
<div class="card hero">
    <div>
        <span class="badge">Layanan Pengiriman Lokal</span>

        <h1>Kirim Paket Lebih Mudah, Cepat, dan Terpantau</h1>

        <p>
            Sobat Kurir membantu kamu mengecek ongkir, membuat pesanan,
            dan melacak status pengiriman secara praktis. Kurir dapat
            mengambil tugas pengiriman, sedangkan admin mengelola operasional
            dari satu dashboard.
        </p>

        <div class="menu">
            <a class="btn" href="{{ route('customer.cek-ongkir') }}">Cek Ongkir</a>
            <a class="btn" href="{{ route('customer.pesanan.create') }}">Buat Pesanan</a>
            <a class="btn btn-secondary" href="{{ route('customer.tracking') }}">Tracking Resi</a>
            <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
        </div>
    </div>

    <div class="card" style="background:#eff6ff; box-shadow:none;">
        <h3>Fitur Utama</h3>
        <p class="muted">Cek tarif pengiriman berdasarkan kecamatan.</p>
        <p class="muted">Buat pesanan dengan kode resi otomatis.</p>
        <p class="muted">Pantau status paket lewat tracking resi.</p>
        <p class="muted">Dashboard khusus admin dan kurir.</p>
    </div>
</div>

<div class="grid-3">
    <div class="card">
        <h3>Untuk Customer</h3>
        <p class="muted">
            Customer dapat membuat pesanan, memilih metode pembayaran,
            dan melacak paket.
        </p>
    </div>

    <div class="card">
        <h3>Untuk Kurir</h3>
        <p class="muted">
            Kurir dapat melihat tugas baru, mengambil pesanan,
            dan memperbarui status pengiriman.
        </p>
    </div>

    <div class="card">
        <h3>Untuk Admin</h3>
        <p class="muted">
            Admin dapat mengelola kurir, tarif ongkir, data pesanan,
            dan approval akun kurir.
        </p>
    </div>
</div>
@endsection
