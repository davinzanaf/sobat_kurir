<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabel_hapus_kurir', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->unsignedBigInteger('id_user_lama')->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100);
            $table->string('no_hp', 20)->nullable();
            $table->text('alasan_hapus');
            $table->unsignedBigInteger('dihapus_oleh_id')->nullable();
            $table->string('dihapus_oleh_nama', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_hapus_kurir');
    }
};
