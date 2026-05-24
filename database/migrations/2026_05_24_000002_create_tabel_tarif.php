<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName = 'tabel_tarif';

    public function up(): void
    {
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('kecamatan_asal', 100);
                $table->string('kecamatan_tujuan', 100);
                $table->unsignedInteger('harga_per_kg');
                $table->timestamps();

                $table->unique(
                    ['kecamatan_asal', 'kecamatan_tujuan'],
                    'tabel_tarif_rute_unique'
                );
            });

            return;
        }

        Schema::table($this->tableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->tableName, 'kecamatan_asal')) {
                $table->string('kecamatan_asal', 100)->after('id');
            }

            if (!Schema::hasColumn($this->tableName, 'kecamatan_tujuan')) {
                $table->string('kecamatan_tujuan', 100)->after('kecamatan_asal');
            }

            if (!Schema::hasColumn($this->tableName, 'harga_per_kg')) {
                $table->unsignedInteger('harga_per_kg')->after('kecamatan_tujuan');
            }

            if (!Schema::hasColumn($this->tableName, 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn($this->tableName, 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
