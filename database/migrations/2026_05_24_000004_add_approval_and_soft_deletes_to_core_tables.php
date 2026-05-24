<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tabel_users')) {
            if (!Schema::hasColumn('tabel_users', 'approval_status')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->string('approval_status', 30)->default('approved')->after('status_akun');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'approved_at')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->timestamp('approved_at')->nullable()->after('approval_status');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'approved_by')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'rejected_at')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->timestamp('rejected_at')->nullable()->after('approved_by');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'rejected_by')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'rejected_reason')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->text('rejected_reason')->nullable()->after('rejected_by');
                });
            }

            if (!Schema::hasColumn('tabel_users', 'deleted_at')) {
                Schema::table('tabel_users', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            DB::table('tabel_users')
                ->whereNull('approval_status')
                ->update([
                    'approval_status' => 'approved',
                ]);
        }

        if (Schema::hasTable('tabel_tarif')) {
            if (!Schema::hasColumn('tabel_tarif', 'approval_status')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->string('approval_status', 30)->default('approved')->after('harga_per_kg');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'status_tarif')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->string('status_tarif', 30)->default('aktif')->after('approval_status');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'created_by')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('status_tarif');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'approved_at')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->timestamp('approved_at')->nullable()->after('created_by');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'approved_by')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'rejected_at')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->timestamp('rejected_at')->nullable()->after('approved_by');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'rejected_by')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'rejected_reason')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->text('rejected_reason')->nullable()->after('rejected_by');
                });
            }

            if (!Schema::hasColumn('tabel_tarif', 'deleted_at')) {
                Schema::table('tabel_tarif', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            DB::table('tabel_tarif')
                ->whereNull('approval_status')
                ->update([
                    'approval_status' => 'approved',
                    'status_tarif' => 'aktif',
                ]);
        }

        if (Schema::hasTable('tabel_pesanan')) {
            if (!Schema::hasColumn('tabel_pesanan', 'deleted_at')) {
                Schema::table('tabel_pesanan', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tabel_users')) {
            $columns = [
                'approval_status',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejected_reason',
                'deleted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tabel_users', $column)) {
                    Schema::table('tabel_users', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }

        if (Schema::hasTable('tabel_tarif')) {
            $columns = [
                'approval_status',
                'status_tarif',
                'created_by',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejected_reason',
                'deleted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tabel_tarif', $column)) {
                    Schema::table('tabel_tarif', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }

        if (Schema::hasTable('tabel_pesanan')) {
            if (Schema::hasColumn('tabel_pesanan', 'deleted_at')) {
                Schema::table('tabel_pesanan', function (Blueprint $table) {
                    $table->dropColumn('deleted_at');
                });
            }
        }
    }
};
