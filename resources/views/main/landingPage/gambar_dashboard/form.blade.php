@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Gambar Landing' : 'Edit Gambar Landing')

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
                    <i class="fas fa-plus me-2"></i>Tambah Gambar Landing
                @else
                    <i class="fas fa-edit me-2"></i>Edit Gambar Landing
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.landing.gambar.store') : route('main.landing.gambar.update', $gambarLanding->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- Posisi --}}
                <div class="mb-3">
                    <label for="posisi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Posisi <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           class="form-control @error('posisi') is-invalid @enderror" 
                           id="posisi" 
                           name="posisi" 
                           value="{{ old('posisi', $gambarLanding?->posisi ?? '') }}" 
                           placeholder="Masukkan urutan posisi gambar"
                           min="1"
                           style="font-size: 14px; padding: 10px 14px;">
                    <small class="form-text text-muted">Gunakan angka untuk mengurutkan gambar. Contoh: 1, 2, 3, dst</small>
                    @error('posisi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Path/Gambar --}}
                <div class="mb-4">
                    <label for="path" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Gambar @if($mode === 'create')<span class="text-danger">*</span>@endif
                    </label>
                    <input type="file" 
                           class="form-control @error('path') is-invalid @enderror" 
                           id="path" 
                           name="path" 
                           accept="image/*"
                           style="font-size: 14px; padding: 10px 14px;">
                    <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                    @if($mode === 'edit' && $gambarLanding?->path)
                        <div class="mt-2">
                            <p style="font-size: 13px; color: #555;">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $gambarLanding->path) }}" 
                                 alt="Gambar Landing" 
                                 style="max-width: 300px; max-height: 200px; border-radius: 4px;">
                        </div>
                    @endif
                    @error('path')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
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
                    <a href="{{ route('main.landing.gambar.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
