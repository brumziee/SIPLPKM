<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinLoyalitas extends Model
{
    use HasFactory;

    protected $table = 'poin_loyalitas';
    
    protected $primaryKey = 'ID_Poin';
    
    protected $fillable = [
        'ID_Pelanggan',
        'Jumlah_Poin',
    ];

    protected $casts = [
        'Jumlah_Poin' => 'integer',
    ];

    /**
     * Relasi ke Pelanggan (One to One)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    /**
     * Relasi ke Penukaran Poin (One to Many)
     */
    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Poin', 'ID_Poin');
    }
}