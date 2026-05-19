<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tabel_users MODIFY role ENUM('admin', 'kurir', 'customer') NOT NULL");

        Schema::table('tabel_users', function (Blueprint $table) {
            $table->enum('status_akun', ['AKTIF', 'MENUNGGU', 'DITOLAK', 'NONAKTIF'])
                ->default('AKTIF')
                ->after('role');

            $table->text('alamat')->nullable()->after('no_hp');
            $table->text('alasan_ditolak')->nullable()->after('status_akun');
            $table->timestamp('approved_at')->nullable()->after('alasan_ditolak');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('tabel_users', function (Blueprint $table) {
            $table->dropColumn([
                'status_akun',
                'alamat',
                'alasan_ditolak',
                'approved_at',
                'approved_by',
            ]);
        });

        DB::statement("ALTER TABLE tabel_users MODIFY role ENUM('admin', 'kurir') NOT NULL");
    }
};
