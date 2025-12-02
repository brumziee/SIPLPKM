@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            {{-- <h3 class="fw-bold text-dark mb-0">Dashboard</h3> --}}
            <p class="text-muted mb-0">Ringkasan aktivitas program loyalitas.</p>
        </div>
        <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-cloud-arrow-up me-2"></i>Import CSV
        </button>
    </div>

    @if (session('message'))
        <div class="alert alert-dismissible fade show mb-4 shadow-sm border-0" role="alert"
            style="background-color: {{ session('message_type') == 'success' ? '#d1e7dd' : (session('message_type') == 'warning' ? '#fff3cd' : '#f8d7da') }}; 
                   color: {{ session('message_type') == 'success' ? '#0f5132' : (session('message_type') == 'warning' ? '#664d03' : '#842029') }};">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Total Pelanggan</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalCustomers) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Total Reward</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalRewards) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                            <i class="bi bi-gift-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Total Transaksi</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalTransactions) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Poin Beredar</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalPointsCirculation) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                            <i class="bi bi-coin fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-graph-up-arrow me-2"></i>Tren Poin Masuk (7 Hari)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPoin" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="bi bi-arrow-repeat me-2"></i>Tren Penukaran Reward (7 Hari)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartRedeem" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">🏆 Top 5 Pelanggan</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($topPelanggan as $idx => $c)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="small fw-bold">{{ $idx + 1 }}. {{ $c->Nama_Pelanggan }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $c->total_poin }} pts</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">Data kosong</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">🎁 Top 5 Reward</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($topRewards as $idx => $r)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="small fw-bold">{{ $idx + 1 }}. {{ $r->Nama_Reward }}</span>
                                <span class="badge bg-success rounded-pill">{{ $r->total_terpakai }}x</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">Data kosong</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-secondary">🎁 Penukaran Terkini</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">Pelanggan</th>
                                    <th>Reward</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $act)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold small text-dark">{{ $act->Nama_Pelanggan }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                            {{ $act->Nama_Reward }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4 small">
                                        Belum ada yang menukar reward
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="importModalLabel">Import Data CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center py-4 bg-light border rounded mb-3">
                         <i class="bi bi-file-earmark-spreadsheet display-4 text-secondary"></i>
                         <p class="text-muted mt-2 mb-0">Pilih file CSV</p>
                    </div>
                    <input type="file" name="file" accept=".csv" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($chartDates);
    const dataPoin = @json($chartPoin);
    const dataRedeem = @json($chartRedeem);

    const commonOptions = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { 
            y: { beginAtZero: true, ticks: { precision: 0 } },
            x: { grid: { display: false },
            offset: true }
        }
    };

    new Chart(document.getElementById('chartPoin'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Poin Masuk',
                data: dataPoin,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: commonOptions
    });

    new Chart(document.getElementById('chartRedeem'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penukaran',
                data: dataRedeem,
                backgroundColor: '#198754',
                borderRadius: 4
            }]
        },
        options: commonOptions
    });
</script>
@endsection