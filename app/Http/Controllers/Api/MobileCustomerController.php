<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Tarif;
use App\Models\Tracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileCustomerController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $customer = $this->ensureCustomer($request);
        if ($customer instanceof JsonResponse) {
            return $customer;
        }

        $idCustomer = $customer->id_user;

        $pesananTerbaru = Pesanan::with('kurir')
            ->where('id_customer', $idCustomer)
            ->orderBy('id_pesanan', 'desc')
            ->limit(5)
            ->get()
            ->map(fn (Pesanan $pesanan) => $this->formatPesanan($pesanan));

        return response()->json([
            'success' => true,
            'data' => [
                'ringkasan' => [
                    'jumlah_pesanan' => Pesanan::where('id_customer', $idCustomer)->count(),
                    'jumlah_menunggu' => Pesanan::where('id_customer', $idCustomer)->where('status_pesanan', 'MENUNGGU_KURIR')->count(),
                    'jumlah_dikirim' => Pesanan::where('id_customer', $idCustomer)->whereIn('status_pesanan', ['DIJEMPUT', 'DALAM_PENGIRIMAN'])->count(),
                    'jumlah_selesai' => Pesanan::where('id_customer', $idCustomer)->where('status_pesanan', 'SELESAI')->count(),
                ],
                'pesanan_terbaru' => $pesananTerbaru,
            ],
        ]);
    }

    public function opsiTarif(Request $request): JsonResponse
    {
        $customer = $this->ensureCustomer($request);
        if ($customer instanceof JsonResponse) {
            return $customer;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kecamatan_asal' => Tarif::select('kecamatan_asal')
                    ->where('approval_status', 'approved')
                    ->where('status_tarif', 'aktif')
                    ->distinct()
                    ->orderBy('kecamatan_asal')
                    ->pluck('kecamatan_asal'),
                'kecamatan_tujuan' => Tarif::select('kecamatan_tujuan')
                    ->where('approval_status', 'approved')
                    ->where('status_tarif', 'aktif')
                    ->distinct()
                    ->orderBy('kecamatan_tujuan')
                    ->pluck('kecamatan_tujuan'),
            ],
        ]);
    }

    public function cekOngkir(Request $request): JsonResponse
    {
        $customer = $this->ensureCustomer($request);
        if ($customer instanceof JsonResponse) {
            return $customer;
        }

        $request->merge([
            'kecamatan_asal' => trim((string) $request->kecamatan_asal),
            'kecamatan_tujuan' => trim((string) $request->kecamatan_tujuan),
        ]);

        $request->validate([
            'kecamatan_asal' => ['required', 'string', 'max:100'],
            'kecamatan_tujuan' => ['required', 'string', 'max:100'],
            'berat' => ['required', 'numeric', 'min:1'],
        ]);

        $tarif = $this->findTarif($request->kecamatan_asal, $request->kecamatan_tujuan);

        if (!$tarif) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif untuk rute tersebut belum tersedia.',
            ], 404);
        }

        $berat = (float) $request->berat;

        return response()->json([
            'success' => true,
            'data' => [
                'kecamatan_asal' => $tarif->kecamatan_asal,
                'kecamatan_tujuan' => $tarif->kecamatan_tujuan,
                'berat' => $berat,
                'harga_per_kg' => (float) $tarif->harga_per_kg,
                'total_harga' => (float) $tarif->harga_per_kg * $berat,
            ],
        ]);
    }

    public function buatPesanan(Request $request): JsonResponse
    {
        $customer = $this->ensureCustomer($request);
        if ($customer instanceof JsonResponse) {
            return $customer;
        }

        $request->merge([
            'nama_pengirim' => trim((string) $request->nama_pengirim),
            'no_hp_pengirim' => preg_replace('/[^0-9]/', '', (string) $request->no_hp_pengirim),
            'alamat_pengirim' => trim((string) $request->alamat_pengirim),
            'nama_penerima' => trim((string) $request->nama_penerima),
            'no_hp_penerima' => preg_replace('/[^0-9]/', '', (string) $request->no_hp_penerima),
            'alamat_penerima' => trim((string) $request->alamat_penerima),
            'kecamatan_asal' => trim((string) $request->kecamatan_asal),
            'kecamatan_tujuan' => trim((string) $request->kecamatan_tujuan),
            'metode_pembayaran' => strtoupper(trim((string) $request->metode_pembayaran)),
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
        ]);

        $tarif = $this->findTarif($request->kecamatan_asal, $request->kecamatan_tujuan);

        if (!$tarif) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak bisa dibuat karena tarif untuk rute tersebut belum tersedia atau belum disetujui.',
            ], 404);
        }

        $kodeResi = $this->generateKodeResi();
        $berat = (float) $request->berat;
        $totalHarga = (float) $tarif->harga_per_kg * $berat;
        $statusPembayaran = $request->metode_pembayaran === 'TRANSFER' ? 'SUDAH_BAYAR' : 'BELUM_DIBAYAR';

        $pesanan = Pesanan::create([
            'kode_resi' => $kodeResi,
            'id_customer' => $customer->id_user,
            'id_kurir' => null,
            'nama_pengirim' => $request->nama_pengirim,
            'no_hp_pengirim' => $request->no_hp_pengirim,
            'alamat_pengirim' => $request->alamat_pengirim,
            'nama_penerima' => $request->nama_penerima,
            'no_hp_penerima' => $request->no_hp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'berat' => $berat,
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'data' => [
                'kode_resi' => $kodeResi,
                'pesanan' => $this->formatPesanan($pesanan->load(['kurir', 'tracking'])),
            ],
        ], 201);
    }

    public function riwayatPesanan(Request $request): JsonResponse
    {
        $customer = $this->ensureCustomer($request);
        if ($customer instanceof JsonResponse) {
            return $customer;
        }

        $q = trim((string) $request->get('q', ''));

        $pesanan = Pesanan::with('kurir')
            ->where('id_customer', $customer->id_user)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('kode_resi', 'like', '%' . $q . '%')
                        ->orWhere('nama_penerima', 'like', '%' . $q . '%')
                        ->orWhere('nama_pengirim', 'like', '%' . $q . '%');
                });
            })
            ->orderBy('id_pesanan', 'desc')
            ->get()
            ->map(fn (Pesanan $pesanan) => $this->formatPesanan($pesanan));

        return response()->json([
            'success' => true,
            'data' => [
                'pesanan' => $pesanan,
            ],
        ]);
    }

    public function tracking(Request $request, string $kode_resi): JsonResponse
    {
        $kodeResi = strtoupper(trim($kode_resi));

        $pesanan = Pesanan::with(['kurir', 'tracking'])
            ->where('kode_resi', $kodeResi)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Kode resi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pesanan' => $this->formatPesanan($pesanan),
            ],
        ]);
    }

    private function ensureCustomer(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Akses hanya untuk customer.',
            ], 403);
        }

        return $user;
    }

    private function findTarif(string $kecamatanAsal, string $kecamatanTujuan): ?Tarif
    {
        return Tarif::whereRaw('LOWER(kecamatan_asal) = ?', [strtolower($kecamatanAsal)])
            ->whereRaw('LOWER(kecamatan_tujuan) = ?', [strtolower($kecamatanTujuan)])
            ->where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->first();
    }

    private function generateKodeResi(): string
    {
        do {
            $kodeResi = 'SK' . now()->format('YmdHis') . Str::upper(Str::random(4));
        } while (Pesanan::where('kode_resi', $kodeResi)->exists());

        return $kodeResi;
    }

    private function formatPesanan(Pesanan $pesanan): array
    {
        return [
            'id_pesanan' => $pesanan->id_pesanan,
            'kode_resi' => $pesanan->kode_resi,
            'id_customer' => $pesanan->id_customer,
            'id_kurir' => $pesanan->id_kurir,
            'kurir' => $pesanan->kurir ? [
                'id_user' => $pesanan->kurir->id_user,
                'nama_lengkap' => $pesanan->kurir->nama_lengkap,
                'no_hp' => $pesanan->kurir->no_hp,
            ] : null,
            'nama_pengirim' => $pesanan->nama_pengirim,
            'no_hp_pengirim' => $pesanan->no_hp_pengirim,
            'alamat_pengirim' => $pesanan->alamat_pengirim,
            'nama_penerima' => $pesanan->nama_penerima,
            'no_hp_penerima' => $pesanan->no_hp_penerima,
            'alamat_penerima' => $pesanan->alamat_penerima,
            'kecamatan_asal' => $pesanan->kecamatan_asal,
            'kecamatan_tujuan' => $pesanan->kecamatan_tujuan,
            'berat' => (float) $pesanan->berat,
            'total_harga' => (float) $pesanan->total_harga,
            'metode_pembayaran' => $pesanan->metode_pembayaran,
            'status_pembayaran' => $pesanan->status_pembayaran,
            'status_pesanan' => $pesanan->status_pesanan,
            'created_at' => optional($pesanan->created_at)->toDateTimeString(),
            'tracking' => $pesanan->relationLoaded('tracking')
                ? $pesanan->tracking->map(fn ($item) => [
                    'id_tracking' => $item->id_tracking,
                    'keterangan' => $item->keterangan,
                    'waktu_update' => optional($item->waktu_update)->toDateTimeString(),
                ])->values()
                : null,
        ];
    }
}
