<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tabel_pesanan')) {
            return;
        }

        if (!Schema::hasColumn('tabel_pesanan', 'status_pembayaran')) {
            return;
        }

        DB::statement("
            ALTER TABLE tabel_pesanan
            MODIFY status_pembayaran VARCHAR(50) NULL
        ");

        DB::table('tabel_pesanan')
            ->whereNull('status_pembayaran')
            ->orWhere('status_pembayaran', '')
            ->update([
                'status_pembayaran' => 'BELUM_DIBAYAR',
            ]);

        DB::table('tabel_pesanan')
            ->whereIn('status_pembayaran', [
                'BELUM_BAYAR',
                'BELUM BAYAR',
                'BELUM_DIBAYAR',
                'belum_bayar',
                'belum bayar',
                'belum_dibayar',
                'Belum Bayar',
                'Belum Dibayar',
            ])
            ->update([
                'status_pembayaran' => 'BELUM_DIBAYAR',
            ]);

        DB::table('tabel_pesanan')
            ->whereIn('status_pembayaran', [
                'SUDAH_BAYAR',
                'SUDAH BAYAR',
                'SUDAH_DIBAYAR',
                'sudah_bayar',
                'sudah bayar',
                'sudah_dibayar',
                'Sudah Bayar',
                'Sudah Dibayar',
                'LUNAS',
                'lunas',
            ])
            ->update([
                'status_pembayaran' => 'SUDAH_BAYAR',
            ]);

        DB::table('tabel_pesanan')
            ->whereNotIn('status_pembayaran', [
                'BELUM_DIBAYAR',
                'SUDAH_BAYAR',
            ])
            ->update([
                'status_pembayaran' => 'BELUM_DIBAYAR',
            ]);

        DB::statement("
            ALTER TABLE tabel_pesanan
            MODIFY status_pembayaran ENUM('BELUM_DIBAYAR', 'SUDAH_BAYAR')
            NOT NULL DEFAULT 'BELUM_DIBAYAR'
        ");
    }

    public function down(): void
    {
        if (!Schema::hasTable('tabel_pesanan')) {
            return;
        }

        if (!Schema::hasColumn('tabel_pesanan', 'status_pembayaran')) {
            return;
        }

        DB::statement("
            ALTER TABLE tabel_pesanan
            MODIFY status_pembayaran VARCHAR(50) NULL
        ");
    }
};
