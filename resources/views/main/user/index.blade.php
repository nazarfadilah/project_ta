@extends('main.layout.app')

@section('title', 'Kelola Pengguna')

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
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-users me-2"></i>Daftar Pengguna
            </h6>
        </div>
        <div class="card-body" style="padding: 20px;">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Pengguna</th>
                            <th>Alamat Email</th>
                            <th style="width: 80px; text-align: center;">Role</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $user->name_users }}</td>
                            <td>{{ $user->email_users }}</td>
                            <td style="text-align: center;"><span class="badge bg-info">{{ $user->role_users ?? 'User' }}</span></td>
                            <td style="text-align: center;">
                                <a href="{{ route('main.users.edit', $user->email_users) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit"
                                   style="padding: 4px 10px; font-size: 13px;">
                                    <i class="fas fa-edit"></i>
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
    #usersTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #usersTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #usersTable tbody tr:hover {
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
        background: #C9A961 !important;
        border-color: #C9A961 !important;
        color: #fff !important;
        border-radius: 4px;
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
        $('#usersTable').DataTable({
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
                { searchable: false, targets: [0, 3, 4] }
            ]
        });
    });
</script>
@endpush
