@extends('layouts.app')

@section('content')
<div class="container-xl">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Riwayat Penukaran Poin
                </h2>
                <div class="text-muted mt-1">Menampilkan {{ $penukarans->total() }} riwayat penukaran</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('customer.index') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 12 20 22 4 22 4 12" /><rect x="2" y="7" width="20" height="5" /><line x1="12" y1="22" x2="12" y2="7" /></svg>
                    Tukar Poin
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Search Form (SKPL-SIPLPKM-004-02) -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('sales.history') }}" method="GET">
                    <div class="row g-2">
                        <div class="col">
                            <input type="text" name="search" class="form-control" 
                                placeholder="Cari berdasarkan ID Transaksi, Nama Pelanggan, atau Nama Reward..." 
                                value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                                Cari
                            </button>
                            @if($search)
                            <a href="{{ route('sales.history') }}" class="btn btn-secondary">Reset</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Riwayat Penukaran Poin</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="penukaran-table" class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Reward</th>
                                <th>Poin Ditukar</th>
                                <th>Pegawai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penukarans as $penukaran)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $penukaran->transaction_id }}</span>
                                </td>
                                <td>
                                    {{ $penukaran->Tanggal_Penukaran->format('d/m/Y H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $penukaran->Tanggal_Penukaran->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $penukaran->pelanggan->Nama_Pelanggan ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $penukaran->pelanggan->NoTelp_Pelanggan ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $penukaran->reward->Nama_Reward ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ number_format($penukaran->reward->Poin_Dibutuhkan ?? 0) }} poin</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                                        {{ number_format($penukaran->Jumlah_Poin_Ditukar) }} Poin
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $penukaran->pegawai->Nama_Pegawai ?? '-' }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('sales.show', $penukaran->ID_Penukaran) }}" class="btn btn-sm btn-info" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
                                        <p>Belum ada riwayat penukaran poin</p>
                                        <a href="{{ route('customer.index') }}" class="btn btn-primary">Mulai Tukar Poin</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($penukarans->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $penukarans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if($penukarans->count() > 0)
    $('#penukaran-table').DataTable({
        "paging": false,
        "info": false,
        "searching": false,
        "ordering": true,
        "order": [[1, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ],
        "language": {
            "search": "Cari:",
            "zeroRecords": "Tidak ada data yang cocok",
            "emptyTable": "Tidak ada data tersedia dalam tabel"
        }
    });
    @endif
});
</script>
@endpush