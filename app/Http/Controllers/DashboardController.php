<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\PoinLoyalitas;
use App\Models\CsvLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = DB::table('pelanggan')->count();
        $totalRewards = DB::table('reward')->count();

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

        return view('pages.dashboard', compact(
            'totalCustomers',
            'totalRewards',
            'topPelanggan',
            'topRewards'
        ));
    }

    // -------------------------------------
    //              IMPORT CSV
    // -------------------------------------
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        DB::beginTransaction();

        $filename = $request->file('file') ? $request->file('file')->getClientOriginalName() : 'Tidak ada file';
        $rows = [];
        $errors = [];

        try {
            $file = $request->file('file')->getRealPath();
            $rows = array_map('str_getcsv', file($file));

            if (!$rows || count($rows) < 2) {
                throw new \Exception('File CSV kosong atau tidak valid.');
            }

            $expectedHeader = ['No','Nama Pelanggan','Nomor Telepon','Transaksi','Total'];
            $header = $rows[0] ?? [];

            if ($header !== $expectedHeader) {
                throw new \Exception('Format CSV tidak sesuai. Pastikan header: ' . implode(', ', $expectedHeader));
            }

            unset($rows[0]);

            foreach ($rows as $index => $row) {
                $line = $index + 2;

                if (count($row) < 4) {
                    $errors[] = "Baris $line: Data tidak lengkap.";
                    continue;
                }

                $nama = trim($row[1]);
                $telepon = trim($row[2]);
                $trxCount = (int) preg_replace('/\D/', '', $row[3]);

                if ($trxCount <= 0) {
                    $errors[] = "Baris $line: Kolom Transaksi harus angka lebih dari 0.";
                    continue;
                }

                $telepon = preg_replace('/\D+/', '', $telepon);
                if (strpos($telepon, '0') === 0) {
                    $telepon = '62' . substr($telepon, 1);
                }

                $pelanggan = Pelanggan::where('NoTelp_Pelanggan', $telepon)->first();

                if (!$pelanggan) {
                    $pelanggan = Pelanggan::create([
                        'Nama_Pelanggan'    => $nama,
                        'NoTelp_Pelanggan'  => $telepon,
                    ]);
                }

                Transaksi::create([
                    'ID_Pegawai'        => 1,
                    'ID_Pelanggan'      => $pelanggan->ID_Pelanggan,
                    'Jumlah_Transaksi'  => $trxCount,
                    'Tanggal_Transaksi' => now(),
                ]);

                $poin = PoinLoyalitas::where('ID_Pelanggan', $pelanggan->ID_Pelanggan)->first();
                if ($poin) {
                    $poin->Jumlah_Poin += $trxCount;
                    $poin->save();
                } else {
                    PoinLoyalitas::create([
                        'ID_Pelanggan' => $pelanggan->ID_Pelanggan,
                        'Jumlah_Poin'  => $trxCount,
                    ]);
                }
            }

            CsvLog::create([
                'filename'       => $filename,
                'imported_rows'  => count($rows),
                'errors'         => $errors ? json_encode($errors) : null,
                'uploaded_by'    => auth()->id(),
                'uploaded_at'    => now(),
            ]);

            DB::commit();

            $message = 'CSV berhasil diimport.';
            $messageType = 'success';
            if ($errors) {
                $message .= ' Namun beberapa baris memiliki masalah.';
                $messageType = 'warning';
            }

            return redirect()->route('dashboard')->with([
                'message' => $message,
                'message_type' => $messageType
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            // simpan log gagal
            CsvLog::create([
                'filename'       => $filename,
                'imported_rows'  => isset($rows) ? count($rows) : 0,
                'errors'         => $errors ? json_encode($errors) : $th->getMessage(),
                'uploaded_by'    => auth()->id(),
                'uploaded_at'    => now(),
            ]);

            \Log::error('Gagal import CSV: '.$th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);

            return redirect()->route('dashboard')->with([
                'message' => 'Gagal import CSV: '.$th->getMessage(),
                'message_type' => 'danger'
            ]);
        }
    }
}
