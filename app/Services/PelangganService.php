<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pelanggan;
use App\Models\PoinLoyalitas;
use App\Models\Reward;
use App\Models\PenukaranPoin;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Collection;

final class PelangganService
{
    /**
     * Get all pelanggans with poin loyalitas
     */
    public function getAllPelanggansWithPoin(): Collection
    {
        return Pelanggan::with('poinLoyalitas')->get();
    }

    /**
     * Search pelanggan by name
     */
    public function searchPelanggan(string $search): Collection
    {
        return Pelanggan::with('poinLoyalitas')
            ->where('Nama_Pelanggan', 'LIKE', "%{$search}%")
            ->orWhere('NoTelp_Pelanggan', 'LIKE', "%{$search}%")
            ->get();
    }

    /**
     * Get pelanggan by ID
     */
    public function getPelangganById(int $id): Pelanggan
    {
        return Pelanggan::with('poinLoyalitas')->findOrFail($id);
    }

    /**
     * Create new pelanggan + poin loyalitas sekaligus
     */
    public function createPelanggan(array $data): Pelanggan
    {
        $jumlahPoin = $data['Poin_Loyalitas'] ?? 0;
        unset($data['Poin_Loyalitas']); // hapus supaya tidak masuk ke tabel pelanggan

        $pelanggan = Pelanggan::create($data);

        // Buat poin loyalitas
        PoinLoyalitas::create([
            'ID_Pelanggan' => $pelanggan->ID_Pelanggan,
            'Jumlah_Poin'  => $jumlahPoin,
        ]);

        return $pelanggan;
    }

    /**
     * Update pelanggan beserta poin loyalitas jika ada
     */
public function updatePelanggan(int $id, array $data): bool
{
    $pelanggan = Pelanggan::findOrFail($id);

    // Pisahkan jumlah poin dari data pelanggan
    $jumlahPoin = $data['Jumlah_Poin'] ?? null;
    unset($data['Jumlah_Poin']);

    // Update data pelanggan
    $pelanggan->update($data);

    // Update poin loyalitas
    if (!is_null($jumlahPoin)) {
        $poinLoyalitas = $pelanggan->poinLoyalitas;
        if ($poinLoyalitas) {
            $poinLoyalitas->update(['Jumlah_Poin' => $jumlahPoin]);
        } else {
            PoinLoyalitas::create([
                'ID_Pelanggan' => $id,
                'Jumlah_Poin' => $jumlahPoin,
            ]);
        }
    }

    return true;
}

    /**
     * Delete pelanggan
     */
    public function deletePelanggan(int $id): bool
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return $pelanggan->delete();
    }

    /**
     * Tambah poin manual
     */
    public function tambahPoin(int $pelangganId, int $jumlahPoin): bool
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $poinLoyalitas = $pelanggan->poinLoyalitas;

        if (!$poinLoyalitas) {
            PoinLoyalitas::create([
                'ID_Pelanggan' => $pelangganId,
                'Jumlah_Poin' => $jumlahPoin
            ]);
            return true;
        }

        return $poinLoyalitas->increment('Jumlah_Poin', $jumlahPoin);
    }

    /**
     * Get available rewards for pelanggan
     */
    public function getAvailableRewards(int $pelangganId): Collection
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $poinSaatIni = $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0;

        return Reward::with(['pemilik', 'pegawai'])
            ->where('Poin_Dibutuhkan', '<=', $poinSaatIni)
            ->orderBy('Poin_Dibutuhkan', 'desc')
            ->get();
    }

    /**
     * Tukar poin pelanggan untuk reward
     */
    public function tukarPoin(int $pelangganId, int $rewardId, int $userId): array
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $reward = Reward::findOrFail($rewardId);
        $poinLoyalitas = $pelanggan->poinLoyalitas;

        if (!$poinLoyalitas || $poinLoyalitas->Jumlah_Poin < $reward->Poin_Dibutuhkan) {
            throw new \Exception('Poin tidak mencukupi untuk penukaran reward ini');
        }

        $poinBaru = $poinLoyalitas->Jumlah_Poin - $reward->Poin_Dibutuhkan;
        $poinLoyalitas->update(['Jumlah_Poin' => $poinBaru]);

        $penukaran = PenukaranPoin::create([
            'ID_Pemilik' => $reward->ID_Pemilik,
            'ID_Pegawai' => $reward->ID_Pegawai,
            'ID_Pelanggan' => $pelangganId,
            'ID_Poin' => $poinLoyalitas->ID_Poin,
            'ID_Reward' => $rewardId,
            'Jumlah_Poin_Ditukar' => $reward->Poin_Dibutuhkan,
            'Tanggal_Penukaran' => now(),
        ]);

        return [
            'success' => true,
            'sisa_poin' => $poinBaru,
            'reward' => $reward->Nama_Reward,
            'transaction_id' => $penukaran->transaction_id ?? null,
            'penukaran_id' => $penukaran->ID_Penukaran,
        ];
    }

    /**
     * Riwayat penukaran
     */
    public function getRiwayatPenukaran(int $pelangganId): Collection
    {
        return PenukaranPoin::with(['reward', 'pemilik', 'pegawai'])
            ->where('ID_Pelanggan', $pelangganId)
            ->orderBy('Tanggal_Penukaran', 'desc')
            ->get();
    }

    /**
     * Riwayat transaksi
     */
    public function getRiwayatTransaksi(int $pelangganId): Collection
    {
        return Transaksi::with('pegawai')
            ->where('ID_Pelanggan', $pelangganId)
            ->orderBy('Tanggal_Transaksi', 'desc')
            ->get();
    }
}
