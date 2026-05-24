<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'owner@sobatkurir.com',
            ],
            [
                'nama_lengkap' => 'Owner Sobat Kurir',
                'no_hp' => '081234567890',
                'alamat' => 'Kantor Pusat Sobat Kurir',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'status_akun' => 'AKTIF',
                'alasan_tolak' => null,
                'approved_at' => now(),
                'approved_by' => null,
                'created_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'spv@sobatkurir.com',
            ],
            [
                'nama_lengkap' => 'Supervisor Sobat Kurir',
                'no_hp' => '081234567891',
                'alamat' => 'Kantor Operasional Sobat Kurir',
                'password' => Hash::make('password123'),
                'role' => 'supervisor',
                'status_akun' => 'AKTIF',
                'alasan_tolak' => null,
                'approved_at' => now(),
                'approved_by' => null,
                'created_at' => now(),
            ]
        );
    }
}
