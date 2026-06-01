@extends('main.layout.app')

@section('title', 'Kelola Sarana')

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
                <i class="fas fa-tools me-2"></i>Daftar Sarana
            </h6>
            <a href="{{ route('main.sarana.create') }}" class="btn btn-sm btn-light" style="font-size: 13px; padding: 6px 12px;">
                <i class="fas fa-plus me-1"></i> Tambah Sarana
            </a>
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="saranaTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Sarana</th>
                            <th>Kondisi</th>
                            <th>Tgl Penerimaan</th>
                            <th>Stok</th>
                            <th style="width: 110px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saranas as $index => $sarana)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $sarana->nama }}</td>
                            <td>
                                @if($sarana->kondisi === 'Baik Sekali')
                                    <span class="badge bg-success">Baik Sekali</span>
                                @elseif($sarana->kondisi === 'Baik')
                                    <span class="badge bg-primary">Baik</span>
                                @elseif($sarana->kondisi === 'Normal')
                                    <span class="badge bg-info text-dark">Normal</span>
                                @else
                                    <span class="badge bg-danger">Perlu Perbaikan</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($sarana->tgl_penerimaan)->format('d M Y') }}</td>
                            <td>{{ $sarana->stok }}</td>
                            <td style="text-align: center;">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('main.sarana.edit', $sarana->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit"
                                       style="padding: 4px 10px; font-size: 13px; color: #fff;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            title="Hapus"
                                            style="padding: 4px 10px; font-size: 13px;"
                                            onclick="hapusData('{{ route('main.sarana.destroy', $sarana->id) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                                <i class="fas fa-inbox" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                Belum ada data sarana
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
    #saranaTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #saranaTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #saranaTable tbody tr:hover {
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
        $('#saranaTable').DataTable({
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
                { orderable: false, targets: [0, 5] },
                { searchable: false, targets: [0, 5] }
            ]
        });
    });

    function hapusData(url) {
        if (confirm('Apakah Anda yakin ingin menghapus sarana ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
