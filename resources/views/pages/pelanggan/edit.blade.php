@extends('layouts.master')

@section('title', 'Edit Pelanggan')

@section('action')
<a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Informasi Pelanggan</h3>
            </div>
            <form action="{{ route('pelanggan.update', $pelanggan->ID_Pelanggan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Nama Pelanggan -->
                    <div class="mb-3">
                        <label class="form-label required">Nama Pelanggan</label>
                        <input type="text" name="Nama_Pelanggan" 
                               class="form-control @error('Nama_Pelanggan') is-invalid @enderror" 
                               placeholder="Masukkan nama pelanggan" 
                               value="{{ old('Nama_Pelanggan', $pelanggan->Nama_Pelanggan) }}" required>
                        @error('Nama_Pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="mb-3">
                        <label class="form-label required">Nomor Telepon</label>
                        <input type="text" name="NoTelp_Pelanggan" 
                               class="form-control @error('NoTelp_Pelanggan') is-invalid @enderror" 
                               placeholder="Contoh: 081234567890" 
                               value="{{ old('NoTelp_Pelanggan', $pelanggan->NoTelp_Pelanggan) }}" required>
                        @error('NoTelp_Pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Nomor telepon harus unik untuk setiap pelanggan</small>
                    </div>

                    <!-- Poin Loyalitas -->
                    <div class="mb-3">
                        <label class="form-label">Poin Loyalitas</label>
                        <input type="number" name="Jumlah_Poin" 
                               class="form-control @error('Jumlah_Poin') is-invalid @enderror" 
                               value="{{ old('Jumlah_Poin', $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0) }}" 
                               min="0">
                        @error('Jumlah_Poin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Ubah jumlah poin pelanggan secara manual</small>
                    </div>

                    <!-- Warning -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Perhatian!</strong><br>
                            <div class="text-muted">
                                Mengubah poin secara manual akan menggantikan nilai poin saat ini: 
                                <strong>{{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} Poin</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Pelanggan
                    </button>
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pelanggan</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">ID Pelanggan:</dt>
                    <dd class="col-7">{{ $pelanggan->ID_Pelanggan }}</dd>
                    
                    <dt class="col-5">Poin Saat Ini:</dt>
                    <dd class="col-7">
                        <span class="badge bg-primary">
                            <i class="fas fa-star"></i> {{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} Poin
                        </span>
                    </dd>
                    
                    <dt class="col-5">Terdaftar:</dt>
                    <dd class="col-7">{{ $pelanggan->created_at->format('d M Y') }}</dd>
                    
                    <dt class="col-5">Terakhir Update:</dt>
                    <dd class="col-7">{{ $pelanggan->updated_at->format('d M Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Panduan Edit Poin</h3>
            </div>
            <div class="card-body">
                <p><strong>Cara Mengubah Poin:</strong></p>
                <ul class="mb-0">
                    <li>Masukkan jumlah poin baru</li>
                    <li>Nilai akan menggantikan poin saat ini</li>
                    <li>Tidak menambah atau mengurangi, tapi mengganti total</li>
                </ul>
                <hr>
                <p><strong>Contoh:</strong></p>
                <p class="text-muted mb-0">
                    <small>Poin saat ini: 100<br>Input: 150<br>Hasil: 150 poin (bukan 250)</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
