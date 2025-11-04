<?php

namespace App\Services\Interface;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Collection;

interface RewardServiceInterface
{
    public function getAllRewardsWithRelations(): Collection;
    public function getAllPemiliks(): Collection;
    public function getAllPegawais(): Collection;
    public function getRewardById(int $id): Reward;
    public function createReward(array $data): Reward;
    public function updateReward(int $id, array $data): bool;
    public function deleteReward(int $id): bool;
}
