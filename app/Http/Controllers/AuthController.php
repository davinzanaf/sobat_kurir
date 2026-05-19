<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.login');
    }

    public function showKurirLogin()
    {
        return view('auth.login');
    }

    public function showRegisterCustomer()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:tabel_users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status_akun' => 'AKTIF',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Akun customer berhasil dibuat. Silakan login.');
    }

    public function showDaftarKurir()
    {
        return view('auth.daftar-kurir');
    }

    public function daftarKurir(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:tabel_users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'kurir',
            'status_akun' => 'MENUNGGU',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran kurir berhasil dikirim. Silakan tunggu persetujuan admin.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput();
        }

        if ($user->status_akun !== 'AKTIF') {
            $pesan = match ($user->status_akun) {
                'MENUNGGU' => 'Akun Anda masih menunggu persetujuan admin.',
                'DITOLAK' => 'Pendaftaran akun Anda ditolak. Alasan: ' . ($user->alasan_ditolak ?? '-'),
                'NONAKTIF' => 'Akun Anda sedang dinonaktifkan.',
                default => 'Akun Anda belum bisa digunakan.',
            };

            return back()
                ->withErrors(['email' => $pesan])
                ->withInput();
        }

        $request->session()->regenerate();

        session([
            'id_user' => $user->id_user,
            'role' => $user->role,
            'nama_lengkap' => $user->nama_lengkap,
        ]);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kurir' => redirect()->route('kurir.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => back()->withErrors([
                'email' => 'Role akun tidak dikenali.',
            ]),
        };
    }

    public function adminLogin(Request $request)
    {
        return $this->login($request);
    }

    public function kurirLogin(Request $request)
    {
        return $this->login($request);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
