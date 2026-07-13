@extends('users.layout.app')

@section('title', 'Ulasan Saya')

@section('css')
<style>
    .star-rating-display {
        color: #ffc107;
        font-size: 15px;
    }
    .review-card {
        border: 1px solid #eef2f5;
        background: #fff;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }
    .review-img-preview {
        width: 100px;
        height: 75px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }
    .dark-theme .review-card {
        background: #1e1e1e !important;
        border-color: #2d2d2d !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Ulasan Saya</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Kelola dan perbarui ulasan ruangan yang pernah Anda sewa.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 14px; border-left: 4px solid #28a745 !important;">
            <i class="fas fa-check-circle text-success fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 14px; border-left: 4px solid #dc3545 !important;">
            <i class="fas fa-exclamation-circle text-danger fs-5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($reviews->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 rounded-3">
            <div class="card-body py-5">
                <i class="fas fa-star-half-alt fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">Belum Ada Ulasan</h5>
                <p class="text-secondary mx-auto mb-4" style="max-width: 480px; font-size: 13.5px;">
                    Anda belum menulis ulasan untuk peminjaman ruangan apa pun. Anda dapat memberikan ulasan untuk peminjaman yang berstatus <strong>SELESAI</strong> melalui halaman riwayat reservasi Anda.
                </p>
                <a href="{{ route('users.main.reservasi.index') }}" class="btn fw-semibold shadow-sm text-white" style="background: linear-gradient(135deg, #C9A961 0%, #B89750 100%); border: none; font-size: 14px; border-radius: 8px; padding: 10px 20px;">
                    <i class="fas fa-history me-1"></i> Lihat Riwayat Reservasi Saya
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
                @php
                    $transaksi = $review->transaksi;
                    $ruangan = $transaksi->paketRuangan->ruangan ?? null;
                @endphp
                <div class="col-12">
                    <div class="card review-card border-0 shadow-sm p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                            
                            {{-- Info Ruangan & Ulasan --}}
                            <div class="d-flex gap-3 align-items-start">
                                <div class="p-3 rounded bg-light-subtle border d-none d-sm-block">
                                    <i class="fas fa-door-open fa-2x text-warning" style="opacity: 0.8;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-secondary mb-2" style="font-size: 10px; font-weight: 600;">Kode Sewa: {{ $transaksi->kodePeminjaman ?? '-' }}</span>
                                    <h5 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif; font-size: 16px;">
                                        {{ $ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui' }}
                                    </h5>
                                    <p class="text-muted mb-2" style="font-size: 12px;">
                                        Sewa pada: {{ $transaksi->tanggal ? $transaksi->tanggal->format('d M Y') : '-' }}
                                    </p>
                                    
                                    {{-- Star Rating --}}
                                    <div class="mb-2">
                                        <div class="star-rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="text-secondary small fw-semibold ms-1">({{ $review->rating }}/5)</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Review Comment --}}
                                    <p class="text-dark mb-0" style="font-size: 13.5px; line-height: 1.5; white-space: pre-line;">
                                        {{ $review->komentar ?? 'Tidak ada komentar tertulis.' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Foto Ulasan & Action --}}
                            <div class="d-flex flex-row flex-md-column justify-content-between align-items-end gap-3 ms-md-4">
                                @if($review->foto_ulasan)
                                    <div>
                                        <img src="{{ asset($review->foto_ulasan) }}" alt="Foto Ulasan" class="review-img-preview" data-bs-toggle="modal" data-bs-target="#imgModal{{ $review->id }}" />
                                        
                                        <!-- Image Zoom Modal -->
                                        <div class="modal fade" id="imgModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 bg-transparent">
                                                    <div class="modal-header border-0 p-2 justify-content-end">
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-0 text-center">
                                                        <img src="{{ asset($review->foto_ulasan) }}" alt="Foto Ulasan Full" class="img-fluid rounded-3 shadow" style="max-height: 80vh;" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('users.review.edit', $review->id) }}" class="btn btn-sm btn-outline-warning fw-semibold px-3 py-2 d-flex align-items-center gap-1" style="border-radius: 8px; font-size: 12.5px;">
                                        <i class="fas fa-edit"></i> Edit Ulasan
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
