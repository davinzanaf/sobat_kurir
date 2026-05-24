<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'tabel_users';

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

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

    protected $hidden = [
        'password',
        'remember_token',
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

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id_user');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKurir(): bool
    {
        return $this->role === 'kurir';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
