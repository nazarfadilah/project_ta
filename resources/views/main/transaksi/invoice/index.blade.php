@extends('admin.layouts.app')

@section('title', 'Detail Invoice')

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
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .invoice-header h1 {
        color: var(--sidebar-text);
        font-weight: 700;
        font-size: 28px;
        margin: 0;
    }

    .invoice-number {
        background-color: #f8f9fa;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        color: var(--sidebar-text);
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

    .badge-unpaid {
        background-color: #f8d7da;
        color: #721c24;
    }

    .badge-partial {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-overdue {
        background-color: #f5c6cb;
        color: #721c24;
    }

    .invoice-summary {
        background-color: #f9f9f9;
        padding: 24px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
        font-size: 14px;
        font-weight: 600;
    }

    .summary-value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 700;
    }

    .summary-total {
        padding-top: 16px !important;
        border-top: 2px solid var(--gold-primary) !important;
        margin-top: 12px !important;
    }

    .summary-total .summary-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--sidebar-text);
    }

    .summary-total .summary-value {
        font-size: 20px;
        color: var(--gold-primary);
    }

    .facility-info {
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 4px;
        border-left: 4px solid var(--gold-primary);
        margin-top: 20px;
    }

    .facility-info-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .facility-info-item:last-child {
        margin-bottom: 0;
    }

    .facility-info-label {
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .facility-info-value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 600;
    }

    .print-btn {
        background-color: var(--gold-primary);
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
        cursor: pointer;
    }

    .print-btn:hover {
        background-color: #d4a017;
        color: white;
        text-decoration: none;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .invoice-header h1 {
            font-size: 22px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn-back,
        .action-buttons .print-btn {
            width: 100%;
        }
    }

    @media print {
        .btn-back, .print-btn, .action-buttons {
            display: none;
        }

        body {
            background-color: white;
        }

        .card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
</style>
@endsection

@section('content')
@if($invoice)
    <div class="action-buttons">
        <a href="{{ route('main.transaksi.peminjaman.show', $peminjaman->id) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Peminjaman
        </a>
        <button onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
    </div>

    <div class="invoice-header">
        <h1>Invoice</h1>
        <span class="invoice-number">{{ $invoice->noInvoice }}</span>
    </div>

    <!-- Status Invoice -->
    <div class="card">
        <div class="card-body">
            @php
                $statusInvoice = $invoice->statusInvoice;
                $badgeClass = match($statusInvoice) {
                    'UNPAID' => 'badge-unpaid',
                    'PARTIAL' => 'badge-partial',
                    'PAID' => 'badge-paid',
                    'OVERDUE' => 'badge-overdue',
                    default => 'badge-unpaid'
                };
                $statusLabel = match($statusInvoice) {
                    'UNPAID' => 'Belum Dibayar',
                    'PARTIAL' => 'Sebagian Dibayar',
                    'PAID' => 'Lunas',
                    'OVERDUE' => 'Jatuh Tempo',
                    default => 'Belum Dibayar'
                };
            @endphp

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h6 style="color: #666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Status Pembayaran</h6>
                    <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </div>
                <div style="text-align: right;">
                    <h6 style="color: #666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Nomor Invoice</h6>
                    <div style="color: var(--sidebar-text); font-size: 16px; font-weight: 600;">{{ $invoice->noInvoice }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Fasilitas/Ruangan -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-door-open"></i> Informasi Fasilitas</h5>
        </div>
        <div class="card-body">
            <div class="facility-info">
                <div class="facility-info-item">
                    <span class="facility-info-label">Nama Fasilitas</span>
                    <span class="facility-info-value">
                        {{ $peminjaman->paketRuangan->ruangan->nama_ruangan ?? 'Tidak tersedia' }}
                    </span>
                </div>
                <div class="facility-info-item">
                    <span class="facility-info-label">Tipe Ruangan</span>
                    <span class="facility-info-value">
                        {{ str_replace('_', ' ', $peminjaman->paketRuangan->ruangan->tipe_ruangan ?? '-') }}
                    </span>
                </div>
                <div class="facility-info-item">
                    <span class="facility-info-label">Gedung</span>
                    <span class="facility-info-value">
                        {{ $peminjaman->paketRuangan->ruangan->gedung->nama_gedung ?? '-' }}
                    </span>
                </div>
                <div class="facility-info-item">
                    <span class="facility-info-label">Paket</span>
                    <span class="facility-info-value">
                        {{ $peminjaman->paketRuangan->nama_paket ?? 'Paket Standar' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tanggal & Durasi -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-calendar-alt"></i> Informasi Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Tanggal Peminjaman</label>
                    <div class="value">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</div>
                </div>

                <div class="detail-item">
                    <label>Jam Mulai</label>
                    <div class="value">{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</div>
                </div>

                <div class="detail-item">
                    <label>Durasi Peminjaman</label>
                    <div class="value">{{ $peminjaman->durasi }} jam</div>
                </div>

                <div class="detail-item">
                    <label>Kode Peminjaman</label>
                    <div class="value">{{ $peminjaman->kodePeminjaman }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Biaya -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-receipt"></i> Detail Biaya</h5>
        </div>
        <div class="card-body">
            <div class="invoice-summary">
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Biaya Tambahan</span>
                    <span class="summary-value" style="color: #dc3545;">{{ $invoice->biayaTambahan > 0 ? '+' : '' }} Rp {{ number_format($invoice->biayaTambahan, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row summary-total">
                    <span class="summary-label">Total Harga</span>
                    <span class="summary-value">Rp {{ number_format($invoice->totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($invoice->notes)
            <div style="margin-top: 20px; padding: 16px; background-color: #f9f9f9; border-radius: 4px;">
                <h6 style="color: #666; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Catatan</h6>
                <p style="color: var(--sidebar-text); font-size: 14px; margin: 0; line-height: 1.6;">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi Tanggal Invoice -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-info-circle"></i> Informasi Sistem</h5>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Tanggal Invoice</label>
                    <div class="value">{{ \Carbon\Carbon::parse($invoice->tglInvoice)->format('d F Y H:i') }}</div>
                </div>

                <div class="detail-item">
                    <label>Jatuh Tempo</label>
                    <div class="value">
                        @if($invoice->tglDueDate)
                            {{ \Carbon\Carbon::parse($invoice->tglDueDate)->format('d F Y H:i') }}
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <label>Tanggal Pembayaran</label>
                    <div class="value">
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

@else
    <div class="card">
        <div class="card-body text-center" style="padding: 40px;">
            <i class="fas fa-file-invoice" style="font-size: 48px; color: #dc3545; margin-bottom: 16px;"></i>
            <h5>Invoice Tidak Ditemukan</h5>
            <p>Invoice untuk peminjaman ini belum tersedia.</p>
            <a href="{{ route('main.transaksi.peminjaman.index') }}" class="btn-back" style="margin-top: 16px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Peminjaman
            </a>
        </div>
    </div>
@endif

@endsection
