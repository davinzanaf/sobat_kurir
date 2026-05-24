<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
            ->where('approval_status', 'approved')
            ->count();

        $jumlahPendaftarKurir = Pengguna::where('role', 'kurir')
            ->whereIn('status_akun', ['MENUNGGU'])
            ->count();

        $jumlahTarif = Tarif::where('approval_status', 'approved')
            ->where('status_tarif', 'aktif')
            ->count();

        $jumlahPesanan = Pesanan::count();

        return view('admin.dashboard', compact(
            'jumlahKurir',
            'jumlahPendaftarKurir',
            'jumlahTarif',
            'jumlahPesanan'
        ));
    }

    public function kurir(Request $request)
    {
        $q = trim($request->get('q', ''));

        $kurir = Pengguna::where('role', 'kurir')
            ->where('status_akun', 'AKTIF')
            ->where('approval_status', 'approved')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('nama_lengkap', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('no_hp', 'like', '%' . $q . '%');
                });
            })
            ->orderBy('id_user', 'desc')
            ->get();

        return view('admin.kurir.index', compact('kurir', 'q'));
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

        $pengguna = Pengguna::withTrashed()
            ->where('email', $request->email)
            ->first();

        if ($pengguna && $pengguna->approval_status !== 'rejected' && $pengguna->status_akun !== 'DITOLAK') {
            return back()
                ->withErrors([
                    'email' => 'Email sudah terdaftar.',
                ])
                ->withInput();
        }

        if ($pengguna && ($pengguna->approval_status === 'rejected' || $pengguna->status_akun === 'DITOLAK')) {
            $pengguna->restore();

            $pengguna->nama_lengkap = $request->nama_lengkap;
            $pengguna->email = $request->email;
            $pengguna->no_hp = $request->no_hp;
            $pengguna->password = Hash::make($request->password);
            $pengguna->role = 'kurir';
            $pengguna->status_akun = 'MENUNGGU';
            $pengguna->approval_status = 'pending_approval';
            $pengguna->alasan_tolak = null;
            $pengguna->approved_at = null;
            $pengguna->approved_by = null;
            $pengguna->rejected_at = null;
            $pengguna->rejected_by = null;
            $pengguna->rejected_reason = null;
            $pengguna->created_at = now();
            $pengguna->save();

            ActivityLog::record(
                'REQUEST_KURIR_APPROVAL',
                'Admin ' . session('nama_lengkap') . ' mengajukan ulang kurir ' . $pengguna->nama_lengkap . ' untuk approval.'
            );

            return redirect()
                ->route('admin.kurir.index')
                ->with('success', 'Kurir berhasil diajukan ulang dan menunggu approval supervisor/owner.');
        }

        $kurir = Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'kurir',
            'status_akun' => 'MENUNGGU',
            'approval_status' => 'pending_approval',
            'alasan_tolak' => null,
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => null,
            'rejected_by' => null,
            'rejected_reason' => null,
            'created_at' => now(),
        ]);

        ActivityLog::record(
            'REQUEST_KURIR_APPROVAL',
            'Admin ' . session('nama_lengkap') . ' menambahkan kurir baru ' . $kurir->nama_lengkap . ' dan menunggu approval supervisor/owner.'
        );

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Kurir berhasil diajukan. Status menunggu approval supervisor/owner.');
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

        $kurir->approval_status = 'pending_approval';
        $kurir->status_akun = 'MENUNGGU';
        $kurir->approved_at = null;
        $kurir->approved_by = null;
        $kurir->alasan_tolak = null;
        $kurir->save();

        ActivityLog::record(
            'REQUEST_KURIR_APPROVAL',
            'Admin ' . session('nama_lengkap') . ' mengirim pendaftar kurir ' . $kurir->nama_lengkap . ' ke antrean approval supervisor/owner.'
        );

        return redirect()
            ->route('admin.kurir.pendaftar')
            ->with('success', 'Pendaftar kurir dikirim ke antrean approval supervisor/owner.');
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
        $kurir->approval_status = 'rejected';
        $kurir->alasan_tolak = $alasanFinal;
        $kurir->rejected_at = now();
        $kurir->rejected_by = session('id_user');
        $kurir->rejected_reason = $alasanFinal;
        $kurir->approved_at = null;
        $kurir->approved_by = null;
        $kurir->save();

        ActivityLog::record(
            'REJECT_KURIR_BY_ADMIN',
            'Admin ' . session('nama_lengkap') . ' menolak pendaftar kurir ' . $kurir->nama_lengkap . '. Alasan: ' . $alasanFinal
        );

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

            $namaKurir = $kurir->nama_lengkap;
            $kurir->delete();

            ActivityLog::record(
                'SOFT_DELETE_KURIR',
                'Admin ' . session('nama_lengkap') . ' menghapus kurir ' . $namaKurir . '. Alasan: ' . $alasan
            );
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

    public function tarif(Request $request)
    {
        $q = trim($request->get('q', ''));

        $tarif = Tarif::when($q !== '', function ($query) use ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('kecamatan_asal', 'like', '%' . $q . '%')
                    ->orWhere('kecamatan_tujuan', 'like', '%' . $q . '%');
            });
        })
            ->orderBy('id_tarif', 'desc')
            ->get();

        return view('admin.tarif.index', compact('tarif', 'q'));
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
            ->whereIn('approval_status', ['approved', 'pending_approval'])
            ->exists();

        if ($duplikat) {
            return back()
                ->withErrors([
                    'kecamatan_tujuan' => 'Tarif untuk rute kecamatan asal dan tujuan tersebut sudah terdaftar atau sedang menunggu approval.',
                ])
                ->withInput();
        }

        $tarif = Tarif::create([
            'kecamatan_asal' => $request->kecamatan_asal,
            'kecamatan_tujuan' => $request->kecamatan_tujuan,
            'harga_per_kg' => $request->harga_per_kg,
            'approval_status' => 'pending_approval',
            'status_tarif' => 'menunggu_approval',
            'created_by' => session('id_user'),
            'approved_at' => null,
            'approved_by' => null,
            'rejected_at' => null,
            'rejected_by' => null,
            'rejected_reason' => null,
        ]);

        ActivityLog::record(
            'REQUEST_TARIF_APPROVAL',
            'Admin ' . session('nama_lengkap') . ' menambahkan tarif ' . $tarif->kecamatan_asal . ' ke ' . $tarif->kecamatan_tujuan . ' dan menunggu approval supervisor/owner.'
        );

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil diajukan. Status menunggu approval supervisor/owner.');
    }

    public function tarifDestroy($id)
    {
        $tarif = Tarif::where('id_tarif', $id)->firstOrFail();

        $rute = $tarif->kecamatan_asal . ' ke ' . $tarif->kecamatan_tujuan;

        $tarif->delete();

        ActivityLog::record(
            'SOFT_DELETE_TARIF',
            'Admin ' . session('nama_lengkap') . ' menghapus tarif ' . $rute . '.'
        );

        return redirect()
            ->route('admin.tarif.index')
            ->with('success', 'Tarif berhasil dihapus.');
    }

    public function pesanan(Request $request)
    {
        $q = trim($request->get('q', ''));

        $pesanan = Pesanan::with('kurir')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('kode_resi', 'like', '%' . $q . '%')
                        ->orWhere('nama_penerima', 'like', '%' . $q . '%')
                        ->orWhere('nama_pengirim', 'like', '%' . $q . '%')
                        ->orWhere('no_hp_penerima', 'like', '%' . $q . '%');
                });
            })
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('admin.pesanan.index', compact('pesanan', 'q'));
    }
}
