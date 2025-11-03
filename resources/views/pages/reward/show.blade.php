<dt class="col-4">Dibuat Oleh:</dt>
<dd class="col-8">
    @if($reward->pemilik)
        <span class="badge bg-primary">
            <i class="fas fa-user-tie"></i> {{ $reward->pemilik->Nama_Pemilik }} (Owner)
        </span>
    @elseif($reward->pegawai)
        <span class="badge bg-info">
            <i class="fas fa-user"></i> {{ $reward->pegawai->Nama_Pegawai }} (Staff)
        </span>
    @else
        <span class="badge bg-secondary">Unknown</span>
    @endif
</dd>