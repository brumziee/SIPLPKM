<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    
    protected $primaryKey = 'ID_Pegawai';
    
    protected $fillable = [
        'Nama_Pegawai',
        'NoTelp_Pegawai',
    ];

    /**
     * Relasi ke Reward (One to Many)
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class, 'ID_Pegawai', 'ID_Pegawai');
    }

    /**
     * Relasi ke Penukaran Poin (One to Many)
     */
    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Pegawai', 'ID_Pegawai');
    }
}