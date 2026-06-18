@extends('main.layout.app')

@section('title', $isDetail ? 'Detail Tamu' : ($guest->exists ? 'Edit Tamu' : 'Tambah Tamu'))

@section('content')
<div class="container-fluid" style="padding-left: 40px; padding-right: 40px; margin-top: 20px;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold" style="color: #333;">
            <i class="fas {{ $isDetail ? 'fa-eye' : 'fa-edit' }} me-2" style="color: #C9A961;"></i>
            {{ $isDetail ? 'Detail Tamu' : ($guest->exists ? 'Edit Tamu' : 'Tambah Tamu') }}
        </h5>
        <div>
            <a href="{{ route('main.tamu.index') }}" class="btn btn-secondary btn-sm" style="padding: 6px 15px; font-size: 13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            @if($isDetail)
            <a href="{{ route('main.tamu.edit', $guest->id) }}" class="btn btn-warning btn-sm" style="padding: 6px 15px; font-size: 13px; color: #fff;">
                <i class="fas fa-edit me-1"></i> Edit Tamu
            </a>
            @endif
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="font-size: 13px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form / Detail --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body" style="padding: 30px;">
            <form action="{{ $guest->exists ? route('main.tamu.update', $guest->id) : route('main.tamu.store') }}" method="POST">
                @csrf
                @if($guest->exists)
                    @method('PUT')
                @endif

                @if($isDetail)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info d-flex align-items-center" role="alert" style="background-color: #f8f9fa; border-left: 4px solid #C9A961; border-radius: 4px; border-top: none; border-right: none; border-bottom: none; color: #333;">
                            <i class="fas fa-info-circle me-3" style="font-size: 20px; color: #C9A961;"></i>
                            <div>
                                <strong style="display: block; font-size: 14px;">Informasi Peminjaman</strong>
                                <span style="font-size: 13px;">Tamu ini telah melakukan transaksi peminjaman sebanyak <strong>{{ $guest->peminjaman_transaksis_count }} kali</strong>.</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nik" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $guest->nik) }}" required {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $guest->name) }}" required {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;">
                            <option value="">-- Pilih --</option>
                            <option value="MALE" {{ old('gender', $guest->gender) == 'MALE' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="FEMALE" {{ old('gender', $guest->gender) == 'FEMALE' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Nomor Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $guest->phone) }}" {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;" placeholder="Contoh: 08123456789" maxlength="15" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bloodType" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Golongan Darah</label>
                        <input type="text" class="form-control" id="bloodType" name="bloodType" value="{{ old('bloodType', $guest->bloodType) }}" {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;" placeholder="Contoh: A, B, O, AB">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;">{{ old('address', $guest->address) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="notes" class="form-label" style="font-size: 13px; font-weight: 600; color: #555;">Catatan Tambahan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" {{ $isDetail ? 'disabled' : '' }} style="font-size: 14px;">{{ old('notes', $guest->notes) }}</textarea>
                    </div>
                </div>

                @if(!$isDetail)
                <div class="d-flex justify-content-end border-top pt-3 mt-2">
                    <button type="submit" class="btn" style="background-color: #C9A961; color: white; padding: 8px 20px; font-size: 14px; font-weight: 500;">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
