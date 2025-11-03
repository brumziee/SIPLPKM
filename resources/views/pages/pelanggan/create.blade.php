@extends('layouts.master')

@section('title', 'Tambah Pelanggan')

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
                <h3 class="card-title">Informasi Pelanggan</h3>
            </div>
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Pelanggan</label>
                        <input type="text" name="Nama_Pelanggan" class="form-control @error('Nama_Pelanggan') is-invalid @enderror" 
                            placeholder="Masukkan nama pelanggan" value="{{ old('Nama_Pelanggan') }}" required>
                        @error('Nama_Pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nomor Telepon</label>
                        <input type="text" name="NoTelp_Pelanggan" class="form-control @error('NoTelp_Pelanggan') is-invalid @enderror" 
                            placeholder="Contoh: 081234567890" value="{{ old('NoTelp_Pelanggan') }}" required>
                        @error('NoTelp_Pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Nomor telepon harus unik untuk setiap pelanggan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin Awal (Opsional)</label>
                        <input type="number" name="Poin_Loyalitas" class="form-control @error('Poin_Loyalitas') is-invalid @enderror" 
                            value="{{ old('Poin_Loyalitas', 0) }}" min="0">
                        @error('Poin_Loyalitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Isi jika ingin memberikan poin awal selain 0</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Jika poin awal tidak diisi, pelanggan akan otomatis mendapatkan 0 poin.
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pelanggan
                    </button>
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Panduan</h3>
            </div>
            <div class="card-body">
                <p><strong>Informasi Wajib:</strong></p>
                <ul>
                    <li>Nama Pelanggan</li>
                    <li>Nomor Telepon</li>
                </ul>
                <hr>
                <p><strong>Tips:</strong></p>
                <ul class="mb-0">
                    <li>Poin awal dapat diisi jika ingin langsung memberikan poin</li>
                    <li>Pastikan nomor telepon valid dan unik</li>
                </ul>
                <hr>
                <p><strong>Catatan:</strong></p>
                <p class="text-muted mb-0">
                    <small>Poin awal pelanggan otomatis 0 jika tidak diisi dan bisa ditambahkan melalui transaksi atau manual.</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
