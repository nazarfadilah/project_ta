@extends('users.layout.app')

@section('title', 'Daftar Gedung')

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
        background-color: #fdf6e3;
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
        background-color: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: var(--gold-light) !important;
        border-color: var(--gold-light) !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="section-header">
    <h2><i class="fas fa-building"></i> Daftar Gedung</h2>
    <p>Lihat daftar gedung yang terdaftar dalam sistem SIPRASA</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: var(--gold-dark); font-weight: 600;"><i class="fas fa-list"></i> Daftar Semua Gedung</h5>
    </div>
    <div class="card-body">
        @if($gedungs->count() > 0)
        <div class="table-responsive">
            <table id="gedungTable" class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center;">No</th>
                        <th style="width: 35%;">Nama Gedung</th>
                        <th style="width: 25%;">Koordinat</th>
                        <th style="width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gedungs as $index => $gedung)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #7f8c8d;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $gedung->nama_gedung }}</strong>
                        </td>
                        <td>
                            @if($gedung->koordinat)
                                <span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $gedung->koordinat }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $gedung->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="no-data-message">
            <i class="fas fa-inbox"></i>
            <h5>Tidak Ada Gedung</h5>
            <p>Belum ada data gedung yang tersedia.</p>
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
    if (document.getElementById('gedungTable')) {
        $('#gedungTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
});
</script>
@endsection
