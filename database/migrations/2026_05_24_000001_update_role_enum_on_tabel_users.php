<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getUsersTable(): string
    {
        if (Schema::hasTable('tabel_users')) {
            return 'tabel_users';
        }

        return 'users';
    }

    public function up(): void
    {
        $table = $this->getUsersTable();

        if (!Schema::hasTable($table)) {
            return;
        }

        if (Schema::hasColumn($table, 'role')) {
            DB::statement("
                ALTER TABLE {$table}
                MODIFY role ENUM('customer', 'kurir', 'admin', 'owner', 'supervisor')
                NOT NULL DEFAULT 'customer'
            ");
        }
    }

    public function down(): void
    {
        $table = $this->getUsersTable();

        if (!Schema::hasTable($table)) {
            return;
        }

        if (Schema::hasColumn($table, 'role')) {
            DB::table($table)
                ->whereIn('role', ['owner', 'supervisor'])
                ->update([
                    'role' => 'admin',
                ]);

            DB::statement("
                ALTER TABLE {$table}
                MODIFY role ENUM('customer', 'kurir', 'admin')
                NOT NULL DEFAULT 'customer'
            ");
        }
    }
};
