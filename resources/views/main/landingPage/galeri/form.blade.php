@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Galeri' : 'Edit Galeri')

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
                    <i class="fas fa-plus me-2"></i>Tambah Data Galeri
                @else
                    <i class="fas fa-edit me-2"></i>Edit Data Galeri
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.landing.galeri.store') : route('main.landing.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="kategori" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Kategori <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('kategori') is-invalid @enderror" 
                           id="kategori" 
                           name="kategori" 
                           value="{{ old('kategori', $galeri?->kategori ?? '') }}" 
                           placeholder="Masukkan kategori galeri"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Judul <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" 
                           name="judul" 
                           value="{{ old('judul', $galeri?->judul ?? '') }}" 
                           placeholder="Masukkan judul galeri"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Isi --}}
                <div class="mb-3">
                    <label for="isi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Isi / Deskripsi
                    </label>
                    <textarea class="form-control @error('isi') is-invalid @enderror" 
                              id="isi" 
                              name="isi" 
                              rows="4"
                              placeholder="Masukkan deskripsi galeri"
                              style="font-size: 14px; padding: 10px 14px; resize: vertical;">{{ old('isi', $galeri?->isi ?? '') }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="mb-4">
                    <label for="gambar" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Gambar @if($mode === 'create')<span class="text-danger">*</span>@endif
                    </label>
                    <input type="file" 
                           class="form-control @error('gambar') is-invalid @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*"
                           style="font-size: 14px; padding: 10px 14px;">
                    <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                    @if($mode === 'edit' && $galeri?->gambar)
                        <div class="mt-2">
                            <p style="font-size: 13px; color: #555;">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $galeri->gambar) }}" 
                                 alt="{{ $galeri->judul }}" 
                                 style="max-width: 200px; max-height: 150px; border-radius: 4px;">
                        </div>
                    @endif
                    @error('gambar')
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
                    <a href="{{ route('main.landing.galeri.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
