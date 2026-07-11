@extends('users.layout.app')

@section('title', 'Daftar Reservasi Saya')

@section('css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 14px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-calendar me-2"></i>Daftar Reservasi Saya
            </h6>
            <a href="{{ route('users.main.ruangan.index') }}" class="btn btn-sm btn-light fw-bold" style="font-size: 13px;">
                <i class="fas fa-plus me-1"></i> Buat Reservasi Baru
            </a>
        </div>
        <div class="card-body" style="padding: 20px;">
            @if($reservasis->count() > 0)
            <div class="table-responsive">
                <table id="reservasiTable" class="table table-hover table-bordered align-middle" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 20%;">Ruangan</th>
                            <th style="width: 15%;">Tanggal Mulai</th>
                            <th style="width: 15%;">Tanggal Selesai</th>
                            <th style="width: 15%;">Estimasi Peserta</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 15%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasis as $reservasi)
                        <tr>
                            <td>
                                <strong>{{ $reservasi->ruangan->nama_ruangan }}</strong>
                                {{-- Gedung - DISABLED
                                <br>
                                <small class="text-muted">{{ $reservasi->ruangan->gedung->nama_gedung ?? '-' }}</small>
                                --}}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($reservasi->tanggal_mulai)->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($reservasi->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td>
                                <i class="fas fa-users me-1 text-muted"></i> {{ $reservasi->estimasi_peserta }} orang
                            </td>
                            <td>
                                @php
                                    $status = $reservasi->status;
                                    $badgeColor = match($status) {
                                        'PENDING' => 'bg-warning text-dark',
                                        'APPROVED' => 'bg-success text-white',
                                        'REJECTED' => 'bg-danger text-white',
                                        'COMPLETED' => 'bg-info text-dark',
                                        'BATAL' => 'bg-secondary text-white',
                                        default => 'bg-warning text-dark'
                                    };
                                    $statusLabel = match($status) {
                                        'PENDING' => 'Menunggu',
                                        'APPROVED' => 'Disetujui',
                                        'REJECTED' => 'Ditolak',
                                        'COMPLETED' => 'Selesai',
                                        'BATAL' => 'Dibatalkan',
                                        default => 'Menunggu'
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }} px-2 py-1">{{ $statusLabel }}</span>
                            </td>
                            <td style="text-align: center;">
                                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                    <a href="{{ route('users.main.reservasi.show', $reservasi->id) }}" class="btn btn-sm btn-info text-white px-3" style="font-size: 13px;">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                    @if($reservasi->statusPeminjaman === 'RESERVASI')
                                        <button type="button" class="btn btn-sm btn-danger text-white px-3" data-id="{{ $reservasi->id }}" data-bs-toggle="modal" data-bs-target="#modalCancelReservasi" style="font-size: 13px;">
                                            <i class="fas fa-ban me-1"></i> Batal
                                        </button>
                                    @endif
                                    @if($reservasi->status === 'COMPLETED')
                                        @if(!$reservasi->review)
                                            <a href="{{ route('users.review.create', ['transaksi_id' => $reservasi->id]) }}" class="btn btn-sm btn-warning text-dark px-3" style="font-size: 13px; font-weight: 600;">
                                                <i class="fas fa-star me-1"></i> Beri Ulasan
                                            </a>
                                        @endif
                                        @if($reservasi->ruangan && $reservasi->ruangan->id_ruangan)
                                            <a href="{{ route('users.main.reservasi.create', ['ruangan_id' => $reservasi->ruangan->id_ruangan]) }}" class="btn btn-sm btn-success px-3" style="font-size: 13px;">
                                                <i class="fas fa-redo me-1"></i> Reservasi Lagi
                                            </a>
                                        @endif
                                    @elseif($reservasi->status === 'BATAL' || $reservasi->status === 'REJECTED')
                                        @if($reservasi->ruangan && $reservasi->ruangan->id_ruangan)
                                            <a href="{{ route('users.main.reservasi.create', ['ruangan_id' => $reservasi->ruangan->id_ruangan]) }}" class="btn btn-sm btn-warning text-dark px-3" style="font-size: 13px;">
                                                <i class="fas fa-sync me-1"></i> Ajukan Ulang
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.5;"></i>
                <h5>Belum Ada Reservasi</h5>
                <p class="mb-0">Anda belum membuat reservasi apapun. Mari mulai dengan <a href="{{ route('users.main.ruangan.index') }}" class="fw-semibold" style="color: #C9A961; text-decoration: none;">melihat daftar ruangan</a>.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Pembatalan Reservasi -->
<div class="modal fade" id="modalCancelReservasi" tabindex="-1" aria-labelledby="modalCancelReservasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form id="formCancelReservasi" action="" method="POST">
                @csrf
                <div class="modal-header text-white border-bottom-0" style="background-color: #dc3545; border-radius: 8px 8px 0 0; padding: 16px 20px;">
                    <h5 class="modal-title fs-5 fw-semibold" id="modalCancelReservasiLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Batalkan Peminjaman
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background-color: #fcfcfc;">
                    <p class="text-dark" style="font-size: 14px;">Apakah Anda yakin ingin membatalkan peminjaman/reservasi ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="mb-3">
                        <label for="alasan_pembatalan" class="form-label fw-semibold text-muted" style="font-size: 13px;">Alasan Pembatalan (Opsional)</label>
                        <textarea class="form-control" id="alasan_pembatalan" name="alasan_pembatalan" rows="3" placeholder="Masukkan alasan pembatalan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal" style="font-size: 13px;">Kembali</button>
                    <button type="submit" class="btn btn-sm btn-danger text-white px-3" style="font-size: 13px;">Batalkan Reservasi</button>
                </div>
            </form>
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

    // Modal Pembatalan dynamic action URL setter
    const cancelModal = document.getElementById('modalCancelReservasi');
    if (cancelModal) {
        cancelModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = document.getElementById('formCancelReservasi');
            form.action = `/users/main/reservasi/${id}/cancel`;
            
            // Clear input
            const textarea = document.getElementById('alasan_pembatalan');
            if (textarea) textarea.value = '';
        });
    }
});
</script>
@endsection
