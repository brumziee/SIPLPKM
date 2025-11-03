@extends('layouts.master')

@section('title', 'Edit Reward')

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
                <h3 class="card-title">Edit Informasi Reward</h3>
            </div>
            <form action="{{ route('reward.update', $reward->ID_Reward) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Reward</label>
                        <input type="text" name="Nama_Reward" class="form-control @error('Nama_Reward') is-invalid @enderror" 
                            placeholder="Contoh: Diskon 10%, Gratis 1 Item" value="{{ old('Nama_Reward', $reward->Nama_Reward) }}" required>
                        @error('Nama_Reward')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Poin yang Dibutuhkan</label>
                        <input type="number" name="Poin_Dibutuhkan" class="form-control @error('Poin_Dibutuhkan') is-invalid @enderror" 
                            placeholder="Contoh: 100" value="{{ old('Poin_Dibutuhkan', $reward->Poin_Dibutuhkan) }}" min="1" required>
                        @error('Poin_Dibutuhkan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Jumlah poin yang harus ditukarkan pelanggan untuk mendapatkan reward ini</small>
                    </div>

                    {{-- @if(isset($reward->image))
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div>
                            <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->Nama_Reward }}" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">{{ isset($reward->image) ? 'Ganti Gambar (Opsional)' : 'Gambar Reward (Opsional)' }}</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div> --}}

                    <!-- Info: Update otomatis akan mengubah pembuat -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Setelah di-update, pembuat reward akan berubah menjadi Anda 
                        @if(auth()->user()->hasRole('admin'))
                            sebagai <strong>Owner</strong>.
                        @elseif(auth()->user()->hasRole('kasir'))
                            sebagai <strong>Staff</strong>.
                        @endif
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Reward
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
                <h3 class="card-title">Informasi Reward</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">ID Reward:</dt>
                    <dd class="col-7">{{ $reward->ID_Reward }}</dd>
                    
                    <dt class="col-5">Dibuat Oleh:</dt>
                    <dd class="col-7">
                        @if($reward->pemilik)
                            <span class="badge bg-primary">
                                <i class="fas fa-user-tie"></i> {{ $reward->pemilik->Nama_Pemilik }}
                            </span>
                        @elseif($reward->pegawai)
                            <span class="badge bg-info">
                                <i class="fas fa-user"></i> {{ $reward->pegawai->Nama_Pegawai }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </dd>
                    
                    <dt class="col-5">Dibuat:</dt>
                    <dd class="col-7">{{ $reward->created_at->format('d M Y H:i') }}</dd>
                    
                    <dt class="col-5">Terakhir Update:</dt>
                    <dd class="col-7">{{ $reward->updated_at->format('d M Y H:i') }}</dd>
                </dl>
                <hr>
                <div class="d-grid">
                    <a href="{{ route('reward.show', $reward->ID_Reward) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection