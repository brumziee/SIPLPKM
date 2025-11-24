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
     * Ambil semua pelanggan beserta poin loyalitasnya
     */
    public function getAllPelanggansWithPoin(): Collection
    {
        return Pelanggan::with('poinLoyalitas')->get();
    }

    /**
     * Cari pelanggan
     */
    public function searchPelanggan(string $search): Collection
    {
        return Pelanggan::with('poinLoyalitas')
            ->where('Nama_Pelanggan', 'LIKE', "%{$search}%")
            ->orWhere('NoTelp_Pelanggan', 'LIKE', "%{$search}%")
            ->get();
    }

    /**
     * Ambil pelanggan berdasarkan ID
     */
    public function getPelangganById(int $id): Pelanggan
    {
        return Pelanggan::with('poinLoyalitas')->findOrFail($id);
    }

    /**
     * Create pelanggan + poin loyalitas
     */
    public function createPelanggan(array $data): Pelanggan
    {
        $jumlahPoin = $data['Poin_Loyalitas'] ?? 0;
        unset($data['Poin_Loyalitas']);

        $pelanggan = Pelanggan::create($data);

        PoinLoyalitas::create([
            'ID_Pelanggan' => $pelanggan->ID_Pelanggan,
            'Jumlah_Poin'  => $jumlahPoin,
        ]);

        return $pelanggan;
    }

    /**
     * Update pelanggan + poin loyalitas
     */
    public function updatePelanggan(int $id, array $data): bool
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $jumlahPoin = $data['Jumlah_Poin'] ?? null;
        unset($data['Jumlah_Poin']);

        // Update data pelanggan
        $pelanggan->update($data);

        // Update poin jika dikirim dari form
        if (!is_null($jumlahPoin)) {
            $poin = $pelanggan->poinLoyalitas;

            if ($poin) {
                $poin->update(['Jumlah_Poin' => $jumlahPoin]);
            } else {
                PoinLoyalitas::create([
                    'ID_Pelanggan' => $id,
                    'Jumlah_Poin'  => $jumlahPoin,
                ]);
            }
        }

        return true;
    }

    /**
     * Hapus pelanggan
     */
    public function deletePelanggan(int $id): bool
    {
        return Pelanggan::findOrFail($id)->delete();
    }

    /**
     * Tambah poin
     */
    public function tambahPoin(int $pelangganId, int $jumlahPoin): bool
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $poin = $pelanggan->poinLoyalitas;

        if (!$poin) {
            PoinLoyalitas::create([
                'ID_Pelanggan' => $pelangganId,
                'Jumlah_Poin'  => $jumlahPoin,
            ]);
            return true;
        }

        return $poin->increment('Jumlah_Poin', $jumlahPoin);
    }

    /**
     * Ambil semua reward yang bisa ditukar oleh pelanggan tertentu
     */
    public function getAvailableRewards(int $pelangganId): Collection
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $poinSaatIni = $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0;

        return Reward::where('Poin_Dibutuhkan', '<=', $poinSaatIni)
            ->orderBy('Poin_Dibutuhkan', 'desc')
            ->get();
    }

    /**
     * Tukar poin reward
     */
    public function tukarPoin(int $pelangganId, int $rewardId, int $userId): array
    {
        $pelanggan = $this->getPelangganById($pelangganId);
        $reward = Reward::findOrFail($rewardId);
        $poin = $pelanggan->poinLoyalitas;

        if (!$poin || $poin->Jumlah_Poin < $reward->Poin_Dibutuhkan) {
            throw new \Exception('Poin tidak mencukupi untuk penukaran reward');
        }

        $poinBaru = $poin->Jumlah_Poin - $reward->Poin_Dibutuhkan;
        $poin->update(['Jumlah_Poin' => $poinBaru]);

        $penukaran = PenukaranPoin::create([
            'ID_Pemilik' => $reward->ID_Pemilik,
            'ID_Pegawai' => $reward->ID_Pegawai,
            'ID_Pelanggan' => $pelangganId,
            'ID_Poin' => $poin->ID_Poin,
            'ID_Reward' => $rewardId,
            'Jumlah_Poin_Ditukar' => $reward->Poin_Dibutuhkan,
            'Tanggal_Penukaran' => now(),
        ]);

        return [
            'success'      => true,
            'sisa_poin'    => $poinBaru,
            'reward'       => $reward->Nama_Reward,
            'penukaran_id' => $penukaran->ID_Penukaran,
        ];
    }

    /**
     * Riwayat penukaran poin
     */
    public function getRiwayatPenukaran(int $pelangganId): Collection
    {
        return PenukaranPoin::with(['reward'])
            ->where('ID_Pelanggan', $pelangganId)
            ->orderBy('Tanggal_Penukaran', 'desc')
            ->get();
    }

    /**
     * Riwayat transaksi
     */
    public function getRiwayatTransaksi(int $pelangganId): Collection
    {
        return Transaksi::where('ID_Pelanggan', $pelangganId)
            ->orderBy('Tanggal_Transaksi', 'desc')
            ->get();
    }

    /**
     * Ambil semua reward (FIX missing method)
     */
    public function getAllRewards()
    {
        return Reward::orderBy('Poin_Dibutuhkan')->get();
    }
}
