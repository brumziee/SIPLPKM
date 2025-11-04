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
                    Tukar Poin Pelanggan
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customer.show', $pelanggan->ID_Pelanggan) }}" class="btn btn-secondary">
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
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Informasi Pelanggan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-5">Nama:</dt>
                            <dd class="col-7"><strong>{{ $pelanggan->Nama_Pelanggan }}</strong></dd>
                            
                            <dt class="col-5">No. Telepon:</dt>
                            <dd class="col-7">{{ $pelanggan->NoTelp_Pelanggan }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-center">
                        <div class="text-muted mb-2">Poin Tersedia</div>
                        <div class="display-5 fw-bold text-primary">
                            {{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }}
                        </div>
                        <div class="text-muted">Poin</div>
                    </div>
                </div>
            </div>

            <!-- Pilih Reward -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Reward</h3>
                    </div>
                    <div class="card-body">
                        @if($rewards->count() > 0)
                        <form action="{{ route('customer.tukar-poin.process', $pelanggan->ID_Pelanggan) }}" method="POST" id="tukarPoinForm">
                            @csrf
                            <div class="row row-cards">
                                @foreach($rewards as $reward)
                                <div class="col-md-6">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="reward_id" value="{{ $reward->ID_Reward }}" class="form-selectgroup-input" required>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div class="form-selectgroup-label-content">
                                                <div class="fw-bold">{{ $reward->Nama_Reward }}</div>
                                                <div class="text-muted">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                                    {{ $reward->Poin_Dibutuhkan }} Poin
                                                </div>
                                                <small class="text-muted">
                                                    Dikelola oleh: {{ $reward->pemilik->Nama_Pemilik ?? '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <hr>

                            <div class="alert alert-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                <div>
                                    <h4 class="alert-title">Perhatian</h4>
                                    <div class="text-muted">
                                        Pastikan pelanggan setuju dengan penukaran poin ini. Poin yang ditukar tidak dapat dikembalikan.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin menukarkan poin ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    Tukar Poin
                                </button>
                                <a href="{{ route('customer.show', $pelanggan->ID_Pelanggan) }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                        @else
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
                            </div>
                            <p class="empty-title">Tidak ada reward tersedia</p>
                            <p class="empty-subtitle text-muted">
                                Poin pelanggan tidak mencukupi untuk menukar reward yang tersedia, atau belum ada reward yang terdaftar.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('customer.show', $pelanggan->ID_Pelanggan) }}" class="btn btn-secondary">
                                    Kembali
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection