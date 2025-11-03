<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'reward';
    protected $primaryKey = 'ID_Reward';
    public $timestamps = true;

    protected $fillable = [
        'Nama_Reward',
        'Poin_Dibutuhkan',
        'image',
        'ID_Pemilik',  // NULLABLE
        'ID_Pegawai',  // NULLABLE
    ];

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'ID_Pemilik', 'ID_Pemilik');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_Pegawai', 'ID_Pegawai');
    }

    public function penukaranPoin()
    {
        return $this->hasMany(PenukaranPoin::class, 'ID_Reward', 'ID_Reward');
    }
    
    // Helper untuk mendapatkan pembuat reward
    public function getCreatedByAttribute()
    {
        if ($this->pemilik) {
            return $this->pemilik->Nama_Pemilik . ' (Owner)';
        }
        if ($this->pegawai) {
            return $this->pegawai->Nama_Pegawai . ' (Staff)';
        }
        return 'Unknown';
    }
}