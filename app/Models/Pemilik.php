<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemilik';
    
    protected $primaryKey = 'ID_Pemilik';
    
    protected $fillable = [
        'Nama_Pemilik',
        'NoTelp_Pemilik',
    ];

    /**
     * Relasi ke Reward (One to Many)
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class, 'ID_Pemilik', 'ID_Pemilik');
    }

    /**
     * Relasi ke Penukaran Poin (One to Many)
     */
    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Pemilik', 'ID_Pemilik');
    }
}