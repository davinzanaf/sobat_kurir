<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatHapusKurir extends Model
{
    protected $table = 'tabel_hapus_kurir';

    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_user_lama',
        'nama_lengkap',
        'email',
        'no_hp',
        'alasan_hapus',
        'dihapus_oleh_id',
        'dihapus_oleh_nama',
    ];
}
