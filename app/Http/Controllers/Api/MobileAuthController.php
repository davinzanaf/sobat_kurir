<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MobileAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->email)),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if (!in_array($user->role, ['customer', 'kurir'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak tersedia untuk aplikasi mobile.',
            ], 403);
        }

        if ($user->status_akun === 'DITOLAK') {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if ($user->status_akun !== 'AKTIF') {
            $pesan = match ($user->status_akun) {
                'MENUNGGU' => 'Akun Anda masih menunggu persetujuan admin.',
                'NONAKTIF' => 'Akun Anda sedang dinonaktifkan.',
                default => 'Akun tidak aktif.',
            };

            return response()->json([
                'success' => false,
                'message' => $pesan,
            ], 403);
        }

        $tokenName = $request->device_name ?: 'sobat-kurir-mobile';
        $token = $user->createToken($tokenName, [$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $this->userData($user),
            ],
        ]);
    }

    public function registerCustomer(Request $request): JsonResponse
    {
        $request->merge([
            'nama_lengkap' => trim((string) $request->nama_lengkap),
            'email' => strtolower(trim((string) $request->email)),
            'no_hp' => preg_replace('/[^0-9]/', '', (string) $request->no_hp),
            'alamat' => trim((string) $request->alamat),
        ]);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tabel_users', 'email')->whereNull('deleted_at')],
            'no_hp' => ['required', 'digits_between:10,13'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
        ]);

        $user = Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status_akun' => 'AKTIF',
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun customer berhasil dibuat. Silakan login.',
            'data' => [
                'user' => $this->userData($user),
            ],
        ], 201);
    }

    public function registerKurir(Request $request): JsonResponse
    {
        $request->merge([
            'nama_lengkap' => trim((string) $request->nama_lengkap),
            'email' => strtolower(trim((string) $request->email)),
            'no_hp' => preg_replace('/[^0-9]/', '', (string) $request->no_hp),
            'alamat' => trim((string) $request->alamat),
        ]);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'no_hp' => ['required', 'digits_between:10,13'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna && $pengguna->status_akun !== 'DITOLAK') {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar.',
            ], 422);
        }

        if ($pengguna && $pengguna->status_akun === 'DITOLAK') {
            $pengguna->update([
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
        } else {
            $pengguna = Pengguna::create([
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
        }

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran kurir berhasil dikirim. Silakan tunggu persetujuan admin.',
            'data' => [
                'user' => $this->userData($pengguna),
            ],
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->userData($request->user()),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    private function userData(Pengguna $user): array
    {
        return [
            'id_user' => $user->id_user,
            'nama_lengkap' => $user->nama_lengkap,
            'email' => $user->email,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'role' => $user->role,
            'status_akun' => $user->status_akun,
        ];
    }
}
