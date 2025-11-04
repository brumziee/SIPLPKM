<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinLoyalitas extends Model
{
    use HasFactory;

    protected $table = 'poin_loyalitas';
    protected $primaryKey = 'ID_Poin';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ID_Pelanggan',
        'Jumlah_Poin',
    ];

    protected $casts = [
        'Jumlah_Poin' => 'integer',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Poin', 'ID_Poin');
    }
}
