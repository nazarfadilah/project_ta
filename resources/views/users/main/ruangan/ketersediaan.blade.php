@extends('users.layout.app')

@section('title', 'Cek Ketersediaan Ruangan')

@section('css')
<style>
    .ketersediaan-header {
        background: linear-gradient(135deg, #C9A961 0%, #A48135 100%);
        border-radius: 16px;
        color: white;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(201, 169, 97, 0.15);
    }
    .filter-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
    }
    .room-card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .room-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: rgba(201, 169, 97, 0.3);
    }
    .room-img-container {
        height: 200px;
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .room-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .room-card:hover .room-img {
        transform: scale(1.08);
    }
    .room-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .room-body {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .room-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .room-meta {
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .room-meta i {
        color: #C9A961;
        width: 16px;
        text-align: center;
    }
    .btn-detail {
        background-color: #C9A961;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 13px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        width: 100%;
        margin-top: 15px;
    }
    .btn-detail:hover {
        background-color: #A48135;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(201, 169, 97, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px;">

    <!-- Header Banner -->
    <div class="ketersediaan-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif;"><i class="fas fa-calendar-check me-2"></i>Cek Ketersediaan Ruangan</h4>
            <p class="mb-0 text-white-50" style="font-size: 14px;">Periksa ruangan yang kosong pada tanggal penyewaan pilihan Anda berdasarkan kategori tertentu.</p>
        </div>
        <a href="{{ route('users.dashboard') }}" class="btn btn-light btn-sm fw-semibold text-dark px-3 py-2 rounded-3 border-0">
            <i class="fas fa-arrow-left me-1"></i> Dashboard Saya
        </a>
    </div>

    <!-- Filter Form Card -->
    <div class="card filter-card">
        <div class="card-body p-4">
            <form action="{{ route('users.main.ruangan.ketersediaan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="tanggal" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Tanggal Sewa *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="tanggal" id="tanggal" class="form-control bg-light border-start-0 ps-0" value="{{ $tanggal }}" required>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <label for="kategori" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Kategori Ruangan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-layer-group"></i></span>
                        <select name="kategori" id="kategori" class="form-select bg-light border-start-0 ps-0">
                            <option value="ALL" {{ $kategori === 'ALL' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="KAMAR" {{ $kategori === 'KAMAR' ? 'selected' : '' }}>Kamar Penginapan</option>
                            <option value="AULA" {{ $kategori === 'AULA' ? 'selected' : '' }}>Aula Pertemuan</option>
                            <option value="RUANG_MEETING" {{ $kategori === 'RUANG_MEETING' ? 'selected' : '' }}>Ruang Rapat / Meeting</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-success fw-bold w-100 py-2 rounded-3" style="min-height: 40px;" id="btnCariKetersediaan">
                        <i class="fas fa-search me-1"></i> Cari Ruangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Available Rooms Result -->
    <h5 class="fw-bold text-dark mb-4" style="font-family: 'Outfit', sans-serif;">
        <i class="fas fa-door-open text-primary me-2"></i>Ruangan yang Tersedia pada {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
    </h5>

    @if($ruangans->count() > 0)
    <div class="row g-4">
        @foreach($ruangans as $ruangan)
            <div class="col-xl-4 col-md-6 col-sm-12">
                <div class="room-card">
                    <!-- Image Area -->
                    <div class="room-img-container">
                        @php
                            $tipe = $ruangan->tipe_ruangan;
                            $badgeClass = match($tipe) {
                                'KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM' => 'bg-primary text-white',
                                'AULA' => 'bg-warning text-dark',
                                'RUANG_MEETING' => 'bg-secondary text-white',
                                default => 'bg-success text-white'
                            };
                            $badgeLabel = str_replace('_', ' ', $tipe);
                            $firstPhoto = $ruangan->mediaFiles->first()?->path;
                        @endphp
                        <span class="room-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        
                        @if($firstPhoto)
                            <img src="{{ asset($firstPhoto) }}" class="room-img" alt="{{ $ruangan->nama_ruangan }}" onerror="this.src='https://placehold.co/400x300?text=Foto+Ruangan'; this.onerror=null;">
                        @else
                            <img src="https://placehold.co/400x300?text=Foto+Ruangan" class="room-img" alt="Placeholder">
                        @endif
                    </div>

                    <!-- Room Details Body -->
                    <div class="room-body">
                        <div>
                            <h5 class="room-title">{{ $ruangan->nama_ruangan }}</h5>
                            {{-- Disabled Gedung Detail Item
                            <div class="room-meta">
                                <i class="fas fa-building"></i>
                                <span>Gedung: <strong>{{ $ruangan->gedung->nama_gedung ?? '-' }}</strong></span>
                            </div>
                            --}}
                            <div class="room-meta">
                                <i class="fas fa-users"></i>
                                <span>Kapasitas: {{ $ruangan->kapasitas }} Orang</span>
                            </div>
                            <div class="room-meta">
                                <i class="fas fa-align-left"></i>
                                <span>Lantai: {{ $ruangan->lantai ?? '1' }}</span>
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('users.main.ruangan.show', [str_replace(' ', '-', strtolower($ruangan->nama_ruangan)), 'tanggal' => $tanggal]) }}" class="btn btn-detail" id="btnDetail_{{ $ruangan->id_ruangan }}">
                                <i class="fas fa-circle-info"></i> Lihat Detail & Reservasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-3 py-5 my-4">
        <div class="card-body text-center text-muted">
            <i class="far fa-calendar-times fa-4x text-danger mb-3" style="opacity: 0.7;"></i>
            <h5 class="fw-bold text-dark">Tidak Ada Ruangan yang Tersedia</h5>
            <p class="mb-0 small" style="max-width: 450px; margin: 0 auto;">Semua ruangan tipe ini telah dipesan/terbooking pada tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}. Silakan pilih tanggal peminjaman lain atau kategori ruangan lainnya.</p>
        </div>
    </div>
    @endif

</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date of search input to today to prevent past dates checking
        const dateInput = document.getElementById('tanggal');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
    });
</script>
@endsection
