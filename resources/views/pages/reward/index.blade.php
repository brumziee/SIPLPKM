@extends('layouts.master')

@section('title', 'Kelola Reward')

@section('action')
@can('reward.create')
<a href="{{ route('reward.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah Data
</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kelola Reward</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="reward-table" class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        {{-- <th>Gambar</th> --}}
                        <th>Nama Reward</th>
                        <th>Poin Dibutuhkan</th>
                        <th>Dibuat Oleh</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $index => $reward)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        {{-- <td>
                            @if(isset($reward->image))
                            <span class="avatar avatar-sm" style="background-image: url({{ asset('storage/' . $reward->image) }})"></span>
                            @else
                            <span class="avatar avatar-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-gift text-white"></i>
                            </span>
                            @endif
                        </td> --}}
                        <td><strong>{{ $reward->Nama_Reward }}</strong></td>
                        <td>
                            <span class="badge bg-warning">
                                <i class="fas fa-star"></i> {{ number_format($reward->Poin_Dibutuhkan) }} Poin
                            </span>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            @canany(['reward.update', 'reward.delete'])
                                <div class="btn-group" role="group">
                                    @can('reward.update')
                                        <a href="{{ route('reward.edit', $reward->ID_Reward) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M7 7h-1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1"/>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97l-8.415 8.385v3h3l8.385-8.415z"/>
                                                <path d="M16 5l3 3"/>
                                            </svg> Edit
                                        </a>
                                    @endcan
                                    @can('reward.delete')
                                        {{-- PERUBAHAN 1: Menambahkan data-name berisi Nama Reward --}}
                                        <form action="{{ route('reward.destroy', $reward->ID_Reward) }}" 
                                              method="POST" 
                                              class="d-inline form-delete"
                                              data-name="{{ $reward->Nama_Reward }}">
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
                                    @endcan
                                </div>
                            @else
                                <span class="text-muted">Tidak ada aksi</span>
                            @endcanany
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="empty">
                                <div class="empty-icon">
                                    <i class="fas fa-gift fa-3x text-muted"></i>
                                </div>
                                <p class="empty-title">Belum ada reward</p>
                                <p class="empty-subtitle text-muted">
                                    Mulai tambahkan reward untuk program loyalitas Anda
                                </p>
                                @can('reward.create')
                                <div class="empty-action">
                                    <a href="{{ route('reward.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Reward Pertama
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTables
    @if($rewards->count() > 0)
    var table = $('#reward-table').DataTable({
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

    // 2. SweetAlert Logic (Dinamis mengambil nama)
    $(document).on('submit', '.form-delete', function(e) {
        e.preventDefault(); // Matikan submit otomatis browser
        
        var form = this; 
        // PERUBAHAN 2: Mengambil nama reward dari atribut HTML
        var namaReward = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Reward?',
            // PERUBAHAN 3: Menampilkan nama reward secara spesifik
            html: "Apakah Anda yakin ingin menghapus reward <b>" + namaReward + "</b>?",
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