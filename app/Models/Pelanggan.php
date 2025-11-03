<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    
    protected $primaryKey = 'ID_Pelanggan';
    
    protected $fillable = [
        'Nama_Pelanggan',
        'NoTelp_Pelanggan',
    ];

    /**
     * Relasi ke Transaksi (One to Many)
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    /**
     * Relasi ke Penukaran Poin (One to Many)
     */
    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    /**
     * Relasi ke Poin Loyalitas (One to One)
     */
    public function poinLoyalitas()
    {
        return $this->hasOne(PoinLoyalitas::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }
}