<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Tracking;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function dashboard()
    {
        $idKurir = session('id_user');

        $jumlahTugasBaru = Pesanan::whereNull('id_kurir')
            ->where('status_pesanan', 'MENUNGGU_KURIR')
            ->count();

        $jumlahPesananSaya = Pesanan::where('id_kurir', $idKurir)
            ->count();

        return view('kurir.dashboard', compact(
            'jumlahTugasBaru',
            'jumlahPesananSaya'
        ));
    }

    public function tugasBaru()
    {
        $pesanan = Pesanan::whereNull('id_kurir')
            ->where('status_pesanan', 'MENUNGGU_KURIR')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('kurir.tugas-baru', compact('pesanan'));
    }

    public function ambilPesanan($id)
    {
        $idKurir = session('id_user');

        $pesanan = Pesanan::whereNull('id_kurir')
            ->where('status_pesanan', 'MENUNGGU_KURIR')
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $pesanan->update([
            'id_kurir' => $idKurir,
            'status_pesanan' => 'DIJEMPUT',
        ]);

        Tracking::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'keterangan' => 'Pesanan telah diambil oleh kurir dan sedang dalam proses penjemputan.',
            'waktu_update' => now(),
        ]);

        return redirect()
            ->route('kurir.pesanan-saya')
            ->with('success', 'Pesanan berhasil diambil.');
    }

    public function pesananSaya()
    {
        $idKurir = session('id_user');

        $pesanan = Pesanan::where('id_kurir', $idKurir)
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('kurir.pesanan-saya', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => ['required', 'in:DIJEMPUT,DALAM_PENGIRIMAN,SELESAI'],
        ]);

        $idKurir = session('id_user');

        $pesanan = Pesanan::where('id_kurir', $idKurir)
    ->where('id_pesanan', $id)
    ->firstOrFail();

$dataUpdate = [
    'status_pesanan' => $request->status_pesanan,
];

if ($request->status_pesanan === 'SELESAI' && $pesanan->metode_pembayaran === 'COD') {
    $dataUpdate['status_pembayaran'] = 'SUDAH_BAYAR';
}

$pesanan->update($dataUpdate);

$keterangan = match ($request->status_pesanan) {
    'DIJEMPUT' => 'Paket sudah dijemput oleh kurir.',
    'DALAM_PENGIRIMAN' => 'Paket sedang dalam perjalanan menuju alamat penerima.',
    'SELESAI' => 'Paket sudah selesai diantar ke penerima.',
};

        Tracking::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'keterangan' => $keterangan,
            'waktu_update' => now(),
        ]);

        return redirect()
            ->route('kurir.pesanan-saya')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
