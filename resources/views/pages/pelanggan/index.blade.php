@extends('layouts.master')

@section('title', 'Kelola Pelanggan')

@section('action')
    @can('pelanggan.view')
        <a href="{{ route('pelanggan.export.csv') }}" class="btn btn-danger me-2">
            <i class="fas fa-download"></i> Export CSV
        </a>
    @endcan

    {{-- Dipakai jika mau menambahkan fitur create pada website --}}
    {{-- @can('pelanggan.store')
<a href="{{ route('pelanggan.create') }}" class="btn btn-primary me-2">
    <i class="fas fa-plus"></i> Tambah Data
</a>
@endcan --}}

    @can('penukaran.create')
        <a href="{{ route('penukaran-poin.create') }}" class="btn btn-primary">
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
                                    <span class="badge bg-success">
                                        {{ $pelanggan->poinLoyalitas->Jumlah_Poin ?? 0 }} Poin
                                    </span>
                                </td>
                                <td>
                                    @canany(['pelanggan.update', 'pelanggan.delete'])
                                        <div class="d-flex gap-2">
                                            {{-- Dipakai jika mau menambahkan fitur create pada website --}}
                                            {{-- @can('pelanggan.update')
                                    <a href="{{ route('pelanggan.edit', $pelanggan->ID_Pelanggan) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan --}}
                                            @can('pelanggan.delete')
                                                {{-- PERUBAHAN 1: Tambah class form-delete & data-name --}}
                                                <form action="{{ route('pelanggan.destroy', $pelanggan->ID_Pelanggan) }}"
                                                    method="POST" class="d-inline form-delete"
                                                    data-name="{{ $pelanggan->Nama_Pelanggan }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4 7l16 0" />
                                                            <path d="M10 11l0 6" />
                                                            <path d="M14 11l0 6" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                                        </svg> Hapus
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
            @if ($pelanggans->count() > 0)
                var table = $('#pelanggan-table').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "lengthChange": true,
                    "language": {
                        "search": "Cari:",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        "infoEmpty": "Tidak ada data",
                        "infoFiltered": "(disaring dari _MAX_ total data)",
                        "zeroRecords": "Tidak ada data yang cocok",
                        "emptyTable": "Tidak ada data"
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
                    html: "Apakah Anda yakin ingin menghapus data pelanggan <b>" + namaPelanggan +
                        "</b>?<br><small class='text-danger'>Poin dan riwayat penukaran juga akan terhapus!</small>",
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
