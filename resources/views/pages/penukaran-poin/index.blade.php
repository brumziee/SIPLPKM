@extends('layouts.master')

@section('title', 'Kelola Penukaran Poin')

@section('action')
@can('penukaran-poin.create')
<a href="{{ route('penukaran-poin.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tukar Poin
</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Penukaran Poin</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="penukaran-poin-table" class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Reward</th>
                        <th>Jumlah Poin</th>
                        <th>Tanggal Penukaran</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penukarans as $index => $penukaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $penukaran['Nama_Pelanggan'] ?? 'Tidak Diketahui' }}</strong>
                        </td>
                        <td>
                            <strong>{{ $penukaran['Nama_Reward'] ?? 'Reward Dihapus' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-warning">
                                <i class="fas fa-star"></i> {{ number_format($penukaran['Poin_Ditukar']) }} Poin
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($penukaran['Tanggal_Penukaran'])->format('d M Y, H:i') }}</td>
                        <td>
                            @can('penukaran-poin.delete')
                                <form action="{{ route('penukaran-poin.destroy', $penukaran['ID_Penukaran']) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus penukaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 7l16 0"/>
                                            <path d="M10 11l0 6"/>
                                            <path d="M14 11l0 6"/>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12"/>
                                            <path d="M9 7v-3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3"/>
                                        </svg> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Tidak ada aksi</span>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="empty">
                                <div class="empty-icon">
                                    <i class="fas fa-gift fa-3x text-muted"></i>
                                </div>
                                <p class="empty-title">Belum ada penukaran poin</p>
                                <p class="empty-subtitle text-muted">
                                    Data penukaran poin akan muncul setelah pelanggan menukarkan reward.
                                </p>
                                @can('penukaran-poin.create')
                                <div class="empty-action">
                                    <a href="{{ route('penukaran-poin.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tukar Poin Sekarang
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
$(document).ready(function() {
    @if(count($penukarans) > 0)
    $('#penukaran-poin-table').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "infoFiltered": "(disaring dari _MAX_ total data)",
            "zeroRecords": "Tidak ada data yang cocok",
            "emptyTable": "Tidak ada data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
    @endif
});
</script>
@endpush
