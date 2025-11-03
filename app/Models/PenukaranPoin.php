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
    
    protected $fillable = [
        'transaction_id',
        'ID_Pemilik',
        'ID_Pegawai',
        'ID_Pelanggan',
        'ID_Poin',
        'ID_Reward',
        'Jumlah_Poin_Ditukar',
        'Tanggal_Penukaran',
    ];

    protected $casts = [
        'Jumlah_Poin_Ditukar' => 'integer',
        'Tanggal_Penukaran' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate unique transaction ID
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($penukaran) {
            if (empty($penukaran->transaction_id)) {
                $penukaran->transaction_id = 'PNK-' . date('Ymd') . '-' . str_pad((string)random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relasi ke Pemilik (Many to One)
     */
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Pemilik::class, 'ID_Pemilik', 'ID_Pemilik');
    }

    /**
     * Relasi ke Pegawai (Many to One)
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'ID_Pegawai', 'ID_Pegawai');
    }

    /**
     * Relasi ke Pelanggan (Many to One)
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    /**
     * Relasi ke Poin Loyalitas (Many to One)
     */
    public function poinLoyalitas(): BelongsTo
    {
        return $this->belongsTo(PoinLoyalitas::class, 'ID_Poin', 'ID_Poin');
    }

    /**
     * Relasi ke Reward (Many to One)
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'ID_Reward', 'ID_Reward');
    }

    /**
     * Get formatted transaction ID for display
     */
    public function getFormattedTransactionIdAttribute(): string
    {
        return $this->transaction_id;
    }
}