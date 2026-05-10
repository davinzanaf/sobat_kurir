<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        return $this->login($request, 'admin', 'admin.dashboard');
    }

    public function showKurirLogin()
    {
        return view('auth.kurir-login');
    }

    public function kurirLogin(Request $request)
    {
        return $this->login($request, 'kurir', 'kurir.dashboard');
    }

    private function login(Request $request, string $role, string $redirectRoute)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = Pengguna::where('email', $request->email)
            ->where('role', $role)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput();
        }

        $request->session()->regenerate();

        session([
            'id_user' => $user->id_user,
            'role' => $user->role,
            'nama_lengkap' => $user->nama_lengkap,
        ]);

        return redirect()->route($redirectRoute);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
