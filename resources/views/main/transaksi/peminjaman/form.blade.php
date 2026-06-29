@extends('main.layout.app')

@section('title', 'Detail Peminjaman')

@section('css')
<style>
    @media print {
        .btn, .modal, .action-buttons {
            display: none !important;
        }
        body {
            background-color: white;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">
    
    <a href="{{ route('main.transaksi.peminjaman.index') }}" class="btn btn-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>

    @if($peminjaman)
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold text-dark">Detail Peminjaman</h5>
        @php
            $statusPjm = $peminjaman->statusPeminjaman;
            $statusApp = $peminjaman->statusApproval;
            
            if ($statusPjm === 'SELESAI') {
                $badgeColor = 'bg-success text-white';
                $statusLabel = 'Selesai';
            } elseif ($statusPjm === 'BATAL') {
                $badgeColor = 'bg-secondary text-white';
                $statusLabel = 'Dibatalkan';
            } elseif ($statusPjm === 'CHECK_IN') {
                $badgeColor = 'bg-primary text-white';
                $statusLabel = 'Sudah Check-In';
            } elseif ($statusPjm === 'CHECK_OUT') {
                $badgeColor = 'bg-success text-white';
                $statusLabel = 'Sudah Check-Out';
            } else {
                $badgeColor = match($statusApp) {
                    'PENDING' => 'bg-warning text-dark',
                    'APPROVED' => 'bg-success text-white',
                    'REJECTED' => 'bg-danger text-white',
                    default => 'bg-secondary text-white'
                };
                $statusLabel = match($statusApp) {
                    'PENDING' => 'Menunggu Persetujuan',
                    'APPROVED' => 'Disetujui',
                    'REJECTED' => 'Ditolak',
                    default => 'Menunggu Persetujuan'
                };
            }
        @endphp
        <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 13px;">{{ $statusLabel }}</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 14px;">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Status Info -->
    @if($peminjaman->statusApproval === 'PENDING')
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-info-circle"></i>
            <span>Peminjaman ini belum disetujui. Silakan review dan ambil keputusan di bawah.</span>
        </div>
    @elseif($peminjaman->statusPeminjaman === 'SELESAI')
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-check-double"></i>
            <span>Peminjaman telah selesai dan semua sarana/ruangan telah dikembalikan.</span>
        </div>
    @elseif($peminjaman->statusPeminjaman === 'CHECK_IN')
        <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-sign-in-alt"></i>
            <span>Peminjaman sedang aktif (Checked-In). Pengguna sedang menggunakan ruangan/sarana.</span>
        </div>
    @elseif($peminjaman->statusPeminjaman === 'BATAL')
        <div class="alert alert-secondary border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-ban"></i>
            <span>Peminjaman ini telah dibatalkan.</span>
        </div>
    @elseif($peminjaman->statusApproval === 'APPROVED')
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-check-circle"></i>
            <span>Peminjaman telah disetujui pada {{ $peminjaman->tanggalApproval ? $peminjaman->tanggalApproval->format('d F Y H:i') : '-' }}. Menunggu waktu check-in.</span>
        </div>
    @elseif($peminjaman->statusApproval === 'REJECTED')
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-times-circle"></i>
            <span>Peminjaman telah ditolak pada {{ $peminjaman->tanggalApproval ? $peminjaman->tanggalApproval->format('d F Y H:i') : '-' }}</span>
        </div>
    @endif

    <!-- Informasi Tamu/Peminjam -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-user me-2"></i>Informasi Tamu/Peminjam</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Tamu/Peminjam</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->guest->name ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">NIK</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->guest->nik ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jenis Kelamin</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ ($peminjaman->guest->gender ?? '') === 'MALE' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Telepon</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">
                        @php
                            $phoneNum = $peminjaman->guest->phone ?? ($peminjaman->guest->user->phone ?? null);
                        @endphp
                        @if($phoneNum)
                            <a href="tel:{{ $phoneNum }}" style="color: #C9A961; text-decoration: none;">
                                {{ $phoneNum }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Alamat</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->guest->address ?? '-' }}</div>
                </div>
                @if($peminjaman->guest && $peminjaman->guest->notes)
                <div class="col-md-12">
                    <div class="p-3 bg-light rounded border-start border-info border-4 mt-2">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan Tamu</label>
                        <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $peminjaman->guest->notes }}</p>
                    </div>
                </div>
                @endif
                <div class="col-md-12 text-end mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetailTamu">
                        <i class="fas fa-id-card me-1"></i> Lihat Detail Tamu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Ruangan/Fasilitas -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-door-open me-2"></i>Informasi Fasilitas</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Fasilitas</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->ruangan->nama_ruangan ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tipe Ruangan</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ str_replace('_', ' ', $peminjaman->paketRuangan->ruangan->tipe_ruangan ?? '-') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->ruangan->gedung->nama_gedung ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Lantai</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->ruangan->lantai ? 'Lantai ' . $peminjaman->paketRuangan->ruangan->lantai : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kapasitas</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->ruangan->kapasitas ? $peminjaman->paketRuangan->ruangan->kapasitas . ' orang' : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Paket</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->nama_paket ?? 'Paket Standar' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Sarana -->
    @if($peminjaman->detailSaranas && $peminjaman->detailSaranas->count() > 0)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-tools me-2"></i>Informasi Sarana</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Sarana</th>
                            <th style="width: 150px;" class="text-center">Jumlah</th>
                            <th style="width: 150px;" class="text-center">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->detailSaranas as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $detail->sarana->nama ?? 'N/A' }}</td>
                                <td class="text-center fw-bold">{{ $detail->jumlah }} Unit</td>
                                <td class="text-center">
                                    @php
                                        $kondisi = $detail->sarana->kondisi ?? 'Baik';
                                        $badgeColor = match($kondisi) {
                                            'Baik Sekali' => 'bg-success text-white',
                                            'Baik' => 'bg-info text-white',
                                            'Normal' => 'bg-warning text-dark',
                                            'Perlu Perbaikan' => 'bg-danger text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} px-2 py-1">{{ $kondisi }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Informasi Peminjaman -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-calendar-alt me-2"></i>Informasi Peminjaman</h6>
        </div>
        <div class="card-body p-4">
            @php
                $statusPjm = $peminjaman->statusPeminjaman;
                $statusApp = $peminjaman->statusApproval;
                
                if ($statusApp === 'REJECTED') {
                    $pjmBadgeColor = 'bg-danger text-white';
                    $pjmStatusLabel = 'Ditolak';
                } else {
                    $pjmBadgeColor = match($statusPjm) {
                        'RESERVASI' => 'bg-info text-dark',
                        'CHECK_IN' => 'bg-primary text-white',
                        'CHECK_OUT' => 'bg-success text-white',
                        'BATAL' => 'bg-secondary text-white',
                        'SELESAI' => 'bg-success text-white',
                        default => 'bg-secondary text-white'
                    };
                    $pjmStatusLabel = match($statusPjm) {
                        'RESERVASI' => 'Reservasi (Belum Check-In)',
                        'CHECK_IN' => 'Sudah Check-In',
                        'CHECK_OUT' => 'Sudah Check-Out',
                        'BATAL' => 'Dibatalkan',
                        'SELESAI' => 'Selesai',
                        default => $statusPjm
                    };
                }
            @endphp
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kode Peminjaman</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->kodePeminjaman }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Status Peminjaman</label>
                    <div>
                        <span class="badge {{ $pjmBadgeColor }} px-2.5 py-1.5" style="font-size: 12px;">{{ $pjmStatusLabel }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Peminjaman</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jam Mulai</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Durasi</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->durasi }} jam</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Estimasi Jumlah Peserta</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;"><i class="fas fa-users me-1 text-muted"></i> {{ $peminjaman->paketRuangan->ruangan->kapasitas ?? '-' }} orang</div>
                </div>
                <div class="col-md-12">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tujuan Peminjaman (Keperluan)</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->keterangan ?? '-' }}</div>
                </div>
                @if($peminjaman->statusPeminjaman === 'SELESAI')
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Biaya Tambahan</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">Rp {{ number_format($peminjaman->biayaTambahan, 0, ',', '.') }}</div>
                </div>
                @endif
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Waktu Check-In</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->checkIn ? $peminjaman->checkIn->format('d F Y H:i') . ' WIB' : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Waktu Check-Out</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->checkOut ? $peminjaman->checkOut->format('d F Y H:i') . ' WIB' : '-' }}</div>
                </div>
                @if($peminjaman->statusPeminjaman === 'SELESAI')
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kondisi Pengembalian</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->kondisiReturn ? str_replace('_', ' ', $peminjaman->kondisiReturn) : '-' }}</div>
                </div
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Estimasi Denda Kerusakan</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">Rp {{ number_format($peminjaman->estimasiDamage ?? 0, 0, ',', '.') }}</div>
                </div>
                @endif
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Waktu Pengajuan</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->createdAt ? \Carbon\Carbon::parse($peminjaman->createdAt)->format('d F Y H:i') . ' WIB' : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Terakhir Diperbarui</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->updatedAt ? \Carbon\Carbon::parse($peminjaman->updatedAt)->format('d F Y H:i') . ' WIB' : '-' }}</div>
                </div>
            </div>
            @if($peminjaman->keterangan)
            <div class="p-3 bg-light rounded border-start border-warning border-4 mt-3">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Keperluan / Keterangan</label>
                <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $peminjaman->keterangan }}</p>
            </div>
            @endif
            @if($peminjaman->statusPeminjaman === 'SELESAI' && $peminjaman->catatanKerusakan)
            <div class="p-3 bg-light rounded border-start border-danger border-4 mt-3">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan Kerusakan</label>
                <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $peminjaman->catatanKerusakan }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Catatan Approval -->
    @if($peminjaman->catatanApproval)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-comment me-2"></i>{{ $peminjaman->statusApproval === 'REJECTED' ? 'Catatan Penolakan' : 'Catatan Approval' }}</h6>
        </div>
        <div class="card-body p-4">
            <div class="p-3 bg-light rounded border-start border-secondary border-4">
                <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $peminjaman->catatanApproval }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Informasi Invoice & Pembayaran -->
    @if($invoice)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-file-invoice-dollar me-2"></i>Informasi Invoice & Pembayaran</h6>
        </div>
        <div class="card-body p-4">
            @php
                $statusInv = $invoice->statusInvoice;
                $invBadgeColor = match($statusInv) {
                    'UNPAID' => 'bg-warning text-dark',
                    'PARTIAL' => 'bg-info text-dark',
                    'PAID' => 'bg-success text-white',
                    'OVERDUE' => 'bg-danger text-white',
                    default => 'bg-secondary text-white'
                };
                $statusPembayaranLabel = match($invoice->status_pembayaran) {
                    'BELUM_BAYAR' => 'Belum Bayar',
                    'SEBAGIAN' => 'Dibayar Sebagian',
                    'LUNAS' => 'Lunas',
                    default => $invoice->status_pembayaran
                };
                $pembayaranBadgeColor = match($invoice->status_pembayaran) {
                    'BELUM_BAYAR' => 'bg-danger text-white',
                    'SEBAGIAN' => 'bg-warning text-dark',
                    'LUNAS' => 'bg-success text-white',
                    default => 'bg-secondary text-white'
                };
            @endphp
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Invoice</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $invoice->noInvoice }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Status Pembayaran</label>
                    <div>
                        <span class="badge {{ $pembayaranBadgeColor }} px-2.5 py-1.5" style="font-size: 12px;">{{ $statusPembayaranLabel }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Subtotal (Biaya Paket)</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</div>
                </div>
                @if($peminjaman->statusPeminjaman === 'SELESAI')
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Biaya Tambahan / Denda</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">Rp {{ number_format($invoice->biayaTambahan, 0, ',', '.') }}</div>
                </div>
                @endif
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Total Tagihan</label>
                    <div class="fw-semibold text-dark text-primary" style="font-size: 15px;">Rp {{ number_format($invoice->totalHarga, 0, ',', '.') }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Status Invoice</label>
                    <div>
                        <span class="badge {{ $invBadgeColor }} px-2.5 py-1.5" style="font-size: 12px;">{{ $statusInv }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Invoice</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->tglInvoice)->format('d F Y H:i') }} WIB</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Batas Waktu (Due Date)</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $invoice->tglDueDate ? \Carbon\Carbon::parse($invoice->tglDueDate)->format('d F Y') : '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Pembayaran</label>
                    <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $invoice->tgl_pembayaran ? \Carbon\Carbon::parse($invoice->tgl_pembayaran)->format('d F Y H:i') . ' WIB' : '-' }}</div>
                </div>
            </div>
            @if($invoice->notes)
            <div class="p-3 bg-light rounded border-start border-info border-4 mt-3">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan Invoice</label>
                <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2">
                @if(Auth::user()->roleId != 2)
                    @if($peminjaman->statusApproval === 'PENDING')
                        <button type="button" class="btn btn-success fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalApprove" style="font-size: 13px;">
                            <i class="fas fa-check me-1"></i> Setujui
                        </button>
                        <button type="button" class="btn btn-danger fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalReject" style="font-size: 13px;">
                            <i class="fas fa-times me-1"></i> Tolak
                        </button>
                    @endif

                    @if($peminjaman->statusApproval === 'APPROVED' && $peminjaman->statusPeminjaman === 'RESERVASI')
                        <form action="{{ route('main.transaksi.peminjaman.checkin', $peminjaman->id) }}" 
                              method="POST" 
                              class="confirm-submit" 
                              style="display: inline-block;"
                              data-confirm-title="Konfirmasi Check-In"
                              data-confirm-text="Apakah Anda yakin ingin memproses check-in untuk peminjaman ruangan/fasilitas ini?"
                              data-confirm-button="Ya, Check-In">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2" style="font-size: 13px;">
                                <i class="fas fa-sign-in-alt me-1"></i> Proses Check-In
                            </button>
                        </form>
                    @endif

                    @if($peminjaman->statusPeminjaman === 'CHECK_IN')
                        <button type="button" class="btn btn-info text-white fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCheckOut" style="font-size: 13px;">
                            <i class="fas fa-sign-out-alt me-1"></i> Proses Check-Out
                        </button>
                    @endif
                @endif

                @if($invoice)
                    <a href="{{ route('main.transaksi.invoice.show', $peminjaman->id) }}" class="btn fw-bold px-4 py-2 text-white" style="background-color: #C9A961; font-size: 13px;">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Lihat Invoice
                    </a>
                @else
                    <button type="button" class="btn fw-bold px-4 py-2 text-white" disabled style="background-color: #C9A961; opacity: 0.5; cursor: not-allowed; font-size: 13px;">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Invoice Belum Tersedia
                    </button>
                @endif
            </div>
        </div>
    </div>

    @else
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body text-center p-5">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h5>Peminjaman Tidak Ditemukan</h5>
            <p class="text-muted">Peminjaman yang Anda cari tidak tersedia atau telah dihapus.</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove" tabindex="-1" aria-labelledby="modalApproveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fs-6 fw-semibold text-dark" id="modalApproveLabel">
                    <i class="fas fa-check-circle text-success me-1"></i> Setujui Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.approve', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="catatanApprove" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Catatan (Opsional)</label>
                        <textarea id="catatanApprove" class="form-control" name="catatanApproval" placeholder="Tambahkan catatan jika diperlukan..." rows="4" maxlength="1000" style="font-size: 14px;"></textarea>
                        <small class="text-muted d-block mt-1">Maksimal 1000 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="biayaTambahanApprove" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Biaya Tambahan (IDR - Opsional)</label>
                        <input type="number" id="biayaTambahanApprove" class="form-control @error('biayaTambahan') is-invalid @enderror" name="biayaTambahan" placeholder="Contoh: 50000" style="font-size: 14px;" min="0" max="99999999.99" maxlength="11" step="0.01">
                        @error('biayaTambahan')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                        <small class="text-muted d-block mt-1">Isi jika ada biaya tambahan untuk sarana pendukung (Maksimal Rp 99.999.999,99)</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-check me-1"></i> Setujui
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
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fs-6 fw-semibold text-dark" id="modalRejectLabel">
                    <i class="fas fa-times-circle text-danger me-1"></i> Tolak Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.reject', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="catatanReject" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea id="catatanReject" class="form-control" name="catatanApproval" placeholder="Jelaskan alasan penolakan..." rows="4" maxlength="1000" required style="font-size: 14px;"></textarea>
                        <small class="text-muted d-block mt-1">Maksimal 1000 karakter</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-times me-1"></i> Tolak
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
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fs-6 fw-semibold text-dark" id="modalCheckOutLabel">
                    <i class="fas fa-sign-out-alt text-info me-1"></i> Proses Check-Out Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('main.transaksi.peminjaman.checkout', $peminjaman->id ?? '#') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Kondisi Return -->
                    <div class="mb-3">
                        <label for="kondisiReturn" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Kondisi Pengembalian <span class="text-danger">*</span></label>
                        <select class="form-select" id="kondisiReturn" name="kondisiReturn" style="font-size: 14px; padding: 10px;" required>
                            <option value="BAIK">BAIK (Semua Fasilitas Aman)</option>
                            <option value="RUSAK_RINGAN">RUSAK RINGAN</option>
                            <option value="RUSAK_BERAT">RUSAK BERAT</option>
                            <option value="HILANG">ADA YANG HILANG</option>
                        </select>
                    </div>

                    <!-- Catatan Kerusakan -->
                    <div class="mb-3">
                        <label for="catatanKerusakan" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Catatan Kerusakan/Pengembalian</label>
                        <textarea id="catatanKerusakan" class="form-control" name="catatanKerusakan" placeholder="Tuliskan catatan kerusakan jika ada..." rows="3" style="font-size: 14px;"></textarea>
                    </div>

                    <!-- Denda Damage -->
                    <div class="mb-3">
                        <label for="estimasiDamage" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Denda Kerusakan (IDR - Opsional)</label>
                        <input type="number" class="form-control @error('estimasiDamage') is-invalid @enderror" id="estimasiDamage" name="estimasiDamage" placeholder="Contoh: 150000" style="font-size: 14px;" min="0" max="99999999.99" maxlength="11" step="0.01">
                        @error('estimasiDamage')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    <!-- Biaya Tambahan -->
                    <div class="mb-3">
                        <label for="biayaTambahan" class="form-label text-muted small fw-semibold text-uppercase" style="font-size: 11px;">Biaya Tambahan Lainnya (IDR - Opsional)</label>
                        <input type="number" class="form-control @error('biayaTambahan') is-invalid @enderror" id="biayaTambahan" name="biayaTambahan" placeholder="Contoh: 50000 (Misal denda telat check-out)" style="font-size: 14px;" min="0" max="99999999.99" maxlength="11" step="0.01">
                        @error('biayaTambahan')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white btn-sm">
                        <i class="fas fa-check me-1"></i> Selesaikan Check-Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Tamu -->
<div class="modal fade" id="modalDetailTamu" tabindex="-1" aria-labelledby="modalDetailTamuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-white border-bottom-0" style="background-color: #C9A961; border-radius: 8px 8px 0 0; padding: 16px 20px;">
                <h5 class="modal-title fs-5 fw-semibold" id="modalDetailTamuLabel">
                    <i class="fas fa-id-card me-2"></i> Detail Lengkap Tamu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #fcfcfc;">
                @if($peminjaman->guest)
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Tamu/Peminjam</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">{{ $peminjaman->guest->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">NIK (Nomor Induk Kependudukan)</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">{{ $peminjaman->guest->nik ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jenis Kelamin</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">
                            {{ ($peminjaman->guest->gender ?? '') === 'MALE' ? 'Laki-laki' : (($peminjaman->guest->gender ?? '') === 'FEMALE' ? 'Perempuan' : '-') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Telepon / WA</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">
                            {{ $peminjaman->guest->phone ?? ($peminjaman->guest->user->phone ?? '-') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Golongan Darah</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">
                            {{ $peminjaman->guest->bloodType ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Pendaftaran</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px;">
                            {{ $peminjaman->guest->createdAt ? \Carbon\Carbon::parse($peminjaman->guest->createdAt)->format('d F Y H:i') . ' WIB' : '-' }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Alamat Lengkap</label>
                        <div class="fw-semibold text-dark p-2 bg-white rounded border" style="font-size: 14px; min-height: 60px;">
                            {{ $peminjaman->guest->address ?? '-' }}
                        </div>
                    </div>
                    @if($peminjaman->guest->notes)
                    <div class="col-md-12">
                        <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan Tamu</label>
                        <div class="p-3 bg-light rounded border-start border-info border-4">
                            <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $peminjaman->guest->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center p-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                    <p class="mb-0 text-muted">Data tamu tidak ditemukan.</p>
                </div>
                @endif
            </div>
            <div class="modal-footer border-top-0 bg-light rounded-bottom">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
