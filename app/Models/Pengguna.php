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
        'role',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function pesananKurir()
    {
        return $this->hasMany(Pesanan::class, 'id_kurir', 'id_user');
    }
}
