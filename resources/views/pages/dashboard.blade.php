@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistic Cards (full width) -->
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
    <div class="row">
        <!-- Top Pelanggan -->
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

        <!-- Top Reward -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Reward Paling Banyak Ditukar</h5>
                    <ul class="list-group list-group-flush">
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
</div>
@endsection
