<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'tabel_users';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
        'status_akun',
        'alasan_ditolak',
        'approved_at',
        'approved_by',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function pesananCustomer()
    {
        return $this->hasMany(Pesanan::class, 'id_customer', 'id_user');
    }

    public function pesananKurir()
    {
        return $this->hasMany(Pesanan::class, 'id_kurir', 'id_user');
    }
}
