<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Tarif;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function cekOngkir()
    {
        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->distinct()
            ->orderBy('kecamatan_tujuan')
            ->pluck('kecamatan_tujuan');

        return view('customer.cek-ongkir', [
            'kecamatanAsal' => $kecamatanAsal,
            'kecamatanTujuan' => $kecamatanTujuan,
            'hasil' => null,
        ]);
    }

    public function prosesCekOngkir(Request $request)
    {
        $request->validate([
            'kecamatan_asal' => ['required', 'string'],
            'kecamatan_tujuan' => ['required', 'string'],
            'berat' => ['required', 'numeric', 'min:1'],
        ]);

        $tarif = Tarif::where('kecamatan_asal', $request->kecamatan_asal)
            ->where('kecamatan_tujuan', $request->kecamatan_tujuan)
            ->first();

        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->distinct()
            ->orderBy('kecamatan_tujuan')
            ->pluck('kecamatan_tujuan');

    if (!$tarif) {
        return back()
            ->withErrors([
                'tarif' => 'Tarif untuk kecamatan asal dan tujuan tersebut belum tersedia.',
            ])
        ->withInput();
    }

        $totalHarga = $tarif->harga_per_kg * $request->berat;

        return view('customer.cek-ongkir', [
            'kecamatanAsal' => $kecamatanAsal,
            'kecamatanTujuan' => $kecamatanTujuan,
            'hasil' => [
                'kecamatan_asal' => $request->kecamatan_asal,
                'kecamatan_tujuan' => $request->kecamatan_tujuan,
                'berat' => $request->berat,
                'harga_per_kg' => $tarif->harga_per_kg,
                'total_harga' => $totalHarga,
            ],
        ]);
    }

    public function buatPesanan()
    {
        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->distinct()
            ->orderBy('kecamatan_tujuan')
            ->pluck('kecamatan_tujuan');

        return view('customer.buat-pesanan', compact(
            'kecamatanAsal',
            'kecamatanTujuan'
        ));
    }

    public function simpanPesanan(Request $request)
    {
        $request->validate([
            'nama_pengirim' => ['required', 'string', 'max:100'],
            'no_hp_pengirim' => ['required', 'string', 'max:20'],
            'alamat_pengirim' => ['required', 'string'],
            'nama_penerima' => ['required', 'string', 'max:100'],
            'no_hp_penerima' => ['required', 'string', 'max:20'],
            'alamat_penerima' => ['required', 'string'],
            'kecamatan_asal' => ['required', 'string'],
            'kecamatan_tujuan' => ['required', 'string'],
            'berat' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'in:COD,TRANSFER'],
        ]);

        $tarif = Tarif::where('kecamatan_asal', $request->kecamatan_asal)
            ->where('kecamatan_tujuan', $request->kecamatan_tujuan)
            ->first();

        if (!$tarif) {
            return back()
                ->withErrors([
                    'tarif' => 'Tarif untuk kecamatan asal dan tujuan tersebut belum tersedia.',
                ])
                ->withInput();
        }

        $totalHarga = $tarif->harga_per_kg * $request->berat;

        $kodeResi = 'SK' . now()->format('YmdHis') . strtoupper(Str::random(4));

        $pesanan = Pesanan::create([
            'kode_resi' => $kodeResi,
            'id_customer' => null,
            'id_kurir' => null,
            'nama_pengirim' => $request->nama_pengirim,
            'no_hp_pengirim' => $request->no_hp_pengirim,
            'alamat_pengirim' => $request->alamat_pengirim,
            'nama_penerima' => $request->nama_penerima,
            'no_hp_penerima' => $request->no_hp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'berat' => $request->berat,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'BELUM_BAYAR',
            'status_pesanan' => 'MENUNGGU_KURIR',
            'kecamatan_asal' => $request->kecamatan_asal,
            'kecamatan_tujuan' => $request->kecamatan_tujuan,
            'created_at' => now(),
        ]);

        Tracking::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'keterangan' => 'Pesanan berhasil dibuat dan sedang menunggu kurir.',
            'waktu_update' => now(),
        ]);

        return redirect()
            ->route('customer.tracking')
            ->with('success', 'Pesanan berhasil dibuat. Kode resi kamu: ' . $kodeResi);
    }

    public function tracking()
    {
        return view('customer.tracking', [
            'pesanan' => null,
            'kodeResi' => null,
        ]);
    }

    public function cariTracking(Request $request)
    {
        $request->validate([
            'kode_resi' => ['required', 'string', 'max:30'],
        ]);

        $kodeResi = strtoupper(trim($request->kode_resi));

        $pesanan = Pesanan::with([
                'kurir',
                'tracking' => function ($query) {
                    $query->orderBy('waktu_update', 'desc');
                },
            ])
            ->where('kode_resi', $kodeResi)
            ->first();

        return view('customer.tracking', [
            'pesanan' => $pesanan,
            'kodeResi' => $kodeResi,
        ]);
    }
}
