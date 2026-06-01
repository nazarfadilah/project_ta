@extends('users.layout.app')

@section('title', 'Daftar Ruangan')

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

    .btn-detail {
        background-color: var(--gold-primary);
        color: white;
        border: none;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background-color: var(--gold-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(201, 169, 97, 0.3);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-kamar {
        background-color: #e7f3ff;
        color: #0056b3;
    }

    .badge-aula {
        background-color: #fff4e6;
        color: #ff9900;
    }

    .badge-meeting {
        background-color: #f0f0f0;
        color: #333;
    }

    .badge-lainnya {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: var(--gold-light);
        border-color: var(--gold-light);
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
</style>
@endsection

@section('content')
<div class="section-header">
    <h2><i class="fas fa-door-open"></i> Daftar Ruangan</h2>
    <p>Lihat informasi lengkap ruangan yang tersedia dan lakukan reservasi</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Semua Ruangan</h5>
    </div>
    <div class="card-body">
        @if($ruangans->count() > 0)
        <div class="table-responsive">
            <table id="ruanganTable" class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 25%;">Nama Ruangan</th>
                        <th style="width: 15%;">Jenis Ruangan</th>
                        <th style="width: 15%;">Gedung</th>
                        <th style="width: 10%;">Lantai</th>
                        <th style="width: 12%;">Kapasitas</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ruangans as $ruangan)
                    <tr>
                        <td>
                            <strong>{{ $ruangan->nama_ruangan }}</strong>
                        </td>
                        <td>
                            @php
                                $tipe = $ruangan->tipe_ruangan;
                                $badgeClass = match($tipe) {
                                    'KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM' => 'badge-kamar',
                                    'AULA' => 'badge-aula',
                                    'RUANG_MEETING' => 'badge-meeting',
                                    default => 'badge-lainnya'
                                };
                                $tipoLabel = str_replace('_', ' ', $tipe);
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $tipoLabel }}</span>
                        </td>
                        <td>{{ $ruangan->gedung->nama_gedung ?? '-' }}</td>
                        <td>{{ $ruangan->lantai ?? '-' }}</td>
                        <td>
                            <i class="fas fa-users"></i> {{ $ruangan->kapasitas }} orang
                        </td>
                        <td>
                            <a href="{{ route('users.main.ruangan.show', str_replace(' ', '-', strtolower($ruangan->nama_ruangan))) }}" class="btn btn-detail">
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
            <h5>Tidak Ada Ruangan</h5>
            <p>Belum ada ruangan yang tersedia untuk ditampilkan.</p>
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
    if (document.getElementById('ruanganTable')) {
        $('#ruanganTable').DataTable({
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
