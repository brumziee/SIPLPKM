@extends('layouts.app')

@section('content')
<div class="container-xl">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Riwayat Penukaran Poin
                </div>
                <h2 class="page-title">
                    Detail Penukaran
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('sales.history') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="9" y2="16" /><line x1="5" y1="12" x2="9" y2="8" /></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- Transaction Overview -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Penukaran</h3>
                        <div class="card-actions">
                            <span class="badge bg-success">Selesai</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Transaction Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Informasi Transaksi</h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%"><strong>ID Transaksi:</strong></td>
                                        <td>{{ $penukaran->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>{{ $penukaran->Tanggal_Penukaran->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pegawai:</strong></td>
                                        <td>{{ $penukaran->pegawai->Nama_Pegawai ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pemilik:</strong></td>
                                        <td>{{ $penukaran->pemilik->Nama_Pemilik ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Informasi Pelanggan</h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Nama:</strong></td>
                                        <td>{{ $penukaran->pelanggan->Nama_Pelanggan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon:</strong></td>
                                        <td>{{ $penukaran->pelanggan->NoTelp_Pelanggan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Poin Saat Ini:</strong></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ number_format($penukaran->pelanggan->poinLoyalitas->Jumlah_Poin ?? 0) }} Poin
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Reward Detail -->
                        <h5>Detail Reward</h5>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        @if(isset($penukaran->reward->image))
                                        <img src="{{ asset('storage/' . $penukaran->reward->image) }}" 
                                            alt="{{ $penukaran->reward->Nama_Reward }}" 
                                            class="avatar avatar-xl rounded"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                        <div class="avatar avatar-xl rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-white" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 12 20 22 4 22 4 12" /><rect x="2" y="7" width="20" height="5" /><line x1="12" y1="22" x2="12" y2="7" /></svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <h3 class="mb-1">{{ $penukaran->reward->Nama_Reward ?? '-' }}</h3>
                                        <div class="text-muted">
                                            Poin dibutuhkan: 
                                            <span class="badge bg-warning text-dark">
                                                {{ number_format($penukaran->reward->Poin_Dibutuhkan ?? 0) }} Poin
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Point Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Poin</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Poin Ditukar:</strong></td>
                                <td class="text-end">
                                    <span class="badge bg-warning text-dark fs-3">
                                        {{ number_format($penukaran->Jumlah_Poin_Ditukar) }} Poin
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <div class="bg-light rounded p-3 mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Reward:</strong></span>
                                <span>{{ $penukaran->reward->Nama_Reward ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><strong>Status:</strong></span>
                                <span class="badge bg-success">Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('customer.show', $penukaran->ID_Pelanggan) }}" class="btn btn-outline-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                                Lihat Profil Pelanggan
                            </a>
                            <a href="{{ route('product.show', $penukaran->ID_Reward) }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 12 20 22 4 22 4 12" /><rect x="2" y="7" width="20" height="5" /></svg>
                                Lihat Detail Reward
                            </a>
                            <a href="{{ route('customer.index') }}" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 12 20 22 4 22 4 12" /><rect x="2" y="7" width="20" height="5" /></svg>
                                Penukaran Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection