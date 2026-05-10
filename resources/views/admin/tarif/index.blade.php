@extends('layouts.app')

@section('title', 'Kelola Tarif - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Kelola Tarif</h2>
    <p class="muted">Daftar tarif ongkir berdasarkan kecamatan asal dan tujuan.</p>

    <div class="menu">
        <a class="btn" href="{{ route('admin.tarif.create') }}">Tambah Tarif</a>
        <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan Asal</th>
                <th>Kecamatan Tujuan</th>
                <th>Harga per Kg</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarif as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kecamatan_asal }}</td>
                    <td>{{ $item->kecamatan_tujuan }}</td>
                    <td>Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.tarif.destroy', $item->id_tarif) }}" onsubmit="return confirm('Yakin ingin menghapus tarif ini?');">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data tarif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
