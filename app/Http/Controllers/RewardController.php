<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRewardRequest;
use App\Services\RewardService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

final class RewardController extends Controller
{
    protected RewardService $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    // Menampilkan list reward
    public function index()
    {
        $rewards = $this->rewardService->getAllRewardsWithRelations();
        return view('pages.reward.index', compact('rewards'));
    }

    // Menampilkan form tambah reward
    public function create()
    {
        return view('pages.reward.create');
    }

    // Simpan reward baru
    public function store(StoreRewardRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = Auth::user();

            // Tambahkan debug log
            \Log::info('User membuat reward:', [
                'id' => $user->id,
                'roles' => $user->roles->pluck('name')->toArray(),
                'ID_Pemilik' => $user->ID_Pemilik ?? null,
                'ID_Pegawai' => $user->ID_Pegawai ?? null,
            ]);

            if ($user->hasRole('admin')) {
                if (!$user->ID_Pemilik) {
                    return redirect()->back()->withErrors('Akun admin belum terhubung dengan data Pemilik.')->withInput();
                }
                $data['ID_Pemilik'] = $user->ID_Pemilik;
                $data['ID_Pegawai'] = null;
            } elseif ($user->hasRole('kasir')) {
                if (!$user->ID_Pegawai) {
                    return redirect()->back()->withErrors('Akun kasir belum terhubung dengan data Pegawai.')->withInput();
                }
                $data['ID_Pemilik'] = null;
                $data['ID_Pegawai'] = $user->ID_Pegawai;
            } else {
                return redirect()->back()->withErrors('Role Anda tidak memiliki akses untuk membuat reward.')->withInput();
            }

            // Handle upload gambar
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('rewards', 'public');
            }

            // Debug sebelum insert
            \Log::info('Data reward sebelum insert:', $data);

            // Simpan reward
            $this->rewardService->createReward($data);

            DB::commit();
            return redirect()->route('reward.index')->withSuccess('Data reward berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Gagal create reward: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->withErrors("Gagal menambahkan reward: " . $th->getMessage())->withInput();
        }
    }

    // Menampilkan form edit reward
    public function edit(int $id)
    {
        $reward = $this->rewardService->getRewardById($id);
        return view('pages.reward.edit', compact('reward'));
    }

    // Update reward
    public function update(StoreRewardRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $reward = $this->rewardService->getRewardById($id);
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                $data['ID_Pemilik'] = $user->ID_Pemilik;
                $data['ID_Pegawai'] = null;
            } elseif ($user->hasRole('kasir')) {
                $data['ID_Pemilik'] = null;
                $data['ID_Pegawai'] = $user->ID_Pegawai;
            }

            // Handle upload gambar
            if ($request->hasFile('image')) {
                if ($reward->image && Storage::disk('public')->exists($reward->image)) {
                    Storage::disk('public')->delete($reward->image);
                }
                $data['image'] = $request->file('image')->store('rewards', 'public');
            }

            $this->rewardService->updateReward($id, $data);

            DB::commit();
            return redirect()->route('reward.index')->withSuccess('Data reward berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Gagal update reward: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->withErrors("Gagal memperbaharui reward: " . $th->getMessage())->withInput();
        }
    }

    // Hapus reward
    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $reward = $this->rewardService->getRewardById($id);
            if ($reward->image && Storage::disk('public')->exists($reward->image)) {
                Storage::disk('public')->delete($reward->image);
            }

            $this->rewardService->deleteReward($id);
            DB::commit();

            return redirect()->route('reward.index')->withSuccess('Reward berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Gagal hapus reward: ' . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
            return redirect()->back()->withErrors("Gagal menghapus reward: " . $th->getMessage());
        }
    }
}
