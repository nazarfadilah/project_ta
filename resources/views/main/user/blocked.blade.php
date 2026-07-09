@extends('main.layout.app')

@section('title', 'Kelola Pengguna Terblokir')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #ab8b46; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-user-slash me-2"></i>Daftar Pengguna Terblokir
            </h6>
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="blockedUsersTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Pengguna</th>
                            <th>Alamat Email</th>
                            <th style="width: 80px; text-align: center;">Role</th>
                            <th style="width: 120px; text-align: center;">Status</th>
                            <th style="width: 150px; text-align: center;">Alasan Blokir</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td style="text-align: center;"><span class="badge bg-info">{{ $user->role->name ?? 'User' }}</span></td>
                            <td style="text-align: center;">
                                @if($user->status === 'SUSPENDED')
                                    <span class="badge bg-warning text-dark">Di Blokir Sementara</span>
                                @elseif($user->status === 'SUSPENDED_PERMANENT')
                                    <span class="badge bg-danger">Di Blokir Permanen</span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->status }}</span>
                                @endif
                            </td>
                            <td>{{ $user->blocked_reason ?? '-' }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('main.users.blocked.request', $user->id) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Detail Permohonan Buka Blokir"
                                   style="padding: 4px 10px; font-size: 13px; background-color: #1a1a1a; border-color: #1a1a1a;">
                                    <i class="fas fa-info-circle me-1"></i> Detail
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
    #blockedUsersTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #blockedUsersTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #blockedUsersTable tbody tr:hover {
        background-color: #fdf6e3;
    }
    .dataTables_wrapper {
        padding: 12px 0;
    }
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 6px 10px;
        font-size: 13px;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 15px;
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
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 13px;
        margin-top: 15px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #ab8b46 !important;
        border-color: #ab8b46 !important;
        color: #fff !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ab8b46 !important;
        border-color: #ab8b46 !important;
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
        $('#blockedUsersTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Lanjut",
                    previous: "Sebelum"
                }
            },
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 6 } // Matikan sorting untuk kolom aksi
            ]
        });
    });
</script>
@endpush
