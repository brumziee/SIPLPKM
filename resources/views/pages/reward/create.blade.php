@extends('layouts.master')

@section('title', 'Tambah Reward')

@section('action')
<a href="{{ route('reward.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Reward</h3>
            </div>
            <form action="{{ route('reward.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Reward</label>
                        <input type="text" name="Nama_Reward" class="form-control @error('Nama_Reward') is-invalid @enderror" 
                            placeholder="Contoh: Diskon 10%, Gratis 1 Item" value="{{ old('Nama_Reward') }}" required>
                        @error('Nama_Reward')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Poin yang Dibutuhkan</label>
                        <input type="number" name="Poin_Dibutuhkan" class="form-control @error('Poin_Dibutuhkan') is-invalid @enderror" 
                            placeholder="Contoh: 100" value="{{ old('Poin_Dibutuhkan') }}" min="1" required>
                        @error('Poin_Dibutuhkan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Jumlah poin yang harus ditukarkan pelanggan untuk mendapatkan reward ini</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Reward (Opsional)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    </div>

                    <!-- Info: Pembuat otomatis dari user login -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Reward ini akan otomatis tercatat atas nama Anda 
                        @if(auth()->user()->hasRole('admin'))
                            sebagai <strong>Owner</strong>.
                        @elseif(auth()->user()->hasRole('kasir'))
                            sebagai <strong>Staff</strong>.
                        @endif
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Reward
                    </button>
                    <a href="{{ route('reward.index') }}" class="btn btn-secondary">
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
                    <li>Nama Reward</li>
                    <li>Poin yang Dibutuhkan</li>
                </ul>
                <hr>
                <p><strong>Tips:</strong></p>
                <ul class="mb-0">
                    <li>Buat nama reward yang jelas dan menarik</li>
                    <li>Sesuaikan poin dengan nilai reward</li>
                    <li>Tambahkan gambar untuk menarik perhatian</li>
                </ul>
                <hr>
                <p><strong>Catatan:</strong></p>
                <p class="text-muted mb-0">
                    <small>Reward akan otomatis tercatat dengan nama Anda sebagai pembuat.</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection