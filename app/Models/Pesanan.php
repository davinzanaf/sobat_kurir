<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'tabel_pesanan';

    protected $primaryKey = 'id_pesanan';

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
    ];

    public function kurir()
    {
        return $this->belongsTo(Pengguna::class, 'id_kurir', 'id_user');
    }

    public function tracking()
    {
        return $this->hasMany(Tracking::class, 'id_pesanan', 'id_pesanan');
    }
}
