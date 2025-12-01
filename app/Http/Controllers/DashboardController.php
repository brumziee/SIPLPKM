<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\PoinLoyalitas;
use App\Models\CsvLog;
use Carbon\Carbon; // [BARU] Perlu import ini untuk mengolah tanggal

class DashboardController extends Controller
{
public function index()
    {
        // 1. STATISTIK UTAMA (4 KPI)
        $totalCustomers = DB::table('pelanggan')->count();
        $totalRewards = DB::table('reward')->count();
        $totalTransactions = DB::table('transaksi')->count();
        $totalPointsCirculation = DB::table('poin_loyalitas')->sum('Jumlah_Poin');

        // 2. REPORT TABEL 1: Top Pelanggan
        $topPelanggan = DB::table('pelanggan')
            ->select('pelanggan.Nama_Pelanggan', DB::raw('SUM("poin_loyalitas"."Jumlah_Poin") as total_poin'))
            ->join('poin_loyalitas', 'pelanggan.ID_Pelanggan', '=', 'poin_loyalitas.ID_Pelanggan')
            ->groupBy('pelanggan.ID_Pelanggan', 'pelanggan.Nama_Pelanggan')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // 3. REPORT TABEL 2: Top Rewards
        $topRewards = DB::table('reward')
            ->select('reward.Nama_Reward', DB::raw('COUNT("penukaran_poin"."ID_Reward") as total_terpakai'))
            ->join('penukaran_poin', 'reward.ID_Reward', '=', 'penukaran_poin.ID_Reward')
            ->groupBy('reward.ID_Reward', 'reward.Nama_Reward')
            ->orderByDesc('total_terpakai')
            ->limit(5)
            ->get();

        // [BAGIAN INI YANG PERLU DIUBAH]
        // 4. REPORT TABEL 3: Recent Activity (Ganti ke Penukaran Poin)
        $recentActivities = DB::table('penukaran_poin')
            ->join('pelanggan', 'penukaran_poin.ID_Pelanggan', '=', 'pelanggan.ID_Pelanggan')
            ->join('reward', 'penukaran_poin.ID_Reward', '=', 'reward.ID_Reward')
            ->select(
                'pelanggan.Nama_Pelanggan',
                'reward.Nama_Reward',         // Agar $act->Nama_Reward di View tidak error
                'penukaran_poin.created_at'
            )
            ->orderByDesc('penukaran_poin.created_at')
            ->limit(5)
            ->get();

        // 5. DATA GRAFIK (7 Hari Terakhir)
        $chartDates = [];
        $chartPoin = [];
        $chartRedeem = [];

        for ($i = 6; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $dateStr = $dateObj->format('Y-m-d');
            
            $chartDates[] = $dateObj->format('d M'); 

            // Poin masuk berdasarkan Tanggal Transaksi (dari nama file CSV)
            $poinHariIni = DB::table('transaksi')
                ->whereDate('Tanggal_Transaksi', $dateStr) 
                ->sum('Jumlah_Transaksi');
            
            // Penukaran berdasarkan waktu input
            $redeemHariIni = DB::table('penukaran_poin')
                ->whereDate('created_at', $dateStr)
                ->count();

            $chartPoin[] = (int) $poinHariIni;
            $chartRedeem[] = (int) $redeemHariIni;
        }

        return view('pages.dashboard', compact(
            'totalCustomers', 'totalRewards', 'totalTransactions', 'totalPointsCirculation',
            'topPelanggan', 'topRewards', 'recentActivities',
            'chartDates', 'chartPoin', 'chartRedeem'
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
        
        // [LOGIKA BARU] Deteksi tanggal dari nama file
        // Format yang dicari: ..._ddmmyy_... (contoh: _301125_)
        $tanggalTransaksi = now(); // Default hari ini jika gagal deteksi
        if (preg_match('/_(\d{6})_/', $filename, $matches)) {
            try {
                $tanggalTransaksi = Carbon::createFromFormat('dmy', $matches[1]);
            } catch (\Exception $e) {
                // Jika format salah, tetap gunakan now()
            }
        }

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

            // Fix jika header terbaca satu baris panjang
            if ($header !== $expectedHeader && count($header) === 1 && strpos($header[0], ',') !== false) {
                 $header = str_getcsv($header[0]);
            }

            if ($header !== $expectedHeader) {
                throw new \Exception('Format CSV tidak sesuai. Pastikan header: ' . implode(', ', $expectedHeader));
            }

            unset($rows[0]);

            foreach ($rows as $index => $row) {
                $line = $index + 2;

                // Fix baris menyatu karena tanda kutip
                if (count($row) === 1 && isset($row[0]) && strpos($row[0], ',') !== false) {
                    $parsedAgain = str_getcsv($row[0]);
                    if (count($parsedAgain) >= 4) {
                        $row = $parsedAgain; 
                    }
                }

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
                    'Tanggal_Transaksi' => $tanggalTransaksi, // [UBAH DISINI] Pakai tanggal dari file
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

            $message = 'CSV berhasil diimport. Tanggal Data: ' . $tanggalTransaksi->format('d M Y');
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