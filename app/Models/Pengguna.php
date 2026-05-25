<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Model
{
    use HasApiTokens;
    use SoftDeletes;

    protected $table = 'tabel_users';

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_hp',
        'alamat',
        'password',
        'role',
        'status_akun',
        'approval_status',
        'alasan_tolak',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejected_reason',
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function pesananKurir(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_kurir', 'id_user');
    }

    public function pesananCustomer(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_customer', 'id_user');
    }
}
