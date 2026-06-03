@extends('main.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" style="padding-left: 60px; padding-right: 60px; margin-top: 30px;">
    <div class="row mb-2">
        <!-- Card Pengguna -->
        @if(in_array(Auth::user()->roleId, [1, 2]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            @if(Auth::user()->roleId == 2)
                <div class="card border-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); min-height: 150px; cursor: default;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-dark">
                                    {{ $users }}
                                </h3>
                                <p class="card-text text-dark fw-semibold mb-0">Total Pengguna</p>
                            </div>
                            <i class="fas fa-users fa-3x" style="color: rgba(0,0,0,0.1);"></i>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('main.users.index') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%); min-height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between h-100">
                            <div class="d-flex justify-content-between align-items-flex-start">
                                <div>
                                    <h3 class="card-title fw-bold display-4 mb-2 text-dark">
                                        {{ $users }}
                                    </h3>
                                    <p class="card-text text-dark fw-semibold mb-0">Kelola Pengguna</p>
                                </div>
                                <i class="fas fa-users fa-3x" style="color: rgba(0,0,0,0.1);"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endif
        </div>
        @endif

        <!-- Card Tamu -->
        @if(in_array(Auth::user()->roleId, [1, 2]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.tamu.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #17A2B8 0%, #138496 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $guests }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Tamu</p>
                            </div>
                            <i class="fas fa-user-tie fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Gedung -->
        @if(in_array(Auth::user()->roleId, [1, 2]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.gedung.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #6C757D 0%, #5A6268 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $buildings }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Gedung</p>
                            </div>
                            <i class="fas fa-building fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Ruangan -->
        @if(in_array(Auth::user()->roleId, [2, 3]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.ruangan.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #4E73DF 0%, #224ABE 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $rooms }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Ruangan</p>
                            </div>
                            <i class="fas fa-door-open fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Sarana & Prasarana -->
        @if(in_array(Auth::user()->roleId, [2, 3]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.sarana.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #20C997 0%, #17A2B8 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $saranas }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Sarana</p>
                            </div>
                            <i class="fas fa-tools fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Paket Ruangan -->
        @if(in_array(Auth::user()->roleId, [2, 3]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.paket_ruangan.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #E83E8C 0%, #D81B60 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $packages }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Paket Ruangan</p>
                            </div>
                            <i class="fas fa-box-open fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Peminjaman Ruangan -->
        @if(in_array(Auth::user()->roleId, [1, 2, 3]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.transaksi.peminjaman.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #6F42C1 0%, #593196 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $bookings }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Peminjaman Ruangan</p>
                            </div>
                            <i class="fas fa-calendar-check fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- Card Berita -->
        @if(in_array(Auth::user()->roleId, [1, 2, 3]))
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
            <a href="{{ route('main.berita.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 rounded-3" style="background: linear-gradient(135deg, #28A745 0%, #218838 100%); min-height: 150px;">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div class="d-flex justify-content-between align-items-flex-start">
                            <div>
                                <h3 class="card-title fw-bold display-4 mb-2 text-white">
                                    {{ $beritas }}
                                </h3>
                                <p class="card-text text-white fw-semibold mb-0">Kelola Berita</p>
                            </div>
                            <i class="fas fa-newspaper fa-3x" style="color: rgba(255,255,255,0.2);"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>

    <!-- Section Datatables Monitoring -->
    <div class="row mt-4">
        <!-- Datatable Peminjaman Hari Ini (Bisa Check-In) - Khusus Petugas -->
        @if(Auth::user()->roleId == 3)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #007bff; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-calendar-day me-2"></i>Peminjaman Hari Ini (Siap Check-In)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="todayCheckinsTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Nama Guest</th>
                                    <th>Ruangan/Fasilitas</th>
                                    <th>Jam Mulai</th>
                                    <th>Durasi</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayCheckins as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->kodePeminjaman }}</td>
                                    <td>{{ $item->guest->name ?? 'N/A' }}</td>
                                    <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->jamMulai)->format('H:i') }} WIB</td>
                                    <td>{{ $item->durasi }} Jam</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-success px-3 py-2" style="font-size: 12px; font-weight: 600;">APPROVED</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-sign-in-alt me-1"></i> Check-In
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
        @endif

        <!-- Datatable Peminjaman Pending -->
        @if(in_array(Auth::user()->roleId, [1, 3]))
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-hourglass-half me-2"></i>Peminjaman/Reservasi Baru (Menunggu Verifikasi)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="pendingBookingsTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kode Peminjaman</th>
                                    <th>Nama Guest</th>
                                    <th>Ruangan/Fasilitas</th>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Durasi</th>
                                    <th style="width: 120px; text-align: center;">Status Approval</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBookings as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $item->kodePeminjaman }}</td>
                                    <td>{{ $item->guest->name ?? 'N/A' }}</td>
                                    <td>{{ $item->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                    <td>{{ $item->durasi }} Jam</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 12px; font-weight: 600;">PENDING</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.transaksi.peminjaman.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-eye me-1"></i> Detail
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
        @endif

        <!-- Datatable Sarana Perlu Perbaikan -->
        @if(Auth::user()->roleId == 3)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #dc3545; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-tools me-2"></i>Sarana & Prasarana (Perlu Perbaikan)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="brokenSaranasTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Nama Sarana</th>
                                    <th style="width: 150px; text-align: center;">Kondisi</th>
                                    <th style="width: 120px; text-align: center;">Stok</th>
                                    <th>Tanggal Penerimaan</th>
                                    @if(Auth::user()->roleId != 2)
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brokenSaranas as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-danger px-3 py-2" style="font-size: 12px; font-weight: 600;">Perlu Perbaikan</span>
                                    </td>
                                    <td style="text-align: center;" class="fw-bold">{{ $item->stok }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_penerimaan)->format('d F Y') }}</td>
                                    @if(Auth::user()->roleId != 2)
                                    <td style="text-align: center;">
                                        <a href="{{ route('main.sarana.edit', $item->id) }}" class="btn btn-sm btn-warning px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Datatable Berita Draft -->
        @if(Auth::user()->roleId == 1)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #6c757d; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                    <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                        <i class="fas fa-newspaper me-2"></i>Berita & Artikel (Draft)
                    </h6>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="draftBeritasTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Judul Berita</th>
                                    <th>Pembuat</th>
                                    <th>Tanggal Publish</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                    <th style="width: 100px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($draftBeritas as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->judul }}</td>
                                    <td>{{ $item->user->name_users ?? $item->user->username ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_publish)->format('d F Y') }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-secondary px-3 py-2 text-uppercase" style="font-size: 12px; font-weight: 600;">{{ $item->status }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if(Auth::user()->roleId == 2)
                                        <a href="{{ route('main.berita.show', $item->id) }}" class="btn btn-sm btn-primary px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        @else
                                        <a href="{{ route('main.berita.edit', $item->id) }}" class="btn btn-sm btn-warning px-3 py-1" style="font-size: 13px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
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
        @endif
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .display-4 {
        font-size: 3rem;
        line-height: 1;
    }
</style>
@endsection

@push('styles')
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .dataTable thead th {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    .dataTable tbody td {
        vertical-align: middle;
        color: #444;
    }
    .dataTable tbody tr:hover {
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
        @if(Auth::user()->roleId == 3)
        $('#todayCheckinsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada peminjaman hari ini yang siap untuk check-in.",
                emptyTable: "Tidak ada peminjaman hari ini yang siap untuk check-in.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { searchable: false, targets: [0, 6, 7] }
            ]
        });
        @endif

        @if(in_array(Auth::user()->roleId, [1, 3]))
        $('#pendingBookingsTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data peminjaman/reservasi yang diajukan dan belum diverifikasi",
                emptyTable: "Tidak ada data peminjaman/reservasi yang diajukan dan belum diverifikasi",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { searchable: false, targets: [0, 6, 7] }
            ]
        });
        @endif

        @if(Auth::user()->roleId == 3)
        $('#brokenSaranasTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data sarana yang perlu perbaikan.",
                emptyTable: "Tidak ada data sarana yang perlu perbaikan.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: @if(Auth::user()->roleId == 2) [0] @else [0, 5] @endif },
                { searchable: false, targets: @if(Auth::user()->roleId == 2) [0, 2] @else [0, 2, 5] @endif }
            ]
        });
        @endif

        @if(Auth::user()->roleId == 1)
        $('#draftBeritasTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada berita dengan status draft.",
                emptyTable: "Tidak ada berita dengan status draft.",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 5] },
                { searchable: false, targets: [0, 4, 5] }
            ]
        });
        @endif
    });
</script>
@endpush

