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
        $request->merge([
            'nama_lengkap' => trim($request->nama_lengkap),
            'email' => strtolower(trim($request->email)),
            'no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp),
            'alamat' => trim($request->alamat),
        ]);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'no_hp' => ['required', 'digits_between:10,13'],
            'alamat' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus berisi 10 sampai 13 digit angka.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.regex' => 'Password harus mengandung huruf dan angka.',
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna) {
            return back()
                ->withErrors([
                    'email' => 'Email sudah terdaftar.',
                ])
                ->withInput();
        }

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
        $request->merge([
            'nama_lengkap' => trim($request->nama_lengkap),
            'email' => strtolower(trim($request->email)),
            'no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp),
            'alamat' => trim($request->alamat),
        ]);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'no_hp' => ['required', 'digits_between:10,13'],
            'alamat' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus berisi 10 sampai 13 digit angka.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
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
            $pengguna->alamat = $request->alamat;
            $pengguna->password = Hash::make($request->password);
            $pengguna->role = 'kurir';
            $pengguna->status_akun = 'MENUNGGU';
            $pengguna->alasan_tolak = null;
            $pengguna->approved_at = null;
            $pengguna->approved_by = null;
            $pengguna->created_at = now();
            $pengguna->save();

            return redirect()
                ->route('login')
                ->with('success', 'Pendaftaran kurir berhasil dikirim ulang. Silakan tunggu persetujuan admin.');
        }

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'kurir',
            'status_akun' => 'MENUNGGU',
            'alasan_tolak' => null,
            'approved_at' => null,
            'approved_by' => null,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran kurir berhasil dikirim. Silakan tunggu persetujuan admin.');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->withInput();
        }

        if ($user->status_akun === 'DITOLAK') {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->withInput();
        }

        if ($user->status_akun !== 'AKTIF') {
            $pesan = match ($user->status_akun) {
                'MENUNGGU' => 'Akun Anda masih menunggu persetujuan admin.',
                'NONAKTIF' => 'Akun Anda sedang dinonaktifkan.',
                default => 'Email atau password salah.',
            };

            return back()
                ->withErrors([
                    'email' => $pesan,
                ])
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
                'email' => 'Email atau password salah.',
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
