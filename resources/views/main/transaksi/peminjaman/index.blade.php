@extends('main.layout.app')

@section('title', 'Daftar Peminjaman/Reservasi')

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
                <i class="fas fa-book me-2"></i>Daftar Peminjaman/Reservasi
            </h6>
            @if(Auth::user()->roleId != 2)
            <a href="{{ route('main.transaksi.peminjaman.create') }}" class="btn btn-sm btn-light" style="font-size: 13px; padding: 6px 12px;">
                <i class="fas fa-plus me-1"></i> Tambah Reservasi
            </a>
            @endif
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="peminjamanTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Kode Peminjaman</th>
                            <th>Nama Tamu/Peminjam</th>
                            <th>Ruangan</th>
                            <th>Tanggal</th>
                            <th style="width: 150px; text-align: center;">Status Approval</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $item->kodePeminjaman }}</td>
                            <td>{{ $item->guest->name ?? 'N/A' }}</td>
                            <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td style="text-align: center;">
                                @if($item->statusApproval === 'PENDING')
                                    <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 12px; font-weight: 600;">PENDING</span>
                                @elseif($item->statusApproval === 'APPROVED')
                                    <span class="badge bg-success px-3 py-2" style="font-size: 12px; font-weight: 600;">APPROVED</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2" style="font-size: 12px; font-weight: 600;">REJECTED</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Detail"
                                   style="padding: 4px 10px; font-size: 13px;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
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
    #peminjamanTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #peminjamanTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #peminjamanTable tbody tr:hover {
        background-color: #fdf6e3;
    }
    .dataTables_wrapper {
        padding: 0;
        overflow: auto;
    }
    .dataTables_wrapper .dataTables_length {
        display: inline-block;
        margin-bottom: 15px;
        margin-right: 20px;
        float: left;
    }
    .dataTables_wrapper .dataTables_filter {
        display: inline-block;
        margin-bottom: 15px;
        float: right;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ccc !important;
        border-radius: 5px !important;
        padding: 5px 8px !important;
        font-size: 13px !important;
        margin: 0 8px !important;
        width: 60px !important;
        max-width: none !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 6px 10px;
        font-size: 13px;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_info {
        display: inline-block;
        font-size: 13px;
        margin-top: 15px;
        margin-right: 20px;
        clear: both;
    }
    .dataTables_wrapper .dataTables_paginate {
        display: inline-block;
        font-size: 13px;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 10px;
        margin: 0 4px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #fff;
        color: #333;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #C9A961 !important;
        border-color: #C9A961 !important;
        color: #fff !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
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
        $('#peminjamanTable').DataTable({
            language: {
                search: "Cari:",
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
                { orderable: false, targets: [0, 6] },
                { searchable: false, targets: [0, 5, 6] }
            ]
        });
    });
</script>
@endpush
