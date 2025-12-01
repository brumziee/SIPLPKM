@extends('layouts.master')

@section('title', 'Tukar Poin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Penukaran Poin</h3>
            </div>

            <form action="{{ route('penukaran-poin.store') }}" method="POST">
                @csrf
                <div class="card-body">

                    {{-- Pilih Pelanggan --}}
                    <div class="mb-3">
                        <label class="form-label required">Pelanggan</label>
                        <select name="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->ID_Pelanggan }}" 
                                    {{ old('pelanggan_id') == $pelanggan->ID_Pelanggan ? 'selected' : '' }}>
                                    {{ $pelanggan->Nama_Pelanggan }} 
                                    ({{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} poin)
                                </option>
                            @endforeach
                        </select>
                        @error('pelanggan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilih Reward --}}
                    <div class="mb-3">
                        <label class="form-label required">Reward</label>
                        <select name="reward_id" class="form-select @error('reward_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Reward --</option>
                            @foreach($rewards as $reward)
                                <option value="{{ $reward->ID_Reward }}" 
                                    {{ old('reward_id') == $reward->ID_Reward ? 'selected' : '' }}>
                                    {{ $reward->Nama_Reward }} 
                                    ({{ $reward->Poin_Dibutuhkan }} poin)
                                </option>
                            @endforeach
                        </select>
                        @error('reward_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Tukar Poin</button>
                    <a href="{{ route('penukaran-poin.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


{{-- ========================= --}}
{{-- SECTION POPUP SWEETALERT --}}
{{-- ========================= --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Popup Error dari Controller --}}
@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Penukaran Gagal',
    text: '{{ session('error') }}',
    confirmButtonText: 'OK'
});
</script>
@endif

{{-- Popup Success --}}
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}',
    confirmButtonText: 'OK'
});
</script>
@endif

{{-- Popup Validasi --}}
@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Validasi Gagal',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonText: 'OK'
});
</script>
@endif
@endsection
