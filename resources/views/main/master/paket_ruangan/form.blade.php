@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Paket Ruangan' : 'Edit Paket Ruangan')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="font-size: 14px;">
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
                    <i class="fas fa-plus me-2"></i>Tambah Paket Ruangan
                @else
                    <i class="fas fa-edit me-2"></i>Edit Paket Ruangan
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.paket_ruangan.store') : route('main.paket_ruangan.update', $paketRuangan->id) }}" method="POST">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    {{-- Ruangan --}}
                    <div class="col-md-6 mb-3">
                        <label for="ruangan_id" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Pilih Ruangan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select border-grey @error('ruangan_id') is-invalid @enderror" 
                                id="ruangan_id" 
                                name="ruangan_id" 
                                style="font-size: 14px; padding: 10px 14px;" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}" {{ old('ruangan_id', $paketRuangan?->ruangan_id) == $ruangan->id_ruangan ? 'selected' : '' }}>
                                    {{ $ruangan->nama_ruangan }} (Gedung: {{ $ruangan->gedung->nama_gedung ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Paket --}}
                    <div class="col-md-6 mb-3">
                        <label for="nama_paket" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Nama Paket <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control border-grey @error('nama_paket') is-invalid @enderror" 
                               id="nama_paket" 
                               name="nama_paket" 
                               value="{{ old('nama_paket', $paketRuangan?->nama_paket ?? '') }}" 
                               placeholder="Contoh: Paket Harian, Paket Jam-Jaman"
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('nama_paket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Durasi --}}
                    <div class="col-md-4 mb-3">
                        <label for="durasi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Durasi Sewa (Jam - Opsional)
                        </label>
                        <input type="number" 
                               class="form-control border-grey @error('durasi') is-invalid @enderror" 
                               id="durasi" 
                               name="durasi" 
                               value="{{ old('durasi', $paketRuangan?->durasi ?? '') }}" 
                               placeholder="Contoh: 8 (Biarkan kosong jika fleksibel)"
                               style="font-size: 14px; padding: 10px 14px;"
                               min="1" max="999" maxlength="3">
                        @error('durasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="col-md-4 mb-3">
                        <label for="harga" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Harga Sewa (IDR) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text border-grey" style="font-size: 14px; background-color: #f8f9fa;">Rp</span>
                            <input type="number" 
                                   class="form-control border-grey @error('harga') is-invalid @enderror" 
                                   id="harga" 
                                   name="harga" 
                                   value="{{ old('harga', $paketRuangan?->harga ?? '') }}" 
                                   placeholder="Contoh: 1500000"
                                   style="font-size: 14px; padding: 10px 14px;" required
                                   min="0" max="99999999.99" maxlength="11" step="0.01">
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select border-grey @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                style="font-size: 14px; padding: 10px 14px;" required>
                            <option value="ACTIVE" {{ old('status', $paketRuangan?->status) === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                            <option value="INACTIVE" {{ old('status', $paketRuangan?->status) === 'INACTIVE' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="MAINTENANCE" {{ old('status', $paketRuangan?->status) === 'MAINTENANCE' ? 'selected' : '' }}>Perbaikan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-sm px-4" style="background-color: #C9A961; color: #fff; font-size: 13px; padding: 8px 20px; border: none;">
                        <i class="fas fa-save me-1"></i> 
                        @if($mode === 'create')
                            Tambah
                        @else
                            Simpan
                        @endif
                    </button>
                    <a href="{{ route('main.paket_ruangan.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-grey {
        border: 1px solid #dcdcdc;
    }
    .border-grey:focus {
        border-color: #C9A961;
        box-shadow: 0 0 0 0.2rem rgba(201, 169, 97, 0.25);
    }
</style>
@endpush
