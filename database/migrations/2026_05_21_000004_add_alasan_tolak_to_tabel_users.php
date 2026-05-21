<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tabel_users', 'alasan_tolak')) {
            Schema::table('tabel_users', function (Blueprint $table) {
                $table->text('alasan_tolak')->nullable()->after('alasan_ditolak');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tabel_users', 'alasan_tolak')) {
            Schema::table('tabel_users', function (Blueprint $table) {
                $table->dropColumn('alasan_tolak');
            });
        }
    }
};
