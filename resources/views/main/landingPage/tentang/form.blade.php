@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Tentang' : 'Edit Tentang')

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
                    <i class="fas fa-plus me-2"></i>Tambah Data Tentang
                @else
                    <i class="fas fa-edit me-2"></i>Edit Data Tentang
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.landing.tentang.store') : route('main.landing.tentang.update', $tentang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- Key --}}
                <div class="mb-3">
                    <label for="key" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Key <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('key') is-invalid @enderror" 
                           id="key" 
                           name="key" 
                           value="{{ old('key', $tentang?->key ?? '') }}" 
                           placeholder="Masukkan key (contoh: deskripsi, visi, misi)"
                           style="font-size: 14px; padding: 10px 14px;"
                           {{ $mode === 'edit' ? 'readonly' : '' }}>
                    @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Value --}}
                <div class="mb-4">
                    <label for="value" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Value <span class="text-danger">*</span>
                    </label>
                    @if(($tentang?->key ?? old('key', '')) === 'logo')
                        <input type="file" 
                               class="form-control @error('value_file') is-invalid @enderror" 
                               id="value_file" 
                               name="value_file" 
                               accept="image/*"
                               style="font-size: 14px; padding: 10px 14px;">
                        <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB</small>
                        @if($mode === 'edit' && $tentang?->value)
                            <div class="mt-2">
                                <p style="font-size: 13px; color: #555; margin-bottom: 5px;">Logo Saat Ini:</p>
                                @php
                                    $logoUrl = filter_var($tentang->value, FILTER_VALIDATE_URL) ? $tentang->value : (str_starts_with($tentang->value, 'storage/') ? asset($tentang->value) : asset('storage/' . $tentang->value));
                                @endphp
                                <img src="{{ $logoUrl }}" 
                                     alt="Logo" 
                                     style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd; background: #eee; padding: 5px; object-fit: contain;">
                            </div>
                        @endif
                        @error('value_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    @else
                        <textarea class="form-control @error('value') is-invalid @enderror" 
                                  id="value" 
                                  name="value" 
                                  rows="6"
                                  placeholder="Masukkan nilai/isi"
                                  style="font-size: 14px; padding: 10px 14px; resize: vertical;">{{ old('value', $tentang?->value ?? '') }}</textarea>
                        <small class="form-text text-muted">Maksimal 10000 karakter</small>
                        @error('value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    @endif
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
                    <a href="{{ route('main.landing.tentang.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
