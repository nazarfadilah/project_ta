@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Buat Peminjaman Sarana' : 'Edit Peminjaman Sarana')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 14px;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0" style="padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                @if($mode === 'create')
                    <i class="fas fa-plus me-2"></i>Buat Peminjaman Sarana
                @else
                    <i class="fas fa-edit me-2"></i>Edit Peminjaman Sarana
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.peminjaman_sarana.store') : route('main.peminjaman_sarana.update', $peminjaman?->id) }}" method="POST">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- Informasi Tanggal Peminjaman --}}
                <div class="card mb-3 border-light">
                    <div class="card-header bg-light">
                        <h6 class="mb-0" style="font-size: 13px;"><i class="fas fa-calendar me-2"></i>Informasi Tanggal Peminjaman</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                                    Tanggal Peminjaman <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ old('tanggal', $peminjaman?->tanggal ?? '') }}" 
                                       style="font-size: 14px; padding: 10px 14px;"
                                       required
                                       onchange="checkAvailability()">
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="durasi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                                    Durasi (jam) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('durasi') is-invalid @enderror" 
                                       id="durasi" 
                                       name="durasi" 
                                       value="{{ old('durasi', $peminjaman?->durasi ?? 24) }}" 
                                       min="1"
                                       max="720"
                                       style="font-size: 14px; padding: 10px 14px;"
                                       required
                                       onchange="checkAvailability()">
                                <small class="form-text text-muted">Dalam satuan jam (1 hari = 24 jam)</small>
                                @error('durasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Sarana --}}
                <div class="card mb-3 border-light">
                    <div class="card-header bg-light">
                        <h6 class="mb-0" style="font-size: 13px;"><i class="fas fa-tools me-2"></i>Detail Sarana</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sarana_id" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                                    Pilih Sarana <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('sarana_id') is-invalid @enderror" 
                                        id="sarana_id" 
                                        name="sarana_id" 
                                        style="font-size: 14px; padding: 10px 14px;"
                                        required
                                        onchange="checkAvailability()">
                                    <option value="">-- Pilih Sarana --</option>
                                    @foreach($saranas as $sarana)
                                    <option value="{{ $sarana->id }}" 
                                            @if(old('sarana_id', $peminjaman?->sarana_id) == $sarana->id) selected @endif
                                            data-stok="{{ $sarana->stok }}">
                                        {{ $sarana->nama }} (Stok: {{ $sarana->stok }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('sarana_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                                    Jumlah Yang Dipinjam <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('jumlah') is-invalid @enderror" 
                                       id="jumlah" 
                                       name="jumlah" 
                                       value="{{ old('jumlah', $peminjaman?->detailSaranas()->sum('jumlah') ?? 1) }}" 
                                       min="1"
                                       style="font-size: 14px; padding: 10px 14px;"
                                       required
                                       onchange="checkAvailability()">
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Stock Availability Info --}}
                        <div id="availability-info" style="display: none;">
                            <div class="alert alert-info" style="margin-bottom: 0;">
                                <small>
                                    <strong>Informasi Stok:</strong><br>
                                    <i class="fas fa-box me-2"></i>Total Stok: <span id="stok-total">-</span> unit<br>
                                    <i class="fas fa-cart-arrow-down me-2"></i>Sudah Dipinjam: <span id="stok-borrowed">0</span> unit<br>
                                    <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>Stok Tersedia: <span id="stok-available" style="color: #28a745; font-weight: bold;">-</span> unit
                                </small>
                            </div>
                            <div id="availability-warning" style="display: none; margin-top: 10px;">
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span id="warning-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Lainnya --}}
                <div class="card mb-3 border-light">
                    <div class="card-header bg-light">
                        <h6 class="mb-0" style="font-size: 13px;"><i class="fas fa-info-circle me-2"></i>Informasi Lainnya</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                                Keterangan
                            </label>
                            <textarea class="form-control" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3"
                                      placeholder="Masukkan keterangan peminjaman"
                                      style="font-size: 14px; padding: 10px 14px;">{{ old('keterangan', $peminjaman?->keterangan ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-save me-1"></i>
                        @if($mode === 'create')
                            Buat Peminjaman
                        @else
                            Update Peminjaman
                        @endif
                    </button>
                    <a href="{{ route('main.peminjaman_sarana.index') }}" class="btn btn-secondary" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function checkAvailability() {
    const saranaId = document.getElementById('sarana_id').value;
    const tanggal = document.getElementById('tanggal').value;
    const durasi = document.getElementById('durasi').value;
    const jumlah = document.getElementById('jumlah').value;

    if (!saranaId || !tanggal || !durasi || !jumlah) {
        document.getElementById('availability-info').style.display = 'none';
        return;
    }

    // Calculate end date based on duration
    const startDate = new Date(tanggal);
    const durationDays = Math.ceil(durasi / 24);
    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + (durationDays - 1));

    const startDateStr = tanggal;
    const endDateStr = endDate.toISOString().split('T')[0];

    // Call API to check availability
    fetch(`/sarana/availability/check?sarana_id=${saranaId}&start_date=${startDateStr}&end_date=${endDateStr}&jumlah=${jumlah}`)
        .then(response => response.json())
        .then(data => {
            const infoDiv = document.getElementById('availability-info');
            const warningDiv = document.getElementById('availability-warning');

            document.getElementById('stok-total').textContent = data.stok.total;
            document.getElementById('stok-borrowed').textContent = data.stok.borrowed;
            document.getElementById('stok-available').textContent = data.stok.available;

            const jumlahInput = document.getElementById('jumlah');
            jumlahInput.max = data.stok.available;

            infoDiv.style.display = 'block';

            if (data.can_borrow) {
                warningDiv.style.display = 'none';
            } else {
                warningDiv.style.display = 'block';
                document.getElementById('warning-message').innerHTML = 
                    `<strong>Stok tidak cukup!</strong> ${data.message}`;
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            document.getElementById('availability-info').style.display = 'none';
        });
}

// Check availability on page load if edit mode
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('sarana_id').value) {
        checkAvailability();
    }

    const jumlahInput = document.getElementById('jumlah');
    jumlahInput.addEventListener('change', function() {
        const max = parseInt(this.max || 0);
        const val = parseInt(this.value || 0);
        if (max > 0 && val > max) {
            alert(`Peringatan: Jumlah sewa melebihi stok yang tersedia (${max} unit)!`);
            this.value = max;
            checkAvailability();
        }
    });
});
</script>
@endpush
