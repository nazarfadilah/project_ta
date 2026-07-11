@extends('main.layout.app')

@section('title', 'Semua Ulasan - ' . ($ruangan->nama_ruangan ?? 'Ruangan'))

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>
    .star-color {
        color: #ffc107;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">
    
    <!-- Top Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('main.ruangan.show', $ruangan->id_ruangan) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Ruangan
        </a>
    </div>

    <!-- Header Informasi Rating -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4" style="background-color: #fcfcfc;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold text-dark mb-1">Semua Ulasan: {{ $ruangan->nama_ruangan }}</h5>
                    <p class="text-muted mb-0" style="font-size: 14px;">Daftar lengkap seluruh penilaian dan komentar yang diberikan oleh tamu untuk ruangan ini.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block p-3 bg-white border rounded shadow-sm text-center" style="min-width: 150px;">
                        <h3 class="fw-bold text-dark mb-1">{{ number_format($averageRating, 1) }} <span class="text-muted" style="font-size: 16px;">/ 5</span></h3>
                        <div class="star-color mb-1" style="font-size: 14px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <span class="text-muted small fw-semibold" style="font-size: 11px;">({{ $reviews->total() }} Ulasan)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Ulasan -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-comments me-2"></i>Daftar Lengkap Ulasan Tamu
            </h6>
        </div>
        <div class="card-body p-4">
            @if($reviews->count() > 0)
                <div class="d-flex flex-column gap-4">
                    @foreach($reviews as $review)
                        @php
                            $guestName = $review->transaksi->guest->name ?? 'Tamu Anonim';
                            $guestPhoto = $review->transaksi->guest->user->profile_photo ?? null;
                        @endphp
                        <div class="pb-4 border-bottom last-border-none">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                @if($guestPhoto)
                                    <img src="{{ asset($guestPhoto) }}" alt="{{ $guestName }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                        {{ strtoupper(substr($guestName, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $guestName }}</h6>
                                    <span class="text-muted" style="font-size: 11px;">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-2 star-color" style="font-size: 12px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>

                            @if($review->komentar)
                                <p class="text-secondary mb-3" style="font-size: 13.5px; line-height: 1.6; font-style: italic;">
                                    "{{ $review->komentar }}"
                                </p>
                            @endif

                            @if($review->foto_ulasan)
                                <div class="mt-2" style="max-width: 250px; aspect-ratio: 1.5; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                    <a href="{{ asset($review->foto_ulasan) }}" data-lightbox="review-gallery-all-{{ $review->id }}">
                                        <img src="{{ asset($review->foto_ulasan) }}" alt="Foto ulasan" class="w-100 h-100 object-fit-cover">
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="far fa-star fa-3x mb-3" style="opacity: 0.5;"></i>
                    <h5>Belum Ada Ulasan</h5>
                    <p class="mb-0">Belum ada penilaian yang diberikan untuk ruangan ini.</p>
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
