@extends('main.layout.app')

@section('title', $mode === 'create' ? 'Tambah FAQ' : 'Edit FAQ')

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Card Form --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
            <h6 class="mb-0 fw-semibold" style="font-size: 15px;">
                <i class="fas fa-question-circle me-2"></i>{{ $mode === 'create' ? 'Tambah FAQ Baru' : 'Edit FAQ' }}
            </h6>
        </div>
        <div class="card-body" style="padding: 30px;">
            <form action="{{ $mode === 'create' ? route('main.landing.faq.store') : route('main.landing.faq.update', $faq->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="key2" class="form-label fw-semibold" style="font-size: 14px; color: #333;">
                            Pertanyaan <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="key2" 
                               name="key2" 
                               class="form-control @error('key2') is-invalid @enderror"
                               value="{{ old('key2', $faq?->key2 ?? '') }}"
                               {{ $mode === 'edit' ? 'readonly' : '' }}
                               style="border-radius: 5px; border: 1px solid #ddd; padding: 10px; font-size: 14px;"
                               required>
                        @error('key2')
                            <div class="invalid-feedback" style="display: block; font-size: 13px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="value" class="form-label fw-semibold" style="font-size: 14px; color: #333;">
                            Jawaban <span class="text-danger">*</span>
                        </label>
                        <textarea id="value" 
                                  name="value" 
                                  class="form-control @error('value') is-invalid @enderror"
                                  rows="8"
                                  style="border-radius: 5px; border: 1px solid #ddd; padding: 10px; font-size: 14px;"
                                  required>{{ old('value', $faq?->value ?? '') }}</textarea>
                        @error('value')
                            <div class="invalid-feedback" style="display: block; font-size: 13px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn" style="background-color: #C9A961; color: #fff; padding: 10px 20px; border-radius: 5px; border: none; font-size: 14px; font-weight: 500;">
                                <i class="fas fa-save me-1"></i>{{ $mode === 'create' ? 'Simpan' : 'Update' }}
                            </button>
                            <a href="{{ route('main.landing.faq.index') }}" class="btn btn-secondary" style="padding: 10px 20px; border-radius: 5px; border: none; font-size: 14px; font-weight: 500;">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
