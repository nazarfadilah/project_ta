@extends('users.layout.app')

@section('title', 'Detail Invoice')

@section('css')
<style>
    @media print {
        .btn-back, .print-btn, .action-buttons, #sidebar, #topNavbar, .main-footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .main-content {
            padding: 0 !important;
        }
        body {
            background-color: white !important;
            color: black !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            background-color: white !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: black !important;
            border-bottom: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@section('content')
@if($invoice)
<div class="container-fluid py-2 col-lg-8 mx-auto">

    <!-- Top Action Buttons -->
    <div class="action-buttons d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <a href="{{ route('users.main.reservasi.show', $peminjaman->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Reservasi
        </a>
        <button onclick="window.print()" class="btn btn-sm text-white px-3" style="background-color: #C9A961; border-color: #C9A961;">
            <i class="fas fa-print me-1"></i> Cetak Invoice
        </button>
    </div>

    <!-- Status Invoice -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-file-invoice-dollar me-2"></i>Status Invoice
            </h6>
            <span class="fw-bold">{{ $invoice->noInvoice }}</span>
        </div>
        <div class="card-body p-4">
            @php
                $statusInvoice = $invoice->statusInvoice;
                $badgeColor = match($statusInvoice) {
                    'UNPAID' => 'bg-danger text-white',
                    'PARTIAL' => 'bg-warning text-dark',
                    'PAID' => 'bg-success text-white',
                    'OVERDUE' => 'bg-danger text-white',
                    default => 'bg-danger text-white'
                };
                $statusLabel = match($statusInvoice) {
                    'UNPAID' => 'Belum Dibayar',
                    'PARTIAL' => 'Sebagian Dibayar',
                    'PAID' => 'Lunas',
                    'OVERDUE' => 'Jatuh Tempo',
                    default => 'Belum Dibayar'
                };
            @endphp

            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h6 class="text-muted small fw-semibold text-uppercase mb-2" style="font-size: 11px;">Status Pembayaran</h6>
                    <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 13px;">{{ $statusLabel }}</span>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h6 class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 11px;">Nomor Invoice</h6>
                    <div class="fw-semibold text-dark" style="font-size: 16px;">{{ $invoice->noInvoice }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Fasilitas/Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-door-open me-2"></i>Informasi Fasilitas</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Fasilitas</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        {{ $invoice->peminjamanTransaksi->paketRuangan->ruangan->nama_ruangan ?? 'Tidak tersedia' }}
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tipe Ruangan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        {{ str_replace('_', ' ', $invoice->peminjamanTransaksi->paketRuangan->ruangan->tipe_ruangan ?? '-') }}
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        -
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Paket</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        {{ $invoice->peminjamanTransaksi->paketRuangan->nama_paket ?? 'Paket Standar' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tanggal & Durasi -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-calendar-alt me-2"></i>Informasi Peminjaman</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Peminjaman</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->peminjamanTransaksi->tanggal)->format('d F Y') }}</div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jam Mulai</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->peminjamanTransaksi->jamMulai)->format('H:i') }} WIB</div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Durasi Peminjaman</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        @php
                            $pjm = $invoice->peminjamanTransaksi;
                            $isHarian = ($pjm->paketRuangan && $pjm->paketRuangan->tipe_paket == 1);
                        @endphp
                        @if($isHarian)
                            {{ $pjm->durasi }} hari
                        @else
                            {{ $pjm->durasi ? $pjm->durasi . ' jam' : 'Fleksibel' }}
                        @endif
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kode Peminjaman</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $invoice->peminjamanTransaksi->kodePeminjaman }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Biaya -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-receipt me-2"></i>Detail Biaya</h6>
        </div>
        <div class="card-body p-4">
            <div class="list-group list-group-flush rounded border mb-3">
                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Subtotal</span>
                    <span class="fw-bold text-dark">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size: 11px;">Biaya Tambahan</span>
                    <span class="fw-bold text-danger">{{ $invoice->biayaTambahan > 0 ? '+' : '' }} Rp {{ number_format($invoice->biayaTambahan, 0, ',', '.') }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                    <span class="text-dark fw-bold text-uppercase" style="font-size: 12px;">Total Harga</span>
                    <span class="fw-bold text-success fs-4">Rp {{ number_format($invoice->totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($invoice->notes)
            <div class="p-3 bg-light rounded border-start border-warning border-4 mt-3">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan</label>
                <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi Tanggal Invoice / Informasi Sistem -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Invoice</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->tglInvoice)->format('d F Y H:i') }}</div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jatuh Tempo</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        @if($invoice->tglDueDate)
                            {{ \Carbon\Carbon::parse($invoice->tglDueDate)->format('d F Y H:i') }}
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Pembayaran</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        @if($invoice->tglPaid)
                            {{ \Carbon\Carbon::parse($invoice->tglPaid)->format('d F Y H:i') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@else
<div class="container-fluid py-2 col-lg-8 mx-auto">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-file-invoice fa-3x text-danger mb-3"></i>
            <h5>Invoice Tidak Ditemukan</h5>
            <p>Invoice untuk reservasi ini belum tersedia.</p>
            <a href="{{ route('users.main.reservasi.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Reservasi
            </a>
        </div>
    </div>
</div>
@endif
@endsection
