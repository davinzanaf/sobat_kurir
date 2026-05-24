<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Tarif;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $idCustomer = session('id_user');

        $jumlahPesanan = Pesanan::where('id_customer', $idCustomer)->count();

        $jumlahMenunggu = Pesanan::where('id_customer', $idCustomer)
            ->where('status_pesanan', 'MENUNGGU_KURIR')
            ->count();

        $jumlahDikirim = Pesanan::where('id_customer', $idCustomer)
            ->whereIn('status_pesanan', ['DIJEMPUT', 'DALAM_PENGIRIMAN'])
            ->count();

        $jumlahSelesai = Pesanan::where('id_customer', $idCustomer)
            ->where('status_pesanan', 'SELESAI')
            ->count();

        $pesananTerbaru = Pesanan::with('kurir')
            ->where('id_customer', $idCustomer)
            ->orderBy('id_pesanan', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact(
            'jumlahPesanan',
            'jumlahMenunggu',
            'jumlahDikirim',
            'jumlahSelesai',
            'pesananTerbaru'
        ));
    }

    public function cekOngkir()
    {
        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->distinct()
            ->orderBy('kecamatan_tujuan')
            ->pluck('kecamatan_tujuan');

        $hasil = null;

        return view('customer.cek-ongkir', compact(
            'kecamatanAsal',
            'kecamatanTujuan',
            'hasil'
        ));
    }

    public function prosesCekOngkir(Request $request)
    {
        $request->merge([
            'kecamatan_asal' => trim($request->kecamatan_asal),
            'kecamatan_tujuan' => trim($request->kecamatan_tujuan),
        ]);

        $request->validate([
            'kecamatan_asal' => ['required', 'string', 'max:100'],
            'kecamatan_tujuan' => ['required', 'string', 'max:100'],
            'berat' => ['required', 'numeric', 'min:1'],
        ], [
            'kecamatan_asal.required' => 'Kecamatan asal wajib dipilih.',
            'kecamatan_tujuan.required' => 'Kecamatan tujuan wajib dipilih.',
            'berat.required' => 'Berat paket wajib diisi.',
            'berat.numeric' => 'Berat paket harus berupa angka.',
            'berat.min' => 'Berat paket minimal 1 kg.',
        ]);

        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->distinct()
            ->orderBy('kecamatan_tujuan')
            ->pluck('kecamatan_tujuan');

        $tarif = Tarif::whereRaw('LOWER(kecamatan_asal) = ?', [strtolower($request->kecamatan_asal)])
            ->whereRaw('LOWER(kecamatan_tujuan) = ?', [strtolower($request->kecamatan_tujuan)])
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->first();

        if (!$tarif) {
            return back()
                ->withErrors([
                    'kecamatan_tujuan' => 'Tarif untuk rute tersebut belum tersedia.',
                ])
                ->withInput();
        }

        $hasil = [
            'kecamatan_asal' => $tarif->kecamatan_asal,
            'kecamatan_tujuan' => $tarif->kecamatan_tujuan,
            'berat' => $request->berat,
            'harga_per_kg' => $tarif->harga_per_kg,
            'total_harga' => $tarif->harga_per_kg * $request->berat,
        ];

        return view('customer.cek-ongkir', compact(
            'kecamatanAsal',
            'kecamatanTujuan',
            'hasil'
        ));
    }

    public function buatPesanan()
    {
        $kecamatanAsal = Tarif::select('kecamatan_asal')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->distinct()
            ->orderBy('kecamatan_asal')
            ->pluck('kecamatan_asal');

        $kecamatanTujuan = Tarif::select('kecamatan_tujuan')
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
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
        $request->merge([
            'nama_pengirim' => trim($request->nama_pengirim),
            'no_hp_pengirim' => preg_replace('/[^0-9]/', '', $request->no_hp_pengirim),
            'alamat_pengirim' => trim($request->alamat_pengirim),
            'nama_penerima' => trim($request->nama_penerima),
            'no_hp_penerima' => preg_replace('/[^0-9]/', '', $request->no_hp_penerima),
            'alamat_penerima' => trim($request->alamat_penerima),
            'kecamatan_asal' => trim($request->kecamatan_asal),
            'kecamatan_tujuan' => trim($request->kecamatan_tujuan),
            'metode_pembayaran' => strtoupper(trim($request->metode_pembayaran)),
        ]);

        $request->validate([
            'nama_pengirim' => ['required', 'string', 'max:100'],
            'no_hp_pengirim' => ['required', 'digits_between:10,13'],
            'alamat_pengirim' => ['required', 'string'],
            'nama_penerima' => ['required', 'string', 'max:100'],
            'no_hp_penerima' => ['required', 'digits_between:10,13'],
            'alamat_penerima' => ['required', 'string'],
            'kecamatan_asal' => ['required', 'string', 'max:100'],
            'kecamatan_tujuan' => ['required', 'string', 'max:100'],
            'berat' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'in:TRANSFER,COD'],
        ], [
            'nama_pengirim.required' => 'Nama pengirim wajib diisi.',
            'no_hp_pengirim.required' => 'Nomor HP pengirim wajib diisi.',
            'no_hp_pengirim.digits_between' => 'Nomor HP pengirim harus 10 sampai 13 digit angka.',
            'alamat_pengirim.required' => 'Alamat pengirim wajib diisi.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'no_hp_penerima.required' => 'Nomor HP penerima wajib diisi.',
            'no_hp_penerima.digits_between' => 'Nomor HP penerima harus 10 sampai 13 digit angka.',
            'alamat_penerima.required' => 'Alamat penerima wajib diisi.',
            'kecamatan_asal.required' => 'Kecamatan asal wajib dipilih.',
            'kecamatan_tujuan.required' => 'Kecamatan tujuan wajib dipilih.',
            'berat.required' => 'Berat paket wajib diisi.',
            'berat.numeric' => 'Berat paket harus berupa angka.',
            'berat.min' => 'Berat paket minimal 1 kg.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ]);

        $tarif = Tarif::whereRaw('LOWER(kecamatan_asal) = ?', [strtolower($request->kecamatan_asal)])
            ->whereRaw('LOWER(kecamatan_tujuan) = ?', [strtolower($request->kecamatan_tujuan)])
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->first();

        if (!$tarif) {
            return back()
                ->withErrors([
                    'kecamatan_tujuan' => 'Pesanan tidak bisa dibuat karena tarif untuk rute tersebut belum tersedia atau belum disetujui.',
                ])
                ->withInput();
        }

        $kodeResi = $this->generateKodeResi();

        $totalHarga = $tarif->harga_per_kg * $request->berat;

        $statusPembayaran = $request->metode_pembayaran === 'TRANSFER'
            ? 'SUDAH_BAYAR'
            : 'BELUM_DIBAYAR';

        $pesanan = Pesanan::create([
            'kode_resi' => $kodeResi,
            'id_customer' => session('id_user'),
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
            'status_pembayaran' => $statusPembayaran,
            'status_pesanan' => 'MENUNGGU_KURIR',
            'kecamatan_asal' => $tarif->kecamatan_asal,
            'kecamatan_tujuan' => $tarif->kecamatan_tujuan,
            'created_at' => now(),
        ]);

        Tracking::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'keterangan' => 'Pesanan dibuat dan menunggu kurir.',
            'waktu_update' => now(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('customer.riwayat-pesanan')
            ->with('success', 'Pesanan berhasil dibuat. Kode resi Anda: ' . $kodeResi);
    }

    public function riwayatPesanan(Request $request)
    {
        $q = trim($request->get('q', ''));

        $pesanan = Pesanan::with('kurir')
            ->where('id_customer', session('id_user'))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('kode_resi', 'like', '%' . $q . '%')
                        ->orWhere('nama_penerima', 'like', '%' . $q . '%')
                        ->orWhere('nama_pengirim', 'like', '%' . $q . '%');
                });
            })
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('customer.riwayat-pesanan', compact('pesanan', 'q'));
    }

    public function tracking()
    {
        $kodeResi = null;
        $pesanan = null;

        return view('customer.tracking', compact('kodeResi', 'pesanan'));
    }

    public function cariTracking(Request $request)
    {
        $request->merge([
            'kode_resi' => strtoupper(trim($request->kode_resi)),
        ]);

        $request->validate([
            'kode_resi' => ['required', 'string', 'max:50'],
        ], [
            'kode_resi.required' => 'Kode resi wajib diisi.',
        ]);

        $kodeResi = $request->kode_resi;

        $pesanan = Pesanan::with(['kurir', 'tracking'])
            ->where('kode_resi', $kodeResi)
            ->first();

        return view('customer.tracking', compact('kodeResi', 'pesanan'));
    }

    private function generateKodeResi()
    {
        do {
            $kodeResi = 'SK' . now()->format('YmdHis') . Str::upper(Str::random(4));
        } while (Pesanan::where('kode_resi', $kodeResi)->exists());

        return $kodeResi;
    }
}
