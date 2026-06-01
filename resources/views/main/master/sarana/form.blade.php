@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Sarana' : 'Edit Sarana')

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
                    <i class="fas fa-plus me-2"></i>Tambah Sarana
                @else
                    <i class="fas fa-edit me-2"></i>Edit Sarana
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.sarana.store') : route('main.sarana.update', $sarana->id) }}" method="POST">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Nama Sarana <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $sarana?->nama ?? '') }}" 
                               placeholder="Masukkan nama sarana"
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kondisi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Kondisi <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('kondisi') is-invalid @enderror" 
                                id="kondisi" 
                                name="kondisi" 
                                style="font-size: 14px; padding: 10px 14px;" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik Sekali" {{ old('kondisi', $sarana?->kondisi) === 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali</option>
                            <option value="Baik" {{ old('kondisi', $sarana?->kondisi) === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Normal" {{ old('kondisi', $sarana?->kondisi) === 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Perlu Perbaikan" {{ old('kondisi', $sarana?->kondisi) === 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        </select>
                        @error('kondisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="tgl_penerimaan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Tanggal Penerimaan <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('tgl_penerimaan') is-invalid @enderror" 
                               id="tgl_penerimaan" 
                               name="tgl_penerimaan" 
                               value="{{ old('tgl_penerimaan', $sarana?->tgl_penerimaan ?? '') }}" 
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('tgl_penerimaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="stok" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Stok <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('stok') is-invalid @enderror" 
                               id="stok" 
                               name="stok" 
                               value="{{ old('stok', $sarana?->stok ?? '') }}" 
                               placeholder="Masukkan stok (contoh: 10)"
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm px-4" style="background-color: #C9A961; color: #fff; font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-save me-1"></i> 
                        @if($mode === 'create')
                            Tambah
                        @else
                            Simpan
                        @endif
                    </button>
                    <a href="{{ route('main.sarana.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
