<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahKurir = Pengguna::where('role', 'kurir')->count();
        $jumlahTarif = Tarif::count();
        $jumlahPesanan = Pesanan::count();

        return view('admin.dashboard', compact(
            'jumlahKurir',
            'jumlahTarif',
            'jumlahPesanan'
        ));
    }

    public function kurir()
    {
        $kurir = Pengguna::where('role', 'kurir')
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
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function tarif()
    {
        $tarif = Tarif::orderBy('id_tarif', 'desc')->get();

        return view('admin.tarif.index', compact('tarif'));
    }

    public function pesanan()
    {
        $pesanan = Pesanan::with('kurir')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('admin.pesanan.index', compact('pesanan'));
    }
}
