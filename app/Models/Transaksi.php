<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    protected $primaryKey = 'ID_Transaksi';
    
    protected $fillable = [
        'ID_Pegawai',
        'ID_Pelanggan',
        'Jumlah_Transaksi',
        'Tanggal_Transaksi',
    ];

    protected $casts = [
        'Tanggal_Transaksi' => 'datetime',
        'Jumlah_Transaksi' => 'decimal:2',
    ];

    /**
     * Relasi ke Pegawai (Many to One)
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ID_Pegawai', 'ID_Pegawai');
    }

    /**
     * Relasi ke Pelanggan (Many to One)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }
}