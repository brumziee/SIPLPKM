@extends('layouts.master')

@section('title', 'Log Upload CSV')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Log Upload CSV</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="csv-log-table" class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama File</th>
                        <th>Jumlah Baris</th>
                        <th>Status</th>
                        <th>Error</th>
                        <th>Di-upload Oleh</th>
                        <th>Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $log->filename }}</td>
                        <td>{{ $log->imported_rows }}</td>
                        <td>
                            @if (empty($log->errors) || $log->errors === '[]')
                                <span class="badge bg-success">Berhasil</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </td>
                        <td>{{ is_array($log->errors) ? implode(', ', $log->errors) : $log->errors }}</td>
                        <td>{{ optional($log->user)->name ?? 'System' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->uploaded_at)->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty">
                                <p class="empty-title">Belum ada log upload CSV</p>
                                <p class="empty-subtitle text-muted">
                                    Log akan muncul setiap kali kamu melakukan upload CSV.
                                </p>
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
    @if(count($logs) > 0)
    $('#csv-log-table').DataTable({
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
        }
    });
    @endif
});
</script>
@endpush
