<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KurirController extends Controller
{
    public function dashboard()
    {
        $idKurir = session('id_user');

        $jumlahTugasBaru = Pesanan::whereNull('id_kurir')
            ->where('status_pesanan', 'MENUNGGU_KURIR')
            ->count();

        $jumlahPesananSaya = Pesanan::where('id_kurir', $idKurir)
            ->whereIn('status_pesanan', ['DIJEMPUT', 'DALAM_PENGIRIMAN'])
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

        $berhasil = false;

        DB::transaction(function () use ($id, $idKurir, &$berhasil) {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->whereNull('id_kurir')
                ->where('status_pesanan', 'MENUNGGU_KURIR')
                ->lockForUpdate()
                ->first();

            if (!$pesanan) {
                return;
            }

            $pesanan->id_kurir = $idKurir;
            $pesanan->status_pesanan = 'DIJEMPUT';
            $pesanan->save();

            Tracking::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'keterangan' => 'Pesanan telah diambil oleh kurir.',
                'waktu_update' => now(),
                'created_at' => now(),
            ]);

            $berhasil = true;
        });

        if (!$berhasil) {
            return redirect()
                ->route('kurir.tugas-baru')
                ->withErrors([
                    'pesanan' => 'Pesanan ini sudah diambil oleh kurir lain atau sudah tidak tersedia.',
                ]);
        }

        return redirect()
            ->route('kurir.pesanan-saya')
            ->with('success', 'Pesanan berhasil diambil.');
    }

    public function pesananSaya()
    {
        $pesanan = Pesanan::where('id_kurir', session('id_user'))
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('kurir.pesanan-saya', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => ['required', 'in:DIJEMPUT,DALAM_PENGIRIMAN,SELESAI'],
        ], [
            'status_pesanan.required' => 'Status pesanan wajib dipilih.',
            'status_pesanan.in' => 'Status pesanan tidak valid.',
        ]);

        $idKurir = session('id_user');

        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_kurir', $idKurir)
            ->lockForUpdate()
            ->firstOrFail();

        DB::transaction(function () use ($pesanan, $request) {
            $statusBaru = $request->status_pesanan;

            $pesanan->status_pesanan = $statusBaru;

            if ($statusBaru === 'SELESAI' && $pesanan->metode_pembayaran === 'COD') {
                $pesanan->status_pembayaran = 'SUDAH_BAYAR';
            }

            $pesanan->save();

            $keterangan = match ($statusBaru) {
                'DIJEMPUT' => 'Pesanan telah dijemput oleh kurir.',
                'DALAM_PENGIRIMAN' => 'Pesanan sedang dalam pengiriman.',
                'SELESAI' => 'Pesanan selesai dan telah diterima.',
                default => 'Status pesanan diperbarui.',
            };

            Tracking::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'keterangan' => $keterangan,
                'waktu_update' => now(),
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('kurir.pesanan-saya')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
