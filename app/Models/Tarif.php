<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarif extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tabel_tarif';

    protected $primaryKey = 'id_tarif';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'kecamatan_asal',
        'kecamatan_tujuan',
        'harga_per_kg',
        'approval_status',
        'status_tarif',
        'created_by',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejected_reason',
    ];

    protected $casts = [
        'harga_per_kg' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
