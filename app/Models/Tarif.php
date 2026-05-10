<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 'tabel_tarif';

    protected $primaryKey = 'id_tarif';

    public $timestamps = false;

    protected $fillable = [
        'kecamatan_asal',
        'kecamatan_tujuan',
        'harga_per_kg',
        'created_at',
    ];
}
