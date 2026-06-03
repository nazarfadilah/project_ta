@extends('main.layout.app')

@section('title', 'Kelola Peminjaman Sarana')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-dolly me-2"></i>Daftar Peminjaman Sarana
            </h6>
            @if(Auth::user()->roleId != 2)
            <a href="{{ route('main.peminjaman_sarana.create') }}" class="btn btn-sm btn-light" style="font-size: 13px; padding: 6px 12px;">
                <i class="fas fa-plus me-1"></i> Buat Peminjaman
            </a>
            @endif
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="peminjamanTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Sarana</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            @if(Auth::user()->roleId != 2)
                            <th style="width: 110px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanSaranas as $index => $peminjaman)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $peminjaman->detailSaranas()->first()?->sarana->nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}</td>
                            <td>{{ $peminjaman->durasi }} jam</td>
                            <td>{{ $peminjaman->detailSaranas()->sum('jumlah') }} unit</td>
                            <td>
                                @php
                                    $statusClass = match($peminjaman->statusPeminjaman) {
                                        'RESERVASI' => 'bg-info',
                                        'CHECK_IN' => 'bg-warning',
                                        'CHECK_OUT' => 'bg-success',
                                        'BATAL' => 'bg-danger',
                                        'SELESAI' => 'bg-secondary',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $peminjaman->statusPeminjaman }}</span>
                            </td>
                            @if(Auth::user()->roleId != 2)
                            <td style="text-align: center;">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('main.peminjaman_sarana.edit', $peminjaman->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit"
                                       style="padding: 4px 10px; font-size: 13px; color: #fff;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            title="Hapus"
                                            style="padding: 4px 10px; font-size: 13px;"
                                            onclick="hapusData('{{ route('main.peminjaman_sarana.destroy', $peminjaman->id) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->roleId == 2 ? 6 : 7 }}" style="text-align: center; color: #999; padding: 30px;">
                                <i class="fas fa-inbox" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                Belum ada peminjaman sarana
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #C9A961 !important;
        border-color: #C9A961 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #C9A961 !important;
        border-color: #C9A961 !important;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#peminjamanTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
        }
    });
});

function hapusData(url) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
