@extends('main.layout.app')

@section('title', 'Detail Invoice')

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
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="font-size: 14px;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="font-size: 14px;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($invoice)
                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3 action-buttons">
                    <a href="{{ route('main.transaksi.peminjaman.show', $peminjaman->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Peminjaman
                    </a>
                    <button onclick="window.print()" class="btn btn-sm text-white" style="background-color: #C9A961;">
                        <i class="fas fa-print me-1"></i> Cetak Invoice
                    </button>
                </div>

                <!-- Title Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h4 class="mb-0 fw-bold text-dark">Invoice</h4>
                    <span class="bg-light text-dark px-3 py-2 rounded border fw-semibold" style="font-size: 14px;">{{ $invoice->noInvoice }}</span>
                </div>

                <!-- Status Invoice -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        @php
                            $statusInvoice = $invoice->statusInvoice;
                            $badgeColor = match($statusInvoice) {
                                'UNPAID' => 'bg-danger text-white',
                                'PARTIAL' => 'bg-warning text-dark',
                                'PAID' => 'bg-success text-white',
                                'OVERDUE' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            $statusLabel = match($statusInvoice) {
                                'UNPAID' => 'Belum Dibayar',
                                'PARTIAL' => 'Sebagian Dibayar',
                                'PAID' => 'Lunas',
                                'OVERDUE' => 'Jatuh Tempo',
                                default => 'Belum Dibayar'
                            };
                        @endphp

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Status Pembayaran</label>
                                <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 13px;">{{ $statusLabel }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Invoice</label>
                                <div class="fw-bold text-dark" style="font-size: 16px;">{{ $invoice->noInvoice }}</div>
                            </div>
                        </div>

                        @if(Auth::user()->roleId != 2)
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span class="text-muted small fw-semibold" style="font-size: 12px; color: #555;">Ubah Status Pembayaran:</span>
                            <div>
                                @if($invoice->statusInvoice === 'UNPAID')
                                    <form action="{{ route('main.transaksi.invoice.updateStatus', $invoice->id) }}" 
                                          method="POST" 
                                          class="d-inline confirm-submit"
                                          data-confirm-title="Konfirmasi Pembayaran"
                                          data-confirm-text="Apakah Anda yakin ingin menandai invoice ini sebagai LUNAS?"
                                          data-confirm-button="Ya, Lunas">
                                         @csrf
                                         <input type="hidden" name="statusInvoice" value="PAID">
                                         <button type="submit" class="btn btn-sm btn-success px-3 py-1 fw-semibold" style="font-size: 13px;">
                                             <i class="fas fa-check me-1"></i> Tandai Lunas (PAID)
                                         </button>
                                     </form>
                                 @else
                                     <form action="{{ route('main.transaksi.invoice.updateStatus', $invoice->id) }}" 
                                          method="POST" 
                                          class="d-inline confirm-submit"
                                          data-confirm-title="Konfirmasi Pembayaran"
                                          data-confirm-text="Apakah Anda yakin ingin menandai invoice ini sebagai BELUM DIBAYAR?"
                                          data-confirm-button="Ya, Ubah">
                                         @csrf
                                         <input type="hidden" name="statusInvoice" value="UNPAID">
                                         <button type="submit" class="btn btn-sm btn-danger px-3 py-1 fw-semibold" style="font-size: 13px;">
                                             <i class="fas fa-times me-1"></i> Tandai Belum Bayar (UNPAID)
                                         </button>
                                     </form>
                                 @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Fasilitas/Ruangan -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                        <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-door-open me-2"></i>Informasi Fasilitas</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Fasilitas</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->ruangan->nama_ruangan ?? 'Tidak tersedia' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tipe Ruangan</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ str_replace('_', ' ', $peminjaman->paketRuangan->ruangan->tipe_ruangan ?? '-') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">-</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Paket</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->paketRuangan->nama_paket ?? 'Paket Standar' }}</div>
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
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Peminjaman</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jam Mulai</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($peminjaman->jamMulai)->format('H:i') }} WIB</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Durasi Peminjaman</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">
                                    @php
                                        $isHarian = ($peminjaman->paketRuangan && (stripos($peminjaman->paketRuangan->nama_paket, 'hari') !== false || stripos($peminjaman->paketRuangan->nama_paket, 'harian') !== false));
                                    @endphp
                                    @if($isHarian)
                                        {{ $peminjaman->durasi }} hari
                                    @else
                                        {{ $peminjaman->durasi ? $peminjaman->durasi . ' jam' : 'Fleksibel' }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kode Peminjaman</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $peminjaman->kodePeminjaman }}</div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" style="width: 100%; font-size: 14px;">
                                <tbody>
                                    <tr>
                                        <td class="text-muted fw-semibold" style="width: 50%;">Subtotal</td>
                                        <td class="text-end fw-semibold text-dark">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-semibold">Biaya Tambahan</td>
                                        <td class="text-end text-danger fw-semibold">{{ $invoice->biayaTambahan > 0 ? '+' : '' }} Rp {{ number_format($invoice->biayaTambahan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="fw-bold text-dark" style="font-size: 15px;">Total Harga</td>
                                        <td class="text-end fw-bold" style="font-size: 17px; color: #C9A961 !important;">Rp {{ number_format($invoice->totalHarga, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($invoice->notes)
                        <div class="p-3 bg-light rounded border-start border-warning border-4 mt-3">
                            <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Catatan</label>
                            <p class="text-dark mb-0" style="line-height: 1.6; font-size: 14px;">{{ $invoice->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Tanggal Invoice -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                        <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Invoice</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->tglInvoice)->format('d F Y H:i') }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jatuh Tempo</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">
                                    @if($invoice->tglDueDate)
                                        {{ \Carbon\Carbon::parse($invoice->tglDueDate)->format('d F Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Pembayaran</label>
                                <div class="fw-semibold text-dark" style="font-size: 14px;">
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
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-file-invoice fa-3x text-danger mb-3"></i>
                        <h5>Invoice Tidak Ditemukan</h5>
                        <p class="text-muted">Invoice untuk peminjaman ini belum tersedia.</p>
                        <a href="{{ route('main.transaksi.peminjaman.index') }}" class="btn btn-secondary btn-sm mt-3">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Peminjaman
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
