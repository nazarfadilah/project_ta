@extends('main.layout.app')

@section('title', 'Detail Ruangan - ' . ($ruangan->nama_ruangan ?? 'Ruangan'))

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .star-color {
        color: #ffc107;
    }
    .detail-item label {
        font-size: 11px;
    }
    .detail-item .value {
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">
    
    <!-- Top Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('main.ruangan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Ruangan
        </a>
        @if(Auth::user()->roleId != 2)
        <a href="{{ route('main.ruangan.edit', $ruangan->id_ruangan) }}" class="btn btn-warning btn-sm text-white px-3">
            <i class="fas fa-edit me-1"></i> Edit Ruangan
        </a>
        @endif
    </div>

    <!-- Informasi Detail Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-info-circle me-2"></i>Informasi Detail Ruangan
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Nama Ruangan & Rating</label>
                    <div class="fw-semibold text-dark value">
                        {{ $ruangan->nama_ruangan }}
                        <div class="mt-1" style="font-size: 12px;">
                            @if($averageRating > 0)
                                <x-star-rating :rating="$averageRating" fontSize="12px" />
                                <span class="text-muted ms-1" style="font-size: 11px;">({{ number_format($averageRating, 1) }}/5 dari {{ $totalReviewsCount }} ulasan)</span>
                            @else
                                <span class="text-muted" style="font-size: 11px;">Belum ada ulasan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Jenis Ruangan</label>
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
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Lantai</label>
                    <div class="fw-semibold text-dark value">
                        {{ $ruangan->lantai ? 'Lantai ' . $ruangan->lantai : '-' }}
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Kapasitas</label>
                    <div class="fw-semibold text-dark value">
                        <i class="fas fa-users me-1 text-muted"></i> {{ $ruangan->kapasitas }} Orang
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 detail-item">
                    <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Kebijakan Gender</label>
                    <div class="fw-semibold text-dark value">
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
                <label class="text-muted small fw-semibold text-uppercase d-block mb-1">Keterangan & Deskripsi</label>
                <div class="text-dark value" style="line-height: 1.6;">{{ $ruangan->keterangan }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Paket Ruangan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-box-open me-2"></i>Paket Harga Sewa Ruangan
            </h6>
        </div>
        <div class="card-body p-4">
            @if($ruangan->paketRuangans && $ruangan->paketRuangans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 14px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Paket</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangan->paketRuangans as $idx => $paket)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $paket->nama_paket }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $paket->durasi ? $paket->durasi . ' Jam' : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $statusBadge = match($paket->status) {
                                        'ACTIVE' => 'bg-success text-white',
                                        'MAINTENANCE' => 'bg-warning text-dark',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $statusBadge }} px-2 py-1" style="font-size: 11px;">{{ $paket->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-3 text-muted">
                <i class="fas fa-exclamation-triangle fa-2x mb-2" style="opacity: 0.5;"></i>
                <p class="mb-0">Belum ada paket sewa yang dibuat untuk ruangan ini.</p>
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

    <!-- Ulasan & Review Tamu -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-star me-2"></i>Ulasan & Penilaian Tamu
            </h6>
        </div>
        <div class="card-body p-4">
            @if(isset($reviews) && $reviews->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($reviews as $review)
                        @php
                            $guestName = $review->transaksi->guest->name ?? 'Tamu Anonim';
                            $guestPhoto = $review->transaksi->guest->user->profile_photo ?? null;
                        @endphp
                        <div class="pb-3 border-bottom last-border-none">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($guestPhoto)
                                    <img src="{{ asset($guestPhoto) }}" alt="{{ $guestName }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                        {{ strtoupper(substr($guestName, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="fw-bold text-dark" style="font-size: 13.5px;">{{ $guestName }}</span>
                                    <span class="text-muted ms-2" style="font-size: 11px;">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <x-star-rating :rating="$review->rating" fontSize="12px" />
                            </div>

                            @if($review->komentar)
                                <p class="text-secondary mb-2" style="font-size: 13px; line-height: 1.5; font-style: italic;">
                                    "{{ $review->komentar }}"
                                </p>
                            @endif

                            @if($review->foto_ulasan)
                                <div class="mt-2" style="max-width: 200px; aspect-ratio: 1.5; border-radius: 6px; overflow: hidden; border: 1px solid #ddd;">
                                    <a href="{{ asset($review->foto_ulasan) }}" data-lightbox="review-gallery-{{ $review->id }}">
                                        <img src="{{ asset($review->foto_ulasan) }}" alt="Foto ulasan" class="w-100 h-100 object-fit-cover">
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if(isset($totalReviewsCount) && $totalReviewsCount > 3)
                    <div class="text-center mt-4">
                        <a href="{{ route('main.ruangan.ulasan', $ruangan->id_ruangan) }}" class="btn btn-outline-primary btn-sm px-4 py-2 fw-semibold" style="font-size: 13px; border-radius: 5px;">
                            <i class="fas fa-comments me-1"></i> Baca Semua Ulasan ({{ $totalReviewsCount }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-4 text-muted">
                    <i class="far fa-star fa-3x mb-3" style="opacity: 0.5;"></i>
                    <h5>Belum Ada Ulasan</h5>
                    <p class="mb-0">Belum ada ulasan yang diberikan untuk ruangan ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection
