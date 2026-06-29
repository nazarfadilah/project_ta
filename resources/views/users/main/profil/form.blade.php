@extends('users.layout.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px; margin-top: 20px;">

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="font-size: 14px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 14px;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="font-size: 14px;">
        <div class="fw-bold mb-1">
            <i class="fas fa-exclamation-circle me-2"></i>Gagal memperbarui profil:
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: User Summary Card -->
        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light border border-2 border-warning text-warning fw-bold rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $guest->name ?? $user->username }}</h5>
                    <div class="text-muted small mb-3">@&#64;{{ $user->username }}</div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 12px;">
                        <i class="fas fa-check-double me-1"></i> Terverifikasi Tamu
                    </span>
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded bg-warning bg-opacity-10 text-warning" style="width: 32px; height: 32px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0" style="font-size: 11px;">Alamat Email</h6>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded bg-warning bg-opacity-10 text-warning" style="width: 32px; height: 32px;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0" style="font-size: 11px;">Nomor NIK</h6>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $guest->nik ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-0">
                            <div class="d-flex align-items-center justify-content-center rounded bg-warning bg-opacity-10 text-warning" style="width: 32px; height: 32px;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-0" style="font-size: 11px;">No. Telepon</h6>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $user->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning border-0 shadow-sm mb-4" style="background-color: #fffcf5; border: 1px solid rgba(201,169,97,0.15) !important;">
                <div class="d-flex gap-2">
                    <i class="fas fa-info-circle text-warning fs-5 mt-1"></i>
                    <div style="font-size: 13px; color: #7f6831; line-height: 1.5;">
                        <strong>Catatan Informasi:</strong><br>
                        Pastikan data NIK dan Nama Lengkap Anda sesuai dengan dokumen identitas resmi (KTP/KTM). Keakuratan data membantu admin memverifikasi pengajuan peminjaman ruangan Anda secara cepat dan akurat.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Profile Edit Form -->
        <div class="col-lg-8 col-md-7">
            <form action="{{ route('users.profil.update') }}" 
                  method="POST"
                  class="confirm-submit"
                  data-confirm-title="Simpan Perubahan Profil"
                  data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan data profil Anda?"
                  data-confirm-button="Ya, Simpan">
                @csrf
                @method('PUT')
                
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header" style="background-color: #C9A961; color: #fff; border-radius: 8px 8px 0 0; padding: 14px 20px;">
                        <h6 class="mb-0 fw-semibold" style="font-size: 15px;"><i class="fas fa-user-edit me-2"></i>Edit Data Diri Peminjam</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Section 1: Personal Data -->
                        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #B8953F;"><i class="fas fa-id-badge me-1"></i> Data Diri Peminjam</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nik" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">NIK / Nomor Identitas <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control ps-2 border-start-0" id="nik" name="nik" value="{{ old('nik', $guest->nik ?? '') }}" placeholder="Masukkan 16 digit NIK" maxlength="16" minlength="16" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required style="font-size: 14px; padding: 10px 14px;">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control ps-2 border-start-0" id="name" name="name" value="{{ old('name', $guest->name ?? '') }}" placeholder="Nama sesuai identitas" required style="font-size: 14px; padding: 10px 14px;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_male" value="MALE" {{ old('gender', $guest->gender ?? '') === 'MALE' ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-semibold" for="gender_male" style="font-size: 14px; color: #555;">
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_female" value="FEMALE" {{ old('gender', $guest->gender ?? '') === 'FEMALE' ? 'checked' : '' }} required>
                                        <label class="form-check-label fw-semibold" for="gender_female" style="font-size: 14px; color: #555;">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="bloodType" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Golongan Darah (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-tint"></i></span>
                                    <select class="form-select ps-2 border-start-0" id="bloodType" name="bloodType" style="font-size: 14px; padding: 10px 14px;">
                                        <option value="" {{ empty(old('bloodType', $guest->bloodType ?? '')) ? 'selected' : '' }}>-- Pilih Golongan Darah --</option>
                                        <option value="-" {{ old('bloodType', $guest->bloodType ?? '') === '-' ? 'selected' : '' }}>- (Tidak Tahu)</option>
                                        <option value="A" {{ old('bloodType', $guest->bloodType ?? '') === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('bloodType', $guest->bloodType ?? '') === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('bloodType', $guest->bloodType ?? '') === 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('bloodType', $guest->bloodType ?? '') === 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="phone" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-phone-alt"></i></span>
                                    <input type="text" class="form-control ps-2 border-start-0" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789" maxlength="15" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required style="font-size: 14px; padding: 10px 14px;">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Alamat Lengkap</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap rumah / domisili" style="font-size: 14px; padding: 10px 14px;">{{ old('address', $guest->address ?? '') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Catatan / Keterangan (Opsional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan tambahan seperti nama instansi, jurusan, dll" style="font-size: 14px; padding: 10px 14px;">{{ old('notes', $guest->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Section 2: Security & Password -->
                        <h6 class="fw-bold mb-3 border-bottom pb-2 mt-5" style="color: #B8953F;"><i class="fas fa-shield-alt me-1"></i> Keamanan Akun</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i> Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengubah password masuk Anda saat ini.
                                </small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control ps-2 border-start-0" id="password" name="password" placeholder="Minimal 6 karakter" style="font-size: 14px; padding: 10px 14px;">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-muted small text-uppercase" style="font-size: 11px;">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control ps-2 border-start-0" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" style="font-size: 14px; padding: 10px 14px;">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn text-white px-4 py-2" style="background-color: #C9A961; font-weight: 600; font-size: 13px;">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
