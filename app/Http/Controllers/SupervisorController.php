<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Tarif;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        $kinerjaKurir = User::where('role', 'kurir')
            ->where('approval_status', 'approved')
            ->withCount([
                'pesananKurir as paket_total',
                'pesananKurir as paket_sukses' => function ($query) {
                    $query->where('status_pesanan', 'SELESAI');
                },
                'pesananKurir as paket_gagal' => function ($query) {
                    $query->whereIn('status_pesanan', ['GAGAL', 'DIBATALKAN']);
                },
            ])
            ->orderBy('nama_lengkap')
            ->get();

        $pendingTarif = Tarif::where('approval_status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingKurir = User::where('role', 'kurir')
            ->where('approval_status', 'pending_approval')
            ->orderBy('id_user', 'desc')
            ->get();

        $logs = ActivityLog::with('user')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return view('supervisor.dashboard', compact(
            'kinerjaKurir',
            'pendingTarif',
            'pendingKurir',
            'logs'
        ));
    }

    public function approveAction(string $type, int $id)
    {
        if ($type === 'tarif') {
            $tarif = Tarif::where('id_tarif', $id)
                ->where('approval_status', 'pending_approval')
                ->firstOrFail();

            $tarif->approval_status = 'approved';
            $tarif->status_tarif = 'aktif';
            $tarif->approved_at = now();
            $tarif->approved_by = session('id_user');
            $tarif->rejected_at = null;
            $tarif->rejected_by = null;
            $tarif->rejected_reason = null;
            $tarif->save();

            ActivityLog::record(
                'APPROVE_TARIF',
                session('role') . ' ' . session('nama_lengkap') . ' menyetujui tarif ' . $tarif->kecamatan_asal . ' ke ' . $tarif->kecamatan_tujuan . '.'
            );

            return redirect()
                ->route('supervisor.dashboard')
                ->with('success', 'Tarif berhasil disetujui.');
        }

        if ($type === 'kurir') {
            $kurir = User::where('id_user', $id)
                ->where('role', 'kurir')
                ->where('approval_status', 'pending_approval')
                ->firstOrFail();

            $kurir->approval_status = 'approved';
            $kurir->status_akun = 'AKTIF';
            $kurir->approved_at = now();
            $kurir->approved_by = session('id_user');
            $kurir->rejected_at = null;
            $kurir->rejected_by = null;
            $kurir->rejected_reason = null;
            $kurir->alasan_tolak = null;
            $kurir->save();

            ActivityLog::record(
                'APPROVE_KURIR',
                session('role') . ' ' . session('nama_lengkap') . ' menyetujui kurir ' . $kurir->nama_lengkap . '.'
            );

            return redirect()
                ->route('supervisor.dashboard')
                ->with('success', 'Kurir berhasil disetujui.');
        }

        return redirect()
            ->route('supervisor.dashboard')
            ->withErrors([
                'approval' => 'Jenis approval tidak valid.',
            ]);
    }

    public function rejectAction(Request $request, string $type, int $id)
    {
        $request->validate([
            'alasan_reject' => ['nullable', 'string', 'max:255'],
        ], [
            'alasan_reject.max' => 'Alasan penolakan maksimal 255 karakter.',
        ]);

        $alasan = $request->filled('alasan_reject')
            ? trim($request->alasan_reject)
            : 'Ditolak oleh supervisor atau owner.';

        if ($type === 'tarif') {
            $tarif = Tarif::where('id_tarif', $id)
                ->where('approval_status', 'pending_approval')
                ->firstOrFail();

            $tarif->approval_status = 'rejected';
            $tarif->status_tarif = 'ditolak';
            $tarif->rejected_at = now();
            $tarif->rejected_by = session('id_user');
            $tarif->rejected_reason = $alasan;
            $tarif->save();

            ActivityLog::record(
                'REJECT_TARIF',
                session('role') . ' ' . session('nama_lengkap') . ' menolak tarif ' . $tarif->kecamatan_asal . ' ke ' . $tarif->kecamatan_tujuan . '. Alasan: ' . $alasan
            );

            return redirect()
                ->route('supervisor.dashboard')
                ->with('success', 'Tarif berhasil ditolak.');
        }

        if ($type === 'kurir') {
            $kurir = User::where('id_user', $id)
                ->where('role', 'kurir')
                ->where('approval_status', 'pending_approval')
                ->firstOrFail();

            $kurir->approval_status = 'rejected';
            $kurir->status_akun = 'DITOLAK';
            $kurir->alasan_tolak = $alasan;
            $kurir->rejected_at = now();
            $kurir->rejected_by = session('id_user');
            $kurir->rejected_reason = $alasan;
            $kurir->save();

            ActivityLog::record(
                'REJECT_KURIR',
                session('role') . ' ' . session('nama_lengkap') . ' menolak kurir ' . $kurir->nama_lengkap . '. Alasan: ' . $alasan
            );

            return redirect()
                ->route('supervisor.dashboard')
                ->with('success', 'Kurir berhasil ditolak.');
        }

        return redirect()
            ->route('supervisor.dashboard')
            ->withErrors([
                'approval' => 'Jenis approval tidak valid.',
            ]);
    }

    public function cetakKinerjaPdf()
    {
        $kinerjaKurir = User::where('role', 'kurir')
            ->where('approval_status', 'approved')
            ->withCount([
                'pesananKurir as paket_total',
                'pesananKurir as paket_sukses' => function ($query) {
                    $query->where('status_pesanan', 'SELESAI');
                },
                'pesananKurir as paket_gagal' => function ($query) {
                    $query->whereIn('status_pesanan', ['GAGAL', 'DIBATALKAN']);
                },
            ])
            ->orderBy('nama_lengkap')
            ->get();

        ActivityLog::record(
            'EXPORT_PDF_KINERJA',
            session('role') . ' ' . session('nama_lengkap') . ' mencetak laporan kinerja kurir.'
        );

        $pdf = Pdf::loadView('supervisor.laporan-kinerja-pdf', compact('kinerjaKurir'));

        return $pdf->download('laporan-kinerja-kurir-sobat-kurir.pdf');
    }
}
