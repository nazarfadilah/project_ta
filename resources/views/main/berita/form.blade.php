@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Berita' : 'Edit Berita')

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
                    <i class="fas fa-plus me-2"></i>Tambah Berita
                @else
                    <i class="fas fa-edit me-2"></i>Edit Berita
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.berita.store') : route('main.berita.update', $berita->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="confirm-submit"
                  data-confirm-title="{{ $mode === 'create' ? 'Tambah Berita' : 'Simpan Perubahan' }}"
                  data-confirm-text="Apakah Anda yakin ingin {{ $mode === 'create' ? 'menambahkan berita baru ini' : 'menyimpan perubahan pada berita ini' }}?"
                  data-confirm-button="{{ $mode === 'create' ? 'Ya, Tambahkan' : 'Ya, Simpan' }}">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Judul <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" 
                           name="judul" 
                           value="{{ old('judul', $berita?->judul ?? '') }}" 
                           placeholder="Masukkan judul berita"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Isi Berita --}}
                <div class="mb-3">
                    <label for="isi" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Isi Berita <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('isi') is-invalid @enderror" 
                              id="isi" 
                              name="isi" 
                              rows="8"
                              placeholder="Masukkan isi berita"
                              style="font-size: 14px; padding: 10px 14px; resize: vertical;">{{ old('isi', $berita?->isi ?? '') }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Publikasi --}}
                <div class="mb-3">
                    <label for="tanggal_publish" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Tanggal Publikasi <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('tanggal_publish') is-invalid @enderror" 
                           id="tanggal_publish" 
                           name="tanggal_publish" 
                           value="{{ old('tanggal_publish', $berita?->tanggal_publish?->format('Y-m-d') ?? '') }}"
                           style="font-size: 14px; padding: 10px 14px;">
                    @error('tanggal_publish')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="mb-4">
                    <label for="gambar" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Gambar 
                        @if($mode === 'create')
                            <span class="text-danger">*</span>
                        @else
                            <span class="text-muted">(Kosongkan jika tidak ingin mengubah)</span>
                        @endif
                    </label>
                    
                    {{-- Preview Gambar Lama (Edit Mode) --}}
                    @if($mode === 'edit' && $berita?->gambar)
                        <div class="mb-3">
                            <div style="max-width: 300px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                <img src="{{ asset($berita->gambar) }}" alt="Gambar Lama" style="width: 100%; height: auto; display: block;">
                            </div>
                            <small class="form-text text-muted d-block mt-2">Gambar saat ini</small>
                        </div>
                    @endif

                    <input type="file" 
                           class="form-control @error('gambar') is-invalid @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*"
                           style="font-size: 14px; padding: 10px 14px;"
                           {{ $mode === 'create' ? 'required' : '' }}>
                    <small class="form-text text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    {{-- Preview Gambar Baru --}}
                    <div id="imagePreview" style="margin-top: 15px;"></div>
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
                    <a href="{{ route('main.berita.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview gambar sebelum upload
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = `
                    <div style="max-width: 300px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                        <img src="${event.target.result}" alt="Preview" style="width: 100%; height: auto; display: block;">
                    </div>
                    <small class="form-text text-muted d-block mt-2">Preview gambar baru</small>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });
</script>
@endpush
@endsection
