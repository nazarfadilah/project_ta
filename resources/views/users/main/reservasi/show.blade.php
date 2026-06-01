@extends('users.layout.app')

@section('title', 'Detail Reservasi')

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

    .btn-back[style*="gold-primary"] {
        background-color: var(--gold-primary);
    }

    .btn-back[style*="gold-primary"]:hover {
        background-color: #d4a017;
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
        color: var(--gold-primary);
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

    .badge-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .keterangan-section {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin-top: 20px;
    }

    .keterangan-section label {
        color: #666;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .keterangan-section .value {
        color: var(--sidebar-text);
        font-size: 14px;
        line-height: 1.6;
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

    .ruangan-info {
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 4px;
        border-left: 4px solid var(--gold-primary);
    }

    .ruangan-info-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ruangan-info-item:last-child {
        margin-bottom: 0;
    }

    .ruangan-info-label {
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .ruangan-info-value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 600;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 4px;
        background-color: #f5f5f5;
        aspect-ratio: 1;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        margin-bottom: 20px;
        position: relative;
        padding-left: 40px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 28px;
        height: 28px;
        background-color: var(--gold-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 700;
    }

    .timeline-content {
        padding: 12px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }

    .timeline-content .label {
        color: #666;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .timeline-content .value {
        color: var(--sidebar-text);
        font-size: 14px;
        font-weight: 600;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-header h1 {
            font-size: 22px;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
    <a href="{{ route('users.main.reservasi.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Reservasi
    </a>
    <a href="{{ route('users.main.invoice.index', $reservasi->id) }}" class="btn-back" style="background-color: var(--gold-primary);">
        <i class="fas fa-file-invoice-dollar"></i> Lihat Invoice
    </a>
</div>

@if($reservasi)
<div class="detail-header">
    <h1>Detail Reservasi</h1>
    @php
        $status = $reservasi->status;
        $badgeClass = match($status) {
            'PENDING' => 'badge-pending',
            'APPROVED' => 'badge-approved',
            'REJECTED' => 'badge-rejected',
            'COMPLETED' => 'badge-completed',
            default => 'badge-pending'
        };
        $statusLabel = match($status) {
            'PENDING' => 'Menunggu Persetujuan',
            'APPROVED' => 'Disetujui',
            'REJECTED' => 'Ditolak',
            'COMPLETED' => 'Selesai',
            default => 'Menunggu Persetujuan'
        };
    @endphp
    <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
</div>

<!-- Status Info -->
<div class="card">
    <div class="card-body">
        @if($reservasi->status === 'PENDING')
        <div class="info-box">
            <i class="fas fa-hourglass-half"></i>
            <p class="info-box-text">Reservasi Anda sedang dalam proses menunggu persetujuan. Kami akan segera menghubungi Anda melalui nomor telepon yang terdaftar.</p>
        </div>
        @elseif($reservasi->status === 'APPROVED')
        <div class="info-box" style="background-color: #d4edda; border-left-color: #155724;">
            <i class="fas fa-check-circle" style="color: #155724;"></i>
            <p class="info-box-text" style="color: #155724;">Selamat! Reservasi Anda telah disetujui. Silakan hubungi petugas untuk konfirmasi lebih lanjut.</p>
        </div>
        @elseif($reservasi->status === 'REJECTED')
        <div class="info-box" style="background-color: #f8d7da; border-left-color: #721c24;">
            <i class="fas fa-times-circle" style="color: #721c24;"></i>
            <p class="info-box-text" style="color: #721c24;">Maaf, reservasi Anda telah ditolak. Silakan hubungi untuk alasan penolakan.</p>
        </div>
        @elseif($reservasi->status === 'COMPLETED')
        <div class="info-box" style="background-color: #d1ecf1; border-left-color: #0c5460;">
            <i class="fas fa-check-double" style="color: #0c5460;"></i>
            <p class="info-box-text" style="color: #0c5460;">Reservasi Anda telah selesai. Terima kasih telah menggunakan layanan kami.</p>
        </div>
        @endif
    </div>
</div>

<!-- Informasi Ruangan -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-door-open"></i> Informasi Ruangan</h5>
    </div>
    <div class="card-body">
        <div class="ruangan-info">
            <div class="ruangan-info-item">
                <span class="ruangan-info-label">Nama Ruangan</span>
                <span class="ruangan-info-value">{{ $reservasi->ruangan->nama_ruangan }}</span>
            </div>
            <div class="ruangan-info-item">
                <span class="ruangan-info-label">Jenis Ruangan</span>
                <span class="ruangan-info-value">{{ str_replace('_', ' ', $reservasi->ruangan->tipe_ruangan) }}</span>
            </div>
            <div class="ruangan-info-item">
                <span class="ruangan-info-label">Gedung</span>
                <span class="ruangan-info-value">{{ $reservasi->ruangan->gedung->nama_gedung ?? '-' }}</span>
            </div>
            <div class="ruangan-info-item">
                <span class="ruangan-info-label">Lantai</span>
                <span class="ruangan-info-value">{{ $reservasi->ruangan->lantai ? 'Lantai ' . $reservasi->ruangan->lantai : '-' }}</span>
            </div>
            <div class="ruangan-info-item">
                <span class="ruangan-info-label">Kapasitas</span>
                <span class="ruangan-info-value"><i class="fas fa-users"></i> {{ $reservasi->ruangan->kapasitas }} orang</span>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Reservasi -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-calendar-alt"></i> Informasi Reservasi</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Tanggal Mulai</label>
                <div class="value">{{ \Carbon\Carbon::parse($reservasi->tanggal_mulai)->format('d F Y') }}</div>
            </div>

            <div class="detail-item">
                <label>Tanggal Selesai</label>
                <div class="value">{{ \Carbon\Carbon::parse($reservasi->tanggal_selesai)->format('d F Y') }}</div>
            </div>

            <div class="detail-item">
                <label>Durasi Peminjaman</label>
                <div class="value">
                    {{ \Carbon\Carbon::parse($reservasi->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($reservasi->tanggal_selesai)) }} hari
                </div>
            </div>

            <div class="detail-item">
                <label>Estimasi Peserta</label>
                <div class="value"><i class="fas fa-users"></i> {{ $reservasi->estimasi_peserta }} orang</div>
            </div>
        </div>

        <div class="keterangan-section">
            <label>Keperluan / Tujuan Penggunaan</label>
            <div class="value">{{ $reservasi->keperluan }}</div>
        </div>
    </div>
</div>

<!-- Data Kontak -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-user"></i> Data Kontak</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Nama Kontak Person</label>
                <div class="value">{{ $reservasi->kontak_person }}</div>
            </div>

            <div class="detail-item">
                <label>Nomor Telepon</label>
                <div class="value">
                    <a href="tel:{{ $reservasi->no_telepon }}" style="color: var(--gold-primary); text-decoration: none;">
                        {{ $reservasi->no_telepon }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Foto Ruangan -->
@if($reservasi->ruangan->mediaFiles && $reservasi->ruangan->mediaFiles->count() > 0)
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-images"></i> Foto Ruangan</h5>
    </div>
    <div class="card-body">
        <div class="gallery-grid">
            @foreach($reservasi->ruangan->mediaFiles as $media)
            <div class="gallery-item">
                <img src="{{ asset($media->path) }}" alt="Foto {{ $reservasi->ruangan->nama_ruangan }}" onerror="this.src='https://via.placeholder.com/300?text=Foto+Ruangan'">
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Metadata -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-info-circle"></i> Informasi Sistem</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>ID Reservasi</label>
                <div class="value">#{{ $reservasi->id }}</div>
            </div>

            <div class="detail-item">
                <label>Tanggal Pembuatan</label>
                <div class="value">{{ $reservasi->created_at->format('d F Y H:i') }}</div>
            </div>

            <div class="detail-item">
                <label>Terakhir Diubah</label>
                <div class="value">{{ $reservasi->updated_at->format('d F Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center" style="padding: 40px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc3545; margin-bottom: 16px;"></i>
        <h5>Reservasi Tidak Ditemukan</h5>
        <p>Reservasi yang Anda cari tidak tersedia atau telah dihapus.</p>
        <a href="{{ route('users.main.reservasi.index') }}" class="btn-back" style="margin-top: 16px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Reservasi
        </a>
    </div>
</div>
@endif

@endsection
