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

        $statusAkun = $user->status_akun ?? 'AKTIF';

        if ($statusAkun !== 'AKTIF') {
            return back()
                ->withErrors(['email' => 'Akun belum aktif atau masih menunggu persetujuan admin.'])
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
