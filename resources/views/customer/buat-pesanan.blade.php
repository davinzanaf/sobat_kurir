@extends('layouts.app')

@section('title', 'Buat Pesanan - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Buat Pesanan
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Form Pengiriman Paket
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Isi data pengirim, penerima, dan detail paket dengan benar.
            </p>
        </div>

        <form method="POST" action="{{ route('customer.pesanan.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">Data Pengirim</h2>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Pengirim</label>
                        <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim') }}" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">No HP Pengirim</label>
                        <input type="text" name="no_hp_pengirim" value="{{ old('no_hp_pengirim') }}" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-bold text-slate-700">Alamat Pengirim</label>
                    <textarea name="alamat_pengirim" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>{{ old('alamat_pengirim') }}</textarea>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">Data Penerima</h2>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Penerima</label>
                        <input type="text" name="nama_penerima" value="{{ old('nama_penerima') }}" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">No HP Penerima</label>
                        <input type="text" name="no_hp_penerima" value="{{ old('no_hp_penerima') }}" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-bold text-slate-700">Alamat Penerima</label>
                    <textarea name="alamat_penerima" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>{{ old('alamat_penerima') }}</textarea>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">Detail Pengiriman</h2>

                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Asal</label>
                        <select name="kecamatan_asal" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                            <option value="">Pilih kecamatan asal</option>
                            @foreach($kecamatanAsal as $asal)
                                <option value="{{ $asal }}" {{ old('kecamatan_asal') == $asal ? 'selected' : '' }}>{{ $asal }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Tujuan</label>
                        <select name="kecamatan_tujuan" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                            <option value="">Pilih kecamatan tujuan</option>
                            @foreach($kecamatanTujuan as $tujuan)
                                <option value="{{ $tujuan }}" {{ old('kecamatan_tujuan') == $tujuan ? 'selected' : '' }}>{{ $tujuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Berat Paket</label>
                        <input type="number" name="berat" min="1" value="{{ old('berat') }}" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="h-12 w-full rounded-xl border border-slate-200 px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                            <option value="">Pilih metode pembayaran</option>
                            <option value="TRANSFER" {{ old('metode_pembayaran') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                            <option value="COD" {{ old('metode_pembayaran') == 'COD' ? 'selected' : '' }}>COD</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="inline-flex h-12 items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Buat Pesanan
                </button>

                <a href="{{ route('customer.dashboard') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
