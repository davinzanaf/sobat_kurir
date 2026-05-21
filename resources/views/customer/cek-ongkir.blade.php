@extends('layouts.app')

@section('title', 'Cek Ongkir - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Cek Ongkir
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Cek Tarif Pengiriman
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Pilih kecamatan asal, tujuan, dan berat paket untuk melihat estimasi biaya pengiriman.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('customer.cek-ongkir.process') }}" class="grid gap-5 md:grid-cols-4">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Asal</label>
                    <select name="kecamatan_asal" class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                        <option value="">Pilih asal</option>
                        @foreach($kecamatanAsal as $asal)
                            <option value="{{ $asal }}" {{ old('kecamatan_asal') == $asal ? 'selected' : '' }}>
                                {{ $asal }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Tujuan</label>
                    <select name="kecamatan_tujuan" class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                        <option value="">Pilih tujuan</option>
                        @foreach($kecamatanTujuan as $tujuan)
                            <option value="{{ $tujuan }}" {{ old('kecamatan_tujuan') == $tujuan ? 'selected' : '' }}>
                                {{ $tujuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Berat Paket</label>
                    <input type="number" name="berat" min="1" value="{{ old('berat') }}" placeholder="Kg" class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Cek Ongkir
                    </button>
                </div>
            </form>
        </div>

        @if($hasil)
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">Hasil Cek Ongkir</h2>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full min-w-[620px] text-left text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <th class="w-56 bg-slate-50 px-4 py-3 font-bold text-slate-600">Kecamatan Asal</th>
                                <td class="px-4 py-3 text-slate-700">{{ $hasil['kecamatan_asal'] }}</td>
                            </tr>
                            <tr>
                                <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Kecamatan Tujuan</th>
                                <td class="px-4 py-3 text-slate-700">{{ $hasil['kecamatan_tujuan'] }}</td>
                            </tr>
                            <tr>
                                <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Berat</th>
                                <td class="px-4 py-3 text-slate-700">{{ $hasil['berat'] }} kg</td>
                            </tr>
                            <tr>
                                <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Harga per Kg</th>
                                <td class="px-4 py-3 text-slate-700">Rp {{ number_format($hasil['harga_per_kg'], 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-slate-50 px-4 py-3 font-bold text-slate-600">Total Ongkir</th>
                                <td class="px-4 py-3 text-xl font-extrabold text-slate-950">Rp {{ number_format($hasil['total_harga'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    <a href="{{ route('customer.pesanan.create') }}" class="inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Buat Pesanan
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
