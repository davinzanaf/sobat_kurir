@extends('layouts.app')

@section('title', 'Pendaftar Kurir - Sobat Kurir')

@section('content')
<div class="card">
    <h2>Pendaftar Kurir</h2>
    <p class="muted">
        Daftar calon kurir yang menunggu persetujuan admin.
    </p>

    <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Setujui</th>
                    <th>Tolak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftar as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td>{{ $item->alamat ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.kurir.approve', $item->id_user) }}">
                                @csrf
                                @method('PATCH')

                                <button class="btn" type="submit">
                                    Setujui
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.kurir.reject', $item->id_user) }}">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="alasan_ditolak"
                                        placeholder="Alasan ditolak"
                                        required
                                    >
                                </div>

                                <button class="btn btn-danger" type="submit">
                                    Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            Belum ada pendaftar kurir.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
