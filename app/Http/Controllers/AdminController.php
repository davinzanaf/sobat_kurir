<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\RiwayatHapusKurir;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahKurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'AKTIF')
            ->count();

        $jumlahPendaftarKurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->count();

        $jumlahTarif = Tarif::count();

        $jumlahPesanan = Pesanan::count();

        return view('admin.dashboard', compact(
            'jumlahKurir',
            'jumlahPendaftarKurir',
            'jumlahTarif',
            'jumlahPesanan'
        ));
    }

    public function kurir()
    {
        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'AKTIF')
            ->orderBy('id_user', 'desc')
            ->get();

        return view('admin.kurir.index', compact('kurir'));
    }

    public function kurirCreate()
    {
        return view('admin.kurir.create');
    }

    public function kurirStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:tabel_users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'kurir',
            'status_akun' => 'AKTIF',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function pendaftarKurir()
    {
        $pendaftar = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->orderBy('id_user', 'desc')
            ->get();

        return view('admin.kurir.pendaftar', compact('pendaftar'));
    }

    public function approveKurir($id)
    {
        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->where('id_user', $id)
            ->firstOrFail();

        $kurir->update([
            'status_akun' => 'AKTIF',
            'approved_at' => now(),
            'approved_by' => session('id_user'),
            'alasan_ditolak' => null,
        ]);

        return redirect()
            ->route('admin.kurir.pendaftar')
            ->with('success', 'Pendaftar kurir berhasil disetujui.');
    }

    public function rejectKurir(Request $request, $id)
    {
        $request->validate([
            'alasan_ditolak' => ['required', 'string', 'max:255'],
        ]);

        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->where('id_user', $id)
            ->firstOrFail();

        $kurir->update([
            'status_akun' => 'DITOLAK',
            'alasan_ditolak' => $request->alasan_ditolak,
            'approved_at' => null,
            'approved_by' => session('id_user'),
        ]);

        return redirect()
            ->route('admin.kurir.pendaftar')
            ->with('success', 'Pendaftar kurir berhasil ditolak.');
    }

    public function kurirDestroy(Request $request, $id)
    {
        $request->validate([
            'alasan_hapus' => ['required', 'string', 'max:150'],
            'catatan_hapus' => ['nullable', 'string', 'max:255'],
        ]);

        $kurir = Pengguna::where('role', 'kurir')
            ->where('id_user', $id)
            ->firstOrFail();

        $alasan = $request->alasan_hapus;

        if ($request->filled('catatan_hapus')) {
            $alasan .= ' - ' . $request->catatan_hapus;
        }

        DB::transaction(function () use ($kurir, $alasan) {
            RiwayatHapusKurir::create([
                'id_user_lama' => $kurir->id_user,
                'nama_lengkap' => $kurir->nama_lengkap,
                'email' => $kurir->email,
                'no_hp' => $kurir->no_hp,
                'alasan_hapus' => $alasan,
                'dihapus_oleh_id' => session('id_user'),
                'dihapus_oleh_nama' => session('nama_lengkap'),
            ]);

            Pesanan::where('id_kurir', $kurir->id_user)->update([
                'id_kurir' => null,
            ]);

            $kurir->delete();
        });

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil dihapus dan masuk ke riwayat hapus.');
    }

    public function riwayatHapusKurir()
    {
        $riwayat = RiwayatHapusKurir::orderBy('id_riwayat', 'desc')->get();

        return view('admin.kurir.riwayat-hapus', compact('riwayat'));
    }

    public function tarif()
    {
        $tarif = Tarif::orderBy('id_tarif', 'desc')->get();

        return view('admin.tarif.index', compact('tarif'));
    }

    public function tarifCreate()
    {
        return view('admin.tarif.create');
    }

    public function tarifStore(Request $request)
    {
        $request->validate([
            'kecamatan_asal' => ['required', 'string', 'max:100'],
            'kecamatan_tujuan' => ['required', 'string', 'max:100'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
        ]);

        Tarif::create([
            'kecamatan_asal' => $request->kecamatan_asal,
            'kecamatan_tujuan' => $request->kecamatan_tujuan,
            'harga_per_kg' => $request->harga_per_kg,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function tarifDestroy($id)
    {
        $tarif = Tarif::where('id_tarif', $id)->firstOrFail();

        $tarif->delete();

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil dihapus.');
    }

    public function pesanan()
    {
        $pesanan = Pesanan::with('kurir')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('admin.pesanan.index', compact('pesanan'));
    }
}
