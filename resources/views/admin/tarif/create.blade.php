@extends('layouts.app')

@section('title', 'Tambah Tarif - Sobat Kurir')

@section('content')
<section class="bg-slate-50 px-5 py-10 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <x-back-button url="{{ url('/admin/tarif') }}" />

        <div class="mb-8">
            <p class="text-sm font-bold uppercase tracking-wider text-blue-600">
                Tambah Tarif
            </p>

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
                Buat Rute Tarif Baru
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Tambahkan tarif ongkir dari satu kecamatan ke kecamatan lain berdasarkan berat paket.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.tarif.store') }}" class="grid gap-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Asal</label>
                        <input
                            type="text"
                            name="kecamatan_asal"
                            value="{{ old('kecamatan_asal') }}"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecamatan Tujuan</label>
                        <input
                            type="text"
                            name="kecamatan_tujuan"
                            value="{{ old('kecamatan_tujuan') }}"
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Harga per Kg</label>
                    <input
                        type="number"
                        name="harga_per_kg"
                        value="{{ old('harga_per_kg') }}"
                        min="0"
                        class="h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        required
                    >
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm font-semibold leading-6 text-blue-800">
                    Pastikan kombinasi asal dan tujuan sudah benar agar customer bisa cek ongkir dengan akurat.
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Simpan Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
