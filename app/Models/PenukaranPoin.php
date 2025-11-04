<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PenukaranPoin extends Model
{
    use HasFactory;

    protected $table = 'penukaran_poin';
    protected $primaryKey = 'ID_Penukaran';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ID_Pelanggan',
        'ID_Poin',               // wajib karena not null
        'ID_Reward',
        'Jumlah_Poin_Ditukar',
        'Tanggal_Penukaran',
        'ID_Pemilik',   // opsional
        'ID_Pegawai',   // opsional
    ];

    protected $casts = [
        'Jumlah_Poin_Ditukar' => 'integer',
        'Tanggal_Penukaran'   => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'ID_Reward', 'ID_Reward');
    }

    public function poinLoyalitas(): BelongsTo
    {
        return $this->belongsTo(PoinLoyalitas::class, 'ID_Poin', 'ID_Poin');
    }
}
