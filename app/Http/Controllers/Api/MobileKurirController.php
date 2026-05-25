<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Tracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileKurirController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $kurir = $this->ensureKurir($request);
        if ($kurir instanceof JsonResponse) {
            return $kurir;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ringkasan' => [
                    'jumlah_tugas_baru' => Pesanan::whereNull('id_kurir')
                        ->where('status_pesanan', 'MENUNGGU_KURIR')
                        ->count(),
                    'jumlah_pesanan_saya' => Pesanan::where('id_kurir', $kurir->id_user)
                        ->whereIn('status_pesanan', ['DIJEMPUT', 'DALAM_PENGIRIMAN'])
                        ->count(),
                ],
            ],
        ]);
    }

    public function tugasBaru(Request $request): JsonResponse
    {
        $kurir = $this->ensureKurir($request);
        if ($kurir instanceof JsonResponse) {
            return $kurir;
        }

        $pesanan = Pesanan::whereNull('id_kurir')
            ->where('status_pesanan', 'MENUNGGU_KURIR')
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

    public function ambilPesanan(Request $request, int $id): JsonResponse
    {
        $kurir = $this->ensureKurir($request);
        if ($kurir instanceof JsonResponse) {
            return $kurir;
        }

        $pesananDiambil = null;

        DB::transaction(function () use ($id, $kurir, &$pesananDiambil) {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->whereNull('id_kurir')
                ->where('status_pesanan', 'MENUNGGU_KURIR')
                ->lockForUpdate()
                ->first();

            if (!$pesanan) {
                return;
            }

            $pesanan->id_kurir = $kurir->id_user;
            $pesanan->status_pesanan = 'DIJEMPUT';
            $pesanan->save();

            Tracking::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'keterangan' => 'Pesanan telah diambil oleh kurir.',
                'waktu_update' => now(),
            ]);

            $pesananDiambil = $pesanan->load(['customer', 'tracking']);
        });

        if (!$pesananDiambil) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah diambil oleh kurir lain atau sudah tidak tersedia.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diambil.',
            'data' => [
                'pesanan' => $this->formatPesanan($pesananDiambil),
            ],
        ]);
    }

    public function pesananSaya(Request $request): JsonResponse
    {
        $kurir = $this->ensureKurir($request);
        if ($kurir instanceof JsonResponse) {
            return $kurir;
        }

        $pesanan = Pesanan::where('id_kurir', $kurir->id_user)
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

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $kurir = $this->ensureKurir($request);
        if ($kurir instanceof JsonResponse) {
            return $kurir;
        }

        $request->validate([
            'status_pesanan' => ['required', 'in:DIJEMPUT,DALAM_PENGIRIMAN,SELESAI'],
        ]);

        $pesananUpdate = null;

        DB::transaction(function () use ($request, $id, $kurir, &$pesananUpdate) {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->where('id_kurir', $kurir->id_user)
                ->lockForUpdate()
                ->first();

            if (!$pesanan) {
                return;
            }

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
            };

            Tracking::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'keterangan' => $keterangan,
                'waktu_update' => now(),
            ]);

            $pesananUpdate = $pesanan->load(['customer', 'tracking']);
        });

        if (!$pesananUpdate) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan untuk kurir ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'data' => [
                'pesanan' => $this->formatPesanan($pesananUpdate),
            ],
        ]);
    }

    private function ensureKurir(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'kurir') {
            return response()->json([
                'success' => false,
                'message' => 'Akses hanya untuk kurir.',
            ], 403);
        }

        return $user;
    }

    private function formatPesanan(Pesanan $pesanan): array
    {
        return [
            'id_pesanan' => $pesanan->id_pesanan,
            'kode_resi' => $pesanan->kode_resi,
            'id_customer' => $pesanan->id_customer,
            'id_kurir' => $pesanan->id_kurir,
            'customer' => $pesanan->relationLoaded('customer') && $pesanan->customer ? [
                'id_user' => $pesanan->customer->id_user,
                'nama_lengkap' => $pesanan->customer->nama_lengkap,
                'no_hp' => $pesanan->customer->no_hp,
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
