@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Gedung' : 'Edit Gedung')

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
                    <i class="fas fa-plus me-2"></i>Tambah Gedung
                @else
                    <i class="fas fa-edit me-2"></i>Edit Gedung
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.gedung.store') : route('main.gedung.update', $gedung->id_gedung) }}" method="POST">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_gedung" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Nama Gedung <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_gedung') is-invalid @enderror" 
                               id="nama_gedung" 
                               name="nama_gedung" 
                               value="{{ old('nama_gedung', $gedung?->nama_gedung ?? '') }}" 
                               placeholder="Masukkan nama gedung"
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('nama_gedung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="koordinat" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Koordinat Map (Opsional)
                        </label>
                        <input type="text" 
                               class="form-control @error('koordinat') is-invalid @enderror" 
                               id="koordinat" 
                               name="koordinat" 
                               value="{{ old('koordinat', $gedung?->koordinat ?? '') }}" 
                               placeholder="Contoh: -6.200000, 106.816666"
                               style="font-size: 14px; padding: 10px 14px;">
                        @error('koordinat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Keterangan (Opsional)
                    </label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" 
                              name="keterangan" 
                              rows="4"
                              placeholder="Masukkan keterangan gedung"
                              style="font-size: 14px; padding: 10px 14px; resize: vertical;">{{ old('keterangan', $gedung?->keterangan ?? '') }}</textarea>
                    @error('keterangan')
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
                    <a href="{{ route('main.gedung.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
