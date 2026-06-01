@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah Ruangan' : 'Edit Ruangan')

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
                    <i class="fas fa-plus me-2"></i>Tambah Ruangan
                @else
                    <i class="fas fa-edit me-2"></i>Edit Ruangan
                @endif
            </h6>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ $mode === 'create' ? route('main.ruangan.store') : route('main.ruangan.update', $ruangan->id_ruangan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    {{-- Gedung --}}
                    <div class="col-md-6 mb-3">
                        <label for="gedung_id" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Gedung <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('gedung_id') is-invalid @enderror" 
                                id="gedung_id" 
                                name="gedung_id" 
                                style="font-size: 14px; padding: 10px 14px;" required>
                            <option value="">Pilih Gedung</option>
                            @foreach($gedungs as $gedung)
                                <option value="{{ $gedung->id_gedung }}" {{ old('gedung_id', $ruangan?->gedung_id) == $gedung->id_gedung ? 'selected' : '' }}>
                                    {{ $gedung->nama_gedung }}
                                </option>
                            @endforeach
                        </select>
                        @error('gedung_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Ruangan --}}
                    <div class="col-md-6 mb-3">
                        <label for="nama_ruangan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Nama Ruangan <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_ruangan') is-invalid @enderror" 
                               id="nama_ruangan" 
                               name="nama_ruangan" 
                               value="{{ old('nama_ruangan', $ruangan?->nama_ruangan ?? '') }}" 
                               placeholder="Masukkan nama ruangan"
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('nama_ruangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Tipe Ruangan --}}
                    <div class="col-md-6 mb-3">
                        <label for="tipe_ruangan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Tipe Ruangan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('tipe_ruangan') is-invalid @enderror" 
                                id="tipe_ruangan" 
                                name="tipe_ruangan" 
                                style="font-size: 14px; padding: 10px 14px;" required>
                            <option value="">Pilih Tipe Ruangan</option>
                            <option value="KAMAR_STANDAR" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'KAMAR_STANDAR' ? 'selected' : '' }}>KAMAR STANDAR</option>
                            <option value="KAMAR_VIP" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'KAMAR_VIP' ? 'selected' : '' }}>KAMAR VIP</option>
                            <option value="KAMAR_PREMIUM" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'KAMAR_PREMIUM' ? 'selected' : '' }}>KAMAR PREMIUM</option>
                            <option value="AULA" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'AULA' ? 'selected' : '' }}>AULA</option>
                            <option value="RUANG_MEETING" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'RUANG_MEETING' ? 'selected' : '' }}>RUANG MEETING</option>
                            <option value="RUANG_LAINNYA" {{ old('tipe_ruangan', $ruangan?->tipe_ruangan) === 'RUANG_LAINNYA' ? 'selected' : '' }}>RUANG LAINNYA</option>
                        </select>
                        @error('tipe_ruangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lantai --}}
                    <div class="col-md-6 mb-3">
                        <label for="lantai" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Lantai (Opsional)
                        </label>
                        <input type="number" 
                               class="form-control @error('lantai') is-invalid @enderror" 
                               id="lantai" 
                               name="lantai" 
                               value="{{ old('lantai', $ruangan?->lantai ?? '') }}" 
                               placeholder="Contoh: 1"
                               style="font-size: 14px; padding: 10px 14px;">
                        @error('lantai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Kapasitas --}}
                    <div class="col-md-6 mb-3">
                        <label for="kapasitas" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Kapasitas (Orang) <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('kapasitas') is-invalid @enderror" 
                               id="kapasitas" 
                               name="kapasitas" 
                               value="{{ old('kapasitas', $ruangan?->kapasitas ?? '1') }}" 
                               style="font-size: 14px; padding: 10px 14px;" required>
                        @error('kapasitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gender Policy --}}
                    <div class="col-md-6 mb-3">
                        <label for="gender_policy" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                            Gender Policy (Opsional)
                        </label>
                        <select class="form-select @error('gender_policy') is-invalid @enderror" 
                                id="gender_policy" 
                                name="gender_policy" 
                                style="font-size: 14px; padding: 10px 14px;">
                            <option value="">Pilih Kebijakan (Bebas)</option>
                            <option value="MALE_ONLY" {{ old('gender_policy', $ruangan?->gender_policy) === 'MALE_ONLY' ? 'selected' : '' }}>Pria Saja</option>
                            <option value="FEMALE_ONLY" {{ old('gender_policy', $ruangan?->gender_policy) === 'FEMALE_ONLY' ? 'selected' : '' }}>Wanita Saja</option>
                            <option value="MIXED" {{ old('gender_policy', $ruangan?->gender_policy) === 'MIXED' ? 'selected' : '' }}>Campur</option>
                        </select>
                        @error('gender_policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Keterangan (Opsional)
                    </label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" 
                              name="keterangan" 
                              rows="3"
                              placeholder="Masukkan keterangan ruangan"
                              style="font-size: 14px; padding: 10px 14px; resize: vertical;">{{ old('keterangan', $ruangan?->keterangan ?? '') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Media Files (Foto Ruangan) --}}
                <div class="mb-4">
                    <label for="media_files" class="form-label fw-semibold" style="font-size: 13px; color: #555;">
                        Foto Ruangan (Bisa pilih lebih dari satu)
                    </label>
                    
                    {{-- Preview Gambar Lama (Edit Mode) --}}
                    @if($mode === 'edit' && $ruangan?->mediaFiles->count() > 0)
                        <div class="mb-3">
                            <label style="font-size: 12px; color: #777; margin-bottom: 5px; display: block;">Foto Saat Ini:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($ruangan->mediaFiles as $media)
                                    <div class="position-relative" style="width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                                        <img src="{{ asset($media->path) }}" alt="Foto Ruangan" style="width: 100%; height: 100%; object-fit: cover;">
                                        {{-- Optional: Tombol hapus foto bisa ditaruh di sini jika ada logic-nya --}}
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">Untuk mengganti, upload foto baru. (Foto lama akan tetap ada jika tidak diatur untuk dihapus di backend)</small>
                        </div>
                    @endif

                    <input type="file" 
                           class="form-control @error('media_files.*') is-invalid @enderror" 
                           id="media_files" 
                           name="media_files[]" 
                           accept="image/*"
                           multiple
                           style="font-size: 14px; padding: 10px 14px;">
                    <small class="form-text text-muted">Format: JPG, PNG, GIF (Max: 2MB/file). Bisa pilih banyak file sekaligus.</small>
                    @error('media_files.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    {{-- Preview Gambar Baru --}}
                    <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
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
                    <a href="{{ route('main.ruangan.index') }}" class="btn btn-sm btn-secondary px-4" style="font-size: 13px; padding: 8px 20px;">
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
    document.getElementById('media_files').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = ''; // Bersihkan preview sebelumnya

        if (this.files) {
            [].forEach.call(this.files, function(file) {
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const imgDiv = document.createElement('div');
                        imgDiv.style = "width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;";
                        imgDiv.innerHTML = `<img src="${event.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
                        previewContainer.appendChild(imgDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection
