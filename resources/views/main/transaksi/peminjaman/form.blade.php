@extends('main.layout.app')

@section('title', 'Detail Peminjaman')

@section('css')
<style>
    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .detail-header h1 {
        color: var(--sidebar-text);
        font-weight: 700;
        font-size: 28px;
        margin: 0;
    }

    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
    }

    .card-header h5 {
        color: var(--sidebar-text);
        font-weight: 600;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
    }

    .detail-item {
        padding: 20px;
    }

    .detail-item label {
        color: #666;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .detail-item .value {
        color: var(--sidebar-text);
        font-size: 16px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
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

    .info-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background-color: #e7f3ff;
        border-left: 4px solid #0056b3;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .info-box i {
        color: #0056b3;
        font-size: 18px;
    }

    .info-box-text {
        color: #0056b3;
        font-size: 13px;
        margin: 0;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-approve {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-approve:hover {
        background-color: #218838;
        color: white;
        text-decoration: none;
    }

    .btn-reject {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-reject:hover {
        background-color: #c82333;
        color: white;
        text-decoration: none;
    }

    .btn-invoice {
        background-color: var(--gold-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-invoice:hover {
        background-color: #d4a017;
        color: white;
        text-decoration: none;
    }

    .guest-info {
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 4px;
        border-left: 4px solid var(--gold-primary);
        margin-top: 20px;
    }

    .guest-info-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .guest-info-item:last-child {
        margin-bottom: 0;
    }

    .guest-info-label {
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .guest-info-value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 600;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-title {
        color: var(--sidebar-text);
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        color: #666;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px rgba(220, 130, 23, 0.1);
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-header h1 {
            font-size: 22px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons button,
        .action-buttons a {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<a href="{{ route('main.transaksi.peminjaman.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
</a>

@if($peminjaman)
<div class="detail-header">
    <h1>Detail Peminjaman</h1>
    @php
        $status = $peminjaman->statusApproval;
        $badgeClass = match($status) {
            'PENDING' => 'badge-pending',
            'APPROVED' => 'badge-approved',
            'REJECTED' => 'badge-rejected',
            default => 'badge-pending'
        };
        $statusLabel = match($status) {
            'PENDING' => 'Menunggu Persetujuan',
            'APPROVED' => 'Disetujui',
            'REJECTED' => 'Ditolak',
            default => 'Menunggu Persetujuan'
        };
    @endphp
    <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Status Info -->
<div class="card">
    <div class="card-body">
        @if($peminjaman->statusApproval === 'PENDING')
        <div class="info-box">
            <i class="fas fa-hourglass-half"></i>
            <p class="info-box-text">Peminjaman ini belum disetujui. Silakan review dan ambil keputusan di bawah.</p>
        </div>
        @elseif($peminjaman->statusApproval === 'APPROVED')
        <div class="info-box" style="background-color: #d4edda; border-left-color: #155724;">
            <i class="fas fa-check-circle" style="color: #155724;"></i>
            <p class="info-box-text" style="color: #155724;">Peminjaman telah disetujui pada {{ $peminjaman->tanggalApproval->format('d F Y H:i') }}</p>
        </div>
        @elseif($peminjaman->statusApproval === 'REJECTED')
        <div class="info-box" style="background-color: #f8d7da; border-left-color: #721c24;">
            <i class="fas fa-times-circle" style="color: #721c24;"></i>
            <p class="info-box-text" style="color: #721c24;">Peminjaman telah ditolak pada {{ $peminjaman->tanggalApproval->format('d F Y H:i') }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Informasi Guest -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-user"></i> Informasi Guest</h5>
    </div>
    <div class="card-body">
        <div class="guest-info">
            <div class="guest-info-item">
                <span class="guest-info-label">Nama Guest</span>
                <span class="guest-info-value">{{ $peminjaman->guest->name ?? 'N/A' }}</span>
            </div>
            <div class="guest-info-item">
                <span class="guest-info-label">NIK</span>
                <span class="guest-info-value">{{ $peminjaman->guest->nik ?? '-' }}</span>
            </div>
            <div class="guest-info-item">
                <span class="guest-info-label">Jenis Kelamin</span>
                <span class="guest-info-value">{{ $peminjaman->guest->gender === 'MALE' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            <div class="guest-info-item">
                <span class="guest-info-label">Alamat</span>
                <span class="guest-info-value">{{ $peminjaman->guest->address ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Ruangan/Fasilitas -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-door-open"></i> Informasi Fasilitas</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Nama Fasilitas</label>
                <div class="value">{{ $peminjaman->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <label>Tipe Ruangan</label>
                <div class="value">{{ str_replace('_', ' ', $peminjaman->paketRuangan->ruangan->tipe_ruangan ?? '-') }}</div>
            </div>

            <div class="detail-item">
                <label>Gedung</label>
                <div class="value">{{ $peminjaman->paketRuangan->ruangan->gedung->nama_gedung ?? '-' }}</div>
            </div>

            <div class="detail-item">
                <label>Paket</label>
                <div class="value">{{ $peminjaman->paketRuangan->nama_paket ?? 'Paket Standar' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Peminjaman -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-calendar-alt"></i> Informasi Peminjaman</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Kode Peminjaman</label>
                <div class="value">{{ $peminjaman->kodePeminjaman }}</div>
            </div>

            <div class="detail-item">
                <label>Tanggal Peminjaman</label>
                <div class="value">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</div>
            </div>

            <div class="detail-item">
                <label>Jam Mulai</label>
                <div class="value">{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</div>
            </div>

            <div class="detail-item">
                <label>Durasi</label>
                <div class="value">{{ $peminjaman->durasi }} jam</div>
            </div>
        </div>

        @if($peminjaman->keterangan)
        <div style="margin-top: 20px; padding: 16px; background-color: #f9f9f9; border-radius: 4px;">
            <h6 style="color: #666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Keterangan</h6>
            <p style="color: var(--sidebar-text); font-size: 14px; margin: 0; line-height: 1.6;">{{ $peminjaman->keterangan }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Catatan Approval -->
@if($peminjaman->catatanApproval)
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-comment"></i> Catatan Approval</h5>
    </div>
    <div class="card-body">
        <div style="padding: 16px; background-color: #f9f9f9; border-radius: 4px;">
            <p style="color: var(--sidebar-text); font-size: 14px; margin: 0; line-height: 1.6;">{{ $peminjaman->catatanApproval }}</p>
        </div>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="card">
    <div class="card-body">
        <div class="action-buttons">
            @if($peminjaman->statusApproval === 'PENDING')
                <button type="button" class="btn-approve" data-bs-toggle="modal" data-bs-target="#modalApprove">
                    <i class="fas fa-check"></i> Setujui
                </button>
                <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#modalReject">
                    <i class="fas fa-times"></i> Tolak
                </button>
            @endif

            @if($peminjaman->statusApproval === 'APPROVED' && $peminjaman->statusPeminjaman === 'RESERVASI')
                <form action="{{ route('main.transaksi.peminjaman.checkin', $peminjaman->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-primary fw-bold text-white px-4 py-2" style="background-color: #007bff; border: none; font-size: 13px; font-weight: 600; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-sign-in-alt"></i> Proses Check-In
                    </button>
                </form>
            @endif

            @if($peminjaman->statusPeminjaman === 'CHECK_IN')
                <button type="button" class="btn btn-info fw-bold text-white px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCheckOut" style="background-color: #17a2b8; border: none; font-size: 13px; font-weight: 600; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sign-out-alt"></i> Proses Check-Out
                </button>
            @endif

            @if($invoice)
                <a href="{{ route('main.transaksi.invoice.show', $peminjaman->id) }}" class="btn-invoice">
                    <i class="fas fa-file-invoice-dollar"></i> Lihat Invoice
                </a>
            @else
                <button type="button" class="btn-invoice" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fas fa-file-invoice-dollar"></i> Invoice Belum Tersedia
                </button>
            @endif
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center" style="padding: 40px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc3545; margin-bottom: 16px;"></i>
        <h5>Peminjaman Tidak Ditemukan</h5>
        <p>Peminjaman yang Anda cari tidak tersedia atau telah dihapus.</p>
    </div>
</div>
@endif

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove" tabindex="-1" aria-labelledby="modalApproveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalApproveLabel">
                    <i class="fas fa-check-circle"></i> Setujui Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.approve', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatanApprove">Catatan (Opsional)</label>
                        <textarea id="catatanApprove" name="catatanApproval" placeholder="Tambahkan catatan jika diperlukan..." maxlength="1000"></textarea>
                        <small style="color: #666; display: block; margin-top: 4px;">Maksimal 1000 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRejectLabel">
                    <i class="fas fa-times-circle"></i> Tolak Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.reject', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatanReject">Alasan Penolakan <span style="color: red;">*</span></label>
                        <textarea id="catatanReject" name="catatanApproval" placeholder="Jelaskan alasan penolakan..." maxlength="1000" required></textarea>
                        <small style="color: #666; display: block; margin-top: 4px;">Maksimal 1000 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Check-Out -->
<div class="modal fade" id="modalCheckOut" tabindex="-1" aria-labelledby="modalCheckOutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCheckOutLabel">
                    <i class="fas fa-sign-out-alt text-info"></i> Proses Check-Out Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.checkout', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Kondisi Return -->
                    <div class="form-group mb-3">
                        <label for="kondisiReturn" class="fw-bold" style="font-size: 12px; color: #666; text-transform: uppercase;">Kondisi Pengembalian <span style="color: red;">*</span></label>
                        <select class="form-select" id="kondisiReturn" name="kondisiReturn" style="font-size: 14px; padding: 10px;" required>
                            <option value="BAIK">BAIK (Semua Fasilitas Aman)</option>
                            <option value="RUSAK_RINGAN">RUSAK RINGAN</option>
                            <option value="RUSAK_BERAT">RUSAK BERAT</option>
                            <option value="HILANG">ADA YANG HILANG</option>
                        </select>
                    </div>

                    <!-- Catatan Kerusakan -->
                    <div class="form-group mb-3">
                        <label for="catatanKerusakan" class="fw-bold" style="font-size: 12px; color: #666; text-transform: uppercase;">Catatan Kerusakan/Pengembalian</label>
                        <textarea id="catatanKerusakan" name="catatanKerusakan" placeholder="Tuliskan catatan kerusakan jika ada..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; min-height: 80px;"></textarea>
                    </div>

                    <!-- Denda Damage -->
                    <div class="form-group mb-3">
                        <label for="estimasiDamage" class="fw-bold" style="font-size: 12px; color: #666; text-transform: uppercase;">Denda Kerusakan (IDR - Opsional)</label>
                        <input type="number" class="form-control" id="estimasiDamage" name="estimasiDamage" placeholder="Contoh: 150000" style="font-size: 14px; padding: 10px;" min="0">
                    </div>

                    <!-- Biaya Tambahan -->
                    <div class="form-group mb-3">
                        <label for="biayaTambahan" class="fw-bold" style="font-size: 12px; color: #666; text-transform: uppercase;">Biaya Tambahan Lainnya (IDR - Opsional)</label>
                        <input type="number" class="form-control" id="biayaTambahan" name="biayaTambahan" placeholder="Contoh: 50000 (Misal denda telat check-out)" style="font-size: 14px; padding: 10px;" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="fas fa-check"></i> Selesaikan Check-Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
