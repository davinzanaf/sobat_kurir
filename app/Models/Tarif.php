<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tabel_tarif';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'kecamatan_asal',
        'kecamatan_tujuan',
        'harga_per_kg',
    ];

    protected $casts = [
        'harga_per_kg' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
