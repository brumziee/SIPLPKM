@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    <!-- Notification Sukses / Error -->
    @if(session('message'))
        <div class="text-center mb-3">
            <div class="alert alert-dismissible fade show" role="alert" style="background-color: {{ session('message_type') == 'success' ? '#28a745' : (session('message_type') == 'warning' ? '#ffc107' : '#dc3545') }}; color: #fff;">
                {{ session('message') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Statistic Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-people-fill display-4 text-primary"></i>
                    <h5 class="mt-2 card-title">Total Pelanggan</h5>
                    <h2 class="fw-bold">{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-gift-fill display-4 text-success"></i>
                    <h5 class="mt-2 card-title">Total Reward</h5>
                    <h2 class="fw-bold">{{ $totalRewards }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pelanggan & Top Reward -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top 3 Pelanggan dengan Poin Tertinggi</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($topPelanggan as $customer)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $customer->Nama_Pelanggan }}
                                <span class="badge bg-primary rounded-pill">{{ $customer->total_poin }} poin</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Reward Paling Banyak Ditukar</h5>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($topRewards as $reward)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $reward->Nama_Reward }}
                                <span class="badge bg-success rounded-pill">{{ $reward->total_terpakai }} kali</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 7 Hari Terakhir -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Total Poin Pelanggan (7 Hari Terakhir)</h5>
                    <canvas id="chartPoin"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Total Penukaran Reward (7 Hari Terakhir)</h5>
                    <canvas id="chartPenukaran"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Import CSV -->
    <div class="text-center mt-4">
        <button type="button" class="btn btn-danger px-4 py-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload"></i> Import CSV
        </button>
    </div>

</div>

<!-- Modal Import CSV -->
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
                    <p class="text-muted mb-3">Pilih file CSV yang ingin diunggah untuk menambah data ke sistem.</p>
                    <input type="file" name="file" accept=".csv" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-cloud-arrow-up"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const poinData = @json($poin7Hari);
    const penukaranData = @json($penukaran7Hari);

    const labels = [...Array(7).keys()].map(i => {
        const d = new Date();
        d.setDate(d.getDate() - (6 - i));
        return d.toISOString().slice(0,10);
    });

    const poinTotals = labels.map(l => {
        const match = poinData.find(d => d.date === l);
        return match ? parseInt(match.total_poin) : 0;
    });

    const penukaranTotals = labels.map(l => {
        const match = penukaranData.find(d => d.date === l);
        return match ? parseInt(match.total_terpakai) : 0;
    });

    // Chart Poin
    new Chart(document.getElementById('chartPoin'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Poin',
                data: poinTotals,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.2)', // biru transparan
                borderColor: '#0d6efd',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Chart Penukaran dengan arsir hijau
    new Chart(document.getElementById('chartPenukaran'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penukaran',
                data: penukaranTotals,
                fill: true,
                backgroundColor: 'rgba(25, 135, 84, 0.2)', // hijau transparan
                borderColor: '#198754',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
