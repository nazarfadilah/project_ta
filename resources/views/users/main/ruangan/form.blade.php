@extends('users.layout.app')

@section('title', $ruangan->nama_ruangan ?? 'Detail Ruangan')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .breadcrumb-section {
        margin-bottom: 30px;
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
        font-size: 32px;
        margin: 0;
    }

    .btn-reservasi {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 28px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        min-width: 160px;
        justify-content: center;
    }

    .btn-reservasi:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: white;
    }

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

    .detail-item .value.badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-kamar {
        background-color: #e7f3ff;
        color: #0056b3;
    }

    .badge-aula {
        background-color: #fff4e6;
        color: #ff9900;
    }

    .badge-meeting {
        background-color: #f0f0f0;
        color: #333;
    }

    .badge-lainnya {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .gallery-section {
        padding: 20px;
    }

    .gallery-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--sidebar-text);
        margin-bottom: 16px;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f5f5f5;
        aspect-ratio: 1;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .gallery-item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .gallery-item:hover .gallery-item-overlay {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .gallery-item-overlay i {
        color: white;
        font-size: 28px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-item-overlay i {
        opacity: 1;
    }

    .no-gallery {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .no-gallery i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
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

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background-color: #e7f3ff;
        color: #0056b3;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-header h1 {
            font-size: 24px;
        }

        .btn-reservasi {
            width: 100%;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>
@endsection

@section('content')
@if($ruangan)
<div class="breadcrumb-section">
    <a href="{{ route('users.main.ruangan.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ruangan
    </a>
</div>

<div class="detail-header">
    <h1>{{ $ruangan->nama_ruangan }}</h1>
    <form action="{{ route('users.main.reservasi.create') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="ruangan_id" value="{{ $ruangan->id_ruangan }}">
        <input type="hidden" name="nama_ruangan" value="{{ $ruangan->nama_ruangan }}">
        <input type="hidden" name="tipe_ruangan" value="{{ $ruangan->tipe_ruangan }}">
        <input type="hidden" name="kapasitas" value="{{ $ruangan->kapasitas }}">
        <input type="hidden" name="gedung_id" value="{{ $ruangan->gedung_id }}">
        <button type="submit" class="btn-reservasi">
            <i class="fas fa-calendar-check"></i> Reservasi Ruangan
        </button>
    </form>
</div>

<!-- Informasi Detail Ruangan -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-info-circle"></i> Informasi Detail Ruangan</h5>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Nama Ruangan</label>
                <div class="value">{{ $ruangan->nama_ruangan }}</div>
            </div>

            <div class="detail-item">
                <label>Jenis Ruangan</label>
                @php
                    $tipe = $ruangan->tipe_ruangan;
                    $badgeClass = match($tipe) {
                        'KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM' => 'badge-kamar',
                        'AULA' => 'badge-aula',
                        'RUANG_MEETING' => 'badge-meeting',
                        default => 'badge-lainnya'
                    };
                    $tipoLabel = str_replace('_', ' ', $tipe);
                @endphp
                <div class="value badge {{ $badgeClass }}">{{ $tipoLabel }}</div>
            </div>

            <div class="detail-item">
                <label>Gedung</label>
                <div class="value">{{ $ruangan->gedung->nama_gedung ?? '-' }}</div>
            </div>

            <div class="detail-item">
                <label>Lantai</label>
                <div class="value">
                    @if($ruangan->lantai)
                        Lantai {{ $ruangan->lantai }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <label>Kapasitas</label>
                <div class="value">
                    <i class="fas fa-users"></i> {{ $ruangan->kapasitas }} orang
                </div>
            </div>

            <div class="detail-item">
                <label>Kebijakan Gender</label>
                <div class="value">
                    @php
                        $genderLabel = match($ruangan->gender_policy) {
                            'MALE_ONLY' => 'Khusus Pria',
                            'FEMALE_ONLY' => 'Khusus Wanita',
                            'MIXED' => 'Campuran',
                            default => '-'
                        };
                    @endphp
                    {{ $genderLabel }}
                </div>
            </div>
        </div>

        @if($ruangan->keterangan)
        <div class="keterangan-section">
            <label>Keterangan & Deskripsi</label>
            <div class="value">{{ $ruangan->keterangan }}</div>
        </div>
        @endif
    </div>
</div>

<!-- Gallery Ruangan -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-images"></i> Galeri Foto Ruangan</h5>
    </div>
    <div class="card-body">
        @if($ruangan->mediaFiles && $ruangan->mediaFiles->count() > 0)
        <div class="gallery-section">
            <div class="gallery-grid">
                @foreach($ruangan->mediaFiles as $media)
                <a href="{{ asset($media->path) }}" data-lightbox="ruangan-gallery" class="gallery-item">
                    <img src="{{ asset($media->path) }}" alt="Foto {{ $ruangan->nama_ruangan }}" onerror="this.src='https://via.placeholder.com/300?text=Foto+Ruangan'">
                    <div class="gallery-item-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @else
        <div class="gallery-section">
            <div class="no-gallery">
                <i class="fas fa-image"></i>
                <h5>Tidak Ada Foto</h5>
                <p>Belum ada foto yang ditambahkan untuk ruangan ini.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-exclamation-circle"></i> Informasi Penting</h5>
    </div>
    <div class="card-body">
        <div class="info-badge">
            <i class="fas fa-calendar"></i>
            <span>Untuk melihat ketersediaan ruangan, silakan lakukan proses reservasi</span>
        </div>
        <div class="info-badge">
            <i class="fas fa-phone"></i>
            <span>Jika ada pertanyaan, hubungi petugas melalui halaman kontak kami</span>
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center" style="padding: 40px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc3545; margin-bottom: 16px;"></i>
        <h5>Ruangan Tidak Ditemukan</h5>
        <p>Ruangan yang Anda cari tidak tersedia atau telah dihapus.</p>
        <a href="{{ route('users.main.ruangan.index') }}" class="btn-back" style="margin-top: 16px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Ruangan
        </a>
    </div>
</div>
@endif

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'alwaysShowNavOnTouchDevices': true
    })
</script>
@endsection
