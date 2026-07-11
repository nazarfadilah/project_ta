@extends('users.layout.app')

@section('title', 'Beri Ulasan Ruangan')

@section('css')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 8px;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 32px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }
    .image-preview-container {
        width: 100%;
        max-width: 300px;
        aspect-ratio: 1.5;
        border: 2px dashed #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #fafafa;
        position: relative;
        cursor: pointer;
    }
    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }
    .image-preview-placeholder {
        text-align: center;
        color: #aaa;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="container py-4" style="max-width: 650px;">
    
    <div class="mb-4">
        <a href="{{ route('users.main.reservasi.index') }}" class="text-decoration-none text-muted" style="font-size: 13px;">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat Reservasi
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header border-bottom-0 text-white p-4" style="background-color: #C9A961;">
            <h5 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif;">Beri Ulasan Ruangan</h5>
            <p class="mb-0" style="font-size: 12.5px; opacity: 0.9;">Bantu kami meningkatkan kualitas layanan dengan memberikan ulasan jujur Anda.</p>
        </div>
        <div class="card-body p-4">
            {{-- Room Summary Card --}}
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background-color: #fcf8f0; border: 1px solid rgba(201, 169, 97, 0.15);">
                <i class="fas fa-door-open fa-2x text-warning" style="opacity: 0.8;"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 14.5px;">{{ $ruangan->nama_ruangan }}</h6>
                    <small class="text-muted" style="font-size: 12px;">Kode Sewa: {{ $transaksi->kodePeminjaman }}</small>
                </div>
            </div>

            <form action="{{ route('users.review.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">

                {{-- Rating input (1-5) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-2" style="font-size: 14px;">Rating Ruangan <span class="text-danger">*</span></label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required />
                        <label for="star5" title="Sangat Bagus"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" />
                        <label for="star4" title="Bagus"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" />
                        <label for="star3" title="Cukup"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" />
                        <label for="star2" title="Buruk"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" />
                        <label for="star1" title="Sangat Buruk"><i class="fas fa-star"></i></label>
                    </div>
                    @error('rating')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Komentar input --}}
                <div class="mb-4">
                    <label for="komentar" class="form-label fw-bold text-dark" style="font-size: 14px;">Teks Ulasan / Komentar <span class="text-muted fw-normal">(Opsional)</span></label>
                    <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Bagikan pengalaman Anda menggunakan ruangan ini (kebersihan, fasilitas, kenyamanan)..." style="font-size: 13.5px; border-radius: 8px;"></textarea>
                    @error('komentar')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto Ulasan input --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-2" style="font-size: 14px;">Foto Kondisi Ruangan <span class="text-muted fw-normal">(Opsional, Maks 2MB)</span></label>
                    
                    <div class="image-preview-container" id="uploadTrigger">
                        <div class="image-preview-placeholder" id="placeholder">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                            <p class="mb-0 fw-semibold">Pilih Foto Gambar</p>
                            <small class="text-muted">Klik untuk mencari file foto</small>
                        </div>
                        <img src="" id="imgPreview" alt="Pratinjau Foto" />
                    </div>
                    <input type="file" id="foto_ulasan" name="foto_ulasan" class="d-none" accept="image/*" />
                    
                    @error('foto_ulasan')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-warning py-2 fw-bold text-dark rounded-3" style="background-color: #C9A961; border: none; font-size: 14.5px;">
                        Kirim Ulasan & Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadTrigger = document.getElementById('uploadTrigger');
        const fileInput = document.getElementById('foto_ulasan');
        const imgPreview = document.getElementById('imgPreview');
        const placeholder = document.getElementById('placeholder');

        if (uploadTrigger && fileInput) {
            uploadTrigger.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = 'block';
                        placeholder.style.display = 'none';
                        uploadTrigger.style.border = '1px solid #C9A961';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imgPreview.style.display = 'none';
                    placeholder.style.display = 'block';
                    uploadTrigger.style.border = '2px dashed #ddd';
                }
            });
        }
    });
</script>
@endsection
