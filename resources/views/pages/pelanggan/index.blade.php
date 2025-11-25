@extends('layouts.master')

@section('title', 'Kelola Pelanggan')

@section('action')
@can('pelanggan.view')
<a href="{{ route('pelanggan.export.csv') }}" class="btn btn-danger me-2">
    <i class="fas fa-download"></i> Export CSV
</a>
@endcan

@can('pelanggan.store')
<a href="{{ route('pelanggan.create') }}" class="btn btn-primary me-2">
    <i class="fas fa-plus"></i> Tambah Data
</a>
@endcan

@can('penukaran.create')
<a href="{{ route('penukaran-poin.create') }}" class="btn btn-success">
    <i class="fas fa-exchange-alt"></i> Penukaran Poin
</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kelola Pelanggan</h3>
    </div>
    <div class="card-body">

        {{-- SEARCH START --}}
        <div class="mb-3">
            <input type="text" id="search-input" class="form-control" placeholder="Cari nama atau nomor telepon pelanggan...">
        </div>
        {{-- SEARCH END --}}

        <div class="table-responsive">
            <table id="pelanggan-table" class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Poin Saat Ini</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $index => $pelanggan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $pelanggan->Nama_Pelanggan }}</strong></td>
                        <td>{{ $pelanggan->NoTelp_Pelanggan }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} Poin
                            </span>
                        </td>
                        <td>
                            @canany(['pelanggan.update', 'pelanggan.delete'])
                                <div class="d-flex gap-2">
                                    @can('pelanggan.update')
                                    <a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    @can('pelanggan.delete')
                                    {{-- PERUBAHAN 1: Tambah class form-delete & data-name --}}
                                    <form action="{{ route('pelanggan.destroy', $pelanggan->ID_Pelanggan) }}" 
                                          method="POST" 
                                          class="d-inline form-delete"
                                          data-name="{{ $pelanggan->Nama_Pelanggan }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            @else
                                <span class="text-muted">Tidak ada aksi</span>
                            @endcanany
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="empty">
                                <div class="empty-icon">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                                <p class="empty-title">Belum ada pelanggan</p>
                                <p class="empty-subtitle text-muted">
                                    Mulai tambahkan pelanggan untuk program loyalitas Anda
                                </p>
                                @can('pelanggan.store')
                                <div class="empty-action">
                                    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Pelanggan Pertama
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
{{-- PERUBAHAN 2: Load SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    @if($pelanggans->count() > 0)
    var table = $('#pelanggan-table').DataTable({
        "responsive": true,
        "autoWidth": false,
        // "searching": false,  <-- HAPUS INI (jika false, API search tidak jalan)
        "dom": "rtip",       // <-- GANTI DENGAN INI (Menyembunyikan kotak search bawaan, tapi fungsi search tetap aktif)
        "lengthChange": false,
        "language": {
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

    // Custom Search Logic
    $('#search-input').on('keyup', function() {
        table.search(this.value).draw();
    });
    @endif

    // PERUBAHAN 3: SweetAlert Logic (Dynamic Name & Event Delegation)
    $(document).on('submit', '.form-delete', function(e) {
        e.preventDefault(); // Mencegah submit default
        
        var form = this;
        var namaPelanggan = $(this).data('name'); // Ambil nama dari atribut HTML
        
        Swal.fire({
            title: 'Hapus Pelanggan?',
            html: "Apakah Anda yakin ingin menghapus data pelanggan <b>" + namaPelanggan + "</b>?<br><small class='text-danger'>Poin dan riwayat penukaran juga akan terhapus!</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush