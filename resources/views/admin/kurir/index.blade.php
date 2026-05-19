@extends('layouts.app')

@section('title', 'Kelola Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Kelola Kurir</h2>
    <p class="muted">Daftar akun kurir yang terdaftar di sistem.</p>

    <div class="menu">
        <a class="btn" href="{{ route('admin.kurir.create') }}">Tambah Kurir</a>
        <a class="btn btn-secondary" href="{{ route('admin.kurir.riwayat-hapus') }}">Riwayat Hapus Kurir</a>
        <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kurir</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi Hapus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kurir as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                    <td>{{ $item->created_at ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.kurir.destroy', $item->id_user) }}" onsubmit="return confirm('Yakin ingin menghapus kurir ini? Data akan masuk ke riwayat hapus.');">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <select name="alasan_hapus" required>
                                    <option value="">Pilih alasan hapus</option>
                                    <option value="Kurir tidak aktif">Kurir tidak aktif</option>
                                    <option value="Kurir mengundurkan diri">Kurir mengundurkan diri</option>
                                    <option value="Data kurir tidak valid">Data kurir tidak valid</option>
                                    <option value="Pelanggaran operasional">Pelanggaran operasional</option>
                                    <option value="Alasan lainnya">Alasan lainnya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="text" name="catatan_hapus" placeholder="Catatan tambahan opsional">
                            </div>

                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Belum ada data kurir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
