<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Reward;
use App\Models\PenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenukaranPoinController extends Controller
{
    /**
     * Index: tampilkan semua penukaran poin
     */
    public function index(): View
    {
        $penukarans = PenukaranPoin::with(['pelanggan.poinLoyalitas', 'reward'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'ID_Penukaran'        => $item->ID_Penukaran,
                    'Nama_Pelanggan'      => $item->pelanggan->Nama_Pelanggan ?? 'Tidak Diketahui',
                    'Total_Poin_Pelanggan'=> $item->pelanggan->poinLoyalitas->Jumlah_Poin ?? 0,
                    'Nama_Reward'         => $item->reward->Nama_Reward ?? 'Reward Dihapus',
                    'Poin_Ditukar'        => $item->Jumlah_Poin_Ditukar,
                    'Tanggal_Penukaran'   => $item->Tanggal_Penukaran,
                ];
            });

        return view('pages.penukaran-poin.index', compact('penukarans'));
    }

    /**
     * Form create: tampilkan pelanggan + reward
     */
    public function create(): View
    {
        $pelanggans = Pelanggan::with('poinLoyalitas')->get()->map(function ($pelanggan) {
            $pelanggan->total_poin = $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0;
            return $pelanggan;
        });

        $rewards = Reward::all();

        return view('pages.penukaran-poin.create', compact('pelanggans', 'rewards'));
    }

    /**
     * Store: proses penukaran poin
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,ID_Pelanggan',
            'reward_id'    => 'required|exists:reward,ID_Reward',
        ]);

        $pelanggan = Pelanggan::with('poinLoyalitas')->findOrFail($request->pelanggan_id);
        $reward    = Reward::findOrFail($request->reward_id);

        // Ambil ID_Poin dari poin loyalitas pelanggan
        $idPoin = $pelanggan->poinLoyalitas->ID_Poin ?? null;
        if (!$idPoin) {
            return redirect()->back()->with('error', 'Poin loyalitas pelanggan tidak ditemukan.');
        }

        // Cek apakah pelanggan memiliki cukup poin
        $totalPoin = $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0;
        if ($totalPoin < $reward->Poin_Dibutuhkan) {
            return redirect()->back()->with('error', 'Poin pelanggan tidak cukup untuk menukar reward ini.');
        }

        // Proses penukaran
        $penukaran = PenukaranPoin::create([
            'ID_Pelanggan'       => $pelanggan->ID_Pelanggan,
            'ID_Poin'            => $idPoin,
            'ID_Reward'          => $reward->ID_Reward,
            'Jumlah_Poin_Ditukar'=> $reward->Poin_Dibutuhkan,
            'Tanggal_Penukaran'  => now(),
        ]);

        // Kurangi poin pelanggan
        $pelanggan->poinLoyalitas->decrement('Jumlah_Poin', $reward->Poin_Dibutuhkan);

        return redirect()->route('penukaran-poin.index')->with('success', 'Poin berhasil ditukar.');
    }

    /**
     * Destroy: hapus data penukaran poin
     */
    public function destroy($id)
    {
        $penukaran = PenukaranPoin::findOrFail($id);

        try {
            // Tambahkan kembali poin ke pelanggan saat dihapus
            $pelanggan = $penukaran->pelanggan;
            if ($pelanggan && $pelanggan->poinLoyalitas) {
                $pelanggan->poinLoyalitas->increment('Jumlah_Poin', $penukaran->Jumlah_Poin_Ditukar);
            }

            $penukaran->delete();

            return redirect()->route('penukaran-poin.index')
                ->with('success', 'Penukaran poin berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('penukaran-poin.index')
                ->with('error', 'Gagal menghapus penukaran poin: ' . $e->getMessage());
        }
    }
}
