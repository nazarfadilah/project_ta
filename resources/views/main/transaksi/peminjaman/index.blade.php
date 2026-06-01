@extends('admin.layouts.app')

@section('title', 'Daftar Peminjaman/Reservasi')

@section('css')
<style>
    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-approved {
        background-color: #d4edda;
        color: #155724;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .btn-detail {
        background-color: #007bff;
        color: white;
        border: none;
        text-decoration: none;
    }

    .btn-detail:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: var(--sidebar-text);
        font-weight: 700;
        font-size: 24px;
        margin: 0;
    }

    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .card-header h5 {
        color: var(--sidebar-text);
        font-weight: 600;
        margin: 0;
    }

    .dataTables_wrapper {
        padding: 16px 0;
    }

    table.dataTable {
        border-collapse: collapse;
        width: 100%;
    }

    table.dataTable thead th {
        background-color: #f8f9fa;
        color: var(--sidebar-text);
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        padding: 12px;
        text-align: left;
    }

    table.dataTable tbody td {
        border-bottom: 1px solid #e9ecef;
        padding: 12px;
        color: var(--sidebar-text);
    }

    table.dataTable tbody tr:hover {
        background-color: #f9f9f9;
    }

    .dataTables_paginate {
        margin-top: 16px;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 20px;
        }

        table.dataTable {
            font-size: 13px;
        }

        table.dataTable thead th,
        table.dataTable tbody td {
            padding: 8px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-list"></i> Daftar Peminjaman/Reservasi</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-book"></i> Data Peminjaman</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="peminjamanTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Peminjaman</th>
                        <th>Nama Guest</th>
                        <th>Ruangan</th>
                        <th>Tanggal</th>
                        <th>Status Approval</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $item->kodePeminjaman }}</strong>
                        </td>
                        <td>{{ $item->guest->name ?? 'N/A' }}</td>
                        <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>
                            @php
                                $statusClass = match($item->statusApproval) {
                                    'PENDING' => 'badge-pending',
                                    'APPROVED' => 'badge-approved',
                                    'REJECTED' => 'badge-rejected',
                                    default => 'badge-pending'
                                };
                                $statusLabel = match($item->statusApproval) {
                                    'PENDING' => 'Menunggu',
                                    'APPROVED' => 'Disetujui',
                                    'REJECTED' => 'Ditolak',
                                    default => 'Menunggu'
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" class="btn btn-sm btn-detail">
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

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#peminjamanTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                },
                emptyTable: 'Tidak ada data yang ditemukan'
            }
        });
    });
</script>
@endsection
