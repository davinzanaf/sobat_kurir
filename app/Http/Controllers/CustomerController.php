<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
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
            'kode_resi' => ['required', 'string', 'max:20'],
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