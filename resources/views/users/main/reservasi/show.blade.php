@extends('users.layout.app')

@section('title', 'Detail Reservasi')

@section('content')
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">

    @if($reservasi)
    <!-- Top Action Buttons & Status -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('users.main.reservasi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('users.main.invoice.index', $reservasi->id) }}" class="btn btn-primary btn-sm text-white" style="background-color: #C9A961; border-color: #C9A961;">
                <i class="fas fa-file-invoice-dollar me-1"></i> Lihat Invoice
            </a>
            @if($reservasi->status === 'COMPLETED')
                @if($reservasi->ruangan && $reservasi->ruangan->id_ruangan)
                    <a href="{{ route('users.main.reservasi.create', ['ruangan_id' => $reservasi->ruangan->id_ruangan]) }}" class="btn btn-success btn-sm px-3">
                        <i class="fas fa-redo me-1"></i> Reservasi Lagi
                    </a>
                @endif
            @elseif($reservasi->status === 'BATAL' || $reservasi->status === 'REJECTED')
                @if($reservasi->ruangan && $reservasi->ruangan->id_ruangan)
                    <a href="{{ route('users.main.reservasi.create', ['ruangan_id' => $reservasi->ruangan->id_ruangan]) }}" class="btn btn-warning btn-sm text-dark px-3">
                        <i class="fas fa-sync me-1"></i> Ajukan Ulang
                    </a>
                @endif
            @endif
        </div>
        
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
                'PENDING' => 'Menunggu Persetujuan',
                'APPROVED' => 'Disetujui',
                'REJECTED' => 'Ditolak',
                'COMPLETED' => 'Selesai',
                'BATAL' => 'Dibatalkan',
                default => 'Menunggu Persetujuan'
            };
        @endphp
        <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 13px;">{{ $statusLabel }}</span>
    </div>

    <!-- Status Info Alert -->
    @if($reservasi->status === 'PENDING')
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-hourglass-half me-1"></i>
            <span>Reservasi Anda sedang dalam proses menunggu persetujuan. Kami akan segera menghubungi Anda melalui nomor telepon yang terdaftar.</span>
        </div>
    @elseif($reservasi->status === 'APPROVED')
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-check-circle me-1"></i>
            <span>Selamat! Reservasi Anda telah disetujui. Silakan hubungi petugas untuk konfirmasi lebih lanjut.</span>
        </div>
    @elseif($reservasi->status === 'REJECTED')
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-times-circle me-1"></i>
            <span>Maaf, reservasi Anda telah ditolak. Silakan hubungi petugas untuk alasan penolakan.</span>
        </div>
    @elseif($reservasi->status === 'BATAL')
        <div class="alert alert-secondary border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-ban me-1"></i>
            <span>Reservasi ini telah dibatalkan. Anda dapat mengajukan ulang reservasi untuk ruangan ini jika diperlukan.</span>
        </div>
    @elseif($reservasi->status === 'COMPLETED')
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="font-size: 14px;">
            <i class="fas fa-check-double me-1"></i>
            <span>Reservasi Anda telah selesai. Terima kasih telah menggunakan layanan kami.</span>
        </div>
    @endif

    <!-- Informasi Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-door-open me-2"></i>Informasi Ruangan</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Ruangan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->ruangan->nama_ruangan }}</div>
                </div>
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jenis Ruangan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ str_replace('_', ' ', $reservasi->ruangan->tipe_ruangan) }}</div>
                </div>
                {{-- Gedung - DISABLED
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->ruangan->gedung->nama_gedung ?? '-' }}</div>
                </div>
                --}}
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Lantai</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->ruangan->lantai ? 'Lantai ' . $reservasi->ruangan->lantai : '-' }}</div>
                </div>
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kapasitas</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        <i class="fas fa-users me-1 text-muted"></i> {{ $reservasi->ruangan->kapasitas }} orang
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Paket Ruangan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        {{ $reservasi->paketRuangan->nama_paket ?? 'Paket Standar' }} ({{ $reservasi->paketRuangan->durasi ?? $reservasi->durasi }} Jam) - Rp {{ number_format($reservasi->paketRuangan->harga ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Reservasi -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-calendar-alt me-2"></i>Informasi Reservasi</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Mulai</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($reservasi->tanggal_mulai)->format('d F Y') }}</div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Selesai</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($reservasi->tanggal_selesai)->format('d F Y') }}</div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Durasi Peminjaman</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        @php
                            $isHarian = ($reservasi->paketRuangan && (stripos($reservasi->paketRuangan->nama_paket, 'hari') !== false || stripos($reservasi->paketRuangan->nama_paket, 'harian') !== false));
                        @endphp
                        @if($isHarian)
                            {{ $reservasi->durasi }} hari
                        @else
                            {{ $reservasi->durasi ? $reservasi->durasi . ' jam' : 'Fleksibel' }}
                        @endif
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Estimasi Peserta</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;"><i class="fas fa-users me-1 text-muted"></i> {{ $reservasi->estimasi_peserta }} orang</div>
                </div>
            </div>

            <div class="keterangan-section p-3 bg-light rounded border-start border-warning border-4 mt-4">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Keperluan / Tujuan Penggunaan</label>
                <div class="text-dark value" style="font-size: 14px; line-height: 1.6;">{{ $reservasi->keperluan }}</div>
            </div>
        </div>
    </div>

    <!-- Informasi Sarana yang Dipinjam -->
    @if($reservasi->detailSaranas && $reservasi->detailSaranas->count() > 0)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-tools me-2"></i>Sarana yang Dipinjam</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0" style="width: 100%; font-size: 14px;">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Sarana</th>
                            <th style="width: 150px;" class="text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasi->detailSaranas as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $detail->sarana->nama ?? 'N/A' }}</td>
                                <td class="text-center fw-bold">{{ $detail->jumlah }} Unit</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Kontak -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-user me-2"></i>Data Kontak</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Kontak Person</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->kontak_person }}</div>
                </div>

                <div class="col-md-6 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nomor Telepon</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        <a href="tel:{{ $reservasi->no_telepon }}" style="color: #C9A961; text-decoration: none;">
                            {{ $reservasi->no_telepon }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Foto Ruangan -->
    @if($reservasi->ruangan->mediaFiles && $reservasi->ruangan->mediaFiles->count() > 0)
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-images me-2"></i>Foto Ruangan</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-2">
                @foreach($reservasi->ruangan->mediaFiles as $media)
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="rounded overflow-hidden border" style="aspect-ratio: 1;">
                        <img src="{{ asset($media->path) }}" alt="Foto {{ $reservasi->ruangan->nama_ruangan }}" class="w-100 h-100 object-fit-cover" onerror="this.src='https://placehold.co/300?text=Foto+Ruangan'; this.onerror=null;">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Metadata / Informasi Sistem -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">ID Reservasi</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">#{{ $reservasi->id }}</div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Tanggal Pembuatan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->createdAt ? $reservasi->createdAt->format('d F Y H:i') : '-' }}</div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Terakhir Diubah</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $reservasi->updatedAt ? $reservasi->updatedAt->format('d F Y H:i') : '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h5>Reservasi Tidak Ditemukan</h5>
            <p>Reservasi yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('users.main.reservasi.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Reservasi
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
