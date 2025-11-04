<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pelanggan
        $totalCustomers = DB::table('pelanggan')->count();

        // Total Reward
        $totalRewards = DB::table('reward')->count();

        // Top 3 pelanggan dengan poin tertinggi
        $topPelanggan = DB::table('pelanggan')
            ->select(
                'pelanggan.ID_Pelanggan',
                'pelanggan.Nama_Pelanggan',
                DB::raw('SUM("poin_loyalitas"."Jumlah_Poin") as total_poin')
            )
            ->join('poin_loyalitas', 'pelanggan.ID_Pelanggan', '=', 'poin_loyalitas.ID_Pelanggan')
            ->groupBy('pelanggan.ID_Pelanggan', 'pelanggan.Nama_Pelanggan')
            ->orderByDesc('total_poin')
            ->limit(3)
            ->get();

        // Top 3 reward paling banyak ditukar
        $topRewards = DB::table('reward')
            ->select(
                'reward.ID_Reward',
                'reward.Nama_Reward',
                DB::raw('COUNT("penukaran_poin"."ID_Reward") as total_terpakai')
            )
            ->join('penukaran_poin', 'reward.ID_Reward', '=', 'penukaran_poin.ID_Reward')
            ->groupBy('reward.ID_Reward', 'reward.Nama_Reward')
            ->orderByDesc('total_terpakai')
            ->limit(3)
            ->get();

        // Kembalikan view yang benar
        return view('pages.dashboard', compact(
            'totalCustomers',
            'totalRewards',
            'topPelanggan',
            'topRewards'
        ));
    }
}
