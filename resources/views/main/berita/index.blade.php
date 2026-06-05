@extends('main.layout.app')

@section('title', 'Kelola Berita')

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
                <i class="fas fa-newspaper me-2"></i>Daftar Berita
            </h6>
            @if(Auth::user()->roleId != 2)
            <a href="{{ route('main.berita.create') }}" class="btn btn-sm btn-light" style="font-size: 13px; padding: 6px 12px;">
                <i class="fas fa-plus me-1"></i> Tambah Berita
            </a>
            @endif
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="beritaTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Judul</th>
                            <th style="width: 150px;">Pembuat</th>
                            <th style="width: 100px; text-align: center;">Status</th>
                            <th style="width: 110px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beritas as $index => $berita)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $berita->judul }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($berita->isi, 60) }}</small>
                            </td>
                            <td>{{ $berita->user->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">
                                @if($berita->status === 'approved')
                                    <span class="badge bg-success" style="font-size: 12px;">Approved</span>
                                @elseif($berita->status === 'rejected')
                                    <span class="badge bg-danger" style="font-size: 12px;">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark" style="font-size: 12px;">Draft</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('main.berita.show', $berita->id) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Detail"
                                   style="padding: 4px 10px; font-size: 13px; color: #fff;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->roleId == 1 || (Auth::user()->roleId == 3 && $berita->userId === Auth::id()))
                                    @if($berita->status !== 'draft')
                                    <a href="{{ route('main.berita.edit', $berita->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit"
                                       style="padding: 4px 10px; font-size: 13px; color: #fff; margin-left: 2px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                <button type="button" 
                                        onclick="hapusData('{{ route('main.berita.destroy', $berita->id) }}')" 
                                        class="btn btn-sm btn-danger" 
                                        title="Hapus"
                                        style="padding: 4px 10px; font-size: 13px; margin-left: 2px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
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
    #beritaTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #beritaTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #beritaTable tbody tr:hover {
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
        $('#beritaTable').DataTable({
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
                { orderable: false, targets: [0, 4] },
                { searchable: false, targets: [0, 4] }
            ]
        });
    });

    function hapusData(url) {
        if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
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
