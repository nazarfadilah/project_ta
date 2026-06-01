@extends('users.layout.app')

@section('title', 'Daftar Reservasi Saya')

@section('css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<style>
    .section-header {
        margin-bottom: 30px;
    }

    .section-header h2 {
        color: var(--gold-primary);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .section-header p {
        color: #666;
        font-size: 14px;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h5 {
        margin: 0;
        color: var(--sidebar-text);
        font-weight: 600;
    }

    .btn-cari-ruangan {
        background-color: var(--gold-primary);
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cari-ruangan:hover {
        background-color: var(--gold-dark);
        color: white;
        text-decoration: none;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: var(--sidebar-text);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: 14px;
        border-bottom: 1px solid #f1f3f5;
    }

    .table tbody tr:hover {
        background-color: #f9f9f9;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .badge-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .btn-action {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-action:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }

    .no-data-message {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .no-data-message i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: var(--gold-light);
        border-color: var(--gold-light);
    }
</style>
@endsection

@section('content')
<div class="section-header">
    <h2><i class="fas fa-list"></i> Daftar Reservasi Saya</h2>
    <p>Lihat dan kelola semua reservasi yang telah Anda buat</p>
</div>

@if(session('success'))
<div class="card" style="border-left: 4px solid #28a745; margin-bottom: 20px;">
    <div class="card-body">
        <div style="color: #155724; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-calendar"></i> Daftar Reservasi</h5>
        <a href="{{ route('users.main.ruangan.index') }}" class="btn-cari-ruangan">
            <i class="fas fa-plus"></i> Buat Reservasi Baru
        </a>
    </div>
    <div class="card-body">
        @if($reservasis->count() > 0)
        <div class="table-responsive">
            <table id="reservasiTable" class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 20%;">Ruangan</th>
                        <th style="width: 15%;">Tanggal Mulai</th>
                        <th style="width: 15%;">Tanggal Selesai</th>
                        <th style="width: 12%;">Estimasi Peserta</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasis as $reservasi)
                    <tr>
                        <td>
                            <strong>{{ $reservasi->ruangan->nama_ruangan }}</strong>
                            <br>
                            <small style="color: #666;">{{ $reservasi->ruangan->gedung->nama_gedung ?? '-' }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($reservasi->tanggal_mulai)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($reservasi->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td>
                            <i class="fas fa-users"></i> {{ $reservasi->estimasi_peserta }} orang
                        </td>
                        <td>
                            @php
                                $status = $reservasi->status;
                                $badgeClass = match($status) {
                                    'PENDING' => 'badge-pending',
                                    'APPROVED' => 'badge-approved',
                                    'REJECTED' => 'badge-rejected',
                                    'COMPLETED' => 'badge-completed',
                                    default => 'badge-pending'
                                };
                                $statusLabel = match($status) {
                                    'PENDING' => 'Menunggu',
                                    'APPROVED' => 'Disetujui',
                                    'REJECTED' => 'Ditolak',
                                    'COMPLETED' => 'Selesai',
                                    default => 'Menunggu'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            <a href="{{ route('users.main.reservasi.show', $reservasi->id) }}" class="btn-action">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="no-data-message">
            <i class="fas fa-inbox"></i>
            <h5>Belum Ada Reservasi</h5>
            <p>Anda belum membuat reservasi apapun. Mari mulai dengan <a href="{{ route('users.main.ruangan.index') }}">melihat daftar ruangan</a>.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('reservasiTable')) {
        $('#reservasiTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
});
</script>
@endsection
