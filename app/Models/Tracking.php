<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tabel_tracking';

    protected $primaryKey = 'id_tracking';

    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'keterangan',
    ];

    protected $casts = [
        'waktu_update' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}