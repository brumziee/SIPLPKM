<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PenukaranPoinService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PenukaranPoinController extends Controller
{
    protected PenukaranPoinService $penukaranPoinService;

    public function __construct(PenukaranPoinService $penukaranPoinService)
    {
        $this->penukaranPoinService = $penukaranPoinService;
    }

    /**
     * Display a listing of the resource.
     * Fungsi Tampil Semua Riwayat Penukaran Poin (SKPL-SIPLPKM-004-01)
     * dan Fungsi Cari Riwayat Transaksi (SKPL-SIPLPKM-004-02)
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            $penukarans = $this->penukaranPoinService->searchPenukaran($search);
        } else {
            $penukarans = $this->penukaranPoinService->getAllPenukaranPaginated();
        }

        return view('pages.penukaran-poin.index', compact('penukarans', 'search'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $penukaran = $this->penukaranPoinService->getPenukaranById((int)$id);

        if (!$penukaran) {
            abort(404, 'Data penukaran poin tidak ditemukan');
        }

        return view('pages.penukaran-poin.show', compact('penukaran'));
    }

    /**
     * Get today's summary
     */
    public function todaySummary(): \Illuminate\Http\JsonResponse
    {
        $stats = $this->penukaranPoinService->getStatistics();

        return response()->json([
            'success' => true,
            'total_penukaran' => $stats['total_today'],
            'total_points' => $stats['total_points_today'],
            'unique_pelanggans' => $stats['unique_pelanggans_today'],
        ]);
    }
}