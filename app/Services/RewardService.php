<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Reward;
use App\Models\Pemilik;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Collection;

final class RewardService
{
    /**
     * Get all rewards with relations
     */
    public function getAllRewardsWithRelations(): Collection
    {
        return Reward::with(['pemilik', 'pegawai'])->get();
    }

    /**
     * Top reward paling banyak ditukar
     */
    public function getTopRewardMostRedeemed(int $limit = 3): Collection
    {
        return Reward::withCount('penukaranPoin')
                     ->orderByDesc('penukaran_poin_count')
                     ->limit($limit)
                     ->get();
    }

    /**
     * Get all pemiliks
     */
    public function getAllPemiliks(): Collection
    {
        return Pemilik::all();
    }

    /**
     * Get all pegawais
     */
    public function getAllPegawais(): Collection
    {
        return Pegawai::all();
    }

    /**
     * Get reward by ID
     */
    public function getRewardById(int $id): Reward
    {
        return Reward::with(['pemilik', 'pegawai', 'penukaranPoin'])->findOrFail($id);
    }

    /**
     * Create new reward
     */
    public function createReward(array $data): Reward
    {
        return Reward::create($data);
    }

    /**
     * Update reward
     */
    public function updateReward(int $id, array $data): bool
    {
        $reward = Reward::findOrFail($id);
        return $reward->update($data);
    }

    /**
     * Delete reward
     */
    public function deleteReward(int $id): bool
    {
        $reward = Reward::findOrFail($id);
        return $reward->delete();
    }
}
