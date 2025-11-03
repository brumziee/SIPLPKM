@extends('layouts.app')

@section('content')
<div class="container-xl">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Kelola Poin Pelanggan
                </div>
                <h2 class="page-title">
                    Detail Pelanggan
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="9" y2="16" /><line x1="5" y1="12" x2="9" y2="8" /></svg>
                        Kembali
                    </a>
                    @can('customer.update')
                    <a href="{{ route('customer.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /></svg>
                        Edit
                    </a>
                    <a href="{{ route('customer.tukar-poin', $pelanggan->ID_Pelanggan) }}" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 12 20 22 4 22 4 12" /><rect x="2" y="7" width="20" height="5" /><line x1="12" y1="22" x2="12" y2="7" /></svg>
                        Tukar Poin
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row row-deck row-cards">
            <!-- Informasi Pelanggan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">ID:</dt>
                            <dd class="col-7">{{ $pelanggan->ID_Pelanggan }}</dd>
                            
                            <dt class="col-5">Nama:</dt>
                            <dd class="col-7"><strong>{{ $pelanggan->Nama_Pelanggan }}</strong></dd>
                            
                            <dt class="col-5">No. Telepon:</dt>
                            <dd class="col-7">{{ $pelanggan->NoTelp_Pelanggan }}</dd>
                            
                            <dt class="col-5">Terdaftar:</dt>
                            <dd class="col-7">{{ $pelanggan->created_at->format('d M Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Poin Loyalitas</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="display-5 fw-bold text-primary mb-2">
                            {{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }}
                        </div>
                        <div class="text-muted">Total Poin</div>
                        
                        @can('customer.update')
                        <hr>
                        <form action="{{ route('customer.tambah-poin', $pelanggan->ID_Pelanggan) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="number" name="jumlah_poin" class="form-control" placeholder="Jumlah poin" min="1" required>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                    Tambah
                                </button>
                            </div>
                            <small class="form-hint">Tambah poin secara manual</small>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Riwayat -->
            <div class="col-md-8">
                <!-- Riwayat Penukaran Poin -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Penukaran Poin</h3>
                    </div>
                    <div class="card-body">
                        @if($riwayatPenukaran->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Reward</th>
                                        <th>Poin Ditukar</th>
                                        <th>Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPenukaran as $penukaran)
                                    <tr>
                                        <td>{{ $penukaran->Tanggal_Penukaran->format('d/m/Y H:i') }}</td>
                                        <td>{{ $penukaran->reward->Nama_Reward }}</td>
                                        <td>
                                            <span class="badge bg-red">
                                                -{{ $penukaran->Jumlah_Poin_Ditukar }} Poin
                                            </span>
                                        </td>
                                        <td>{{ $penukaran->pegawai->Nama_Pegawai ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                            </div>
                            <p class="empty-title">Belum ada penukaran poin</p>
                            <p class="empty-subtitle text-muted">
                                Pelanggan belum pernah menukarkan poin
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Transaksi</h3>
                    </div>
                    <div class="card-body">
                        @if($riwayatTransaksi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatTransaksi as $transaksi)
                                    <tr>
                                        <td>{{ $transaksi->Tanggal_Transaksi->format('d/m/Y H:i') }}</td>
                                        <td>Rp {{ number_format($transaksi->Jumlah_Transaksi, 0, ',', '.') }}</td>
                                        <td>{{ $transaksi->pegawai->Nama_Pegawai ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                            </div>
                            <p class="empty-title">Belum ada transaksi</p>
                            <p class="empty-subtitle text-muted">
                                Pelanggan belum pernah melakukan transaksi
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection