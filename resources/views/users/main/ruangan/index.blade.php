@extends('users.layout.app')

@section('title', 'Daftar Ruangan')

@section('css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">
    
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-door-open me-2"></i>Daftar Semua Ruangan
            </h6>
        </div>
        <div class="card-body" style="padding: 20px;">
            @if($ruangans->count() > 0)
            <div class="table-responsive">
                <table id="ruanganTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 25%;">Nama Ruangan</th>
                            <th style="width: 15%;">Jenis Ruangan</th>
                            {{-- <th style="width: 15%;">Gedung</th> --}}
                            <th style="width: 10%;">Lantai</th>
                            <th style="width: 15%;">Kapasitas</th>
                            <th style="width: 20%;">Rating</th>
                            <th style="width: 15%; text-align: center;">Aksi</th>
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
                                    $badgeColor = match($tipe) {
                                        'KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM' => 'bg-primary text-white',
                                        'AULA' => 'bg-warning text-dark',
                                        'RUANG_MEETING' => 'bg-secondary text-white',
                                        default => 'bg-success text-white'
                                    };
                                    $tipoLabel = str_replace('_', ' ', $tipe);
                                @endphp
                                <span class="badge {{ $badgeColor }} px-2 py-1">{{ $tipoLabel }}</span>
                            </td>
                            {{-- <td>{{ $ruangan->gedung->nama_gedung ?? '-' }}</td> --}}
                            <td>{{ $ruangan->lantai ?? '-' }}</td>
                            <td>
                                <i class="fas fa-users me-1 text-muted"></i> {{ $ruangan->kapasitas }} orang
                            </td>
                            <td>
                                @php
                                    $avgRating = $ruangan->average_rating;
                                    $reviewsCount = $ruangan->reviews_count;
                                @endphp
                                @if($avgRating > 0)
                                    <div style="font-size: 13px;">
                                        <x-star-rating :rating="$avgRating" fontSize="13px" />
                                        <span class="text-muted ms-1">({{ number_format($avgRating, 1) }})</span>
                                        <br>
                                        <small class="text-secondary fw-semibold">({{ $reviewsCount }} Ulasan)</small>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size: 13px;">Belum ada ulasan</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('users.main.ruangan.show', str_replace(' ', '-', strtolower($ruangan->nama_ruangan))) }}" class="btn btn-sm btn-info text-white px-3" style="font-size: 13px;">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.5;"></i>
                <h5>Tidak Ada Ruangan</h5>
                <p class="mb-0">Belum ada ruangan yang tersedia untuk ditampilkan.</p>
            </div>
            @endif
        </div>
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
