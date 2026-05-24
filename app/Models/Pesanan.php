<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $table = 'tabel_pesanan';

    protected $primaryKey = 'id_pesanan';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'kode_resi',
        'id_customer',
        'id_kurir',
        'nama_pengirim',
        'no_hp_pengirim',
        'alamat_pengirim',
        'nama_penerima',
        'no_hp_penerima',
        'alamat_penerima',
        'berat',
        'total_harga',
        'metode_pembayaran',
        'status_pembayaran',
        'status_pesanan',
        'kecamatan_asal',
        'kecamatan_tujuan',
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'berat' => 'integer',
        'total_harga' => 'integer',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function kurir(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_kurir', 'id_user');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_customer', 'id_user');
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(Tracking::class, 'id_pesanan', 'id_pesanan');
    }
}
