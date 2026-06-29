@extends('users.layout.app')

@section('title', $ruangan->nama_ruangan ?? 'Detail Ruangan')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
@endsection

@section('content')
@if($ruangan)
@php
    $tanggal = request()->query('tanggal');
@endphp
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">
    
    <!-- Top Action Buttons -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        @if($tanggal)
            <a href="{{ route('users.main.ruangan.ketersediaan', ['tanggal' => $tanggal]) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Cek Ketersediaan
            </a>
        @else
            <a href="{{ route('users.main.ruangan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Ruangan
            </a>
        @endif
        
        <form action="{{ route('users.main.reservasi.create') }}" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" name="ruangan_id" value="{{ $ruangan->id_ruangan }}">
            <input type="hidden" name="nama_ruangan" value="{{ $ruangan->nama_ruangan }}">
            <input type="hidden" name="tipe_ruangan" value="{{ $ruangan->tipe_ruangan }}">
            <input type="hidden" name="kapasitas" value="{{ $ruangan->kapasitas }}">
            <input type="hidden" name="gedung_id" value="{{ $ruangan->gedung_id }}">
            @if($tanggal)
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            @endif
            <button type="submit" class="btn btn-success fw-bold px-4" id="btnReservasi">
                <i class="fas fa-calendar-check me-1"></i> Reservasi Ruangan
            </button>
        </form>
    </div>

    <!-- Informasi Detail Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-info-circle me-2"></i>Informasi Detail Ruangan
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Nama Ruangan</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $ruangan->nama_ruangan }}</div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Jenis Ruangan</label>
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
                    <div class="value">
                        <span class="badge {{ $badgeColor }} px-2 py-1">{{ $tipoLabel }}</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Gedung</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">{{ $ruangan->gedung->nama_gedung ?? '-' }}</div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Lantai</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        @if($ruangan->lantai)
                            Lantai {{ $ruangan->lantai }}
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kapasitas</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
                        <i class="fas fa-users me-1 text-muted"></i> {{ $ruangan->kapasitas }} orang
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Kebijakan Gender</label>
                    <div class="fw-semibold text-dark value" style="font-size: 14px;">
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
            <div class="keterangan-section p-3 bg-light rounded border-start border-warning border-4 mt-4">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1" style="font-size: 11px;">Keterangan & Deskripsi</label>
                <div class="text-dark value" style="font-size: 14px; line-height: 1.6;">{{ $ruangan->keterangan }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Gallery Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-images me-2"></i>Galeri Foto Ruangan
            </h6>
        </div>
        <div class="card-body p-4">
            @if($ruangan->mediaFiles && $ruangan->mediaFiles->count() > 0)
            <div class="row g-3">
                @foreach($ruangan->mediaFiles as $media)
                <div class="col-6 col-sm-4 col-md-3">
                    <a href="{{ asset($media->path) }}" data-lightbox="ruangan-gallery" class="d-block rounded overflow-hidden border" style="aspect-ratio: 1;">
                        <img src="{{ asset($media->path) }}" alt="Foto {{ $ruangan->nama_ruangan }}" class="w-100 h-100 object-fit-cover" onerror="this.src='https://placehold.co/300?text=Foto+Ruangan'; this.onerror=null;">
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-image fa-3x mb-3" style="opacity: 0.5;"></i>
                <h5>Tidak Ada Foto</h5>
                <p class="mb-0">Belum ada foto yang ditambahkan untuk ruangan ini.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-exclamation-circle me-2"></i>Informasi Penting
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 mb-3" style="font-size: 14px;">
                <i class="fas fa-calendar"></i>
                <span>Untuk melihat ketersediaan ruangan, silakan lakukan proses reservasi</span>
            </div>
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 mb-0" style="font-size: 14px;">
                <i class="fas fa-phone"></i>
                <span>Jika ada pertanyaan, hubungi petugas melalui halaman kontak kami</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h5>Ruangan Tidak Ditemukan</h5>
            <p>Ruangan yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('users.main.ruangan.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Ruangan
            </a>
        </div>
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
