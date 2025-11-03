<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Reward;
use App\Models\PoinLoyalitas;
use App\Models\PenukaranPoin;
use Illuminate\Support\Facades\DB;

class PenukaranPoinController extends Controller
{
    // Menampilkan form tukar poin
    public function create()
    {
        $pelanggan = Pelanggan::with('poinLoyalitas')->get(); // Bisa untuk dropdown semua pelanggan
        $rewards = Reward::all();

        return view('penukaran.create', compact('pelanggan', 'rewards'));
    }

    // Proses tukar poin
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,ID_Pelanggan',
            'reward_id' => 'required|exists:reward,ID_Reward',
        ]);

        $pelanggan = Pelanggan::with('poinLoyalitas')->findOrFail($request->pelanggan_id);
        $reward = Reward::findOrFail($request->reward_id);
        $jumlahPoin = $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0;

        if ($jumlahPoin < $reward->Poin_Dibutuhkan) {
            return redirect()->back()->withErrors('Poin pelanggan tidak mencukupi.');
        }

        DB::transaction(function () use ($pelanggan, $reward) {
            // Kurangi poin
            $pelanggan->poinLoyalitas->decrement('Jumlah_Poin', $reward->Poin_Dibutuhkan);

            // Simpan penukaran
            PenukaranPoin::create([
                'pelanggan_id' => $pelanggan->ID_Pelanggan,
                'reward_id' => $reward->ID_Reward,
                'jumlah_poin' => $reward->Poin_Dibutuhkan,
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('penukaran.create')->with('success', 'Poin berhasil ditukar!');
    }
}
