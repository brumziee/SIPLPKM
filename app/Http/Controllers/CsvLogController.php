<?php

namespace App\Http\Controllers;

use App\Models\CsvLog;

class CsvLogController extends Controller
{
    public function index()
    {
        $logs = CsvLog::with('user')
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return view('pages.csv_logs.index', compact('logs'));
    }
}
