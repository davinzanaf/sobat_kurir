<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $tahun = now()->year;

        $pendapatan = Pesanan::query()
            ->selectRaw('MONTH(created_at) as bulan, SUM(total_harga) as total')
            ->whereYear('created_at', $tahun)
            ->where('status_pesanan', 'SELESAI')
            ->where('status_pembayaran', 'SUDAH_BAYAR')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $data = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $labels[] = Carbon::create($tahun, $bulan, 1)->translatedFormat('M');

            $row = $pendapatan->firstWhere('bulan', $bulan);
            $data[] = $row ? (int) $row->total : 0;
        }

        $totalPendapatan = array_sum($data);

        $jumlahPesananSelesai = Pesanan::where('status_pesanan', 'SELESAI')->count();

        $jumlahPesananAktif = Pesanan::whereIn('status_pesanan', [
            'MENUNGGU_KURIR',
            'DIJEMPUT',
            'DALAM_PENGIRIMAN',
        ])->count();

        $logs = ActivityLog::with('user')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return view('owner.dashboard', compact(
            'tahun',
            'labels',
            'data',
            'totalPendapatan',
            'jumlahPesananSelesai',
            'jumlahPesananAktif',
            'logs'
        ));
    }

    public function cetakLaporanPdf()
    {
        $tahun = now()->year;

        $pendapatanBulanan = Pesanan::query()
            ->selectRaw('MONTH(created_at) as bulan, SUM(total_harga) as total, COUNT(*) as jumlah')
            ->whereYear('created_at', $tahun)
            ->where('status_pesanan', 'SELESAI')
            ->where('status_pembayaran', 'SUDAH_BAYAR')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        $totalPendapatan = $pendapatanBulanan->sum('total');
        $totalPesanan = $pendapatanBulanan->sum('jumlah');

        ActivityLog::record(
            'EXPORT_PDF_KEUANGAN',
            'Owner ' . session('nama_lengkap') . ' mencetak laporan keuangan tahun ' . $tahun . '.'
        );

        $pdf = Pdf::loadView('owner.laporan-keuangan-pdf', compact(
            'tahun',
            'pendapatanBulanan',
            'totalPendapatan',
            'totalPesanan'
        ));

        return $pdf->download('laporan-keuangan-sobat-kurir-' . $tahun . '.pdf');
    }
}
