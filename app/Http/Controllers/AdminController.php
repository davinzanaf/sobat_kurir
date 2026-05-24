<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\RiwayatHapusKurir;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahKurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'AKTIF')
            ->count();

        $jumlahPendaftarKurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->count();

        $jumlahTarif = Tarif::count();

        $jumlahPesanan = Pesanan::count();

        return view('admin.dashboard', compact(
            'jumlahKurir',
            'jumlahPendaftarKurir',
            'jumlahTarif',
            'jumlahPesanan'
        ));
    }

    public function kurir()
    {
        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'AKTIF')
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
        $request->merge([
            'nama_lengkap' => trim($request->nama_lengkap),
            'email' => strtolower(trim($request->email)),
            'no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp),
        ]);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'no_hp' => ['required', 'digits_between:10,13'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus berisi 10 sampai 13 digit angka.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf dan angka.',
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna && $pengguna->status_akun !== 'DITOLAK') {
            return back()
                ->withErrors([
                    'email' => 'Email sudah terdaftar.',
                ])
                ->withInput();
        }

        if ($pengguna && $pengguna->status_akun === 'DITOLAK') {
            $pengguna->nama_lengkap = $request->nama_lengkap;
            $pengguna->email = $request->email;
            $pengguna->no_hp = $request->no_hp;
            $pengguna->password = Hash::make($request->password);
            $pengguna->role = 'kurir';
            $pengguna->status_akun = 'AKTIF';
            $pengguna->alasan_tolak = null;
            $pengguna->approved_at = now();
            $pengguna->approved_by = session('id_user');
            $pengguna->save();

            return redirect()
                ->route('admin.kurir.index')
                ->with('success', 'Kurir berhasil diaktifkan kembali.');
        }

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'kurir',
            'status_akun' => 'AKTIF',
            'alasan_tolak' => null,
            'approved_at' => now(),
            'approved_by' => session('id_user'),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function pendaftarKurir()
    {
        $pendaftar = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->orderBy('id_user', 'desc')
            ->get();

        return view('admin.kurir.pendaftar', compact('pendaftar'));
    }

    public function approveKurir($id)
    {
        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->where('id_user', $id)
            ->firstOrFail();

        $kurir->status_akun = 'AKTIF';
        $kurir->approved_at = now();
        $kurir->approved_by = session('id_user');
        $kurir->alasan_tolak = null;
        $kurir->save();

        return redirect()
            ->route('admin.kurir.pendaftar')
            ->with('success', 'Pendaftar kurir berhasil disetujui.');
    }

    public function rejectKurir(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => ['required', 'string', 'in:Data kurir tidak valid,Dokumen tidak lengkap,Kendaraan tidak memenuhi syarat,Lainnya'],
            'alasan_lainnya' => ['nullable', 'string', 'max:255', 'required_if:alasan_tolak,Lainnya'],
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib dipilih.',
            'alasan_tolak.in' => 'Alasan penolakan tidak valid.',
            'alasan_lainnya.required_if' => 'Detail alasan wajib diisi jika memilih Lainnya.',
            'alasan_lainnya.max' => 'Detail alasan maksimal 255 karakter.',
        ]);

        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'MENUNGGU')
            ->where('id_user', $id)
            ->firstOrFail();

        $alasanFinal = $request->alasan_tolak === 'Lainnya'
            ? trim($request->alasan_lainnya)
            : $request->alasan_tolak;

        $kurir->status_akun = 'DITOLAK';
        $kurir->alasan_tolak = $alasanFinal;
        $kurir->approved_at = null;
        $kurir->approved_by = session('id_user');
        $kurir->save();

        return redirect()
            ->route('admin.kurir.pendaftar')
            ->with('success', 'Pendaftar kurir berhasil ditolak.');
    }

    public function kurirDestroy(Request $request, $id)
    {
        $request->validate([
            'alasan_hapus' => ['required', 'string', 'max:150'],
            'catatan_hapus' => ['nullable', 'string', 'max:255'],
        ], [
            'alasan_hapus.required' => 'Alasan hapus kurir wajib dipilih.',
            'alasan_hapus.max' => 'Alasan hapus maksimal 150 karakter.',
            'catatan_hapus.max' => 'Catatan hapus maksimal 255 karakter.',
        ]);

        $kurir = Pengguna::where('role', 'kurir')
            ->where('id_user', $id)
            ->firstOrFail();

        $alasan = $request->alasan_hapus;

        if ($request->filled('catatan_hapus')) {
            $alasan .= ' - ' . trim($request->catatan_hapus);
        }

        DB::transaction(function () use ($kurir, $alasan) {
            RiwayatHapusKurir::create([
                'id_user_lama' => $kurir->id_user,
                'nama_lengkap' => $kurir->nama_lengkap,
                'email' => $kurir->email,
                'no_hp' => $kurir->no_hp,
                'alasan_hapus' => $alasan,
                'dihapus_oleh_id' => session('id_user'),
                'dihapus_oleh_nama' => session('nama_lengkap'),
            ]);

            Pesanan::where('id_kurir', $kurir->id_user)->update([
                'id_kurir' => null,
                'status_pesanan' => 'MENUNGGU_KURIR',
            ]);

            $kurir->delete();
        });

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil dihapus dan masuk ke riwayat hapus.');
    }

    public function riwayatHapusKurir()
    {
        $riwayat = RiwayatHapusKurir::orderBy('id_riwayat', 'desc')->get();

        return view('admin.kurir.riwayat-hapus', compact('riwayat'));
    }

    public function tarif()
    {
        $tarif = Tarif::orderBy('id_tarif', 'desc')->get();

        return view('admin.tarif.index', compact('tarif'));
    }

    public function tarifCreate()
    {
        return view('admin.tarif.create');
    }

    public function tarifStore(Request $request)
    {
        $request->merge([
            'kecamatan_asal' => trim($request->kecamatan_asal),
            'kecamatan_tujuan' => trim($request->kecamatan_tujuan),
        ]);

        $request->validate([
            'kecamatan_asal' => ['required', 'string', 'max:100'],
            'kecamatan_tujuan' => ['required', 'string', 'max:100'],
            'harga_per_kg' => ['required', 'numeric', 'min:1'],
        ], [
            'kecamatan_asal.required' => 'Kecamatan asal wajib diisi.',
            'kecamatan_tujuan.required' => 'Kecamatan tujuan wajib diisi.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg minimal 1.',
        ]);

        $duplikat = Tarif::whereRaw('LOWER(kecamatan_asal) = ?', [strtolower($request->kecamatan_asal)])
            ->whereRaw('LOWER(kecamatan_tujuan) = ?', [strtolower($request->kecamatan_tujuan)])
            ->exists();

        if ($duplikat) {
            return back()
                ->withErrors([
                    'kecamatan_tujuan' => 'Tarif untuk rute kecamatan asal dan tujuan tersebut sudah terdaftar.',
                ])
                ->withInput();
        }

        Tarif::create([
            'kecamatan_asal' => $request->kecamatan_asal,
            'kecamatan_tujuan' => $request->kecamatan_tujuan,
            'harga_per_kg' => $request->harga_per_kg,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function tarifDestroy($id)
    {
        $tarif = Tarif::where('id_tarif', $id)->firstOrFail();

        $tarif->delete();

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil dihapus.');
    }

    public function pesanan()
    {
        $pesanan = Pesanan::with('kurir')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('admin.pesanan.index', compact('pesanan'));
    }
}
