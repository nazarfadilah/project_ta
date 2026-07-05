@extends('main.layout.app')

@section('title', 'Laporan & Statistik Peminjaman')

@section('content')
<div class="container-fluid py-4" style="padding-left: 20px; padding-right: 20px;">
    
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2 text-success"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2 text-danger"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fas fa-chart-line me-2" style="color: #C9A961;"></i>Laporan & Statistik Peminjaman</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Monitor aktivitas transaksi, statistik bulanan, dan ekspor data peminjaman ruangan.</p>
        </div>
        <div>
            <button class="btn btn-warning shadow-sm border-0 text-white fw-semibold px-4 py-2" 
                    data-bs-toggle="modal" 
                    data-bs-target="#exportFilterModal" 
                    style="background: linear-gradient(135deg, #C9A961, #B8953F); font-size: 13px; border-radius: 6px; transition: all 0.2s;">
                <i class="fas fa-file-excel me-2"></i>Ekspor Excel
            </button>
        </div>
    </div>

    {{-- KPI Cards Row --}}
    <div class="row g-4 mb-4">
        <!-- KPI 1: Total Transaksi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card bg-white p-4 rounded-3 border-0 shadow-sm position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">Total Transaksi</span>
                        <h3 class="fw-bold mt-2 mb-0" style="color: #2b2b2b;">{{ $totalPeminjaman }}</h3>
                    </div>
                    <div class="kpi-icon-container bg-primary-subtle text-primary rounded-circle" style="background-color: rgba(13, 110, 253, 0.1) !important; color: #0d6efd !important;">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="kpi-decor bg-primary"></div>
            </div>
        </div>

        <!-- KPI 2: Approved -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card bg-white p-4 rounded-3 border-0 shadow-sm position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">Disetujui (Approved)</span>
                        <h3 class="fw-bold mt-2 mb-0 text-success">{{ $totalApproved }}</h3>
                    </div>
                    <div class="kpi-icon-container bg-success-subtle text-success rounded-circle" style="background-color: rgba(25, 135, 84, 0.1) !important; color: #198754 !important;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
                <div class="kpi-decor bg-success"></div>
            </div>
        </div>

        <!-- KPI 3: Pending -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card bg-white p-4 rounded-3 border-0 shadow-sm position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">Menunggu (Pending)</span>
                        <h3 class="fw-bold mt-2 mb-0 text-warning">{{ $totalPending }}</h3>
                    </div>
                    <div class="kpi-icon-container bg-warning-subtle text-warning rounded-circle" style="background-color: rgba(255, 193, 7, 0.1) !important; color: #ffc107 !important;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="kpi-decor bg-warning"></div>
            </div>
        </div>

        <!-- KPI 4: Total Pendapatan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card bg-white p-4 rounded-3 border-0 shadow-sm position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">Total Pendapatan</span>
                        <h3 class="fw-bold mt-2 mb-0 text-dark" style="font-size: 1.4rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                    <div class="kpi-icon-container bg-info-subtle text-info rounded-circle" style="background-color: #fcf6e5 !important; color: #C9A961 !important;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="kpi-decor" style="background-color: #C9A961 !important;"></div>
            </div>
        </div>
    </div>

    {{-- Diagram Batang Statistik --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 8px 8px 0 0;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">
                    <i class="fas fa-chart-bar me-2" style="color: #C9A961;"></i>Statistik Transaksi Bulanan (12 Bulan Terakhir)
                </h5>
                <span class="badge bg-light text-muted fw-semibold" style="font-size: 11px; padding: 6px 10px;">Diagram Batang</span>
            </div>
        </div>
        <div class="card-body">
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="peminjamanChart"></canvas>
            </div>
        </div>
    </div>

    {{-- DataTables List --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between py-3" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-list-ul me-2"></i>Daftar Peminjaman Ruangan
            </h6>
            <button class="btn btn-sm btn-light fw-bold" 
                    data-bs-toggle="modal" 
                    data-bs-target="#exportFilterModal" 
                    style="font-size: 12px; padding: 6px 14px; border-radius: 5px; color: #B8953F; border: none;">
                <i class="fas fa-filter me-1"></i>Filter & Ekspor
            </button>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="laporanTable" class="table table-hover table-bordered align-middle w-100" style="font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Kode Peminjaman</th>
                            <th>Nama Guest</th>
                            {{-- <th>Gedung</th> --}}
                            <th>Ruangan</th>
                            <th>Tanggal</th>
                            <th>Waktu Mulai</th>
                            <th style="text-align: center;">Status Approval</th>
                            <th style="text-align: center;">Status Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->kodePeminjaman }}</span>
                            </td>
                            <td>{{ $item->guest->name ?? 'N/A' }}</td>
                            {{-- <td>{{ $item->paketRuangan->ruangan->gedung->nama_gedung ?? 'N/A' }}</td> --}}
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->jamMulai)->format('H:i') }} WIB</td>
                            <td style="text-align: center;">
                                @php
                                    $appClass = match($item->statusApproval) {
                                        'APPROVED' => 'bg-success-subtle text-success border border-success-subtle',
                                        'REJECTED' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                        default => 'bg-warning-subtle text-warning border border-warning-subtle'
                                    };
                                    $appLabel = match($item->statusApproval) {
                                        'APPROVED' => 'Disetujui',
                                        'REJECTED' => 'Ditolak',
                                        default => 'Menunggu'
                                    };
                                @endphp
                                <span class="badge {{ $appClass }}" style="font-size: 12px; padding: 5px 10px;">{{ $appLabel }}</span>
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $transClass = match($item->statusPeminjaman) {
                                        'SELESAI' => 'bg-success',
                                        'BATAL' => 'bg-danger',
                                        'CHECK_IN' => 'bg-primary',
                                        'CHECK_OUT' => 'bg-info text-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $transClass }} text-white" style="font-size: 11px; padding: 5px 8px;">{{ $item->statusPeminjaman }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open d-block mb-2 text-secondary" style="font-size: 24px;"></i>
                                Belum ada data peminjaman/transaksi ruangan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FILTER EXPORT --}}
<div class="modal fade" id="exportFilterModal" tabindex="-1" aria-labelledby="exportFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #C9A961, #B8953F);">
                <h6 class="modal-title fw-bold" id="exportFilterModalLabel">
                    <i class="fas fa-file-export me-2"></i>Filter & Ekspor Excel
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.laporan.export') }}" method="GET">
                <div class="modal-body p-4">
                    <p class="text-muted mb-4" style="font-size: 13px;">Silakan tentukan kriteria penyaringan data peminjaman yang ingin Anda ekspor ke bentuk Microsoft Excel (.csv).</p>
                    
                    <!-- 1. Filter Gedung - DISABLED
                    <div class="mb-3">
                        <label for="gedung_id" class="form-label fw-semibold text-dark" style="font-size: 13px;">Gedung</label>
                        <select class="form-select border-grey" id="gedung_id" name="gedung_id" style="font-size: 13px;">
                            <option value="">-- Semua Gedung --</option>
                            @foreach($gedungs as $gedung)
                                <option value="{{ $gedung->id_gedung }}">{{ $gedung->nama_gedung }}</option>
                            @endforeach
                        </select>
                    </div>
                    -->

                    <!-- 2. Filter Ruangan -->
                    <div class="mb-3">
                        <label for="ruangan_id" class="form-label fw-semibold text-dark" style="font-size: 13px;">Ruangan</label>
                        <select class="form-select border-grey" id="ruangan_id" name="ruangan_id" style="font-size: 13px;">
                            <option value="">-- Semua Ruangan --</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}" data-gedung="">{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Filter Bulan -->
                    <div class="mb-3">
                        <label for="bulan" class="form-label fw-semibold text-dark" style="font-size: 13px;">Bulan Transaksi</label>
                        <select class="form-select border-grey" id="bulan" name="bulan" style="font-size: 13px;">
                            <option value="">-- Semua Bulan --</option>
                            @foreach($monthFilterList as $monthKey => $monthLabel)
                                <option value="{{ $monthKey }}">{{ $monthLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center text-muted my-3 position-relative">
                        <hr class="m-0">
                        <span class="px-2 bg-white position-absolute top-50 start-50 translate-middle" style="font-size: 10px; letter-spacing: 0.5px; color: #888;">ATAU RENTANG TANGGAL</span>
                    </div>

                    <!-- 4. Filter Rentang Tanggal -->
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <label for="tanggal_mulai" class="form-label fw-semibold text-dark" style="font-size: 13px;">Dari Tanggal</label>
                            <input type="date" class="form-control border-grey" id="tanggal_mulai" name="tanggal_mulai" style="font-size: 13px;">
                        </div>
                        <div class="col-6">
                            <label for="tanggal_selesai" class="form-label fw-semibold text-dark" style="font-size: 13px;">Sampai Tanggal</label>
                            <input type="date" class="form-control border-grey" id="tanggal_selesai" name="tanggal_selesai" style="font-size: 13px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-semibold text-muted px-3" data-bs-dismiss="modal" style="font-size: 13px;">Batal</button>
                    <button type="submit" class="btn btn-warning text-white fw-bold shadow-sm px-4" style="background: linear-gradient(135deg, #C9A961, #B8953F); font-size: 13px; border: none;">
                        <i class="fas fa-file-export me-1"></i>Ekspor Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Styling Premium & Harmonious */
    .kpi-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-left: 4px solid transparent;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .kpi-icon-container {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .kpi-decor {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0.7;
    }
    
    /* DataTable overrides */
    #laporanTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    #laporanTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    #laporanTable tbody tr:hover {
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
    
    .border-grey {
        border: 1px solid #dcdcdc;
    }
    .border-grey:focus {
        border-color: #C9A961;
        box-shadow: 0 0 0 0.2rem rgba(201, 169, 97, 0.25);
    }
</style>
@endpush

@push('scripts')
{{-- JQuery & DataTables JS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#laporanTable').DataTable({
            language: {
                search: "Cari Peminjaman:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data peminjaman",
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
                { orderable: false, targets: [0] },
                { searchable: false, targets: [0] }
            ]
        });

        // Dynamic Filtering in Room Dropdown based on Selected Building
        const gedungSelect = $('#gedung_id');
        const ruanganSelect = $('#ruangan_id');
        const originalRuanganOptions = ruanganSelect.html();

        gedungSelect.on('change', function() {
            const selectedGedungId = $(this).val();
            
            // Reset ruangan select
            ruanganSelect.html(originalRuanganOptions);
            
            if (selectedGedungId) {
                // Keep only room options with matching data-gedung attribute
                ruanganSelect.find('option').each(function() {
                    const roomGedungId = $(this).attr('data-gedung');
                    if (roomGedungId && roomGedungId !== selectedGedungId) {
                        $(this).remove();
                    }
                });
            }
        });

        // Initialize Chart.js Bar Chart
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        
        // Gradient styling for bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#C9A961'); // Gold primary
        gradient.addColorStop(1, '#E9D6AC'); // Light gold

        const chartLabels = {!! json_encode($formattedLabels) !!};
        const chartData = {!! json_encode($formattedData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Booking',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: '#B8953F',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        titleFont: {
                            family: 'Inter',
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Inter',
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 6,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            color: '#777'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            color: '#e2e2e2'
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                family: 'Inter',
                                size: 11
                            },
                            color: '#777'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
