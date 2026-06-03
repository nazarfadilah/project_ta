@extends('main.layout.app')

@section('title', 'Kelola Paket Ruangan')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between py-3" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-box-open me-2"></i>Daftar Paket Ruangan
            </h6>
            @if(Auth::user()->roleId != 2)
            <a href="{{ route('main.paket_ruangan.create') }}" class="btn btn-sm btn-light fw-bold" style="font-size: 13px; padding: 6px 14px; border-radius: 5px; color: #B8953F; border: none;">
                <i class="fas fa-plus me-1"></i> Tambah Paket
            </a>
            @endif
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="table-responsive">
                <table id="paketRuanganTable" class="table table-hover table-bordered align-middle w-100" style="font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Paket</th>
                            <th>Ruangan</th>
                            <th>Gedung</th>
                            <th style="width: 120px; text-align: center;">Durasi (Jam)</th>
                            <th style="width: 150px; text-align: right;">Harga Sewa</th>
                            <th style="width: 120px; text-align: center;">Status</th>
                            @if(Auth::user()->roleId != 2)
                            <th style="width: 120px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paketRuangans as $index => $paket)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-dark">{{ $paket->nama_paket }}</strong>
                            </td>
                            <td>{{ $paket->ruangan->nama_ruangan ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $paket->ruangan->gedung->nama_gedung ?? 'N/A' }}</span>
                            </td>
                            <td style="text-align: center;">
                                {{ $paket->durasi ? $paket->durasi . ' Jam' : 'Fleksibel' }}
                            </td>
                            <td style="text-align: right; font-weight: 600;" class="text-success">
                                Rp {{ number_format($paket->harga, 2, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                @if($paket->status === 'ACTIVE')
                                    <span class="badge bg-success" style="font-size: 12px; padding: 5px 10px;">Aktif</span>
                                @elseif($paket->status === 'MAINTENANCE')
                                    <span class="badge bg-warning text-dark" style="font-size: 12px; padding: 5px 10px;">Perbaikan</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 12px; padding: 5px 10px;">Nonaktif</span>
                                @endif
                            </td>
                            @if(Auth::user()->roleId != 2)
                            <td style="text-align: center;">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('main.paket_ruangan.edit', $paket->id) }}" 
                                       class="btn btn-sm btn-warning text-white" 
                                       title="Edit"
                                       style="padding: 5px 10px; font-size: 13px; background-color: #C9A961; border: none;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                             class="btn btn-sm btn-danger" 
                                             title="Hapus"
                                             onclick="confirmDelete('{{ route('main.paket_ruangan.destroy', $paket->id) }}')"
                                             style="padding: 5px 10px; font-size: 13px; background-color: #dc3545; border: none;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->roleId == 2 ? 7 : 8 }}" style="text-align: center; color: #999; padding: 30px;">
                                <i class="fas fa-inbox d-block mb-2 text-secondary" style="font-size: 24px;"></i>
                                Belum ada data paket ruangan.
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    #paketRuanganTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #paketRuanganTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #paketRuanganTable tbody tr:hover {
        background-color: #fdfaf2;
    }
    .dataTables_wrapper {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ccc !important;
        border-radius: 5px !important;
        padding: 5px 8px !important;
        font-size: 13px !important;
        margin: 0 8px !important;
        width: 75px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 6px 12px;
        font-size: 13px;
        margin-left: 8px;
        width: 240px;
    }
    .dataTables_wrapper .dataTables_info {
        float: left;
        font-size: 13px;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        font-size: 13px;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #fff;
        color: #333;
        text-decoration: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #C9A961 !important;
        border-color: #C9A961 !important;
        color: #fff !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #d4ba7a !important;
        border-color: #d4ba7a !important;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
{{-- DataTables JS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#paketRuanganTable').DataTable({
            language: {
                search: "Cari Paket:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 10,
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: @if(Auth::user()->roleId == 2) [0] @else [0, 7] @endif },
                { searchable: false, targets: @if(Auth::user()->roleId == 2) [0] @else [0, 7] @endif }
            ]
        });
    });

    function confirmDelete(url) {
        if (confirm('Apakah Anda yakin ingin menghapus paket ruangan ini?')) {
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
