@extends('layouts.master')

@section('title', 'Tukar Poin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tukar Poin</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('penukaran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Pilih Pelanggan</label>
                <select name="pelanggan_id" class="form-control">
                    @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->ID_Pelanggan }}" data-points="{{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }}">
                        {{ $pelanggan->Nama_Pelanggan }} ({{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} Poin)
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pilih Reward</label>
                <select name="reward_id" class="form-control" id="reward-select">
                    @foreach($rewards as $reward)
                    <option value="{{ $reward->ID_Reward }}" data-required="{{ $reward->Poin_Dibutuhkan }}">
                        {{ $reward->Nama_Reward }} ({{ $reward->Poin_Dibutuhkan }} Poin)
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Poin Saat Ini</label>
                <input type="text" id="current-points" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label>Poin Diperlukan</label>
                <input type="text" id="required-points" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-success">Tukar Poin</button>
        </form>
    </div>
</div>
@endsection

@push('addon-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pelangganSelect = document.querySelector('select[name="pelanggan_id"]');
    const rewardSelect = document.getElementById('reward-select');
    const currentPoints = document.getElementById('current-points');
    const requiredPoints = document.getElementById('required-points');

    function updatePoints() {
        const pelangganOption = pelangganSelect.options[pelangganSelect.selectedIndex];
        const rewardOption = rewardSelect.options[rewardSelect.selectedIndex];

        currentPoints.value = pelangganOption.getAttribute('data-points') || 0;
        requiredPoints.value = rewardOption.getAttribute('data-required');
    }

    pelangganSelect.addEventListener('change', updatePoints);
    rewardSelect.addEventListener('change', updatePoints);
    updatePoints();
});
</script>
@endpush
