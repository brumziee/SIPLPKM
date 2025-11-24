<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePelangganRequest;
use App\Services\PelangganService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class PelangganController extends Controller
{
    protected PelangganService $pelangganService;

    public function __construct(PelangganService $pelangganService)
    {
        $this->pelangganService = $pelangganService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        if ($search) {
            $pelanggans = $this->pelangganService->searchPelanggan($search);
        } else {
            $pelanggans = $this->pelangganService->getAllPelanggansWithPoin();
        }

        return view('pages.pelanggan.index', compact('pelanggans', 'search'));
    }

    public function create()
    {
        return view('pages.pelanggan.create');
    }

    public function store(Request $request, PelangganService $pelangganService)
    {
        $request->validate([
            'Nama_Pelanggan' => 'required|string|max:255',
            'NoTelp_Pelanggan' => 'required|string|unique:pelanggan,NoTelp_Pelanggan',
            'Poin_Loyalitas' => 'nullable|integer|min:0',
        ]);

        $pelangganService->createPelanggan($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $pelanggan = $this->pelangganService->getPelangganById((int)$id);
        $riwayatPenukaran = $this->pelangganService->getRiwayatPenukaran((int)$id);
        $riwayatTransaksi = $this->pelangganService->getRiwayatTransaksi((int)$id);

        return view('pages.pelanggan.show', compact('pelanggan', 'riwayatPenukaran', 'riwayatTransaksi'));
    }

    public function edit(string $id)
    {
        $pelanggan = $this->pelangganService->getPelangganById((int)$id);
        return view('pages.pelanggan.edit', compact('pelanggan'));
    }

    public function update(StorePelangganRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $this->pelangganService->updatePelanggan((int)$id, $data);

            DB::commit();
            return redirect()->route('pelanggan.index')
                ->withSuccess('Data pelanggan berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors("Gagal memperbaharui pelanggan: " . $th->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->pelangganService->deletePelanggan((int)$id);
            return redirect()->route('pelanggan.index')->withSuccess('Data pelanggan berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors("Gagal menghapus pelanggan: " . $th->getMessage());
        }
    }

    public function tukarPoinForm(string $id)
    {
        $pelanggan = $this->pelangganService->getPelangganById((int)$id);
        $rewards = $this->pelangganService->getAvailableRewards((int)$id);

        return view('pages.pelanggan.tukar-poin', compact('pelanggan', 'rewards'));
    }

    public function tukarPoin(Request $request, string $id)
    {
        $request->validate([
            'reward_id' => 'required|exists:reward,ID_Reward',
        ]);

        DB::beginTransaction();
        try {
            $result = $this->pelangganService->tukarPoin(
                (int)$id,
                (int)$request->reward_id,
                $request->user()->id   // 🔥 FIX intelephense error
            );

            DB::commit();
            return redirect()->route('pelanggan.show', $id)
                ->withSuccess("Penukaran poin berhasil! Poin tersisa: {$result['sisa_poin']}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function tambahPoin(Request $request, string $id)
    {
        $request->validate([
            'jumlah_poin' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $this->pelangganService->tambahPoin((int)$id, (int)$request->jumlah_poin);

            DB::commit();
            return redirect()->route('pelanggan.show', $id)
                ->withSuccess("Berhasil menambahkan {$request->jumlah_poin} poin");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function tukarPoinFormGlobal()
    {
        $pelanggans = $this->pelangganService->getAllPelanggansWithPoin();
        $rewards = $this->pelangganService->getAllRewards();

        return view('pages.pelanggan.tukar-poin-global', compact('pelanggans', 'rewards'));
    }

    /**
 * ============================
 * EXPORT CSV FUNCTION
 * ============================
 */
public function exportCSV()
{
    $pelanggans = $this->pelangganService->getAllPelanggansWithPoin();

    $filename = "data_pelanggan_" . date('Ymd_His') . ".csv";

    $columns = [
        'ID Pelanggan',
        'Nama Pelanggan',
        'Nomor Telepon',
        'Poin Loyalitas'
    ];

    $callback = function() use ($pelanggans, $columns) {
        $file = fopen('php://output', 'w');

        // Tambahkan BOM UTF-8 agar Excel membuka CSV dengan encoding benar
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Tulis header kolom
        fputcsv($file, $columns);

        // Tulis data pelanggan
        foreach ($pelanggans as $p) {
            fputcsv($file, [
                $p->ID_Pelanggan,
                $p->Nama_Pelanggan,
                $p->NoTelp_Pelanggan,
                $p->poinLoyalitas->Jumlah_Poin ?? 0
            ]);
        }

        fclose($file);
    };

    return response()->streamDownload($callback, $filename, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}"
    ]);
}
}
